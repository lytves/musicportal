$(function(){
	var visib = false;
	var quer;
	var ra;
	var boxcont = '';
	var radiobutton;
	
	 
	
	$("#search").keyup(function(){
		radiobutton = $("input:checked").val();
		quer = $("#search").val();
		if(quer.length>1) {
			boxcont = '';
			$.post("http://domain.com/engine/modules/mservice/livesearch.php",{ q: quer, r: radiobutton },function(data){
 
				if(data!='') {
 
					if(!visib) {
						$("#box").fadeIn(100);
						visib = true;
					}
					boxcont = '';
					ra = data.split('$#');
					for (var i = 0; i < Math.round(ra.length/2); i++){
						boxcont = boxcont + '<div class="itm" cat="'+ra[i*2+1]+'">'+ra[i*2]+'</div>';
					}
					$("#box").html(boxcont);
					$(".itm").bind("mousedown",itmclick);
				} else {
					$("#box").fadeOut(100);
					visib = false;
				}
 
			});
 
		} else {
			$("#box").fadeOut(100);
			visib = false;
		}
	});
 
	$("#search").focusout(function(){
		$("#box").fadeOut(100);
		visib = false;
	});
 
	$("#search").focusin(function(){
    radiobutton = $("input:checked").val();
		quer = $("#search").val();
		if(quer.length>1) {
			boxcont = '';
			$.post("http://domain.com/engine/modules/mservice/livesearch.php",{ q: quer, r: radiobutton },function(data){
 
				if(data!='') {
 
					if(!visib) {
						$("#box").fadeIn(100);
						visib = true;
					}
					boxcont = '';
					ra = data.split('$#');
					for (var i = 0; i < Math.round(ra.length/2); i++){
						boxcont = boxcont + '<div class="itm" cat="'+ra[i*2+1]+'">'+ra[i*2]+'</div>';
					}
					$("#box").html(boxcont);
					$(".itm").bind("mousedown",itmclick);
				} else {
					$("#box").fadeOut(100);
					visib = false;
				}
 
			});
 
		} else {
			$("#box").fadeOut(100);
			visib = false;
		}
	});

 
	function itmclick(){
			window.location.pathname = '/music/search-'+radiobutton+'-1-'+$(this).attr("cat")+'.html';
	};
 
});