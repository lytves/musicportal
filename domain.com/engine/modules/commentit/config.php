<?php
######## Переменные месторасположения скрипта ################

#### ВНИМАНИЕ: Скрипт обязательно должен лежать в папке.

$wwp="engine/modules/commentit"; //Название папки со скриптом от корня

######## Например: $wwp="comment"; / в корневой папке comment
######## Например: $wwp="module/comment"; / в папке module/comment
##############################################################

$table="xhw7tid4_mservice_commentit"; //Название таблицы для комментариев, используеться для разных сайтов с 1 БД
$table2="xhw7tid4_mservice_commentit_config"; //Название таблицы настроек комментариев, используеться для разных сайтов с 1 БД

######## Настройка подключения к MySQL (MSSQL)  ################

$coder=1; // Кодировка Вашей БД 1-cp1251(Win)/0-UTF 

$sql_host='localhost';   /// Хост
$sql_id='domain';         /// Логин
$sql_pass='KgnunGT8';    /// Пароль
$sql_db='domain';      /// База

######### Язык  ###########
#Список языков находится в папке /lang/
#".php" писать не нужно. Регистро чувствительный параметр
# 
#Например для использования английского языка нужно прописать так:
#$mylang='english';
#!!!Например для использования русского языка в UTF-8 нужно прописать так:
#$mylang='russianutf';

$mylang='russian';

######## Доступ к админке ################
$typeadm=1; //Вид входа в панель администрирования (1 - Стандартный / 0 - Веб-форма. Используется, если PHP установлен как CGI)

$login='odmin'; //Логин латиницей

$pass='bktgct155'; //Пароль

########## Тонкие настройки каптчи ######
$alphabet = "0123456789abcdefghijklmnopqrstuvwxyz";

# Символы для отрисовки
//$allowed_symbols = "0123456789"; #digits
$allowed_symbols = "23456789abcdeghkmnpqsuvxyz";

# Папка с шрифтами
$fontsdir = 'fonts';	

# Длина каптчи
$length = mt_rand(5,6); # Случайно количество от 5 до 6 символов
//$length = 6; // Чёткое количество в 6-ть символов

# Размер рисунка каптчи. (По умолчанию оптимальный вариант)
$width = 120;
$height = 60;
$fluctuation_amplitude = 5;

# Пробелы между символами (true - Убираются / false - Оставлять )
$no_spaces = true;

# Отображать адрес сайта
$show_credits = false; 
$credits = '';

# Цвета (RGB, 0-255)
//$foreground_color = array(0, 0, 0);
//$background_color = array(220, 230, 255);
$foreground_color = array(mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
$background_color = array(mt_rand(200,255), mt_rand(200,255), mt_rand(200,255));

# JPEG качество каптчи
$jpeg_quality = 90;

######### END ################
$link = mysql_connect ("$sql_host", "$sql_id", "$sql_pass") or $error.="<b>CommentIt Ajax. Error</b>: ".mysql_error()."<br />";
$link2 = mysql_select_db("$sql_db") or $error.="<b>CommentIt Ajax. Error</b>:".mysql_error()."<br />";
if ($coder==1) {mysql_query("SET NAMES 'cp1251'");$codername="windows-1251";} else {mysql_query("SET NAMES 'utf8'");$codername="UTF-8";}
$massparam='';$sql = mysql_query("SELECT * FROM `$table2`"); while ($rowclubs = @mysql_fetch_array($sql)){$massparam[$rowclubs['par']]=$rowclubs['val'];}
$massparam['nameadmin']=$login;
?>
