<?php
######## ���������� ����������������� ������� ################

#### ��������: ������ ����������� ������ ������ � �����.

$wwp="engine/modules/commentit"; //�������� ����� �� �������� �� �����

######## ��������: $wwp="comment"; / � �������� ����� comment
######## ��������: $wwp="module/comment"; / � ����� module/comment
##############################################################

$table="xhw7tid4_mservice_commentit"; //�������� ������� ��� ������������, ������������� ��� ������ ������ � 1 ��
$table2="xhw7tid4_mservice_commentit_config"; //�������� ������� �������� ������������, ������������� ��� ������ ������ � 1 ��

######## ��������� ����������� � MySQL (MSSQL)  ################

$coder=1; // ��������� ����� �� 1-cp1251(Win)/0-UTF 

$sql_host='localhost';   /// ����
$sql_id='domain';         /// �����
$sql_pass='KgnunGT8';    /// ������
$sql_db='domain';      /// ����

######### ����  ###########
#������ ������ ��������� � ����� /lang/
#".php" ������ �� �����. �������� �������������� ��������
# 
#�������� ��� ������������� ����������� ����� ����� ��������� ���:
#$mylang='english';
#!!!�������� ��� ������������� �������� ����� � UTF-8 ����� ��������� ���:
#$mylang='russianutf';

$mylang='russian';

######## ������ � ������� ################
$typeadm=1; //��� ����� � ������ ����������������� (1 - ����������� / 0 - ���-�����. ������������, ���� PHP ���������� ��� CGI)

$login='odmin'; //����� ���������

$pass='bktgct155'; //������

########## ������ ��������� ������ ######
$alphabet = "0123456789abcdefghijklmnopqrstuvwxyz";

# ������� ��� ���������
//$allowed_symbols = "0123456789"; #digits
$allowed_symbols = "23456789abcdeghkmnpqsuvxyz";

# ����� � ��������
$fontsdir = 'fonts';	

# ����� ������
$length = mt_rand(5,6); # �������� ���������� �� 5 �� 6 ��������
//$length = 6; // ׸���� ���������� � 6-�� ��������

# ������ ������� ������. (�� ��������� ����������� �������)
$width = 120;
$height = 60;
$fluctuation_amplitude = 5;

# ������� ����� ��������� (true - ��������� / false - ��������� )
$no_spaces = true;

# ���������� ����� �����
$show_credits = false; 
$credits = '';

# ����� (RGB, 0-255)
//$foreground_color = array(0, 0, 0);
//$background_color = array(220, 230, 255);
$foreground_color = array(mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
$background_color = array(mt_rand(200,255), mt_rand(200,255), mt_rand(200,255));

# JPEG �������� ������
$jpeg_quality = 90;

######### END ################
$link = mysql_connect ("$sql_host", "$sql_id", "$sql_pass") or $error.="<b>CommentIt Ajax. Error</b>: ".mysql_error()."<br />";
$link2 = mysql_select_db("$sql_db") or $error.="<b>CommentIt Ajax. Error</b>:".mysql_error()."<br />";
if ($coder==1) {mysql_query("SET NAMES 'cp1251'");$codername="windows-1251";} else {mysql_query("SET NAMES 'utf8'");$codername="UTF-8";}
$massparam='';$sql = mysql_query("SELECT * FROM `$table2`"); while ($rowclubs = @mysql_fetch_array($sql)){$massparam[$rowclubs['par']]=$rowclubs['val'];}
$massparam['nameadmin']=$login;
?>
