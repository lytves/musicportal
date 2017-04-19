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

function CheckCanGzip(){

if (headers_sent() || connection_aborted() || !function_exists('ob_gzhandler') || ini_get('zlib.output_compression')) return 0; 

if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false) return "x-gzip"; 
if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) return "gzip"; 

return 0; 
}


function GzipOut($debug=0){
	global $config, $Timer, $db, $tpl, $_DOCUMENT_DATE;

$s = "
<!-- ����� ���������� ������� ".$Timer->stop()." ������ -->
<!-- ����� ����������� �� ���������� �������� ".round($tpl->template_parse_time, 5)." ������ -->
<!-- ����� ����������� �� ���������� MySQL ��������: ".round($db->MySQL_time_taken, 5)." ������ -->
<!-- ����� ���������� MySQL �������� ".$db->query_num." -->";

if($_DOCUMENT_DATE)
{
	@header ("Last-Modified: " . date('r', $_DOCUMENT_DATE) ." GMT");

} else {

	@header ("Last-Modified: " . date('r', time()-60*60*10) ." GMT");

}

if ($config['allow_gzip'] != "yes") {if ($debug) echo $s; ob_end_flush(); return;}

    $ENCODING = CheckCanGzip(); 

    if ($ENCODING){
        $s .= "\n<!-- ��� ������ �������������� ������ $ENCODING -->\n"; 
        $Contents = ob_get_contents(); 
        ob_end_clean(); 

        if ($debug){
            $s .= "<!-- ����� ������ �����: ".strlen($Contents)." ���� "; 
            $s .= "����� ������: ".
                   strlen(gzencode($Contents, 1, FORCE_GZIP)).
                   " ���� -->"; 
            $Contents .= $s; 
        }

        header("Content-Encoding: $ENCODING"); 

		$Contents = gzencode($Contents, 1, FORCE_GZIP);
		echo $Contents;
        exit; 

    }else{

        ob_end_flush(); 
        exit; 

    }
}
?>