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

if ($is_logged AND $user_group[$member_id['user_group']]['allow_all_edit']) {

	if ($_GET['hash'] == "" OR $_GET['hash'] != $dle_login_hash) {

		  die("Hacking attempt! User not found");

	}

	$id = intval($_GET['id']);

	if ($id > 0) {

		$row = $db->super_query("SELECT id, autor FROM " . PREFIX . "_post where id = '$id'");

		if ($row['id']) {

		   $db->query("DELETE FROM " . PREFIX . "_post WHERE id='{$row['id']}'");
		   $db->query("DELETE FROM " . PREFIX . "_comments WHERE post_id='{$row['id']}'");
		   $db->query("DELETE FROM " . PREFIX . "_poll WHERE news_id='{$row['id']}'");
		   $db->query("DELETE FROM " . PREFIX . "_poll_log WHERE news_id='{$row['id']}'");
		   $db->query("DELETE FROM " . PREFIX . "_tags WHERE news_id = '{$row['id']}'");
		   $db->query("UPDATE " . USERPREFIX . "_users set news_num=news_num-1 where name='{$row['autor']}'");


			$row_images = $db->super_query("SELECT images  FROM " . PREFIX . "_images where news_id = '{$row['id']}'");

			$listimages = explode("|||", $row_images['images']);

		    if ($row_images['images'] != "")
				foreach ($listimages as $dataimages) {

					$url_image = explode("/", $dataimages);

					if (count($url_image) == 2) {

						$folder_prefix = $url_image[0]."/";
						$dataimages = $url_image[1];

					} else {

						$folder_prefix = "";
						$dataimages = $url_image[0];

					}

					@unlink(ROOT_DIR."/uploads/posts/".$folder_prefix.$dataimages);
					@unlink(ROOT_DIR."/uploads/posts/".$folder_prefix."thumbs/".$dataimages);
			}

			$db->query("DELETE FROM " . PREFIX . "_images WHERE news_id = '{$row['id']}'");

		 	$db->query("SELECT id, onserver FROM " . PREFIX . "_files WHERE news_id = '{$row['id']}'");

			while($row_files = $db->get_row()){

				@unlink(ROOT_DIR."/uploads/files/".$row_files['onserver']);

			}

			$db->query("DELETE FROM " . PREFIX . "_files WHERE news_id = '{$row['id']}'");

			clear_cache();

		} else {

		  die("Hacking attempt! ID not found");

		}

	} else {

		  die("Hacking attempt! ID not found");
	}

	if ( strpos( $_SESSION['referrer'], "pages.php" ) !== false OR strpos( $_SESSION['referrer'], "do=deletenews" ) !== false OR $_SESSION['referrer'] == "") { 

		msgbox ($lang['all_info'], $lang['news_del_ok']);

	} else {

		header("Location: {$_SESSION['referrer']}");
		die();

	}

} else {

  die("Hacking attempt! Not logged");
}
?>