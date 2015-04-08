<?php
class ControllerMedias {

	var $container = 'container';
	var $authentication;
	var $gallery;
	
	function ControllerMedias($services) {
		$this->authentication = $services['authentication'];
		$this->gallery = $services['gallery'];
	}
	
	function action_upload(&$obj, &$view) {
		$output = array();
		if (isset($_FILES['files'])) {
			foreach($this->diverse_array($_FILES['files']) as $file) {
				if (!$this->gallery->TrySaveAsNew($file, $error, $media)) {
					$output['errors'][] = $error;
				} else {
					$output['medias'][] = $media;
				}
			}
		} else {
			if ( !empty($_SERVER['CONTENT_LENGTH']) && empty($_FILES) && empty($_POST) )
				$output['errors'][] = htmlentities('The uploaded zip was too large. You must upload files smaller than ' . min(ini_get("upload_max_filesize"), ini_get('post_max_size')));
			else	
				$output['errors'][] = "No File uploaded";
		}
		
		$obj = $output;
		$view = 'ajax';
	}
	
	function action_delete(&$obj, &$view) {
		if (isset($_POST['id']) && trim($_POST['id']) != '') {
			$this->gallery->Delete($_POST['id']);
			$output = array('status' => 'ok');
		} else {
			$output = array('status' => 'error');
		}
		$obj = $output;
		$view = 'ajax';
	}

	function view_medias(&$obj, &$view) {
		$obj['medias'] = $this->gallery->GetAll();
	}
	
	private function diverse_array($vector) {
		$result = array();
		foreach($vector as $key1 => $value1)
			foreach($value1 as $key2 => $value2)
				$result[$key2][$key1] = $value2;
		return $result;
	} 
}
?>