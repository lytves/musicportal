<?php
	
	#header('Access-Control-Allow-Origin: *');
	// ** Запускаем сессию ** //
	session_start();
	
	if (!empty($_GET['ref'])) $_SESSION['referrer'] = $_GET['ref'];
  
  //   МОИ ИЗМЕНЕНИЯ
	if (empty($_SESSION['ref_vauth']))  {
    if (strpos($_SERVER['HTTP_REFERER'], 'domain.com') === false) $_SESSION['ref_vauth'] = '';
    else $_SESSION['ref_vauth'] = $_SERVER['HTTP_REFERER'];
  }
		
	// ** Получаем настройки скрипта ** //
	include_once("settings/script_settings.php");
	
	$new_user = '';
	
	if (empty($_POST['regform'])) {
	
		// ** Получаем имя сайта для авторизации ** //
		$vauth_api->go_auth($auth_site);
		
		// ** Логин и сопряжение аккантов пользователя ** //
		$new_user = $vauth_api->go_login($auth_site,$ac_connect);
	
	}
	

	// ** Регистрация нового пользователя ** //
	$vauth_api->go_register($auth_site,$new_user);
	
?>
