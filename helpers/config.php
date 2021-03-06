<?php

class Config {

    var $file;
    var $current;
    var $dbh;
    var $configExists;

    function __construct($file) {
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
            'Contact' => isset($old['Contact']) ? $old['Contact'] : '',
            'TemplateName' => isset($old['TemplateName']) ? $old['TemplateName'] : '',
            'SecretLine' => isset($old['SecretLine']) ? $old['SecretLine'] : '',
            'Analytics' => isset($old['Analytics']) ? $old['Analytics'] : '',
            'DBConnectionString' => isset($old['DBConnectionString']) ? $old['DBConnectionString'] : '',
            'DBUser' => isset($old['DBUser']) ? $old['DBUser'] : '',
            'DBPassword' => isset($old['DBPassword']) ? $old['DBPassword'] : '',
            'DBPrefix' => isset($old['DBPrefix']) ? $old['DBPrefix'] : '',
            'SMTPHosts' => isset($old['SMTPHosts']) ? $old['SMTPHosts'] : '',
            'SMTPAuth' => isset($old['SMTPAuth']) && $old['SMTPAuth'] ? true : false,
            'SMTPUser' => isset($old['SMTPUser']) ? $old['SMTPUser'] : '',
            'SMTPPassword' => isset($old['SMTPPassword']) ? $old['SMTPPassword'] : '',
            'Maintenance' => isset($old['Maintenance']) && $old['Maintenance'] ? true : false,
            'MaintenanceRedirection' => isset($old['MaintenanceRedirection']) ? $old['MaintenanceRedirection'] : '',
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
            $this->dbh->exec("SET CHARACTER SET utf8");
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
            'Contact' => $data['Contact'],
            'TemplateName' => $data['TemplateName'],
            'SecretLine' => $data['SecretLine'],
            'Analytics' => $data['Analytics'],
            'DBConnectionString' => $data['DBConnectionString'],
            'DBUser' => $data['DBUser'],
            'DBPassword' => $data['DBPassword'],
            'DBPrefix' => $data['DBPrefix'],
            'SMTPHosts' => $data['SMTPHosts'],
            'SMTPAuth' => isset($data['SMTPAuth']) ? $data['SMTPAuth'] : false,
            'SMTPUser' => $data['SMTPUser'],
            'SMTPPassword' => $data['SMTPPassword'],
            'Maintenance' => isset($data['Maintenance']) ? $data['Maintenance'] : false,
            'MaintenanceRedirection' => $data['MaintenanceRedirection'],
            'Languages' => array_map('trim', explode(';', $data['Languages'])),
            'ActiveLanguages' => array_map('trim', explode(';', $data['ActiveLanguages'])),
            'Home' => $this->current['Home']
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

    function IsForceNonMaintenance() {
        if (!isset($_COOKIE['fnm']))
            return false;
        if ($this->current == null)
            return false;
        if (!isset($this->current['SecretLine']))
            return false;
        $c = $_COOKIE['fnm'];
        $salt = substr($c, 0, 8);
        $hash = substr($c, 8);
        return $hash == md5($salt.$this->current['SecretLine']);
    }
    
    function SetForceNonMaintenance() {
        $salt = substr(uniqid(), 0, 8);
        $hash = md5($salt.$this->current['SecretLine']);
        setcookie('fnm', $salt.$hash, time()+3600);
    }
    
    function RemoveForceNonMaintenance() {
        unset($_COOKIE['fnm']);
        setcookie('fnm', null, -1);
    }
}

?>