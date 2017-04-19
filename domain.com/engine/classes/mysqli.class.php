<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

class db
{
	var $db_id = false;
	var $query_num = 0;
	var $query_list = array();
	var $mysql_error = '';
	var $mysql_version = '';
	var $mysql_error_num = 0;
	var $mysql_extend = "MySQLi";
	var $MySQL_time_taken = 0;
	var $query_id = false;

	
	function connect($db_user, $db_pass, $db_name, $db_location = 'localhost', $show_error=1)
	{
		$db_location = explode(":", $db_location);

		if (isset($db_location[1])) {

			$this->db_id = @mysqli_connect($db_location[0], $db_user, $db_pass, $db_name, $db_location[1]);

		} else {

			$this->db_id = @mysqli_connect($db_location[0], $db_user, $db_pass, $db_name);

		}

		if(!$this->db_id) {
			if($show_error == 1) {
				$this->display_error(mysqli_connect_error(), '1');
			} else {
				return false;
			}
		} 

		$this->mysql_version = mysqli_get_server_info($this->db_id);

		if(!defined('COLLATE'))
		{ 
			define ("COLLATE", "cp1251");
		}

		mysqli_query($this->db_id, "SET NAMES '" . COLLATE . "'");

		return true;
	}
	
	function query($query, $show_error=true)
	{
		$time_before = $this->get_real_time();

		if(!$this->db_id) $this->connect(DBUSER, DBPASS, DBNAME, DBHOST);
		
		if(!($this->query_id = mysqli_query($this->db_id, $query) )) {

			$this->mysql_error = mysqli_error($this->db_id);
			$this->mysql_error_num = mysqli_errno($this->db_id);

			if($show_error) {
				$this->display_error($this->mysql_error, $this->mysql_error_num, $query);
			}
		}
			
		$this->MySQL_time_taken += $this->get_real_time() - $time_before;
		
//			$this->query_list[] = array( 'time'  => ($this->get_real_time() - $time_before), 
//										 'query' => $query,
//										 'num'   => (count($this->query_list) + 1));
		
		$this->query_num ++;

		return $this->query_id;
	}
	
	function get_row($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return mysqli_fetch_assoc($query_id);
	}

	function get_array($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return mysqli_fetch_array($query_id);
	}
	
	function super_query($query, $multi = false)
	{

		if(!$multi) {

			$this->query($query);
			$data = $this->get_row();
			$this->free();			
			return $data;

		} else {
			$this->query($query);
			
			$rows = array();
			while($row = $this->get_row()) {
				$rows[] = $row;
			}

			$this->free();			

			return $rows;
		}
	}
	
	function num_rows($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return mysqli_num_rows($query_id);
	}
	
	function insert_id()
	{
		return mysqli_insert_id($this->db_id);
	}

	function get_result_fields($query_id = '') {

		if ($query_id == '') $query_id = $this->query_id;

		while ($field = mysqli_fetch_field($query_id))
		{
            $fields[] = $field;
		}
		
		return $fields;
   	}

	function safesql( $source )
	{
		if ($this->db_id) return mysqli_real_escape_string ($this->db_id, $source);
		else return mysql_escape_string($source);
	}

	function free( $query_id = '' )
	{

		if ($query_id == '') $query_id = $this->query_id;

		@mysqli_free_result($query_id);
	}

	function close()
	{
		@mysqli_close($this->db_id);
	}

	function get_real_time()
	{
		list($seconds, $microSeconds) = explode(' ', microtime());
		return ((float)$seconds + (float)$microSeconds);
	}	

	function display_error($error, $error_num, $query = '')
	{
		if($query) {
			// Safify query
			$query = preg_replace("/([0-9a-f]){32}/", "********************************", $query); // Hides all hashes
			$query_str = "$query";
		}
		
		echo '<?xml version="1.0" encoding="iso-8859-1"?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <title>MySQL Fatal Error! (Произошла ошибка)!</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
        <style type="text/css">
            <!-- body {
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 12px;
                font-style: normal;
                color: #000000;
            }
            
            -->

        </style>
    </head>

    <body>
        <font size="4">MySQL Fatal Error! (Произошла ошибка)!</font>
        <br />-------------------------------------------------------------------------
        <br />
        <br /> In the near future we will try to correct the error. Thank you for your understanding!
        <br />Administration.
        <br />-------------------------------------------------------------------------
        <br /> В ближайшее время мы постараемся исправить ошибку. Спасибо за понимание!
        <br />Администрация.
        <br />
        <br />
        <br />
        <a href="javascript:history.go(-1)">Back to previous page (Вернуться на предыдущую страницу)</a>
        <br />
        <br />
        <br />
        <a href="/">Back to main page (Вернуться на главную страницу)</a>
    </body>

    </html>'; //дальше отправляем письмо с текстом ошибки на два мыла function clean_string($string) { $bad = array("content-type","bcc:","to:","cc:","href"); return str_replace($bad,"",$string); } include_once ENGINE_DIR . '/classes/mail.class.php'; require ENGINE_DIR . '/data/config.php'; $ref = $config['http_home_url'].substr($_SERVER['REQUEST_URI'],1); // Адрес страницы ошибки $mailsend = new dle_mail( $config ); $mailsend->from = 'robot@domain.com'; $mailsend->bcc[0] = 'odmin@domain.com'; $mailsend->html_mail = true; $mailtext = "<strong style='font-size:20px;'>MySQL ошибка на сайте domain.com!</strong>
    <br />
    <br />"; $mailtext .= "<strong>Страница с ошибкой:</strong> ".clean_string($ref)."
    <br />"; $mailtext .= "<strong>The Error returned was:</strong> ".clean_string($error)."
    <br />"; $mailtext .= "<strong>Error Number:</strong> ".clean_string($error_num)."
    <br />"; $mailtext .= "<strong>Error:</strong> ".clean_string($query_str); $mailtext .= "
    <br />... возможно строка <strong>Error:</strong> длинне чем в письме!
    <br />
    <br />"; $mailtext .= "<strong>Браузер пользователя:</strong> ".clean_string($_SERVER['HTTP_USER_AGENT'])."
    <br />"; $mailtext .= "<strong>IP-адрес пользователя:</strong> ".clean_string($_SERVER['REMOTE_ADDR'])."
    <br />"; $mailsend->send( "yourmail@yahoo.com", "MySQL ошибка на сайте Domain.com", "$mailtext" ); exit(); } } ?>
