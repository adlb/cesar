<?php

class ParameterDal {
    
    var $db;
    var $tableName;
    var $cache = array();
    
    function TryGet($key, &$value){
        if (isset($this->cache[$key])){
            $value = $this->cache[$key]['value'];
            return $this->cache[$key]['exists'];
        }
        $sql = 'SELECT `value` FROM '.$this->tableName.' WHERE `key` = :key';
        try {
            $statement = $this->db->prepare ($sql);
            $statement->bindParam (":key", $key, PDO::PARAM_STR);
            $statement->execute ();
        } catch (PDOException $e) {
            $this->cache[$key] = array('value' => null, 'exists' => false);
            return false;
        }
        if ($result = $statement->fetch(PDO::FETCH_ASSOC)){
            $value = $result['value'];
            $this->cache[$key] = array('value' => $value, 'exists' => true);
            return true;
        } else {
            $this->cache[$key] = array('value' => null, 'exists' => false);
            return false;
        }
    }
    
    function TrySet($key, $value){
        $sql = 'insert `'.$this->tableName.'` (`key`, `value`) values (:key, :value)
                    ON DUPLICATE KEY UPDATE `value` = :value';

        try {
            $statement = $this->db->prepare ($sql);
            $statement->bindParam (":key", $key, PDO::PARAM_STR);
            $statement->bindParam (":value", $value, PDO::PARAM_STR);
            $statement->execute ();
        } catch (PDOException $e) {
            $this->cache[$key] = array('value' => null, 'exists' => false);
            return false;
        }
        $this->cache[$key] = array('value' => $value, 'exists' => true);
        return true;
    }
    
    function ParameterDal($db, $prefix) {
        $this->db = $db;
        $this->tableName = $prefix.'parameters';
        
        try {
            $db->prepare("CREATE TABLE IF NOT EXISTS `".$this->tableName."` (
                          `key` varchar(50) NOT NULL,
                          `value` text NOT NULL,
                          PRIMARY KEY (`key`)
                        )")->execute();
        } catch (PDOException $e) {
            die('connection impossible');
        }
    }
}

if ($utest) {
    $dbh = new PDO($DBconnectionString, $DBuser, $DBpassword);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->prepare("DROP TABLE IF EXISTS "."utest_parameters")->execute();
    $dal = new ParameterDal($dbh, "utest_");
    
    if ($dal->TryGet('testValue', $res) == false)
        echo "[OK] TryGet non existing value return false <BR/>";
    else
        echo "[NOK] TryGet non existing value return true $res<BR/>";

    if ($dal->TrySet('testValue', 'aaa'))
        echo "[OK] TrySet return true <BR/>";
    else
        echo "[NOK] TrySet cannot store data <BR/>";
    
    if ($dal->TryGet('testValue', $res) && $res == 'aaa')
        echo "[OK] TryGet existing value return true with the right value (from cache)<BR/>";
    else
        echo "[NOK] TryGet existing value does not return true or value is wrong (from cache)<BR/>";
    
    $dal = new ParameterDal($dbh, "utest_");
    if ($dal->TryGet('testValue', $res) && $res == 'aaa')
        echo "[OK] TryGet existing value return true with the right value (from DB)<BR/>";
    else
        echo "[NOK] TryGet existing value does not return true or value is wrong (from DB)<BR/>";
    
    if ($dal->TrySet('testValue', 'bbb'))
        echo "[OK] TrySet update value return true<BR/>";
    else
        echo "[NOK] TrySet update value should return true<BR/>";
    
    if ($dal->TryGet('testValue', $res) && $res == 'bbb')
        echo "[OK] TryGet existing value return true with the right value after update(from cache)<BR/>";
    else
        echo "[NOK] TryGet existing value does not return true or value is wrong after update(from cache) (res=$res)<BR/>";
    
    $dbh->prepare("DROP TABLE IF EXISTS "."utest_parameters")->execute();
}

?>