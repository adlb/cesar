<?php

class Config {

	var $file;
	var $current;
	var $dbh;

	function Config($file) {
		$this->file = $file;
		
		$old = null;
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
			'Home' => isset($old['Home']) ? $old['Home'] : 0,
		);

		try {
			$this->dbh = new PDO(
				$this->current['DBConnectionString'], 
				$this->current['DBUser'], 
				$this->current['DBPassword']
			);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (Exception $e) {
			$this->dbh = null;
		}
	}

	function Set($key, $value) {
		$this->current[$key] = $value;
		file_put_contents($this->file, json_encode($this->current));
	}
	
	function Save($data) {
		$new = array(
			'Title' => $data['Title'],
			'DBConnectionString' => $data['DBConnectionString'],
			'DBUser' => $data['DBUser'],
			'DBPassword' => $data['DBPassword'],
			'DBPrefix' => $data['DBPrefix'],
			'Maintenance' => isset($data['Maintenance']) ? $data['Maintenance'] : false,
			'MaintenanceMessage' => $data['MaintenanceMessage'],
			'Languages' => array_map('trim', explode(';', $data['Languages'])),
			'Home' => $this->current['Home'],
		);
		
		$this->current = $new;
		try {
			$this->dbh = new PDO(
				$this->current['DBConnectionString'], 
				$this->current['DBUser'], 
				$this->current['DBPassword']
			);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (Exception $e) {
			$this->dbh = null;
		}
		file_put_contents($this->file, json_encode($new));
	}
}

?>