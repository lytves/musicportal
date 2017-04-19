<?php

	session_start();

	if ($_GET['error']) {
	
		print_r($_GET);
		header('HTTP/1.1 404 Not Found');
  header('Status: 404 Not Found');
  echo '<?xml version="1.0" encoding="iso-8859-1"?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<title>Not Found - Error 404 (Íå íàéäåíî - Îøèáêà 404)!</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
		<style type="text/css">
		<!--
		body {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 12px;
			font-style: normal;
			color: #000000;
		}
		-->
		</style>
		</head>
		<body>
			<font size="4">Not Found - Error 404 (Íå íàéäåíî - Îøèáêà 404)!</font> 
			<br />-------------------------------------------------------------------------<br />
			<br />
			In the near future we will try to correct the error. Thank you for your understanding!
      <br />Administration.
			<br />-------------------------------------------------------------------------<br />
      Â áëèæàéøåå âðåìÿ ìû ïîñòàðàåìñÿ èñïðàâèòü îøèáêó. Ñïàñèáî çà ïîíèìàíèå!
      <br />Àäìèíèñòðàöèÿ.
      <br /><br /><br />
      <a href="javascript:history.go(-1)">Back to previous page (Âåðíóòüñÿ íà ïðåäûäóùóþ ñòðàíèöó)</a>
      <br /><br /><br />
       <a href="/">Back to main page (Âåðíóòüñÿ íà ãëàâíóþ ñòðàíèöó)</a>
		</body>
		</html>';
		die();
		
	}

	if (!empty($_GET['code'])) {
	
		if (!empty($_SESSION['auth_from'])) {
	
			$sicial_net = $_SESSION['auth_from'];
			
			if(empty($sicial_net)) die($vauth_text['no_auth_site']);

			header('Location: ./auth.php?auth_site='.$sicial_net.'&code='.$_GET['code']);

			die();
		
		}
	}
	
	if (file_exists(dirname (__FILE__).'/functions/twitter_functions.php')) {
		
		include_once(dirname (__FILE__).'/functions/twitteroauth.php');
		include_once(dirname (__FILE__).'/settings/user_settings.php');
		
		/* If the oauth_token is old redirect to the connect page. */
		if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
		  $_SESSION['oauth_status'] = 'oldtoken';
		  header('Location: ./clearsessions.php');
		  die();
		}

			define('CONSUMER_KEY'		, $vauth_config['twitter_app_id']		);
			define('CONSUMER_SECRET'	, $vauth_config['twitter_app_secret']	);
			define('OAUTH_CALLBACK'		, $vauth_config['site_url'] . '/engine/modules/vauth/callback.php'	);
		
			/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

			/* Save the access tokens. Normally these would be saved in a database for future use. */
			$_SESSION['access_token'] = $connection->getAccessToken($_REQUEST['oauth_verifier']);

			/* Remove no longer needed request tokens */
			unset($_SESSION['oauth_token']);
			unset($_SESSION['oauth_token_secret']);


			/* If HTTP response is 200 continue otherwise send to connect page to retry */
			if (200 == $connection->http_code) {
				$_SESSION['status'] = 'verified';
				header('Location: ./auth.php?auth_site=twitter');
				die();
			} else {
			  header('Location: ./clearsessions.php');
			  die();
			}
	}
?>