<?php

class Gallery {
	var $mediaDal;
	
	var $fileType = array('jpg' => 'png', 'jpeg' => 'png', 'pdf' => 'pdf', 'png' => 'png', 'gif' => 'png');
	var $width = 400;
	var $height = 300;
	var $thWidth = 120;
	var $thHeight = 90;
	var $targetPath = "medias/";

	function Gallery($mediaDal) {
		$this->mediaDal = $mediaDal;
	}
	
	function GetAll() {
		return $this->mediaDal->GetWhere(array());
	}
	
	function TrySaveAsNew($file, &$error, &$media) {
		$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$baseName = pathinfo($file['name'], PATHINFO_BASENAME);		
		
		//Check that type is authorized
		if (!in_array($extension, array_keys($this->fileType))) {
			$error = "Bad Type, only support ".join(', ', array_keys($this->fileType)).'.';
			return false;
		}
		
		if ($file['error'] != UPLOAD_ERR_OK) {
			$error = 'Error while uploading (code:'.$file['error'].')';
			return false;
		}
			
		if ($file['size'] == 0) {
			$error = 'Size of file '.$file['name'].' is 0';
			return false;
		}

		$newExtension = $this->fileType[$extension];
		$newBaseName = $this->CreateNewFileName($baseName, $newExtension);
		
		if ($newExtension == 'pdf' && !$this->TrySavePDF($file, $newBaseName, $newExtension, $media, $error))
			return false;
		
		if ($newExtension != 'pdf' && !$this->TrySaveImage($file, $newBaseName, $newExtension, $media, $error))
			return false;
			
		if (!$this->mediaDal->TrySave($media)) {
			$error = 'Can\'t save '.$file['name'].' in database.';
			return false;
		}
		
		return true;
	}
	
	function Delete($id) {
		if (!$this->mediaDal->TryGet($id, $media)) {
			return;
		}
		if (file_exists($media['file']))
			unlink($media['file']);
		if (file_exists($media['thumb']))
			unlink($media['thumb']);
		$this->mediaDal->DeleteWhere(array('id' => $id));
	}
	
	private function TrySavePDF($file, $newBaseName, $newExtension, &$media, &$error) {
		$target = $this->targetPath.$newBaseName.'.'.$newExtension;
		if (!move_uploaded_file($file['tmp_name'], $target)) {
			$error = 'Error while moving '.$file['name'].' to its destination ('.$target.').';
			return false;
		}
		$media = array(
			'name' => $file['name'],
			'file' => $target,
			'thumb' => 'img/thumb_pdf.png'
		);
		return true;
	}
	
	private function TrySaveImage($file, $newBaseName, $newExtension, &$media, &$error) {
		$target = $this->targetPath.$newBaseName.'.'.$newExtension;
		$targetTh = $this->targetPath.$newBaseName.'_th.'.$newExtension;
		
		$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		switch ($extension) {
			case 'jpg':
				$source = imagecreatefromjpeg($file['tmp_name']);
				break;
			case 'png':
				$source = imagecreatefrompng($file['tmp_name']);
				break;
			case 'gif':
				$source = imagecreatefromgif($file['tmp_name']);
				break;
			default:
				$error = "Format not supported";
				return false;
		}
		
		$this->ResizeAndCopy($source, $target, $this->width, $this->height);
		$this->ResizeAndCopy($source, $targetTh, $this->thWidth, $this->thHeight);
		
		$media = array(
			'name' => $file['name'],
			'file' => $target,
			'thumb' => $targetTh
		);
		
		return true;
	}
	
	private function ResizeAndCopy($source, $filename, $width, $height) {
		$w = imagesx($source);
		$h = imagesy($source);
		
		$destination = imagecreatetruecolor($width, $height);
		
		$ratio = min($w / $width, $h / $height);

		imagecopyresized(
			$destination, 							//resource $dst_image
			$source, 	  							//resource $src_image
			0, 			  							//int $dst_x
			0,            							//int $dst_y
			($w - $width * $ratio) / 2,             //int $src_x
			($h - $height * $ratio) / 2,	        //int $src_y
			$width, 							    //int $dst_w
			$height,   						        //int $dst_h
			$width * $ratio,					    //int $src_w
			$height * $ratio					    //int $src_h
		);
		
		imagejpeg($destination, $filename);
	}
	
	private function CreateNewFileName($baseName, $extension) {
		$alphabet = array(
			'�'=>'S', '�'=>'s', '�'=>'Dj','�'=>'Z', '�'=>'z', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A',
			'�'=>'A', '�'=>'A', '�'=>'C', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'I', '�'=>'I', '�'=>'I',
			'�'=>'I', '�'=>'N', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'U', '�'=>'U',
			'�'=>'U', '�'=>'U', '�'=>'Y', '�'=>'B', '�'=>'Ss','�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a',
			'�'=>'a', '�'=>'a', '�'=>'c', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'i', '�'=>'i', '�'=>'i',
			'�'=>'i', '�'=>'o', '�'=>'n', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'u',
			'�'=>'u', '�'=>'u', '�'=>'y', '�'=>'y', '�'=>'b', '�'=>'y', '�'=>'f',
		);
		$newKey = strtr(trim($baseName), $alphabet);
		$newKey = strtolower(preg_replace("/\W+/", "-", $newKey));
		$newKey = substr($newKey, 0, 20);
		
		$new = $newKey;
		while (file_exists($this->targetPath.$newKey.'.'.$extension)) {
			$newKey = $new.substr(md5(uniqid(mt_rand(), true)), 0, 3);
		}
		return $newKey;
	}		
}