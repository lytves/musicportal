$(window).unload(function() {
    $("div.player").html("");
});

(function($) {

    function generatePlaylistID() {
        var i = 1;
        while (true) {
            if ($(".playlist" + i).length == 0) {
                return "playlist" + i;
            }
            else {
                i++;
            }
        }
    }

    function setPlayer(el, url, id, settings) {
    
        var flashWidth = settings.progressBarWidth + settings.barSpace + settings.volumeBarWidth;
        var flashHeight = Math.max(settings.volumeSliderHeight, settings.progressSliderHeight);
            
    var settingsStr = "&amp;backgroundColor=" + settings.backgroundColor +
"&amp;volumeBarWidth=" + settings.volumeBarWidth +
"&amp;progressBarWidth=" + settings.progressBarWidth + 
"&amp;barSpace=" + settings.barSpace + 
"&amp;volumeBarColor=" + settings.volumeBarColor + 
"&amp;volumeBarHeight=" + settings.volumeBarHeight + 
"&amp;volumeSliderWidth=" + settings.volumeSliderWidth + 
"&amp;volumeSliderHeight=" + settings.volumeSliderHeight + 
"&amp;volumeSliderColor=" + settings.volumeSliderColor + 
"&amp;progressBarHeight=" + settings.progressBarHeight + 
"&amp;progressSliderWidth=" + settings.progressSliderWidth + 
"&amp;progressSliderHeight=" + settings.progressSliderHeight + 
"&amp;progressSliderColor=" + settings.progressSliderColor + 
"&amp;progressBarColor=" + settings.progressBarColor + 
"&amp;bufferColor=" + settings.bufferColor;

    $(el).html("<embed wmode=\"transparent\" type='application/x-shockwave-flash' src="+settings.playerurl+" width='" + flashWidth + "' height='" + flashHeight + "' style='undefined' id='player" + id + "' name='player" + id + "' quality='high' swliveconnect='true' allowscriptaccess='sameDomain' flashvars='url=" + url + "&amp;id=" + id + settingsStr + "'>");
        $(el).removeClass("inactive");
    }
    
    function getFlashMovie(id) {
        var isIE = navigator.appName.indexOf("Microsoft") != -1;
        return (isIE) ? document["player" + id] : document["player" + id];
    }
	function invokeFlashMethod(id, methodName, arg1, arg2, arg3)
	{
	   var flsh = getFlashMovie(id);
		var isIE = navigator.appName.indexOf("Microsoft") != -1;
	   var res = null;
	   if (isIE) {
		   var args = [];
		   if (arg1) {
			   args.enqueue(arg1);
		   }
		   if (arg2) {
			   args.enqueue(arg2);
		   }
		   if (arg3) {
			   args.enqueue(arg3);
		   }
		   var xmlArgs = window.self.__flash__argumentsToXML(args, 0);
		   var xml = '<invoke name=\"';
		   xml = xml + methodName;
		   xml = xml + '\" returntype=\"javascript\">';
		   xml = xml + xmlArgs;
		   xml = xml + '</invoke>';
		   var funcRes = flsh.callFunction(xml);
		   if ((funcRes != null) && (funcRes != undefined)) {
			   res = eval(funcRes);
		   };
	   }
	   else {
		   if (!arg1) {
			   res = flsh[methodName]();
		   }
		   else if (arg1 && !arg2) {
			   res = flsh[methodName](arg1);
		   }
		   else if (arg1 && arg2 && !arg3) {
			   res = flsh[methodName](arg1, arg2);
		   }
		   else {
			   res = flsh[methodName](arg1, arg2, arg3);
		   }
	   }
	   return res;
	}

    function removePlayer(el) {
        $(el).addClass("inactive").empty();
    }

    function playTrack(el, id) {
        $(el).addClass("current");
        var settings = $(el).data("settings");
        setPlayer($(el).find("div.player"), $(el).attr("href"), id, settings);
        $.each($(el).find(".btn"), function() {
            if ($(this).hasClass("pause")) {
                $(this).removeClass("pause").addClass("play");
            } else
                if ($(this).hasClass("play")) {
                $(this).removeClass("play").addClass("pause");
            }
        });
    }

    function pause(el) {
        $(el).each(function() {
            var id = $(this).data("id");
			el.find(".current").find(".btn").removeClass("play").removeClass("pause").addClass("paused");
			el.find(".current").addClass("paused");
			invokeFlashMethod(id, "pause");
        });
    }

    function resume(el) {
        $(el).each(function() {
            var id = $(this).data("id");
			el.find(".current").removeClass("paused");
			el.find(".current").find(".btn").removeClass("play").removeClass("paused").addClass("play");
			invokeFlashMethod(id, "play");
        });
    }

    function stop(el) {
        $.each($(el).find(".item"), function() {
            $(this).removeClass("current").removeClass("paused");
            removePlayer($(this).find("div.player"));
            $.each($(this).find(".btn"), function() {
                $(this).removeClass("pause").removeClass("paused").addClass("play");
            });
        });
    }

    var methods = {
        
        init: function(options) {
        
            var settings = {
                backgroundColor      : 0xffffff,
                volumeBarWidth       : 40,
                progressBarWidth     : 400,
                barSpace             : 20,               
                volumeBarColor       : 0x3367AB,
                volumeBarHeight      : 2,
                volumeSliderWidth    : 10,
                volumeSliderHeight   : 5,
                volumeSliderColor    : 0x3367AB,                
                progressBarHeight    : 2,
                progressSliderWidth  : 15,
                progressSliderHeight : 5,
                progressBarColor     : 0xd8dfea,
                progressSliderColor  : 0x3367AB,
                bufferColor          : 0x3367AB,
		playerurl	     : "drplayer.swf"    
            }

            if (options) {
                $.extend( settings, options );
            }
                       
            return this.each(function() {
                var el = $(this);
                var id = generatePlaylistID();
                $(el).data("id", id);
                $(el).addClass(id);
                //init
                $.each($(el).find(".item"), function() {
                    var item = $(this);        
                    $(this).data("settings", settings);   
                    $(this).find(".btn").click(function() {
                        if ($(item).hasClass("paused")) {
                            $(item).removeClass("paused");
                            $(this).removeClass("play").addClass("pause");
                            resume(el);
                            chFavicon( '/favicon_play.ico' );
                        } else if ($(item).hasClass("current")) {
                            $(this).removeClass("pause").addClass("play");
                            $(item).addClass("paused");
                            pause(el);
                            chFavicon( '/favicon_pause.ico' );
                        } else {
                            stop(el);
                            playTrack(item, id);
                            chFavicon( '/favicon_play.ico' );
                        }
                        
                    })
                });

            });
            
        },
        next: function() {
            return this.each(function() {
                var el = $(this);
                var items = el.find(".item")
                var index = -1;
                for (var i = 0; i < items.length; i++) {
                    if ($(items[i]).hasClass("current"))
                        index = i;
                }
                if (index != -1) {
                    stop(el);
                }
                if (index != -1 && index < items.length - 1) {
                    var next = $(items[index + 1]); 
                    playTrack(next, $(el).data("id"));
                }
                if (index >= items.length - 1) {
                    next = $(items[0]);
                    playTrack(next, $(el).data("id"));
                }
            });
        },
        prev: function() {
            return this.each(function() {
                var el = $(this);
                var items = el.find(".item")
                var index = -1;
                for (var i = 0; i < items.length; i++) {
                    if ($(items[i]).hasClass("current"))
                        index = i;
                }
                if (index != -1) {
                    stop(el);
                }
                if (index > 0 && index < items.length) {
                    var prev = $(items[index - 1]); 
                    playTrack(prev, $(el).data("id"));
                }
            });
        },
        pause:function() {
            return this.each(function() {
                var el = $(this);
                pause(el);
            });        
        },

        play:function() {
            return this.each(function() {
                var el = $(this);
                if ($(el).find(".current").length == 0) {
                    var tr = $(el).find(".item:first");
                    playTrack(tr, $(el).data("id"));
                } else {
                    resume(el);
                }
            });      
        }

    };

    $.fn.playlist = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist');
        };

    };
})(jQuery);