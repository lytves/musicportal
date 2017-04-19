<?php

if( ! class_exists( 'YaFunctions' ) )	{

	class YaFunctions extends VAuthFunctions {

		function oauth_data() {
		
			global $site_url;
			global $vauth_config;
			
			$oauth = array();
			$oauth['prefix'] = 'ya';
			$oauth['prefix2'] = 'yandex';
			$oauth['disconnect_str'] 	=	"updtime='', ".$oauth['prefix']."_username='', ".$oauth['prefix']."_connected='0', ".$oauth['prefix']."_user_id=''";			

			$oauth['app_id'] = $vauth_config['yandex_app_id'];
			$oauth['app_secret'] = $vauth_config['yandex_app_secret'];
			$oauth['redirect_uri'] = $site_url.'/engine/modules/vauth/callback.php';
			$oauth['auth_url'] = 'https://oauth.yandex.ru/authorize?response_type=code&client_id='.$oauth['app_id'];
			$oauth['group'] =	$vauth_config['yandex_user_group'];

			if (empty($oauth['group'])) $oauth['group'] = 4;
			if (empty($oauth['app_id'])) die('Unknown Application ID Yandex');
			if (empty($oauth['app_secret'])) die('Unknown Application ID Yandex');
			
			return $oauth;
			
		}
		
		function vauth_auth($oauth) {
		
			global $auth_code;
			global $vauth_text;
		
			$_SESSION['auth_from']	=	'yandex';
		
			if (empty($oauth['access_token']) and empty($auth_code)) {
				header('Location: '.$oauth['auth_url']);
				die;
			}
			
			if ( !empty($auth_code) ) {
			
				$oauth_auth = 'https://oauth.yandex.ru/token';
				
				$datascope = 'grant_type=authorization_code&code='.$auth_code.'&client_id='.$oauth['app_id'].'&client_secret='.$oauth['app_secret'];
				
				$userinfo = json_decode($this->post_curl($oauth_auth,$datascope), FALSE);
				//$userinfo = $this->post_curl($oauth_auth,$datascope); // * Плучаем секретный хэшкод	
				
				//parse_str($userinfo);
				
				if (!empty($userinfo->access_token) ) {
          $access_token	= $userinfo->access_token;
          $_SESSION['yandex_access_token']	=	$access_token;
          $oauth['access_token']	=	$access_token;

					} else die($vauth_text['ya_token_error']);
			
			}
			
			return $oauth;
		}

		function get_oauth_info($oauth) {
			
			global $vauth_text;
			global $db;
			global $site_url;
			
			$oauth['access_token'] = $_SESSION['yandex_access_token'];
			
			$oauth_info		=	json_decode($this->vauth_get_contents('https://login.yandex.ru/info?format=json&oauth_token='.$oauth['access_token']), FALSE); //Получаем информцию о пользователе
			
			$oauth['nick']		=	$this->conv_it($oauth_info->login);
			$oauth['username']		=	$this->conv_it($oauth_info->login);
			$oauth['email']	=	$this->conv_it($oauth_info->default_email);
			$oauth['uid']		=	$this->conv_it($oauth_info->id);
			
			$oauth['avatar'] 		=	"https://avatars.yandex.net/get-yapic/".$oauth['uid']."/islands-200";

      $oauth['bdate'] = $this->conv_it($oauth_info->birthday);
			$oauth['firstname'] =	$this->conv_it($oauth_info->first_name);
			$oauth['lastname'] =	$this->conv_it($oauth_info->last_name);
			
			$oauth['fullname'] =	$oauth['firstname'].' '.$oauth['lastname'];
			$oauth['sex'] =	$this->conv_it($oauth_info->sex);
      switch(	$oauth['sex']	) {
				case "male"	: $oauth['sex'] = $vauth_text[4];	break;
				case "female"	: $oauth['sex'] = $vauth_text[5];	break;
			}
			return $oauth;
		}		
		
	}
}

$vauth_api = new YaFunctions ();			
	
?>