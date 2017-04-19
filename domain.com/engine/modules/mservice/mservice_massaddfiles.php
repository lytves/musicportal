<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}
@error_reporting ( E_ALL ^ E_NOTICE );

switch ( $_REQUEST['act'] ) {

// ����� �������� �������� ������ Uploadify
case 'massaddfiles':

$metatags['title'] = '�������� �������� mp3-������ �� '.$config['home_title_short'];
$metatags['description'] = '�������� ���������� ����� mp3-������ �� '.$config['description'];
$metatags['keywords'] = '��������� mp3, �������� ������, �������� �������� '.$config['keywords'];

$mtitle .= <<<HTML
<style type="text/css">.script{display:none;}</style>
<script type="text/javascript" language="JavaScript">
document.write('<style type="text/css">.noscript{display:none;} .script{display:inline;} </style>');
</script>
HTML;

$mtitle .= '<div class="script"><h3 class="tit"><span>�������� ��������</span> mp3-������ (��� �1 - ����� ������, ��������)</h3></div>';

include_once ENGINE_DIR.'/modules/mservice/mod_massaddfiles.php';
include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';
$mcontent .= $popular_search_artists;
$mcontent .= $popular_search_tracks;
$mcontent .= $popular_search_albums;
break;

// ��������� case ��� ������� $_SESSION['mservice_files'] � ������ �� ����� temp
case 'masstemp':

// ������� ������ � ������� ������� ���� � �����, ����� ������ � �������
if (count($_SESSION['mservice_files']) > 0) {
	foreach ($_SESSION['mservice_files'] as $kluch1 => $znach1) {
		foreach ($znach1 as $znach2) {
			@unlink('/home2/mp3base/temp/'.$_SESSION['mservice_files'][$kluch1]['file']);
			unset($_SESSION['mservice_files'][$kluch1]);
		}
	}
	unset($kluch1, $znach1, $znach2);
}

header("location: {$config['http_home_url']}music/massaddfiles.html");
break;

// �������� ��������� �������������� ����� ������ ��� ���������� �� � ���� ������
case 'massaddfiles02':

$metatags['title'] = '�������� �������� mp3-������ (��� �2) �� '.$config['home_title_short'];
$metatags['description'] = '�������� ���������� ����� mp3-������ �� '.$config['description'];
$metatags['keywords'] = '��������� mp3, �������� ������, �������� �������� '.$config['keywords'];

if (count($_SESSION['mservice_files']) == 0) { 
	header("location: {$config['http_home_url']}music/massaddfiles.html"); 
	exit; 
}

if ( $user_group[$member_id['user_group']]['mservice_addfile'] != 1 ) $stop[] = '� ��� ��� ���� ��� ���������� ����� ������!'; 
        
// ������� ������ � ���� ��� �������� � ������� ��� ��� ������ ����� �� �����, �� ������� ��� ������
if (count($_SESSION['mservice_files']) > 0) {
	foreach ($_SESSION['mservice_files'] as $kluch1 => $znach1) {
		foreach ($znach1 as $znach2) {
			if (!is_file('/home2/mp3base/temp/'.$znach1['file'])) unset($_SESSION['mservice_files'][$kluch1]);
		}
	}
	unset($kluch1, $znach1, $znach2);
}

$mtitle .= '<h3 class="tit" style="margin-bottom:10px;"><span>�������� ��������</span> mp3-������ (��� �2 - ����/�������������� �������� ������)</h3>';

		include_once ENGINE_DIR.'/modules/mservice/mediatags/getid3.php';

      foreach($_SESSION['mservice_files'] as $kluch1 => $mass) {
        $getID3 = new getID3;
        $track = $getID3->analyze( '/home2/mp3base/temp/'.$mass['file'] );

        $artist = parseLinks( $parse->remove( $parse->process( convert_unicode( $track['id3v2']['comments']['artist'][0] ) ) ), $mscfg['parse_id3_tags'] );
        if ( empty( $artist ) ) $artist = parseLinks( $parse->remove( $parse->process( convert_unicode( $track['id3v1']['comments']['artist'][0] ) ) ), $mscfg['parse_id3_tags'] );
        
        $title = parseLinks( $parse->remove( $parse->process( convert_unicode( $track['id3v2']['comments']['title'][0] ) ) ), $mscfg['parse_id3_tags'] );
        if ( empty( $title ) ) $title = parseLinks( $parse->remove( $parse->process( convert_unicode( $track['id3v1']['comments']['title'][0] ) ) ), $mscfg['parse_id3_tags'] );
        
        $comment = parseLinks( $parse->remove( $parse->process( convert_unicode( $track['id3v2']['comments']['comments'][0] ) ) ), $mscfg['parse_id3_tags'] );
			
        $beats = intval( $track['bitrate'] / 1000 );
        $lenght = $track['playtime_string'];

        if ( empty( $artist ) ) $artist = '';
        if ( empty( $title ) ) $title = '';
        if ( empty( $comment ) ) $comment = '';
        if ( (empty( $lenght )) || ( empty( $beats ) )) {
          $lenght = '';
          $beats = '';
          $failed_tracks .= '<li>'.iconv("utf-8", "cp1251" ,urldecode($mass['name'])).'</li>';
          if ( is_file('/home2/mp3base/temp/'.$mass['file'])) {
            @unlink('/home2/mp3base/temp/'.$mass['file']);
            unset($_SESSION['mservice_files'][$numerok]);
          }
          continue;
        }
								
        $sizetemp = @filesize('/home2/mp3base/temp/'.$mass['file']);
        $lenghtsec = $track['playtime_seconds'];
      
        if ( $mscfg['allow_get_apic'] == 1 && isset($track['id3v2']['APIC'][0]['data']) ) {
          $viewcover_size = @show_size_cover(strlen($track['id3v2']['APIC'][0]['data']));
          $viewcover_base64 = @base64_encode($track['id3v2']['APIC'][0]['data']);
          $viewapic = '<img src="data:'.$track['id3v2']['APIC'][0]['mime'].';base64,'.$viewcover_base64.'" border="0" alt="������� �����" class="track_logo" style="margin-right:0px;" /><br /><span style="padding-right:50px;">'.$viewcover_size.'</span>';
          $viewapicdel = '<a href="#" onclick="del_apic('.$kluch1.', \''.$mass['file'].'\'); return false;"><img src="'.$THEME.'/images/uploadify-cancel.png" alt="������� �������" style="margin-right:3px;" align="top" />������� �������</a>';
        }
        $files .= '<div style="padding:10px 10px 0;" id="div_'.$kluch1.'"><span style="padding-left:10px; font-weight: bold; color:#3367AB"><a href="#" style="margin-right:10px;" onclick="infoTrack(\''.$lenghtsec.'\', '.$beats.', \''.$sizetemp.'\', '.$kluch1.'); return false;"><img src="'.$THEME.'/images/filesize.png" alt="�������� ���������� � �����" align="top" /></a> '.iconv("utf-8", "cp1251" ,urldecode($mass['name'])).'</span> <a href="#" onclick="del_track('.$kluch1.', \''.$mass['file'].'\'); return false;"><img src="'.$THEME.'/images/uploadify-cancel.png" alt="������� ���� �� ������" /></a>';
        $files .= '
            <input type="hidden" name="old_file_name[]" value="'.iconv("utf-8", "cp1251" ,urldecode($mass['name'])).'" />
            <input type="hidden" name="file_on_disk[]" value="'.$mass['file'].'" />
            <input type="hidden" name="dlina[]" value="'.$lenght.'" />
            <input type="hidden" name="bity[]" value="'.$beats.'" />
            <input type="hidden" name="kluch[]" value="'.$kluch1.'" />
    <table border="0" width="100%">
            <tr> 
            <td colspan="2"><div style="padding:5px 5px 5px 15px; background:#FBDB85; display:none; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px" id="info-tracks-layer'.$kluch1.'"></div> 
            </td> 
        </tr> 
        <tr>
            <td style="padding:10px 10px 10px 5px;" width="120">����������� <font color="#F7A8A8">*</font></td> 
            <td width="280" colspan="2"><input type="text" size="40" name="artist[]" class="f_input" value="'.$artist.'" /></td>
            <td width="155" rowspan="4"><div id="apic_'.$kluch1.'" style="text-align:right; color:#cc0000;">'.$viewapic.'<br />'.$viewapicdel.'</div></td>
        </tr> 
        <tr> 
          <td style="padding:10px 0 10px 5px;">�������� ����� <font color="#F7A8A8">*</font></td> 
          <td> 
             <input type="text" size="40" id="track_name'.$kluch1.'" name="title[]" class="f_input" value="'.$title.'" />
          </td> 
          <td width="135"><input style="height:18px; font-family:tahoma; font-size:11px; border:1px solid #DFDFDF; background: #FFFFFF; margin:0px 2px 2px;" title="����� � ���������� ������� �����" onclick="viewRTracks( '.$kluch1.'); infoTrack(\''.$lenghtsec.'\', '.$beats.', \''.$sizetemp.'\', '.$kluch1.'); return false;" type="button" value="����� ������� �����" /> 
          </td> 
        </tr>
        <tr> 
            <td colspan="2"><div style="padding:5px; background:#FBDB85; margin-bottom:5px; display:none; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px;" id="related-tracks-layer'.$kluch1.'"></div> 
            </td> 
        </tr> 
         <tr> 
            <td style="padding:10px 0 10px 5px;">�������� �����<br />(�����������)</td> 
            <td colspan="2"><textarea cols="30" style="height:50px; resize:vertical;" name="opisanie[]" class="f_input">'.$comment.'</textarea></td> 
        </tr>
    </table><div class="line" style="margin:5px 0;"></div></div>'; 
        unset($getID3, $track, $artist, $title, $comment, $beats, $lenght, $lenghtsec, $mass, $kluch1, $sizetemp, $viewapic, $viewapicdel);
      } 

		$rlnk = $config['http_home_url'].'music/rules.html'; 
    
    $tpl->load_template( 'mservice/massaddfiles02.tpl' ); 
        if ( $user_group[$member_id['user_group']]['mservice_captcha'] != 1 ) $tpl->set_block( "'\\[captcha\\](.*?)\\[/captcha\\]'si", "" ); 
        else $tpl->set_block( "'\\[captcha\\](.*?)\\[/captcha\\]'si", "\\1" );

if ( $failed_tracks != '' ) $tpl->set( '{failed_tracks}', '<div style="background:#F7A8A8; display:block; padding:10px; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px;">����(�) � ������������ �������� �/��� ������� �� �������� ������������ mp3:'.$failed_tracks.'</div>' );
else $tpl->set( '{failed_tracks}', '' );

if ($member_id['user_id'] == 1017) $tpl->set( '{odmin}', '<br /><input type="checkbox" name="tomoder" value="1" /> (��������� ����� �� ��������� � ��������� ���� �� ����� ������!)');
else $tpl->set( '{odmin}', '');

$rules = '<font color="#F7A8A8">*</font> �������� �����, �� ���������� <a href="'.$rlnk.'" target="_blank">���������������� ����������</a>';

$tpl->set( '{files}', $files ); 
$tpl->set( '{sec_image}', $lang['sec_image'] ); 
$tpl->set( '{rules}', $rules.'<br /> (<font color="#F7A8A8">*</font> ������ ������������ ��� ����������)' ); 

$tpl->compile( 'massaddfiles02' ); 
$mcontent .= $tpl->result['massaddfiles02']; 
$tpl->result['massaddfiles02'] = FALSE; 
break;
        
// ���������� �������� ����� ������ � ���������� �� � ���� ������
case 'domassaddfiles':

$metatags['title'] = '���������� �������� �������� mp3-������ �� '.$config['home_title_short'];
$metatags['description'] = '�������� ����������� ���������� ����� mp3-������ �� '.$config['description'];
$metatags['keywords'] = '��������� mp3, �������� ������, �������� �������� '.$config['keywords'];

if ( count($_SESSION['mservice_files']) == 0 ) { 
	header("location: {$config['http_home_url']}music/massaddfiles.html"); 
	exit; 
}
elseif ( count($_SESSION['mservice_files']) > 20) {
  unset($_SESSION['mservice_files']);
  header("location: {$config['http_home_url']}music/massaddfiles.html"); 
	exit; 
}

if ( $user_group[$member_id['user_group']]['mservice_addfile'] != 1 ) $stop[] = '� ��� ��� ���� ��� ���������� ����� ������!'; 

$hdd = 2;
$time = time();

$tomoder = intval($_POST['tomoder']);
if ($tomoder == 1) {
  $time = $time - 2592000;
  $approve = 0;
}
$subpapka = nameSubDir( $time, $hdd );

if ( $user_group[$member_id['user_group']]['mservice_captcha'] == 1 ) {
	if ( $_POST['sec_code'] != $_SESSION['sec_code_session'] OR ! $_SESSION['sec_code_session'] ) $stop[] = '�� ����� �������� �������� ��� � �����������. ��������� �� ���������� �������� � ���������� ����� ������ �������� ���. <a href="javascript:history.go(-1)">��������� �� ���������� ��������</a>';
}

if ( count( $stop ) == 0 ) {
		$mcontent .= '<div style="padding-left:15px;">';
        $global_message = ''; 
        $good = 0; 
        $bad = 0;
        unset($to_sql_insert);
        
        for ($i = 0, $countpost = count($_POST['file_on_disk']); $i < $countpost; $i++) {
			
          $filename = $_POST['file_on_disk'][$i]; 
          $oldfilename = $_POST['old_file_name'][$i];
          $title = $parse->remove( $parse->process( parseLinks( $_POST['title'][$i], $mscfg['parse_id3_tags'] ) ) );
          $artist = $parse->remove( $parse->process( parseLinks( $_POST['artist'][$i], $mscfg['parse_id3_tags'] ) ) );
          $lenght = $_POST['dlina'][$i];
          $beats = intval($_POST['bity'][$i]);
          $descr = $parse->remove( $parse->process( parseLinks( $_POST['opisanie'][$i], $mscfg['parse_id3_tags'] ) ) );
          $numerok = intval($_POST['kluch'][$i]);
			
          if ( $title == '' AND $artist == '' ) { 
            $message .= '<li>�� �� ����� ����������� � �������� ����� �����: <font color="#cc0000">'.$oldfilename.'</font>. ��������� �� ���������� �������� � ��������� ����.</li>';

           } elseif ( $artist == '' ) { 
             $message .= '<li>�� �� ����� ����������� ����� �����: <font color="#cc0000">'.$oldfilename.'</font>. ��������� �� ���������� �������� � ��������� ����.</li>';

           } elseif ( $title == '' ) { 
             $message .= '<li>�� �� ����� �������� ����� �����: <font color="#cc0000">'.$oldfilename.'</font>. ��������� �� ���������� �������� � ��������� ����.</li>';

           } elseif ( ($lenght == '') || ($beats == '') ) { 
              $message .= '<li>���� <font color="#cc0000">'.$oldfilename.'</font> ����� ������������ ������ ��� �� �������� �����������!</li>';
              /* �������� ��-mp3-�����*/
              if ( is_file('/home2/mp3base/temp/'.$filename)) {
                @unlink('/home2/mp3base/temp/'.$filename);
                unset($_SESSION['mservice_files'][$numerok]);
              }
			     } 
           if ($message != '') { 
                $bad++; 
                $global_message .= $message; 
                unset($message); 
                continue; 
            } 
            $art_tit = $artist.' - '.$title;
            
                                           /*  ��� ����������  ������ � ����� */
                                           /*  ��� ����������  ������ � ����� */
                                           /*  ��� ����������  ������ � ����� */
            $row = $db->super_query( "SELECT COUNT(1) as count FROM ".PREFIX."_mservice WHERE title = '$title' AND artist = '$artist'" );

            if ( $row['count'] != 0 ) {
              $message = '������: ���� <font color="#cc0000">'.$oldfilename.'</font> ('.$art_tit.') ��� ���� �� ����� �����!<br />';
			
              /// �������� �����,���� � ����� ������ � ��������� ��� ���� � ����
              if ( is_file('/home2/mp3base/temp/'.$filename)) {
                @unlink('/home2/mp3base/temp/'.$filename);
                unset($_SESSION['mservice_files'][$numerok]);
              }
              $bad++; 
              $global_message .= $message; 
              unset($message); 
              continue; 
            } else {
              for ($g = 0, $count2 = count($good_art_tit); $g < $count2; $g++) {
                if ($art_tit == $good_art_tit[$g]) {
                  $message = '������: ���� <font color="#cc0000">'.$oldfilename.'</font> ('.$art_tit.') ����� �� �� ������ "����������� - �������� �����" ��� � ������ ����, ������� �� ����������!<br />';
                  $bad++; 
                  $global_message .= $message; 
                  unset($message); 
                  continue(2); 
                }
              }
              $good_art_tit[] = $art_tit;

              if ( $user_group[$member_id['user_group']]['mservice_newtrack_approve'] == 1 AND $tomoder != 1 ) $approve = 1;
              else $approve = 0; 
		
              $transartist = totranslit( $artist );

              $strip_base64_artist = str_replace('/','_',str_replace('+','*',base64_encode($artist)));
              $strip_base64_title = str_replace('/','_',str_replace('+','*',base64_encode($title)));
              // ����� ���� ������ ����� �����, ������������ ������ ��������
              $uploaderid = $member_id['user_id'];
              
              if ($member_id['user_id'] == 1017) {
                $uploaderid = 50597;
                $change_lastdate = TRUE;
              }
              
              if (@rename('/home2/mp3base/temp/'.$filename, '/home2/mp3base/'.$subpapka.'/'.$filename)) {
					      unset($_SESSION['mservice_files'][$numerok]);
					      
//////////////////////////////////////////////////////////////////////* ����������� ID3 ����� ����� */
          include_once ENGINE_DIR.'/modules/mservice/mediatags/getid3.php';
           
					// Initialize getID3 engine
					$getID3 = new getID3;
					$getID3->encoding = $TaggingFormat;
 
					require_once(ENGINE_DIR.'/modules/mservice/mediatags/write.php');
					// Initialize getID3 tag-writing module
					$tagwriter = new getid3_writetags;
					$tagwriter->filename = '/home2/mp3base/'.$subpapka.'/'.$filename;
					$tagwriter->tagformats = array('id3v1', 'id3v2.3');
					
					//��������� ���� �� ����� ��� ��������� �������
          $ThisFileInfo = $getID3->analyze($tagwriter->filename);
					
					// set various options (optional)
					//$tagwriter->tag_encoding = 'cp1251';
					$tagwriter->overwrite_tags = true;
					$tagwriter->remove_other_tags = false;
					
					// populate data array
					$TagData['title'][] = $title.' (domain.com)';
					$TagData['artist'][] = $artist;
					$TagData['album'][] = 'domain.com';
					$TagData['genre'][] = 'domain.com';
					$TagData['comment'][] = $descr;
					if (isset($ThisFileInfo['id3v2']['APIC'][0]['data'])) {
						$TagData['attached_picture'][0]['data'] = $ThisFileInfo['id3v2']['APIC'][0]['data'];
						$TagData['attached_picture'][0]['picturetypeid'] = $ThisFileInfo['id3v2']['APIC'][0]['picturetypeid'];
						$TagData['attached_picture'][0]['description'] = $ThisFileInfo['id3v2']['APIC'][0]['description'];
						$TagData['attached_picture'][0]['mime'] = $ThisFileInfo['id3v2']['APIC'][0]['mime'];
					}
					
					$tagwriter->tag_data = $TagData;
					
					// write tags 
					$tagwriter->WriteTags();
					unset($getID3, $tagwriter, $ThisFileInfo, $TagData);
///////////////////////////////////////////////////////////////////////////* ����� ����������� ID3 ����� ����� */

              $crc32b = @hash_file('crc32b', '/home2/mp3base/'.$subpapka.'/'.$filename);
              $size = @filesize('/home2/mp3base/'.$subpapka.'/'.$filename);
              $to_sql_insert[] = "( '$time', '$title', '0', '$approve', '0', '$artist', '0', '$descr', '$filename', '$hdd', '$size', '$crc32b', '$uploaderid', '0', '$transartist', '$lenght', '$beats', '$strip_base64_artist', '$strip_base64_title' )";
              $lenghtsec = explode(":", $lenght);
              if (($lenghtsec[0]*60+$lenghtsec[1]) < 120) $lenght_small .= '<span style="color:#3367ab; font-weight:bold;">'.$artist.' - '.$title.' ('.$lenght.')</span><br />';
              $good++;
              } 
            } 
         }
        if ($good != 0 AND count($to_sql_insert) != 0) {
           $sql_values = implode(',', $to_sql_insert);
           $db->query ( "INSERT INTO ".PREFIX."_mservice ( time, title, rating, approve, vote_num, artist, download, description, filename, hdd, size, crc32, uploader, view_count, transartist, lenght, bitrate, strip_base64_artist, strip_base64_title ) VALUES $sql_values");
           if ($change_lastdate) $db->query( "UPDATE ".PREFIX."_users SET lastdate = '$time' WHERE user_id = '$uploaderid'" );
        }
        
        $mtitle .= '<h3 class="tit" style="margin-bottom:10px;"><span>���������� �������� ��������</span> mp3-������</h3>';
        
        include_once ENGINE_DIR . '/classes/mail.class.php';
        $mailsend = new dle_mail( $config );
        $mailsend->from = 'robot@domain.com';

		if ( $user_group[$member_id['user_group']]['mservice_newtrack_approve'] == 1 ) { 
			$mcontent .= <<<HTML
{$global_message}<div id="result_mass"></div>
<script type="text/javascript">
showResultMass( {$good}, {$bad}, '' );
</script>
HTML;

			$maila = langdate( 'D d F Y - H:i', time() ).' ���� <span style="color:#3367ab; font-weight:bold;">'.$member_id['name'].'</span> ������� �������� ���������: <span style="color:#16A20D; font-weight:bold;">'.$good.'</span> ������, � �������� <span style="color:#cc0000; font-weight:bold;">'.$bad.'</span> ������!';
			$mailsend->html_mail = true;
		  $mailsend->send( "odmin@domain.com", "����� ����� �� ����� domain.com", "$maila" );
		} else {
			if ($good > 0) $moder = ' �������� ��������, ���� ����� ����� �������� � ����������� ������ ������ ����� ���� ��� ������� ���������.';
			else $moder = '';
			$mcontent .= <<<HTML
			{$global_message}<div id="result_mass"></div>
<script type="text/javascript">
showResultMass( {$good}, {$bad}, '{$moder}' );
</script>
HTML;
			$maila = langdate( 'D d F Y - H:i', time() ).' ���� <span style="color:#3367ab; font-weight:bold;">'.$member_id['name'].'</span> ������� �������� ���������: <span style="color:#16A20D; font-weight:bold;">'.$good.'</span> ������, � �������� <span style="color:#cc0000; font-weight:bold;">'.$bad.'</span> ������! ����� ���������� <span style="color:#cc0000; font-weight:bold;">�� ���������!</span>';
			$mailsend->html_mail = true;
			$mailsend->send( "odmin@domain.com", "����� ����� �� ��������� �� ����� domain.com", "$maila" );
		}
		if ($lenght_small != '') {
      $maila = langdate( 'D d F Y - H:i', time() ).' �� ���� domain.com �������(-�) ����(-�) � <span style="color:#cc0000; font-weight:bold;">������������� ������ 2 �����:</span><br />'.$lenght_small;
			$mailsend->html_mail = true;
			$mailsend->send( "odmin@domain.com", "�� ���� domain.com �������(-�) ����(-�) � ������������� ������ 2 �����!", "$maila" );
    }
}

include_once ENGINE_DIR.'/modules/mservice/mod_massaddfiles.php';
include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';

$mcontent .= $popular_search_artists;
$mcontent .= $popular_search_tracks;
$mcontent .= $popular_search_albums;
$mcontent .= '</div>';

unset($_SESSION['mservice_files'],$numerok,$time,$title,$approve,$artist,$descr,$filename,$oldfilename,$hdd,$size,$crc32b,$uploaderid,$transartist,$lenght,$beats,$strip_base64_artist,$strip_base64_title,$subpapka);
break;

}
?>
