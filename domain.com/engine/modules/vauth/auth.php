<?php
	
	#header('Access-Control-Allow-Origin: *');
	// ** ��������� ������ ** //
	session_start();
	
	if (!empty($_GET['ref'])) $_SESSION['referrer'] = $_GET['ref'];
  
  //   ��� ���������
	if (empty($_SESSION['ref_vauth']))  {
    if (strpos($_SERVER['HTTP_REFERER'], 'domain.com') === false) $_SESSION['ref_vauth'] = '';
    else $_SESSION['ref_vauth'] = $_SERVER['HTTP_REFERER'];
  }
		
	// ** �������� ��������� ������� ** //
	include_once("settings/script_settings.php");
	
	$new_user = '';
	
	if (empty($_POST['regform'])) {
	
		// ** �������� ��� ����� ��� ����������� ** //
		$vauth_api->go_auth($auth_site);
		
		// ** ����� � ���������� �������� ������������ ** //
		$new_user = $vauth_api->go_login($auth_site,$ac_connect);
	
	}
	

	// ** ����������� ������ ������������ ** //
	$vauth_api->go_register($auth_site,$new_user);
	
?>
