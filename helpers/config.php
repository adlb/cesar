<?php

class Config {

    var $file;
    var $current;
    var $dbh;
    var $configExists;

    function Config($file) {
        $this->file = $file;
        $old = null;

        $this->configExists = file_exists($this->file);
        if (file_exists($this->file)) {
            $old = json_decode(file_get_contents($this->file), true, 3);
        }
        if ($old == null) {
            $old = array();
        }
        $this->current = array(
            'Title' => isset($old['Title']) ? $old['Title'] : '',
            'DBConnectionString' => isset($old['DBConnectionString']) ? $old['DBConnectionString'] : '',
            'DBUser' => isset($old['DBUser']) ? $old['DBUser'] : '',
            'DBPassword' => isset($old['DBPassword']) ? $old['DBPassword'] : '',
            'DBPrefix' => isset($old['DBPrefix']) ? $old['DBPrefix'] : '',
            'Maintenance' => isset($old['Maintenance']) && $old['Maintenance'] ? true : false,
            'MaintenanceMessage' => isset($old['MaintenanceMessage']) ? $old['MaintenanceMessage'] : '',
            'Languages' => isset($old['Languages']) && is_array($old['Languages']) ? $old['Languages'] : array('fr'),
            'ActiveLanguages' => isset($old['ActiveLanguages']) && is_array($old['ActiveLanguages']) ? $old['ActiveLanguages'] : array('fr'),
            'Home' => isset($old['Home']) ? $old['Home'] : 0
        );

        try {
            $opt = array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );
            $this->dbh = new PDO(
                $this->current['DBConnectionString'],
                $this->current['DBUser'],
                $this->current['DBPassword'],
                $opt
            );
        } catch (Exception $e) {
            $this->dbh = null;
        }
    }

    function Set($key, $value) {
        $this->current[$key] = $value;
        file_put_contents($this->file, json_encode($this->current));
    }

    function DeleteConfig() {
        unlink($this->file);
    }

    function TrySave($data) {
        $new = array(
            'Title' => $data['Title'],
            'DBConnectionString' => $data['DBConnectionString'],
            'DBUser' => $data['DBUser'],
            'DBPassword' => $data['DBPassword'],
            'DBPrefix' => $data['DBPrefix'],
            'Maintenance' => isset($data['Maintenance']) ? $data['Maintenance'] : false,
            'MaintenanceMessage' => $data['MaintenanceMessage'],
            'Languages' => array_map('trim', explode(';', $data['Languages'])),
            'ActiveLanguages' => array_map('trim', explode(';', $data['ActiveLanguages'])),
            'Home' => $this->current['Home'],
        );

        $this->current = $new;
        try {
            $opt = array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );
            $this->dbh = new PDO(
                $this->current['DBConnectionString'],
                $this->current['DBUser'],
                $this->current['DBPassword'],
                $opt
            );
            file_put_contents($this->file, json_encode($new));
            return true;
        } catch (Exception $e) {
            $this->dbh = null;
        }
        return false;
    }

	function Log($level, $text) {
	}
	
	function SetError(&$obj, $level, $text) {
		$levels = array('success' => ':SUCCESS', 'info' => ':INFORMATION', 'warning' => ':WARNING', 'error' => ':ERROR');
		if (!in_array($level, array_keys($levels)))
			$level = 'info';
		
		if (!isset($obj['errors']))
			$obj['errors'] = array();
		
		$obj['errors'][] = array(
			'level' => $level,
			'strongText' => $levels[$level],
			'text' => $text
		);
	}
}

?>