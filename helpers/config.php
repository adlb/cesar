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
            'SecretLine' => isset($old['SecretLine']) ? $old['SecretLine'] : '',
            'Analytics' => isset($old['Analytics']) ? $old['Analytics'] : '',
            'DBConnectionString' => isset($old['DBConnectionString']) ? $old['DBConnectionString'] : '',
            'DBUser' => isset($old['DBUser']) ? $old['DBUser'] : '',
            'DBPassword' => isset($old['DBPassword']) ? $old['DBPassword'] : '',
            'DBPrefix' => isset($old['DBPrefix']) ? $old['DBPrefix'] : '',
            'SMTPHosts' => isset($old['SMTPHosts']) ? $old['SMTPHosts'] : '',
            'SMTPUser' => isset($old['SMTPUser']) ? $old['SMTPUser'] : '',
            'SMTPPassword' => isset($old['SMTPPassword']) ? $old['SMTPPassword'] : '',
            'Maintenance' => isset($old['Maintenance']) && $old['Maintenance'] ? true : false,
            'MaintenanceRedirection' => isset($old['MaintenanceRedirection']) ? $old['MaintenanceRedirection'] : '',
            'Languages' => isset($old['Languages']) && is_array($old['Languages']) ? $old['Languages'] : array('fr'),
            'ActiveLanguages' => isset($old['ActiveLanguages']) && is_array($old['ActiveLanguages']) ? $old['ActiveLanguages'] : array('fr'),
            'Home' => isset($old['Home']) ? $old['Home'] : 0,
            'PaypalButtonId' => isset($old['PaypalButtonId']) ? $old['PaypalButtonId'] : '',
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
            'Contact' => $data['Contact'],
            'SecretLine' => $data['SecretLine'],
            'Analytics' => $data['Analytics'],
            'DBConnectionString' => $data['DBConnectionString'],
            'DBUser' => $data['DBUser'],
            'DBPassword' => $data['DBPassword'],
            'DBPrefix' => $data['DBPrefix'],
            'SMTPHosts' => $data['SMTPHosts'],
            'SMTPUser' => $data['SMTPUser'],
            'SMTPPassword' => $data['SMTPPassword'],
            'Maintenance' => isset($data['Maintenance']) ? $data['Maintenance'] : false,
            'MaintenanceRedirection' => $data['MaintenanceRedirection'],
            'Languages' => array_map('trim', explode(';', $data['Languages'])),
            'ActiveLanguages' => array_map('trim', explode(';', $data['ActiveLanguages'])),
            'Home' => $this->current['Home'],
            'PaypalButtonId' => $data['PaypalButtonId']
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
}

?>