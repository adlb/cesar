<?php

class Dal {
    
    var $db;
    var $tableName;
    var $cache = array();
    
    function TryGet($key, &$value){
        if (isset($this->cache[$key])){
            $value = $this->cache[$key]['value'];
            return $this->cache[$key]['exists'];
        }
        $sqlFields = array();
        foreach($this->fields as $k => $v)
            $sqlFields[] = '`'.$k.'`';
        $sql = 'SELECT '.join(',', $sqlFields).' FROM `'.$this->tableName.'` WHERE `'.$this->keyName.'` = :key';
        try {
            $statement = $this->db->prepare ($sql);
            $statement->bindParam (':key', $key, PDO::PARAM_STR);
            $statement->execute ();
        } catch (PDOException $e) {
            $this->cache[$key] = array('value' => null, 'exists' => false);
            return false;
        }
        if ($result = $statement->fetch(PDO::FETCH_ASSOC)){
            $value = array();
            foreach($this->fields as $k => $v)
                $value[$k] = $result[$k];
            $this->cache[$key] = array('value' => $value, 'exists' => true);
            return true;
        } else {
            $this->cache[$key] = array('value' => null, 'exists' => false);
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
            var_dump($e);
            die("Error while deleting.");
        }
    }
    
    function GetWhere($conditions, $orderBy = array()) {
		if ($this->db == null)
			return array();
		
        $sqlFields = array();
        foreach($this->fields as $k => $v)
            $sqlFields[] = '`'.$k.'`';
        $sql = 'SELECT '.join(',', $sqlFields).' FROM `'.$this->tableName.'`';
        
		$sqlConditions = array();
        foreach($conditions as $k => $v) 
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
        if (count($sqlConditions) > 0)
            $sql = $sql.' WHERE '.join(' AND ', $sqlConditions);

        $sqlOrderBy = array();
        foreach ($orderBy as $k => $growing) 
            $sqlOrderBy[] = '`'.$k.'`'.($growing ? '' : ' DESC');
        if (count($sqlOrderBy) > 0)
            $sql = $sql.' ORDER BY '.join(', ', $sqlOrderBy);
        
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
                $results[] = $result;
            }
            return $results;
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
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
                if (isset($value[$k]))
					$statement->bindParam (':'.$k, $value[$k], $v['bind']);
            $statement->execute ();
            if (!isset($value[$this->keyName])) {
                $value[$this->keyName] = $this->db->lastInsertId(); 
            }
        } catch (PDOException $e) {
            var_dump($e);
            echo $sql;
            $this->cache[$value[$this->keyName]] = array('value' => null, 'exists' => false);
            return false;
        }
        $this->cache[$value[$this->keyName]] = array('value' => $value, 'exists' => true);
        return $value[$this->keyName];
    }
    
    function Dal($db, $prefix) {
		if ($db == null)
			return;
        $this->db = $db;
        $this->tableName = $prefix.$this->tableSuffix;
		
        $sqlColumn = array();
        foreach($this->fields as $k => $v) {
			$sqlFields[] = '`'.$k.'` '.$v['create'].' NOT NULL';
            if (isset($v['primaryKey']) && $v['primaryKey'])
                $sqlFields[] = 'PRIMARY KEY (`'.$k.'`)';
            if (isset($v['key']) && $v['key'])                
                $sqlFields[] = 'KEY `'.$k.'` (`'.$k.'`)';
        }
		try {
            $db->prepare('CREATE TABLE IF NOT EXISTS `'.$this->tableName.'` (
                          '.join(', ', $sqlFields).'
                        )')->execute();
        } catch (PDOException $e) {
            var_dump($e);
            die('connection impossible');
        }
    }
}
?>