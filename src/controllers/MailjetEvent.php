<?php

class MailjetEvent extends GenericEvent
{
    const FIELDS = ['time', 'MessageID', 'event', 'email'];
    protected $source = "mailjet";
    protected $payload = "";

    public function validate()
    {
        $v = json_decode($this->payload, false, 512, JSON_THROW_ON_ERROR);
        return $this;
    }

    public function store()
    {
        $c = $this->container->get('MySQL');
        $w = "error";
        $p = $this->payload;

        if (strpos($p, $w) !== false) {
            $c->execute(
                "INSERT INTO mailjet_invalid (`ip`, `payload`, `source`, `extra`) VALUES (?,?,?,?)",
                [$this->ip, $this->payload, $this->source, $this->extra]
            );
        } else {
            $c->execute(
                "INSERT INTO `received` (`ip`, `payload`, `source`, `extra`) VALUES (?,?,?,?)",
                [$this->ip, $this->payload, $this->source, $this->extra]
            );
        }
    }

    public function process()
    {
        $c = $this->container->get('MySQL');
        $c->begin();
        try{
            $data =  json_decode($this->payload, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($data)) {
                echo "Not an array\n";
                var_dump($data);
                exit(1);
                throw new \Exception("Invalid payload : not an array", 1);
            }
            if (count($data) == 0) {
                throw new \Exception("Invalid payload : empty array", 1);
            }
            foreach ($data as $event) {
                if (!is_array($event)) {
                    var_dump($event);
                    throw new \Exception("Invalid payload : invalid event in payload ", 1);
                }
                foreach (self::FIELDS as $n) {   
                    if (!array_key_exists($n, $event)) {
                        throw new \Exception("Invalid payload : missing key ".$n, 1);
                    }
                }
                $c->execute(
                    'INSERT INTO mailjet_processed (Time, messageId, type, email, payload) VALUES (?,?,?,?,?)',
                    [$event['time'], $event['Message_GUID'], $event['event'], $event['email'], json_encode($event)]
                    );
            }
        } catch(exception $e){
            $c->rollback();
            $c->begin();
            $c->execute(
                'INSERT INTO mailjet_invalid (ip, payload, source, extra, message_exception) VALUES (?,?,?,?,?)',
                [$this->ip, $this->payload, $this->source, $this->extra, $e->getMessage() ]
                );
        }  
        $c->execute(
            'DELETE FROM received WHERE pk=?',
            [$this->pk]
        );
        $c->commit();
    }
}

