<?php

class Journal {
    var $eventDal;
    var $authentication;
    var $level = array(0 => 'system', 1 => 'notice', 2 => 'medium', 3 => 'severe');
    var $levelToLog;
    
    function __construct($eventDal, $authentication, $level = null) {
        $this->eventDal = $eventDal;
        $this->authentication = $authentication;
        if ($levelId = array_search($level, $this->level)) {
            $this->levelToLog = $levelId;
        } else {
            $this->levelToLog = 0;
        }
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
    
    function Log($level, $string, $params = array()) {
        Global $controller, $action, $view;
        
        for($i = 0 ; $i < count($param); $i++) {
            $string = str_replace('{'.$i.'}', $params[$i], $string);
        }
        $string = date('c').';'.$controller.';'.$view.';'.$action.';'.$string;
        
        if (($levelId = array_search($level, $this->level)) && $levelId >= $this->levelToLog) {
            $file = 'log/'.date('Y-m-d').'-'.$level.'.log';
            file_put_contents($file, $string.PHP_EOL, FILE_APPEND);
        }
    }
}

?>