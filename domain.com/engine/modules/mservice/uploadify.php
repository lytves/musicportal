<?php
/*
Uploadify v3.1.0
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

@session_id($_POST['sessId']);
@session_start();
//это номер файла из очереди загрузки
$swfid = $_POST['swfid'];
$numer = substr($swfid,12);

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = '/home2/mp3base/temp';
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	//Получаем информацию о загруженном файле
	$time = time( );
	$filename = $_FILES['Filedata']['name'];
	$ext = strtolower($fileParts['extension']);
	$for_md5 = $time + mt_rand( 0, 1000 );
	$for_md5 .= $tempFile;
	$newFile = md5( $for_md5 ).'.'.$ext;//Генерируем новое имя файла во избежании совпадения названий	

	if(!is_dir('/home2/mp3base/temp/')) {
		mkdir ('/home2/mp3base/temp/', 0777);
		chmod ('/home2/mp3base/temp/', 0777);
	}
	

	$targetFile = rtrim($targetPath,'/').'/'.$newFile;
	// Validate the file type
	$fileTypes = array('mp3','MP3'); // File extensions

	
	if (in_array($fileParts['extension'],$fileTypes)) {
		if (move_uploaded_file($tempFile,$targetFile)) {
			$_SESSION['mservice_files'][$numer] = array( 
			'name' => $filename,
			'file' => $newFile,
			'date' => time()
			);
		}
		echo $newFile;
	} else {
		echo 'Invalid file type.';
	}
}
?>