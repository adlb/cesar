<?php

class Journal {
    var $eventDal;
    var $authentication;
    
    function __construct($eventDal, $authentication) {
        $this->eventDal = $eventDal;
        $this->authentication = $authentication;
    }

    function LogEvent($controller, $fact, $data) {
        $userId = '';
        if ($this->authentication->currentUser != null && isset($this->authentication->currentUser['id'])) {
            $userId = $this->authentication->currentUser['id'];
        }
        $date = date('Y-m-d H:i:s');
        $event = array(
            'userId' => $userId,
            'date' => $date,
            'controller' => $controller,
            'fact' => $fact,
            'data' => json_encode($data)
        );
        
        $this->eventDal->TrySave($event);
    }
}

?>