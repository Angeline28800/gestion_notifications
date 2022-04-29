<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;


class GenericEvent
{
    protected $container;
    protected static $_receiveList;
    protected $source = "generic";
    protected $ip = "", $payload = "", $extra = "";

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getIP($request)
    {
        $this->ip = $request->getAttribute('ip_address');
        return $this;
    }

    public function getPayload($request)
    {
        $this->payload = $request->getBody();
        return $this;
    }

    public function getExtra($request)
    {
        return $this;
    }

    public function validate()
    {
        return $this;
    }

    public function webhook($request, $response)
    {
        try {
            $this->getIP($request)
                ->getPayload($request)
                ->getExtra($request)
                ->validate()
                ->store();
            $response->getBody()->write("OK");
            return $response->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write("Failed " . $e->getMessage());
            return $response->withStatus(400);
        }
    }
    //instancie l'objet récupéré dans la bdd (inverse du store) 
    static public function factory($container, $data)
    {
        $c = get_called_class();
        $o = new $c($container);
        foreach ($data as $k => $v) {
            $o->$k = $v;
        }
        return $o;
    }

    public function pop()
    {
        if (!is_array(self::$_receiveList)) {
            self::$_receiveList = $this->container->get('MySQL')->execute('SELECT * FROM received');
        }
        if (count(self::$_receiveList) == 0) {
            self::$_receiveList = NULL;
            return NULL;
        }
        $e = array_shift(self::$_receiveList);
        // build object
        switch ($e['source']) {
            case 'mailjet':
                return MailjetEvent::factory($this->container, $e);
            default:
                throw new \Exception('Unexpected event type ' . $e['event']);
        }
    }

    function store()
    {
        $c = $this->container->get('MySQL');
        $c->execute(
            "INSERT INTO `received` (`ip`, `payload`, `source`, `extra`) 
            VALUES
            (?, ?, ?, ?)",
            [$this->ip, $this->payload, $this->source, $this->extra]
        );
    }
    public function process()
    {
        throw new \Exception('This method must be overloaded ');
    }
}
