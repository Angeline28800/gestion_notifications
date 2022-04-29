<?php
/**
 * Classe MySQL utilisant un design pattern Singleton
 * 
 * @author  Julien Crego <prenomnom@gmail.com>
 * @version 1.0
 */
use Psr\Container\ContainerInterface;

class MySQL {

  private $handle;

  public function __construct(ContainerInterface $c)
  {
    function v($c, $n,$d=NULL) { return ($c->has($n)) ? $c->get($n) : $d; }
      $h = new \mysqli(
        v($c, 'MySQL.host'),
        v($c, "MySQL.login"),
        v($c, "MySQL.password"),
        v($c, "MySQL.database", ""),
        v($c, "MySQL.port", NULL),
        v($c, "MySQL.socket", "")
      );

    // Connection failed
    if ($h->connect_errno) {
      throw new \Exception("MySQL connection failed: ".$h->connect_error, $h->connect_errno);
    }
    $this->handle = $h;
  }

  protected static function build_params($types, $params)
  {
    $refs = [ $types ];
    foreach($params as $key => $value)
      $refs[] = &$params[$key];
    return $refs;
  }
  
  public function execute($sql, $params=NULL, $types=NULL) 
  {
    // prepare the statement
    $stmt = $this->handle->prepare($sql);
    if (!$stmt) {
      throw new \Exception("SQL Prepare failed: ".$this->handle->error, $this->handle->errno);
    }
    // bind parameters
    if (! is_null($params)) {
      if (! is_array($params)) {
        $params = [ $params ];
      }
      if (is_null($types)) {
        // Build types string depending on parameters types
        $types = '';
        foreach ($params as $param) {
          switch (gettype($param)) {
            case 'boolean':
            case 'integer': $types .= 's'; break;
            case 'double': $types .= 'd'; break;
            case 'string': $types .= 's'; break;
            case 'NULL': $types .= 's'; break;
            case 'object':
            case 'array':
            case 'resource':
            default:
              // for all these cases, we'll try to use the string type
              $types .= 's';
              break;
          }
        }
      }
      $ref    = new \ReflectionClass('mysqli_stmt');
      $method = $ref->getMethod("bind_param");
      $refParams = self::build_params($types, $params);
      if (false === $method->invokeArgs($stmt, $refParams)) {
        throw new \Exception("Bind failed: ".$stmt->error, $stmt->errno);
      }
    }
    // execute the query
    if (false === $stmt->execute()) {
      throw new \Exception("Execute failed: ".$stmt->error, $stmt->errno);
    }
    // try to fetch metadata if query produced a result
    if (! $stmt->result_metadata()) {
      // just return the number of affected rows
      return $stmt->affected_rows;
    }
    // otherwise, fetch result in an array
    $result = [];
    $r = $stmt->get_result();
    while ($row = $r->fetch_assoc()) {
      $result[] = $row;
    }
    // done
    return $result;
  }
  
  public function last_insert_id() {
    return $this->handle->insert_id;
  }
  
  public function begin() {
    if (! $this->handle->begin_transaction()) {
      throw new \Exception("Begin Transaction failed: ".$this->handle->error, $this->handle->errno);
    }
  }
  
  public function commit() {
    if (! $this->handle->commit()) {
      throw new \Exception("Commit Transaction failed: ".$this->handle->error, $this->handle->errno);
    }
  }

  public function rollback() {
    if (!$this->handle->rollback()) {
      throw new \Exception("Rollback Transaction failed: ".$this->handle->error, $this->handle->errno);
    }
  }
  
  public function last_error() {
    return $this->handle->error;
  }
}