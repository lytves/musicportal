function chFavicon(iconHref){
icon=$('link[rel="shortcut icon"]');
cache=icon.clone();
cache.attr("href", iconHref);
icon.replaceWith(cache);
}

function showAlbumSize( format_album_size, theme) {
  $('#album-size').html('<table><tr><td><img src="'+theme+'/images/filesize.png" align="top" /> &nbsp; Размер альбома: </td><td width="20"></td><td><div class="file-info" style="background:none repeat scroll 0 0 #FBDB85; min-width:0; margin:5px 0;"><i>'+format_album_size+'</i></div></td></tr></table>');
}

function showResultAdd( vlnk, artist, name, moder ) {
  $('#result_add').html("<span>Добавленный Вами аудио трек <a href='"+vlnk+"'><b>"+artist+"</b> - "+name+"</a>, был успешно сохранён на сервере и добавлен на наш сайт!"+moder+"</span>");
    $("#result_add").fadeTo(20000, 0.5);
}

function showResultMass( good, bad, moder ) {
  $('#result_mass').html("<span>Успешно сохранено треков: <font color='#3367AB'><b>"+good+"</b></font>, с ошибками треков: <font color='#3367AB'><b>"+bad+"</b></font>."+moder+"</span>");
  $("#result_mass").fadeTo(20000, 0.5);
}

function hidePodzkazka( ) {
  $('#podskazka').fadeOut(500);
  $('#message_errors').delay(2000).css('top','600px');
}

function hide_message_errors( ) {
  $('#message_errors').fadeOut(500);
}

function showNavigationUp( navig ) {
  $('#navigation_up').html("<div class='navigation'>"+navig+"</div>");
}

function showPrevSearch( text ) {
  $('#prev_search').html("<span><p>Поисковый запрос: <b>"+text+"</b>, нет совпадений в музыкальном архиве сайта!</span>");
  $("#prev_search").fadeTo(20000, 0.5);
}

function showErrorPage( text ) {
  $('#errorpage').html("<h3>Были обнаружены следующие ошибки:</h3><ul style='padding-left:20px; margin:10px; text-align:left'>"+text+"</ul>");
  $("#errorpage").fadeTo(20000, 0.5);
}

function moreBestDown24hr( ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '25' );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'mservice_best_down24hr';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('mservice_best_down24hr').style.display = 'block' );
}

function RateTrack( rate, mid ) {
	var ajax = new dle_ajax();
	ajax.onShow ('');
	var varsString = "go_rate=" + rate;
	ajax.setVar( "mid", mid );
	ajax.setVar( "skin", dle_skin );
	ajax.requestFile = dle_root + "engine/modules/mservice/ajax-rating.php";
	ajax.method = 'GET';
	ajax.element = 'ratig-layer-' + mid;
	ajax.sendAJAX(varsString);
}

function AddFavTrack( mid,is_logged ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '7' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer').style.display = 'block' );

if (is_logged == 1 ) $('#message_alert').text("Трек добавлен в Избранные mp3!");
else $('#message_alert').html("Зарегистрируйтесь или войдите в аккаунт<br />для добавления треков в Избранные!");

  var coor = ($(window).width()-1000)/2;
  $("#favorited-tracks-layer").click(function(e){
    $("#message_alert").css({'top': (e.pageY-60)+'px', 'left': (e.pageX+20-coor)+'px'});
    $("#message_alert").css('display','block');
    $("#message_alert").fadeTo(100,0.9).delay(6000).fadeOut(100);  
  });
}

function DelFavTrack( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '8' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer').style.display = 'block' );

  $('#message_alert').text("Трек удалён из Избранных mp3!");
  var coor = ($(window).width()-1000)/2;
  $("#favorited-tracks-layer").click(function(e){
    $("#message_alert").css({'top': (e.pageY-60)+'px', 'left': (e.pageX+20-coor)+'px'});
    $("#message_alert").css('display','block');
    $("#message_alert").fadeTo(100,0.9).delay(6000).fadeOut(100);  
  });

}

function AddFavTrackList( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '9' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer-'+mid;
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer-'+mid).style.display = 'block' );
}

function DelFavTrackList( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '10' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer-'+mid;
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer-'+mid).style.display = 'block' );
}

function AddFavTrackListTop24hr( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '11' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer-'+mid+'-h';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer-'+mid+'-h').style.display = 'block' );

$('#favorited-tracks-layer-'+mid+'-w').html('<div class="unit-rating_fav"><div class="current-rating"></div><a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTopWeek('+mid+'); return false;">mp3</a></div>');
$('#favorited-tracks-layer-'+mid+'-m').html('<div class="unit-rating_fav"><div class="current-rating"></div><a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTopMonth('+mid+'); return false;">mp3</a></div>');
}

function DelFavTrackListTop24hr( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '12' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer-'+mid+'-h';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer-'+mid+'-h').style.display = 'block' );

$('#favorited-tracks-layer-'+mid+'-w').html('<div class="unit-rating_fav"><a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTopWeek('+mid+'); return false;">mp3</a></div>');
$('#favorited-tracks-layer-'+mid+'-m').html('<div class="unit-rating_fav"><a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTopMonth('+mid+'); return false;">mp3</a></div>');
}

function AddFavTrackListTopWeek( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '13' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer-'+mid+'-w';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer-'+mid+'-w').style.display = 'block' );

$('#favorited-tracks-layer-'+mid+'-h').html('<div class="unit-rating_fav"><div class="current-rating"></div><a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTop24hr('+mid+'); return false;">mp3</a></div>');
$('#favorited-tracks-layer-'+mid+'-m').html('<div class="unit-rating_fav"><div class="current-rating"></div><a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTopMonth('+mid+'); return false;">mp3</a></div>');
}

function DelFavTrackListTopWeek( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '14' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer-'+mid+'-w';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer-'+mid+'-w').style.display = 'block' );

$('#favorited-tracks-layer-'+mid+'-h').html('<div class="unit-rating_fav"><a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTop24hr('+mid+'); return false;">mp3</a></div>');
$('#favorited-tracks-layer-'+mid+'-m').html('<div class="unit-rating_fav"><a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTopMonth('+mid+'); return false;">mp3</a></div>');
}

function AddFavTrackListTopMonth( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '15' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer-'+mid+'-m';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer-'+mid+'-m').style.display = 'block' );

$('#favorited-tracks-layer-'+mid+'-h').html('<div class="unit-rating_fav"><div class="current-rating"></div><a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTop24hr('+mid+'); return false;">mp3</a></div>');
$('#favorited-tracks-layer-'+mid+'-w').html('<div class="unit-rating_fav"><div class="current-rating"></div><a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTopWeek('+mid+'); return false;">mp3</a></div>');
}

function DelFavTrackListTopMonth( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '16' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer-'+mid+'-m';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer-'+mid+'-m').style.display = 'block' );

$('#favorited-tracks-layer-'+mid+'-h').html('<div class="unit-rating_fav"><a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTop24hr('+mid+'); return false;">mp3</a></div>');
$('#favorited-tracks-layer-'+mid+'-w').html('<div class="unit-rating_fav"><a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTopweek('+mid+'); return false;">mp3</a></div>');
}

function AddFavTrackListDownloadsTop( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '17' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer-'+mid+'-dt';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer-'+mid+'-dt').style.display = 'block' );

$('#favorited-tracks-layer-'+mid).html('<div class="unit-rating_fav"><div class="current-rating"></div><a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackList('+mid+'); return false;">mp3</a></div>');
}

function DelFavTrackListDownloadsTop( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '18' );
ajax.setVar( "mid", mid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-tracks-layer-'+mid+'-dt';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-tracks-layer-'+mid+'-dt').style.display = 'block' );

$('#favorited-tracks-layer-'+mid).html('<div class="unit-rating_fav"><a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackList('+mid+'); return false;">mp3</a></div>');
}

function AddFavArtView( transartist,is_logged ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '20' );
ajax.setVar( "transartist", transartist );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favart-artist';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favart-artist').style.display = 'block' );
 
if (is_logged == 1 ) {
  var notreg = 0;
  $('#message_alert').text("Добавлен в Любимые исполнители!");
}
else {
  var notreg = 40;
  $('#message_alert').html("Зарегистрируйтесь или войдите в аккаунт<br />для добавления в Любимые исполнители!");
}
		
  var coor = ($(window).width()-1000)/2;
  $("#favart-artist").click(function(e){
    $("#message_alert").css({'top': (e.pageY-70)+'px', 'left': (e.pageX-300-coor-notreg)+'px'});
    $("#message_alert").css('display','block');
    $("#message_alert").fadeTo(100,0.9).delay(6000).fadeOut(100);  
  });
}

function DelFavArtView( transartist ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '21' );
ajax.setVar( "transartist", transartist );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favart-artist';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favart-artist').style.display = 'block' );

$('#message_alert').text("Удалён из Любимых исполнителей!");
var coor = ($(window).width()-1000)/2;
$("#favart-artist").click(function(e){
  $("#message_alert").css({'top': (e.pageY-70)+'px', 'left': (e.pageX-300-coor)+'px'});
  $("#message_alert").css('display','block');
  $("#message_alert").fadeTo(100,0.9).delay(6000).fadeOut(100);  
});
}

function AddFavArt( transartist ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '22' );
ajax.setVar( "transartist", transartist );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favart-'+transartist;
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favart-'+transartist).style.display = 'block' );
}

function DelFavArt( transartist ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '23' );
ajax.setVar( "transartist", transartist );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favart-'+transartist;
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favart-'+transartist).style.display = 'block' );
}

function AddFavAlbumList( aid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '32' );
ajax.setVar( "aid", aid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-albums-layer-'+aid;
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-albums-layer-'+aid).style.display = 'block' );
}

function DelFavAlbumList( aid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '33' );
ajax.setVar( "aid", aid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'favorited-albums-layer-'+aid;
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('favorited-albums-layer-'+aid).style.display = 'block' );
}

function testVipGroup( uid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '45' );
ajax.setVar( "uid", uid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'test_vip_group';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('test_vip_group').style.display = 'block' );

$(".adv_delay").css('display','none');
}

// логинформа
var logopened=false;
$(document).ready(function(){
    $('#logbtn').click(function(){
        if(logopened)
        {
            $('#logform').hide('fast');
            $('#logbtn').removeClass('selected');
        }    
        else
        {
            $('#logform').show('fast');
            $('#logbtn').addClass('selected');
        }
        logopened=!logopened;
        return false;
    });
}).click(function(e){
    if(!logopened)
        return;
    e=e||window.event;
    var target=e.target||e.srcElement;
    while(target)
    {
        if(target==$('#logform').get(0))
            return;
        target=target.parentNode;
    }
    $('#logform').hide('fast');
    $('#logbtn').removeClass('selected');
    logopened=false;    
});

// livesearchclass для быстрого поиска
$(document).ready(function(){  
   $(".livesearchclass").change(function(){  
        if($(this).is(":checked")){  
            if($(this).is("#RadioBox1")){
              $(".quicksearch .LabelSelectedLive").removeClass("LabelSelectedLive");
              $(this).next("label").addClass("LabelSelectedLive"); 
            }
            if($(this).is("#RadioBox2")){
              $(".quicksearch .LabelSelectedLive").removeClass("LabelSelectedLive");
              $(this).next("label").addClass("LabelSelectedLive"); 
            }
            if($(this).is("#RadioBox3")){
              $(".quicksearch .LabelSelectedLive").removeClass("LabelSelectedLive");
              $(this).next("label").addClass("LabelSelectedLive"); 
            }
            if($(this).is("#RadioBox4")){
              $(".quicksearch .LabelSelectedLive").removeClass("LabelSelectedLive");
              $(this).next("label").addClass("LabelSelectedLive"); 
            }
		}
    });  
});  

// сортировка треков по исполнителю,названию,дате
        $(document).ready(function() {
            $(".dropdown dt span").click(function() {
                $(".dropdown dd ul").toggle();
            });
                        
            $(".dropdown dd ul li a").click(function() {
                var text = $(this).html();
                $(".dropdown dt span").html(text);
                $(".dropdown dd ul").hide();
            });
                        
            $(document).bind('click', function(e) {
                var $clicked = $(e.target);
                if (! $clicked.parents().hasClass("dropdown"))
                    $(".dropdown dd ul").hide();
            });

        });

// сортировка альбомов по исполнителю,названию,году
        $(document).ready(function() {
            $(".dropdown_alb dt span").click(function() {
                $(".dropdown_alb dd ul").toggle();
            });
                        
            $(".dropdown_alb dd ul li a").click(function() {
                var text = $(this).html();
                $(".dropdown_alb dt span").html(text);
                $(".dropdown_alb dd ul").hide();
            });
                        
            $(document).bind('click', function(e) {
                var $clicked = $(e.target);
                if (! $clicked.parents().hasClass("dropdown_alb"))
                    $(".dropdown_alb dd ul").hide();
            });

        });
            

// установка фокуса        
//function myFocus(id){
// try{
//    document.getElementById(id).focus();
// } catch(e) {
// }
//}

//появление ответить и ответить с цитатой (работает до ajax-запросов)
$(document).ready(function() {
$("div.replydiv_commentit").css("opacity", "0");
$("div.view_com_commentit").hover(function() {
$("div.replydiv_commentit", this).stop().fadeTo("fast", 1);},
function() {
$("div.replydiv_commentit", this).stop().fadeTo("fast", .0);
});
});