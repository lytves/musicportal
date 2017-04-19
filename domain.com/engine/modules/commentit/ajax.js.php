<?php include_once (dirname(__FILE__).'/config.php');include_once (dirname(__FILE__).'/lang/'.$mylang.'.php'); header("content-type: application/x-javascript;charset=$codername"); session_start();?>

function send_message() {var oldid=document.getElementById('oldid').value;makeRequestpost('/<?php echo $wwp;?>/func.php',oldid);}

function put_smile(s){ var smilebar = document.getElementById("textz"); if (smilebar.value.length == 0) smilebar.value=s+' '; else smilebar.value = smilebar.value+' '+s+' '; smilebar.focus(); commenthide(); return;}
function insert(start, end) {element = document.getElementById('textz');if (document.selection) {element.focus();sel = document.selection.createRange();sel.text = start + sel.text + end;} else if (element.selectionStart || element.selectionStart == '0') {element.focus();var startPos = element.selectionStart;var endPos = element.selectionEnd;element.value = element.value.substring(0, startPos) + start + element.value.substring(startPos, endPos) + end + element.value.substring(endPos, element.value.length);} else {element.value += start + end;}}

function getsize (num) {
if (num>0&&num<8){insert('[size='+num+']','[/size]');
document.getElementById('commentsize').selectedIndex = 0;}else {document.getElementById('commentsize').selectedIndex = 0;return false;}
}

function getColorTable() {
var perline = 8;
var colors = Array('#000000|Black','#A0522D|Sienna','#556B2F|DarkOliveGreen','#006400|DarkGreen','#483D8B|DarkSlateBlue','#000080|Navy','#4B0082|Indigo','#2F4F4F|DarkSlateGray','#8B0000|DarkRed','#FF8C00|DarkOrange','#808000|Olive','#008000|Green','#008080|Teal','#0000FF|Blue','#708090|SlateGray','#696969|DimGray','#FF0000|Red','#F4A460|SandyBrown','#9ACD32|YellowGreen','#2E8B57|SeaGreen','#48D1CC|MediumTurquoise','#4169E1|RoyalBlue','#800080|Purple','#808080|Gray',' #FF00FF|Magenta','#FFA500|Orange','#FFFF00|Yellow','#00FF00|Lime','#00FFFF|Cyan','#00BFFF|DeepSkyBlue','#9932CC|DarkOrchid','#C0C0C0|Silver','#FFC0CB|Pink','#F5DEB3|Wheat','#FFFACD|LemonChiffon','#98FB98|PaleGreen','#AFEEEE|PaleTurquoise','#ADD8E6|LightBlue','#DDA0DD|Plum','#FFFFFF|White');
var tableCode = '';
tableCode += '<table style="background-color:#F0F0F0;border:8px;" border="0" cellspacing="1" cellpadding="1">';
for (i = 0; i < colors.length; i++) {
if (i % perline == 0) { tableCode += '<tr>'; }
spscolor=colors[i].split('|');
tableCode += '<td bgcolor="#F0F0F0"><a style="outline: 1px solid #000000; color: ' 
+ spscolor[0] + '; background: ' + spscolor[0] + ';font-size: 10px;" title="' 
+ spscolor[1] + '" href="javascript:insert(\'[COLOR='+spscolor[1]+']\',\'[/COLOR]\');commenthide();">   </a></td>';
if (i % perline == perline - 1) { tableCode += '</tr>'; }
}
if (i % perline != 0) { tableCode += '</tr>'; }
tableCode += '</table>';
return tableCode;
}
function showhide(id){
var e=document.getElementById(id);
var IE='\v'=='v';if(IE) {e.style.left=5+'px';}
e.style.top = 20 + 'px';
if( e ) e.style.display = e.style.display ? "" : "none";
}

<?php if ((($massparam['colorbb']==1)||($massparam['smile']==1))&&($massparam['bbpanel']==1)){?>
function commenthide()
{
if( document.getElementById('colorpicz') ) document.getElementById('colorpicz').style.display='none';
if( document.getElementById('smilebar') ) document.getElementById('smilebar').style.display='none';
}
document.onclick=commenthide;
<?php }?>

function autosize(textarea){
var heightLimit = 720; 
var dif = parseInt(textarea.scrollHeight) - parseInt(textarea.clientHeight);
if (dif>0){
if (isNaN(parseInt(textarea.style.height))){
textareaHeight = textarea.scrollHeight*1 + 8;
}else{
textareaHeight = parseInt(textarea.style.height) + parseInt(dif)+8;
}
if (textareaHeight>heightLimit){
if (parseInt(textarea.clientHeight)<heightLimit) textarea.style.height = heightLimit+"px";
return;
}
textarea.style.height = textareaHeight+"px";
dif = parseInt(textarea.scrollHeight) - parseInt(textarea.clientHeight);
if ((dif+8)>0)textarea.style.height = parseInt(textarea.style.height) + parseInt(dif)+8+ "px";
}
}

function add_link() {var link=window.prompt('URL:','http://');if (link) {insert('[url='+link+']','[/url]');}}
function add_pic() { var link=window.prompt('URL:','http://'); if (link) {insert('[img='+link+']','');}}

function makeRequestpost(url,oldid) {
var http_request = false;
var str1 = document.getElementById("nick").value;
var str2 = document.getElementById("textz").value;
var testmail = document.getElementById("usmail").value;
var testurl = document.getElementById("usurl").value;
var pattern = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{1,30})+$/;
var pattern2 = /(\w+):\/\/([^/:]+)/;

if ((str1 == "")||(str1.search(/[^\s]+/)==-1)) {
alert('<?php echo $langcommentit['bad_name'];?>');
return false;
}

if (testmail !="")
{if (!pattern.test(testmail)){
	alert('<?php echo $langcommentit['bad_mail'];?>');
    return false;}
}  

if (testurl !="")
{if (!pattern2.test(testurl)){
	alert('<?php echo $langcommentit['bab_url'];?>');
    return false;}
}  
var re=/\[.*?\]/gi;
str3=str2.replace(re,"");

if ((str2 == "")||(str2.search(/[^\s]+/)==-1)||(str3 == "")||(str3.search(/[^\s]+/)==-1)) {
alert('<?php echo $langcommentit['bad_comment'];?>');
return false;
}

if (str2.length > <?php echo $massparam['sumvl'];?>) {
alert('<?php echo $langcommentit['bab_max_symbol'];?> <?php echo $massparam['sumvl'];?>');
return false;
}

function get_time(){
return parseInt(new Date().getTime()/1000)
}
time_now = get_time();

var prev_comm_time = $("input[name=hidden_inp]").val();

if ((time_now - prev_comm_time) < 30 ) {
alert('Время между добавлениями комментариев не менее 30 секунд!');
return false;
}

if (window.XMLHttpRequest) {http_request = new XMLHttpRequest();if (http_request.overrideMimeType) {http_request.overrideMimeType('text/xml');}} else if (window.ActiveXObject) {try {http_request = new ActiveXObject("Msxml2.XMLHTTP");} catch (e) {try {http_request = new ActiveXObject("Microsoft.XMLHTTP");} catch (e) {}}}if (!http_request) {alert('<?php echo $langcommentit['core_http'];?>');return false;}
http_request.onreadystatechange = function() { alertContents(http_request,oldid); };
http_request.open('POST', url, true);
http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
var textz2 = encodeURIComponent(document.getElementById("textz").value);
var nick2 = document.getElementById("nick").value;
var keystring2 = document.getElementById("keystringz").value;
var urls2 = encodeURIComponent(document.getElementById("urls").value);
var forms2 = document.getElementById("forms").value;
var usmail2 = document.getElementById("usmail").value;
var idcomm2 = document.getElementById("idcomnow").value;
var toke2 = document.getElementById("token").value;
var usurl2 = encodeURIComponent(document.getElementById("usurl").value);
var xmlString = "namenew="+nick2+"&comment="+textz2+"&url="+urls2+"&addcomment="+forms2+"&keystring="+keystring2+"&usmail="+usmail2+"&idcomnow="+idcomm2+"&usurl="+usurl2+"&tokenz="+toke2;
http_request.send(xmlString);
document.getElementById('tableDiv').style.display='';
document.getElementById('miniload').style.display='';
if (document.getElementById('enter')) {document.getElementById('enter').disabled=true;}
}

///////////Listing
function makeRequest(url,iddiv,oldid) {
var http_request = false;if (window.XMLHttpRequest) {http_request = new XMLHttpRequest();if (http_request.overrideMimeType) {http_request.overrideMimeType('text/xml');}} else if (window.ActiveXObject) {try {http_request = new ActiveXObject("Msxml2.XMLHTTP");} catch (e) {try {http_request = new ActiveXObject("Microsoft.XMLHTTP");} catch (e) {}}}if (!http_request) {alert('<?php echo $langcommentit['core_http'];?>');return false;}
http_request.onreadystatechange = function() { alertContents2(http_request,iddiv); };
http_request.open('GET', url, true);
http_request.send(null);
document.getElementById('tableDiv').style.display='';
if (document.getElementById('enter')) {document.getElementById('enter').disabled=true;}
if (document.getElementById('oldid').value != 'addfomz') {
document.getElementById('addfomz').innerHTML=document.getElementById(oldid).innerHTML;
document.getElementById('addfomz').style.display='';
document.getElementById('oldid').value='addfomz';
document.getElementById(oldid).innerHTML="";
}
}
///////


function alertContents(http_request,oldid) {
var olddiv = document.getElementById('oldid').value;
if (http_request.readyState == 4) {
if (http_request.status == 200) {
	if (document.getElementById('oldid').value != 'addfomz') {
	document.getElementById('addfomz').style.display='';
	document.getElementById('oldid').value='addfomz';
	document.getElementById('addfomz').innerHTML=document.getElementById(olddiv).innerHTML;
	}
	
var ra = http_request.responseText.split('$##$##$');
document.getElementById('ok').innerHTML = ra[1];

var x = ra[0];
var y = document.createElement ('script');
y.defer = true;
y.text = x;
document.body.appendChild (y);

$("input[name=hidden_inp]").val(time_now);

} else {alert('<?php echo $langcommentit['core_error_ajax'];?>');}
document.getElementById('tableDiv').style.display='none';
document.getElementById('miniload').style.display='none';
if (document.getElementById('enter')) {document.getElementById('enter').disabled=false;}
if (document.getElementById('errorcamp').value=="0"){
	<?php if ($massparam['moder']==0) {?>
	<?php if ($massparam['hideaddform']==1) {?>
	document.getElementById('addfomz').innerHTML = '<?php echo $langcommentit['skin_no_moder_comment'];?>';
	<?php 
	}
	else 
	{
	?>
	document.getElementById("textz").value="";
	<?php if (!$massparam['loginzaglob'] && !isset($_SESSION['dle_name'])) {?>
	document.getElementById("nick").value="";
	<?php }?>
	if (document.getElementById("usmail")) document.getElementById("usmail").value="";
	if (document.getElementById("keystringz")) document.getElementById("keystringz").value="";
		document.getElementById('oldid').value='addfomz';
		document.getElementById('idcomnow').value='0';
		if (document.getElementById('capt')) {refcapt();}
	<?php 
	}
	}
	else {
	?>
		<?php if ($massparam['hideaddform']==1) {?>
		document.getElementById('addfomz').innerHTML = '<?php echo $langcommentit['skin_no_moder_comment'];?>';
		<?php 
		}
		else 
		{
		?>
		document.getElementById("textz").value="";
		<?php if (!$massparam['loginzaglob'] && !$_SESSION['dle_name']) {?>
		document.getElementById("nick").value="";
		<?php }?>
		if (document.getElementById("usmail")) document.getElementById("usmail").value="";
		if (document.getElementById("keystringz")) document.getElementById("keystringz").value="";
		document.getElementById('oldid').value='addfomz';
		document.getElementById('idcomnow').value='0';
		if (document.getElementById('capt')) {refcapt();}
		<?php 
		}
		
		?>
	if (!document.getElementById("moder")) alert('<?php echo $langcommentit['skin_moder_comment'];?>');
	if (document.getElementById('capt')) {refcapt();}
	<?php }?>
	}
	else
	{
	if (document.getElementById('capt')) {refcapt();}
	if (document.getElementById('commentvis')) {if (document.getElementById('commentvis').value='1') {document.getElementById('addfomz').style.display='none';}}
	}
	
if (olddiv != 'addfomz') {<?php echo 'document.getElementById(olddiv).scrollIntoView(true)';?>;}
else {<?php  if ($massparam['sort']==1) echo 'document.getElementById("commentitstart").scrollIntoView(true)';?>
      <?php  if ($massparam['sort']==2) echo 'document.getElementById(olddiv).scrollIntoView(true)';?>
}

	}

}

function alertContents2(http_request,iddiv) {
if (http_request.readyState == 4) {
if (http_request.status == 200) {
document.getElementById(iddiv).innerHTML = http_request.responseText;
} else {
alert('<?php echo $langcommentit['core_error_ajax'];?>');
}
document.getElementById('tableDiv').style.display='none';
if (document.getElementById('enter')) {document.getElementById('enter').disabled=false;}
}}

function refcapt() {var newcapt = document.getElementById("capt").src;document.getElementById('capt').src= newcapt+'&'+Math.random();}

function otvet(idcomment,iddiv,quer) {
specspan='span-'+idcomment;
repl='repl-'+idcomment;
idspan='spanq-'+idcomment;
oldrepl=iddiv.replace('comment','repl');
oldspan=iddiv.replace('comment','span');
spsquer='z'+idcomment;
names='n'+idcomment;
document.getElementById(specspan).style.display='none';
document.getElementById(repl).style.display='';
if (document.getElementById(oldspan)) {
document.getElementById(oldspan).style.display='';
//document.getElementById(oldrepl).style.display='none';
document.getElementById(idspan).style.display='none';
}
document.getElementById('idcomnow').value=idcomment;
idcomment='comment-'+idcomment;
document.getElementById(idcomment).innerHTML=document.getElementById(iddiv).innerHTML;
if (quer==1) {
document.getElementById(iddiv).innerHTML='';
cuttext=document.getElementById(spsquer).innerHTML.replace(/<br>/gi,"\r\n");

var re=/<\S[^><]*>/gi;
cuttext=cuttext.replace(re,"");
login=document.getElementById(names).innerHTML;
login=login.replace(/<.*?>/gi,'');
cuttext="<?php echo $langcommentit['skin_reply_for']; ?>[b]"+login+"[/b]\r\n"+cuttext;
document.getElementById('textz').value="[quote]"+cuttext+"[/quote]\r\n\r\n";
}
document.getElementById(iddiv).innerHTML='';
document.getElementById('oldid').value=idcomment;
}

function resetrepl(newdiv,olddiv)
{
oldspan=olddiv.replace('comment','span');
oldrepl=olddiv.replace('comment','repl');
oldspanq=olddiv.replace('comment','spanq');
document.getElementById(oldspan).style.display='';
document.getElementById(oldspanq).style.display='';
document.getElementById(oldrepl).style.display='none';
document.getElementById('addfomz').style.display='';
document.getElementById('oldid').value='addfomz';
document.getElementById('addfomz').innerHTML=document.getElementById(olddiv).innerHTML;
document.getElementById(olddiv).innerHTML="";
document.getElementById('idcomnow').value='0';
}

function commentrating (url,iddiv)
{
var oldval=document.getElementById(iddiv).innerHTML;
var http_request = false;if (window.XMLHttpRequest) {http_request = new XMLHttpRequest();if (http_request.overrideMimeType) {http_request.overrideMimeType('text/xml');}} else if (window.ActiveXObject) {try {http_request = new ActiveXObject("Msxml2.XMLHTTP");} catch (e) {try {http_request = new ActiveXObject("Microsoft.XMLHTTP");} catch (e) {}}}if (!http_request) {alert('<?php echo $langcommentit['core_http'];?>');return false;}
http_request.onreadystatechange = function() { alertContents3(http_request,iddiv,oldval); };
http_request.open('GET', url, true);
http_request.setRequestHeader('X-Requested-With','XMLHttpRequest');
http_request.send(null);
document.getElementById(iddiv).innerHTML='<img alt="" title="" style="float:left;" src="/<?php  echo $wwp;?>/im/loadermini.gif" border="0" />';
}

function alertContents3(http_request,iddiv,oldval) {
var color;
if (http_request.readyState == 4) {
if (http_request.status == 200) {
if (http_request.responseText=='z') {alert('<?php  echo $langcommentit['core_vote'];?>');document.getElementById(iddiv).innerHTML=oldval;}
else {
document.getElementById(iddiv).innerHTML = http_request.responseText;
if (http_request.responseText==0) {color='#CCC';}
if (http_request.responseText>0) {color='#339900';}
if (http_request.responseText<0) {color='#FF0000';}
document.getElementById(iddiv).style.color=color;
}
} else {
alert('<?php echo $langcommentit['core_error_ajax'];?>');
}
}}

function exitcomment() {

function Get_Cookie( name ) {
var start = document.cookie.indexOf( name + "=" );
var len = start + name.length + 1;
if ( ( !start ) &&
( name != document.cookie.substring( 0, name.length ) ) )
{
return null;
}
if ( start == -1 ) return null;
var end = document.cookie.indexOf( ";", len );
if ( end == -1 ) end = document.cookie.length;
return unescape( document.cookie.substring( len, end ) );
}

function Delete_Cookie( name, path, domain ) {
if ( Get_Cookie( name ) ) document.cookie = name + "=" +
( ( path ) ? ";path=" + path : "") +
( ( domain ) ? ";domain=" + domain : "" ) +
";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}

Delete_Cookie('PHPSESSID','/','');
location.reload(true);
}