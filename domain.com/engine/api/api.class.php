<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	define( 'DATALIFEENGINE', true );
}
if( ! defined( 'ROOT_DIR' ) ) {
	define( 'ROOT_DIR', substr( dirname( __FILE__ ), 0, - 11 ) );
}

if( ! defined( 'ENGINE_DIR' ) ) {
	define( 'ENGINE_DIR', ROOT_DIR . '/engine' );
}

if( ! class_exists( 'DLE_API' ) )
{
	class DLE_API
	{
		/**
		 * ��������� ������ DB
		 * @var object
		 */
     	var $db = false;
    	 	
		/**
		 * ������ API
		 * @var string
		 */
      	var $version = '0.07';
    	  	
		/**
		 * ����� ������� DLE
		 * @var array
		 */
      	var $dle_config = array ();
      	
		/**
		 * ���� �� ���������� � �����
		 * @var string
		 */
      	var $cache_dir = false;
      	
		/**
		 * ������ �� ����� ������� ����
		 * @var array
		 */      	
      	var $cache_files = array();
    	  	
		/**
		 * ����������� ������
		 * @return boolean
		 */
		function DLE_API()
		{
			if (!$this->cache_dir)
			{
				$this->cache_dir = ENGINE_DIR."/cache/";
			}
			return true;
		}
			
		/**
		 * ��������� ���������� � ������������ �� ��� ID
		 * @param $id int - ID ������������
		 * @param $select_list string - �������� ����� � ���������� ��� * ��� ����
		 * @return ������ � ������� � ������ ������ � false ���� ������������ �� ������
		 */	
		function take_user_by_id ($id, $select_list = "*")
		{
			$id = intval( $id );
			if( $id == 0 ) return false;
			$row = $this->load_table(USERPREFIX."_users", $select_list, "user_id = '$id'");
			if( count( $row ) == 0 )
				return false;
			else
				return $row;
		}
		
		/**
		 * ��������� ���������� � ������������ �� ��� �����
		 * @param $name string - ��� ������������
		 * @param $select_list string - �������� ����� � ���������� ��� * ��� ����
		 * @return ������ � ������� � ������ ������ � false ���� ������������ �� ������
		 */
		function take_user_by_name($name, $select_list = "*")
		{
			$name = $this->db->safesql( $name );
			if( $name == '' ) return false;
			$row = $this->load_table(USERPREFIX."_users", $select_list, "name = '$name'");
			if( count( $row ) == 0 )
				return false;
			else
				return $row;
		}
			
		/**
		 * ��������� ���������� � ������������ �� ��� ������
		 * @param $email string - ����� ������������
		 * @param $select_list string - �������� ����� � ���������� ��� * ��� ����
		 * @return ������ � ������� � ������ ������ � false ���� ������������ �� ������
		 */	
		function take_user_by_email($email, $select_list = "*")
		{
			$email = $this->db->safesql( $email );
			if( $email == '' ) return false;
			$row = $this->load_table(USERPREFIX."_users", $select_list, "email = '$email'");
			if( count( $row ) == 0 )
				return false;
			else
				return $row;
		}
		
		/**
		 * ��������� ������ ������������� ����������� ������
		 * @param $group int - ID ������
		 * @param $select_list string - �������� ����� � ���������� ��� * ��� ����
		 * @param $limit int - ���������� ���������� �������������
		 * @return 2-� ������ ������ � ������� � ������ ������ � false ���� ������������ �� ������
		 */
		function take_users_by_group ($group, $select_list = "*", $limit = 0)
		{
			$group = intval( $group );
			$data = array();
			if( $group == 0 ) return false;
			$data = $this->load_table(USERPREFIX."_users", $select_list, "user_group = '$group'", true, 0, $limit);
			if( count( $data ) == 0 )
				return false;
			else
				return $data;
		}
		
		/**
		 * ��������� ������ �������������, ������������� ��� ����������� IP
		 * @param $ip string - ������������ ��� IP
		 * @param $like bool - ������������ �� ����� ��� ������
		 * @param $select_list string - �������� ����� � ���������� ��� * ��� ����
		 * @param $limit int - ���������� ���������� �������������
		 * @return 2-� ������ ������ � ������� � ������ ������ � false ���� ������������ �� ������
		 */
		function take_users_by_ip ($ip, $like = false, $select_list = "*", $limit = 0)
		{
			$ip = $this->db->safesql( $ip );
			$data = array();
			if( $ip == '' ) return false;
			if( $like )
				$condition  = "logged_ip like '$ip%'";
			else
				$condition  = "logged_ip = '$ip'";
			$data = $this->load_table(USERPREFIX."_users", $select_list, $condition, true, 0, $limit);
			if( count( $data ) == 0 )
				return false;
			else
				return $data;
		}
		
		/**
		 * ����� ����� ������������
		 * @param $user_id int - ID ������������
		 * @param $new_name string - ����� ��� ������������
		 * @return bool - true � ������ ������ � false ����� ����� ��� ��� ������ ������ �������������
		 */
		function change_user_name ($user_id, $new_name)
		{
			$user_id = intval( $user_id );
			$new_name = $this->db->safesql( $new_name );
			$count_arr = $this->load_table(USERPREFIX."_users", "count(user_id) as count", "name = '$new_name'");
			$count = $count_arr['count'];
			
			if( $count > 0 ) return false;

			$old_name_arr = $this->load_table(USERPREFIX."_users", "name", "user_id = '$user_id'");
			$old_name = $old_name_arr['name'];
			$this->db->query( "UPDATE " . PREFIX . "_post SET autor='$new_name' WHERE autor='{$old_name}'" );
			$this->db->query( "UPDATE " . PREFIX . "_comments SET autor='$new_name' WHERE autor='{$old_name}' AND is_register='1'" );
			$this->db->query( "UPDATE " . USERPREFIX . "_pm SET user_from='$new_name' WHERE user_from='{$old_name}'" );
			$this->db->query( "UPDATE " . PREFIX . "_vote_result SET name='$new_name' WHERE name='{$old_name}'" );
			$this->db->query( "UPDATE " . PREFIX . "_images SET author='$new_name' WHERE author='{$old_name}'" );
			$this->db->query( "update " . USERPREFIX . "_users set name = '$new_name' where user_id = '$user_id'" );
			return true;

		}

		/**
		 * ��������� ������ ������������
		 * @param $user_id int - ID ������������
		 * @param $new_password string - ����� ������
		 * @return null
		 */
		function change_user_password($user_id, $new_password)
		{
			$user_id = intval( $user_id );
			$new_password = md5( md5( $new_password ) );
			$this->db->query( "update " . USERPREFIX . "_users set password = '$new_password' where user_id = '$user_id'" );
		}
		
		/**
		 * ��������� ������ ������������
		 * @param $user_id int - ID ������������
		 * @param $new_email string - ����� ����� ������������
		 * @return int - ����� ���
		 * 		-2: ������������ �����
		 * 		-1: ����� ����� ������������ ������ �������������
		 * 		 1: �������� ������ �������
		 */
		function change_user_email($user_id, $new_email)
		{
			$user_id = intval( $user_id );

			if( (! preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])'.'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', $new_email )) or (empty( $new_email )) )
			{
				return -2;
			}

			$new_email = $this->db->safesql( $new_email );
			$email_exist_arr = $this->load_table(USERPREFIX."_users", "count(user_id) as count", "email = '$new_email'");
			if ($email_exist_arr['count'] > 0) return -1;

			$q = $this->db->query( "update " . USERPREFIX . "_users set email = '$new_email' where user_id = '$user_id'" );
			return 1;			
		}
			
			
		/**
		 * ��������� ������ ������������
		 * @param $user_id int - ID ������������
		 * @param $new_group int - ID ����� ������ ������������
		 * @return bool - true � ������ ������ � false ���� ������ ID �������������� ������
		 */
		function change_user_group($user_id, $new_group)
		{
			$user_id = intval( $user_id );
			$new_group = intval( $new_group );
			if($this->checkGroup($new_group) === false) return false;
			$this->db->query( "update " . USERPREFIX . "_users set user_group = '$new_group' where user_id = '$user_id'" );
			return true;
		}
		
		/**
		 * ����������� ������������ �� ����� � ������
		 * @param $login string - ��� ������������
		 * @param $password string - ������ ������������
		 * @return bool
		 * 		true:	��������� �����������
		 * 		false:	����������� �� ��������
		 */
		function external_auth($login, $password)
		{
			$login = $this->db->safesql( $login );
			$password = md5( md5( $password ) );
			$arr = $this->load_table(USERPREFIX."_users", "user_id", "name = '$login' AND password = '$password'");
			if( ! empty( $arr['user_id'] ) )
				return true;
			else
				return false;
		}
		
		/**
		 * ���������� � ���� ������ ������������
		 * @param $login string - ��� ������������
		 * @param $password string - ������ ������������
		 * @param $email string - ����� ������������
		 * @param $group int - ������ ������������
		 * @return int - ���
		 * 		-4: ������ �������������� ������
		 * 		-3: ������������ �����
		 * 		-2: ����� ����� ������ �������������
		 * 		-1: ��� ������������ ���� ������, ��� �������
		 * 		 1: �������� ������ �������
		 */
		function external_register($login, $password, $email, $group)
		{
			$login = $this->db->safesql( $login );
			
			if( preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $login ) ) return -1;
			
			$notmd5password = $password;
			$password = md5( md5( $password ) );
			
			$not_allow_symbol = array ("\x22", "\x60", "\t", '\n', '\r', "\n", "\r", '\\', ",", "/", "�", "#", ";", ":", "~", "[", "]", "{", "}", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"', "'", " " );
			
			$email = $this->db->safesql(trim( str_replace( $not_allow_symbol, '', strip_tags( stripslashes( $email ) ) ) ) );

			$group = intval( $group );
			
			$login_exist_arr = $this->load_table(USERPREFIX."_users", "count(user_id) as count", "name = '$login'");
			if( $login_exist_arr['count'] > 0 ) return -1;
			
			$email_exist_arr = $this->load_table(USERPREFIX."_users", "count(user_id) as count", "email = '$email'");
			if( $email_exist_arr['count'] > 0 ) return -2;
			
			if (!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $email ) or (empty( $email )))	{
				return -3;
			}
			
			if (empty( $email ) OR strlen( $email ) > 50 OR @count(explode("@", $email)) != 2)
			{
				return -3;
			}
			
			if($this->checkGroup($group) === false) return -4;
			
			$now = time();
			$q = $this->db->query( "insert into " . USERPREFIX . "_users (email, password, name, user_group, reg_date) VALUES ('$email', '$password', '$login', '$group', '$now')" );
			
		
			//��� � ������� ���� $notmd5password = $password; ��������� ��� vAUTH �������� ������
			$is_fakemail = (stripos($email, "@fakemail"));
			$is_fakemail2 = (stripos($email, "@odnoklassniki"));
			
			if (($is_fakemail === false) && ($is_fakemail2 === false)) {
			include_once ENGINE_DIR . '/classes/mail.class.php';
			$mail = new dle_mail( $this->dle_config );

			$row = $this->db->super_query( "SELECT template FROM " . PREFIX . "_email where name='reg_sendmail' LIMIT 0,1" );
			
			$row['template'] = stripslashes( $row['template'] );
			
			$row['template'] = str_replace( "{%username%}", $login, $row['template'] );
			$row['template'] = str_replace( "{%password%}", $notmd5password, $row['template'] );
			
			$mail->send( $email, '����������� �� ����������� ����� domain.com', $row['template'] );
			}
			//���
			
			return 1;
		}		

		/**
		 * �������� ������������ ������������� ���������
		 * @param $user_id int - ID ����������
		 * @param $subject string - ���� ���������
		 * @param $text string - ����� ���������
		 * @param $from string - ��� �����������
		 * @return int - ���
		 * 		-1: ���������� �� ����������
		 * 		 0: �������� ���������
		 * 		 1: �������� ������ �������
		 */
		function send_pm_to_user($user_id, $subject, $text, $from)
		{
			$user_id = intval( $user_id );
			// Check if user exist
			$count_arr = $this->load_table(USERPREFIX."_users", "count(user_id) as count", "user_id = '$user_id'");
			if($count_arr['count'] == 0 ) return - 1;			

			$subject = $this->db->safesql( $subject );
			$text = $this->db->safesql( $text );
			$from = $this->db->safesql( $from );
			$now = time();
			$q = $this->db->query( "insert into " . PREFIX . "_pm (subj, text, user, user_from, date, pm_read, folder) VALUES ('$subject', '$text', '$user_id', '$from', '$now', 'no', 'inbox')" );
			if( ! $q ) return 0;

			
			$this->db->query( "update " . USERPREFIX . "_users set pm_unread = pm_unread + 1, pm_all = pm_all+1  where user_id = '$user_id'" );
			return 1;

		}
      	
		/**
		 * Service function - take params from table
		 * @param $table string - �������� �������
		 * @param $fields string - ����������� ���� ����� ������� ��� * ��� ����
		 * @param $where string - ������� �������
		 * @param $multirow bool - �������� �� ���� ��� ��� ���������
		 * @param $start int - ��������� �������� �������
		 * @param $limit int - ���������� ������� ��� �������, 0 - ������� ���
		 * @param $sort string - ����, �� �������� �������������� ����������
		 * @param $sort_order - ����������� ����������
		 * @return array � ������� ��� false ���� mysql ������� 0 �����
		 */
		function load_table ($table, $fields = "*", $where = '1', $multirow = false, $start = 0, $limit = 0, $sort = '', $sort_order = 'desc')
		{
			if (!$table) return false;

			if ($sort!='') $where.= ' order by '.$sort.' '.$sort_order;
			if ($limit>0) $where.= ' limit '.$start.','.$limit;
			$q = $this->db->query("Select ".$fields." from ".$table." where ".$where);
			if ($multirow)
			{
				while ($row = $this->db->get_row())
				{
					$values[] = $row;
				}
			}
			else
			{
				$values = $this->db->get_row();
			}
			if (count($values)>0) return $values;
			
			return false;

		}
        
		/**
		 * ������ ������ � ���
		 * @param $fname string - ��� ����� ��� ���� ��� ����������
		 * @param $vars - ������ ��� ������
		 * @return unknown_type
		 */
		function save_to_cache ($fname, $vars)
		{
			// @TODO ������� - ���
			$filename = $fname.".tmp";
			$f = @fopen($this->cache_dir.$filename, "w+");
			@chmod('0777', $this->cache_dir.$filename);
			if (is_array($vars)) $vars = serialize($vars);
			@fwrite($f, $vars);
			@fclose($f);
			return $vars;
		}
			
			
		/**
		 * �������� ������ �� ����
		 * @param $fnamee string - ��� ����� ��� ���� ��� ����������
		 * @param $timeout int - ����� ����� ���� � ��������
		 * @param $type string - ��� ������ � ����. ���� �� text - �������, ��� �������� ������
		 * @return unknown_type
		 */
		function load_from_cache ($fname, $timeout=300, $type = 'text')
		{
			$filename = $fname.".tmp";
			if (!file_exists($this->cache_dir.$filename)) return false;
			if ((filemtime($this->cache_dir.$filename)) < (time()-$timeout)) return false;

			if ($type=='text')
			{
				return file_get_contents($this->cache_dir.$filename);
			}
			else
			{
				return unserialize(file_get_contents($this->cache_dir.$filename));
			}
		}			

		/**
		 * �������� ����
		 * @param $name string - ��� ����� ��� ��������. ��� �������� GLOBAL ������� ���� ���
		 * @return null
		 */				
		function clean_cache($name = "GLOBAL")
		{
			$this->get_cached_files();
			
			if ($name=="GLOBAL")
			{
				foreach ($this->cache_files as $cached_file)
				{
					@unlink($this->cache_dir.$cached_file);
				}
			}
			elseif (in_array($name.".tmp", $this->cache_files))
			{
				@unlink($this->cache_dir.$name.".tmp");
			}
		}

		/**
		 * ��������� ������� ����������� �������� ������ ����
		 * @return array
		 */		
		function get_cached_files()
		{
			$handle = opendir($this->cache_dir);
			while (($file = readdir($handle)) !== false)
			{
				if ($file != '.' && $file != '..' && (!is_dir($this->cache_dir.$file) && $file !='.htaccess'))
				{
					$this->cache_files [] = $file;
				}
			}
			closedir($handle);
		}		

		/**
		 * ���������� ���������� �������
		 * @param $key string ��� array
		 * 		string: �������� ���������
		 * 		 array: ������������� ������ ����������
		 * @param $new_value - �������� ���������. �� ������������, ���� $key ������
		 * @return null;
		 */				
		function edit_config ($key, $new_value = '')
		{
			$find[] = "'\r'";
			$replace[] = "";
			$find[] = "'\n'";
			$replace[] = "";
			$config = $this->dle_config;
			if (is_array($key))
			{
				foreach ($key as $ckey=>$cvalue)
				{
					if ($config[$ckey])
					{
						$config[$ckey] = $cvalue;
					}
				}
			}
			else
			{
				if ($config[$key])
				{
					$config[$key] = $new_value;
				}
			}
			// ���������� ����� ������
			$handle = @fopen(ENGINE_DIR.'/data/config.php', 'w');
			fwrite( $handle, "<?PHP \n\n//System Configurations\n\n\$config = array (\n\n" );
			foreach ( $config as $name => $value )
			{
				if( $name != "offline_reason" )
				{
					$value = trim( stripslashes( $value ) );
					$value = htmlspecialchars( $value);
					$value = preg_replace( $find, $replace, $value );
					$name = trim( stripslashes( $name ) );
					$name = htmlspecialchars( $name, ENT_QUOTES );
					$name = preg_replace( $find, $replace, $name );
				}
				$value = str_replace( "$", "&#036;", $value );
				$value = str_replace( "{", "&#123;", $value );
				$value = str_replace( "}", "&#125;", $value );
				$name = str_replace( "$", "&#036;", $name );
				$name = str_replace( "{", "&#123;", $name );
				$name = str_replace( "}", "&#125;", $name );
				fwrite( $handle, "'{$name}' => \"{$value}\",\n\n" );
			}
			fwrite( $handle, ");\n\n?>" ); fclose( $handle ); $this->clean_cache(); } /** * ��������� �������� * @param $cat string - ��������� ��������, ����� ������� * @param $fields string - �������� ���������� ����� �������� ��� * ��� ���� * @param $start int - ��������� �������� ������� * @param $limit int - ���������� �������� ��� �������, 0 - ������� ��� ������� * @param $sort string - ����, �� �������� �������������� ���������� * @param $sort_order - ����������� ���������� * @return array - ������������� 2-� ������ ������ � ��������� */ function take_news ($cat, $fields = "*", $start = 0, $limit = 10, $sort = 'id', $sort_order = 'desc') { if ($this->dle_config['allow_multi_category'] == 1) { $condition = 'category regexp "[[:
    <:]]( '.str_replace(', ', '| ', $cat).')[[:>:]]"'; } else { $condition = 'category IN ('.$cat.')'; } return $this->load_table (PREFIX."_post", $fields, $condition, $multirow = true, $start, $limit, $sort, $sort_order); } /** * �������� ������������� ������ � ��������� ID * @param $group int - ID ������ * @return bool - true ���� ���������� � false ���� ��� */ function checkGroup($group) { $row = $this->db->super_query('SELECT group_name FROM '.USERPREFIX.'_usergroups WHERE id = '.intval($group)); return isset($row['group_name']); } /** * ��������� ���������������� ����� ������ * @param $name string - �������� ������, � ������ ����� .php ������������ � ����� engine/inc/, �� ��� ���������� ����� * @param $title string - ��������� ������ * @param $descr string - �������� ������ * @param $icon string - ��� ������ ��� ������, ��� �������� ����. ������ ����������� ��� ���� ������ ��������� � ����� engine/skins/images/ * @param $perm string - ���������� � ������� ������� �������� ����� ������� ������. ������ ���� ����� ��������� ��������� ��������: all ��� ID ����� ����� �������. ��������: 1,2,3. ���� ������� �������� all �� ������ ����� ������������ ���� ������������� ������� ������ � ����������� * @return bool - true ���� ������� ����������� � false ���� ��� */ function install_admin_module ($name, $title, $descr, $icon, $perm = '1') { $name = $this->db->safesql($name); $title = $this->db->safesql($title); $descr = $this->db->safesql($descr); $icon = $this->db->safesql($icon); $perm = $this->db->safesql($perm); // ��� ������ ��������� ������� ������ $this->db->query("Select name from `".PREFIX."_admin_sections` where name = '$name'"); if ($this->db->num_rows()>0) { // ������ ����, ��������� ������ $this->db->query("UPDATE `".PREFIX."_admin_sections` set title = '$title', descr = '$descr', icon = '$icon', allow_groups = '$perm' where name = '$name'"); return true; } else { // ������ ����, ��������� $this->db->query("INSERT INTO `".PREFIX."_admin_sections` (`name`, `title`, `descr`, `icon`, `allow_groups`) VALUES ('$name', '$title', '$descr', '$icon', '$perm')"); return true; } return false; } /** * �������� ���������������� ����� ������ * @param $name string - �������� ������ * @return null */ function uninstall_admin_module ($name) { $name = $this->db->safesql($name); $this->db->query("DELETE FROM `".PREFIX."_admin_sections` where name = '$name'"); } /** * ��������� ���� ���������������� ����� ������ * @param $name string - �������� ������ * @param $perm string - ���������� � ������� ������� �������� ����� ������� ������. ������ ���� ����� ��������� ��������� ��������: all ��� ID ����� ����� �������. ��������: 1,2,3. ���� ������� �������� all �� ������ ����� ������������ ���� ������������� ������� ������ � ����������� * @return null */ function change_admin_module_perms ($name, $perm) { $name = $this->db->safesql($name); $perm = $this->db->safesql($perm); $this->db->query("UPDATE `".PREFIX."_admin_sections` set allow_groups = '$perm' where name = '$name'"); } } } $dle_api = new DLE_API (); if( empty($config['version_id']) ) include_once (ENGINE_DIR . '/data/config.php'); $dle_api->dle_config = $config; if( ! isset( $db ) ) { include_once (ENGINE_DIR . '/classes/mysql.php'); include_once (ENGINE_DIR . '/data/dbconfig.php'); } $dle_api->db = $db; ?>
