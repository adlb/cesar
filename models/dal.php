<?php

class Dal {

    var $db;
    var $tableName;
    
    function __construct($db, $prefix) {
        if ($db == null)
            return;
        $this->db = $db;
        $this->tableName = $prefix.$this->tableSuffix;
        $this->CreateTable();
    }
    
    function TryGet($key, &$value){
        if ($this->db == null)
            return false;

        $sqlFields = array();
        foreach($this->fields as $k => $v)
            $sqlFields[] = '`'.$k.'`';
        $sql = 'SELECT '.join(',', $sqlFields).' FROM `'.$this->tableName.'` WHERE `'.$this->keyName.'` = :key';
        try {
            $statement = $this->db->prepare ($sql);
            $statement->bindParam (':key', $key, PDO::PARAM_STR);
            $statement->execute ();
        } catch (PDOException $e) {
            return false;
        }
        if ($result = $statement->fetch(PDO::FETCH_ASSOC)){
            $value = array();
            foreach($this->fields as $k => $v)
                $value[$k] = $result[$k];
            return true;
        } else {
            return false;
        }
    }

    function DeleteWhere($conditions) {
        $sql = 'DELETE FROM `'.$this->tableName.'`';

        $sqlConditions = array();
        foreach($conditions as $k => $v)
            $sqlConditions[] = '`'.$k.'` = :'.$k.'';
        if (count($sqlConditions) > 0)
            $sql = $sql.' WHERE '.join(' AND ', $sqlConditions);

        try {
            $statement = $this->db->prepare($sql);
            foreach($conditions as $k => $v) {
                $statement->bindValue(':'.$k, $v, $this->fields[$k]['bind']);
            }
            $statement->execute();
        } catch (PDOException $e) {
            die("Error while deleting.");
        }
    }

    function SelectDistinct($fieldName, $conditions = array()) {
        if ($this->db == null)
            return array();

        $sqlField = '`'.$fieldName.'`';
        $sql = 'SELECT DISTINCT '.$sqlField.' FROM `'.$this->tableName.'`';
        
        $sqlConditions = $this->AddSQLConditions($conditions, $sql);
        $sqlConditions = $this->AddSQLOrderBy(array($fieldName => true), $sql);
        
        try {
            $statement = $this->db->prepare($sql);
            foreach($conditions as $k => $v) {
                if (is_array($v)) {
                    for($i = 0; $i < count($v); $i++) {
                        $statement->bindValue(':'.$k.'_'.$i, $v[$i], $this->fields[$k]['bind']);
                    }
                } else {
                    $statement->bindValue(':'.$k, $v, $this->fields[$k]['bind']);
                }
            }
            $statement->execute();
            $results = array();
            while ($result = $statement->fetch(PDO::FETCH_ASSOC)){
                $results[] = $result[$fieldName];
            }
            return $results;
        } catch (PDOException $e) {
            return array();
        }
        
    }
    
    function GetWhere($conditions, $orderBy = array()) {
        if ($this->db == null)
            return array();

        $sqlFields = array();
        foreach($this->fields as $k => $v)
            $sqlFields[] = '`'.$k.'`';
        $sql = 'SELECT '.join(',', $sqlFields).' FROM `'.$this->tableName.'`';

        $sqlConditions = $this->AddSQLConditions($conditions, $sql);
        $sqlOrderBy = $this->AddSQLOrderBy($orderBy, $sql);
        
        $statement = $this->db->prepare($sql);
        foreach($conditions as $k => $v) {
            if (is_array($v)) {
                for($i = 0; $i < count($v); $i++) {
                    $statement->bindValue(':'.$k.'_'.$i, $v[$i], $this->fields[$k]['bind']);
                }
            } else {
                $statement->bindValue(':'.$k, $v, $this->fields[$k]['bind']);
            }
        }
        $statement->execute();
        $results = array();
        while ($result = $statement->fetch(PDO::FETCH_ASSOC)){
            $results[] = $result;
        }
        return $results;
    }

    function TrySave(&$value) { //return key
        if ($this->db == null)
            return false;

        $sqlFields = array();
        $sqlDataName = array();
        $sqlDataSet = array();
        foreach($this->fields as $k => $v) {
            if (isset($value[$k])) {
                $sqlFields[] = '`'.$k.'`';
                $sqlDataNames[] = ':'.$k;
                $sqlDataSets[] = '`'.$k.'` = :'.$k;
            }
        }

        $sql = 'INSERT `'.$this->tableName.'` ('.join(', ', $sqlFields).') values ('.join(', ', $sqlDataNames).')
                    ON DUPLICATE KEY UPDATE '.join(', ', $sqlDataSets);

        try {
            $statement = $this->db->prepare ($sql);
            foreach($this->fields as $k => $v)
                if (isset($value[$k])) {
                    $statement->bindParam (':'.$k, $value[$k], $v['bind']);
                }
            $statement->execute ();
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
        if (!isset($value[$this->keyName]) || trim($value[$this->keyName]) == '') {
            $value[$this->keyName] = $this->db->lastInsertId();
        }
        
        return $value[$this->keyName];
    }

    function ClearAll() {
        if ($this->db == null)
            return;

        try {
            $this->db->prepare('DELETE IGNORE FROM `'.$this->tableName.'`')->execute();
        } catch (PDOException $e) {
            die('connection impossible');
        }
    }

    function DropTable() {
        if ($this->db == null)
            return;

        try {
            $this->db->prepare('DROP TABLE IF EXISTS `'.$this->tableName.'`')->execute();
        } catch (PDOException $e) {
            die('connection impossible');
        }
    }

    function CreateTable() {
        if ($this->db == null)
            return;

        $sqlColumn = array();
        foreach($this->fields as $k => $v) {
            $sqlFields[] = '`'.$k.'` '.$v['create'].' NOT NULL';
            if (isset($v['primaryKey']) && $v['primaryKey'])
                $sqlFields[] = 'PRIMARY KEY (`'.$k.'`)';
            if (isset($v['key']) && $v['key'])
                $sqlFields[] = 'KEY `'.$k.'` (`'.$k.'`)';
        }
        try {
            $this->db->prepare('CREATE TABLE IF NOT EXISTS `'.$this->tableName.'` (
                          '.join(', ', $sqlFields).'
                        )')->execute();
        } catch (PDOException $e) {
            die('connection impossible');
        }
    }
    
    private function AddSQLConditions($conditions, &$sql) {
        $sqlConditions = array();
        foreach($conditions as $k => $v) {
            if (is_array($v)) {
                if (count($v)>0) {
                    $orConditions = array();
                    for($i = 0; $i < count($v); $i++) {
                        $orConditions[] = '`'.$k.'` = :'.$k.'_'.$i.'';
                    }
                    $sqlConditions[] = '('.join($orConditions, ' OR ').')';
                }
            } else {
                $sqlConditions[] = '`'.$k.'` = :'.$k.'';
            }
        }
        if (count($sqlConditions) > 0)
            $sql = $sql.' WHERE '.join(' AND ', $sqlConditions);
    }
    
    private function AddSQLOrderBy($orderBy, &$sql) {
        $sqlOrderBy = array();
        foreach ($orderBy as $k => $growing)
            $sqlOrderBy[] = '`'.$k.'`'.($growing ? '' : ' DESC');
        if (count($sqlOrderBy) > 0)
            $sql = $sql.' ORDER BY '.join(', ', $sqlOrderBy);
    }
}
?>