<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<html xmlns:fb="http://ogp.me/ns/fb#">

<head>
    {headers}
    <link rel="stylesheet" type="text/css" href="{THEME}/css/style.css" media="all" />
    <link rel="stylesheet" type="text/css" href="{THEME}/css/engine.css" media="all" />
    <link rel="icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="yandex-tableau-widget" href="/manifest.json" />
    <meta name="msapplication-config" content="/browserconfig.xml" />
    <link rel="apple-touch-icon" href="apple-touch-icon-precomposed.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="apple-touch-icon-72x72-precomposed.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="apple-touch-icon-114x114-precomposed.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="apple-touch-icon-144x144-precomposed.png" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="/engine/classes/js/jquery-2.1.3.min.js"><\/script>')

    </script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/engine/classes/js/dle_js.js"></script>
    <script type="text/javascript" src="/engine/classes/js/jsfunctions_smartphone.js"></script>
    <!--[if lt IE 9]>
<script src="/engine/classes/js/html5shiv.js"></script>
<![endif]-->
    <script language="javascript" type="text/javascript">
        <!--
        function ShowLoading(a) {};

        function HideLoading(a) {};
        //-->

    </script>
    <script type="text/javascript" src="//vk.com/js/api/openapi.js?109"></script>
    [not-group=1,7]
    <script type="text/javascript">
        (function() {
            var j = 86195,
                f = false,
                b = document,
                c = b.documentElement,
                e = window;

            function g() {
                var a = "";
                a += "rt=" + (new Date).getTime() % 1E7 * 100 + Math.round(Math.random() * 99);
                a += b.referrer ? "&r=" + escape(b.referrer) : "";
                return a
            }

            function h() {
                var a = b.getElementsByTagName("head")[0];
                if (a) return a;
                for (a = c.firstChild; a && a.nodeName.toLowerCase() == "#text";) a = a.nextSibling;
                if (a && a.nodeName.toLowerCase() != "#text") return a;
                a = b.createElement("head");
                c.appendChild(a);
                return a
            }

            function i() {
                var a = b.createElement("script");
                a.setAttribute("type", "text/javascript");
                a.setAttribute("src", "http://s.luxadv.com/t/lb" + j + "_3.js?" + g());
                typeof a != "undefined" && h().appendChild(a)
            }

            function d() {
                if (!f) {
                    f = true;
                    i()
                }
            };
            if (b.addEventListener) b.addEventListener("DOMContentLoaded", d, false);
            else if (b.attachEvent) {
                c.doScroll && e == e.top && function() {
                    try {
                        c.doScroll("left")
                    } catch (a) {
                        setTimeout(arguments.callee, 0);
                        return
                    }
                    d()
                }();
                b.attachEvent("onreadystatechange", function() {
                    b.readyState === "complete" && d()
                })
            } else e.onload = d
        })();

    </script>[/not-group]
</head>

<body>
    <noindex>
        <script type="text/javascript">
            VK.init({
                apiId: 4265726,
                onlyWidgets: true
            });

        </script>
    </noindex>

    <div id="nav_updown">
        <table width="1000">
            <tr>
                <td valign="top" width="994" colspan="2">
                    <table width="100%">
                        <tr>
                            <td rowspan="2" width="294" height="155">
                                <a href="/" title="Скачать бесплатно мп3 музыку на domain.com"><img src="{THEME}/images/download_mp3.jpg" width="294" height="150" title="Бесплатно скачать мп3 музыку на domain.com" alt="Бесплатно скачать mp3 музыку на domain.com" /></a>
                            </td>
                            <td valign="top" height="65">{login}
                                <div class="line_min"></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">

                                <form action="/music/search.html" method="post">
                                    <input type="hidden" name="act" value="dosearch" />
                                    <input type="hidden" name="newpoisk" />
                                    <table class="quicksearch">
                                        <tr>
                                            <td style="padding-left:10px;">
                                                <input id="RadioBox1" type="radio" class="livesearchclass" name="searchtype" value="1" {select1} />
                                                <label for="RadioBox1" {select2}>Общий поиск</label>
                                                <input id="RadioBox2" type="radio" class="livesearchclass" name="searchtype" value="2" {select3} />
                                                <label for="RadioBox2" {select4}>По исполнителям</label>
                                                <input id="RadioBox3" type="radio" class="livesearchclass" name="searchtype" value="3" {select5} />
                                                <label for="RadioBox3" {select6}>По трекам</label>
                                                <input id="RadioBox4" type="radio" class="livesearchclass" name="searchtype" value="4" {select7} />
                                                <label for="RadioBox4" {select8}>По альбомам</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top:1px;">
                                                <input type="text" name="searchtext" size="70" maxlength="100" autocomplete="off" id="search" value="{searchtext}" />
                                                <button type="submit"></button>
                                                <div id="box"></div>
                                            </td>
                                        </tr>
                                    </table>
                                </form>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2">

                    <div class="topmenu">
                        <div class="tmlr">
                            <div class="tmlr">
                                <ul id="nav">
                                    <nobr>
                                        <li id="selected"><a href="/" title="Скачать mp3 на сайте domain.com" style="color:#FC5F5F;">domain.com</a></li>
                                        <li><a href="/music/mp3top.html" title="Скачать ТОП лучших mp3 2015 года" style="color:#3FD0DB;">ТОП mp3 2015 года</a></li>
                                        <li><a href="/music/new_mp3.html" title="Скачать Новинки mp3" style="color:#F49804;">Новинки mp3</a></li>
                                        <li><a href="/music/new-mp3-albums.html" title="Новинки музыкальных альбомов" style="color:#F6FF05;">Новинки МузАльбомов</a></li>
                                        <li><a href="/music/new-music-clips.html" title="Новинки музыкальных клипов" style="color:#DD88F7;">Новинки МузКлипов</a></li>
                                        <li><a href="/music/happy-new-year.html" style="color:#1ECE00;" title="Скачать Все Новогодние песни">Новогодние песни</a></li>
                                    </nobr>
                                </ul>
                            </div>
                        </div>
                    </div>

                </td>
            </tr>

            <tr>
                <td valign="top" width="742">
                    <div id="content" style="height:100%;">
                        <noindex>
                            <div class="adv">
                                <noscript>
                                    <div class="nojs"><span>Для полной функциональности этого сайта необходимо включить JavaScript в вашем браузере!</span></div>
                                </noscript>
                                [not-aviable=static]{banner_adv_center-smartphone}[/not-aviable]
                            </div>
                        </noindex>

                        {info}{content}

                        <noindex>
                            <div class="adv">
                                [not-aviable=static]{banner_adv_footer}[/not-aviable]
                            </div>
                        </noindex>

                    </div>

                </td>
                <td valign="top" width="248" rowspan="2">

                    <div class="box" style="padding:5px 0 10px;">
                        <a class="reg_inf" style="width:auto; font-family:Tahoma; font-size:18px; text-align:center; padding:10px 30px; margin:0 auto;" title="Отключить рекламу и перейти в группу VIP Посетители!" href="/vip_group.html" onMouseOver="this.style.background='#EF5151';this.style.color='#ffffff'" onMouseOut="this.style.background='#f7a8a8';this.style.color='#3367ab'">Отключить рекламу</a>
                    </div>

                    [group=1]
                    <div class="box" style="padding-bottom:10px;">
                        <div class="tit">Группа <span style="color:#ff00b2;">VIP Посетители</span></div>
                        <div class="fov">
                            <div class="box2">{by_odmin_vip_members}</div>
                        </div>
                    </div>
                    [/group]

                    <div class="box">
                        <div class="tit"><span><span>Навигация</span></span>
                        </div>
                        <div class="fov">
                            <div class="box2_navi">
                                &raquo; <a href="/music/euro2015.html" title="Скачать все песни Евровидение 2015">Евровидение 2015</a>
                                <br /> &raquo; <a href="/music/genres-albums.html" title="Все музыкальные альбомы по жанрам">МузАльбомы по жанрам</a>
                                <br /> &raquo; <a href="/music/top_artists.html" title="ТОП 50 исполнителей">ТОП 50 исполнителей</a>
                                <br /> &raquo; <a href="/music/search.html" title="Популярное">Популярное</a>
                                <br />
                                <div class="line" style="margin:5px 0px;"></div>
                                &raquo; <a href="/music/topplay24hr.html" title="ТОП прослушиваемых mp3">ТОП прослушиваний mp3</a>
                                <br />
                                <div class="line" style="margin:5px 0px;"></div>
                                &raquo; <a href="/music/downloadstop24hr.html" title="ТОП 50 скачиваемых mp3 треков за сутки">ТОП скачиваний mp3</a>
                                <br />
                                <div class="line" style="margin:5px 0px;"></div>
                                &raquo; <a href="/music/downloadstopalbums24hr.html" title="ТОП 20 скачиваемых музыкальных альбомов за сутки">ТОП скачиваний альбомов</a>
                                <br />
                                <div class="line" style="margin:5px 0px;"></div>
                                &raquo; <a href="/vip_group.html" style="color:#ff0000;" title="Отключить рекламу">Отключить рекламу</a>
                                <br />

                                <noindex>
                                    <div class="adv">
                                        {banner_adv_right_vertical3}
                                    </div>
                                </noindex>

                            </div>
                        </div>
                    </div>

                    <div class="box">
                        <noindex>
                            <div class="adv">
                                [not-aviable=static]{banner_adv_right_vertical-smartphone}[/not-aviable]
                            </div>
                        </noindex>
                    </div>

                    <div class="box" style="padding-bottom:10px;">
                        <div class="tit"><a href="/music/new_mp3.html"><span><span>Новинки</span></span></a> mp3</div>
                        <div class="fov">
                            <div class="box2">{mservice_newest}</div>
                        </div>
                    </div>

                    <div class="box">
                        <div class="tit"><a href="/music/downloadstop24hr.html"><span><span>Самые скачиваемые mp3</span></span></a> треки за сутки</div>
                        <div class="fov">
                            <div class="box2">{mservice_down24hr}</div>
                        </div>
                    </div>

                </td>
            </tr>
        </table>

        <table border="0" width="1000">
            <tr>
                <td>
                    <div class="line" style="margin-top:10px;"></div>
                    <div style="float:left; margin-top:5px; padding:0 0 0 30px; font-weight:bold">
                        <span><a href="{ssilko}" title="{slova}">{slova}</a></span>
                    </div>
                    <div style="float:right; margin-top:5px; padding:0 30px 0 0;">{banner_counters}</div>
                    <div style="clear:left; padding:10px 0 10px 30px;">Используя ресурс <a href="/" title="Скачать мп3 музыку бесплатно без регистрации прямые ссылки">domain.com</a>, Вы соглашаетесь с <a href="/rules.html" title="Правила пользования мп3 сайтом domain.com">правилами пользования сайтом</a>. Информация для <a href="/pravo.html" title="Информация для правообладателей">правообладателей</a>.
                        <br />Контакты: <a href="/feedback.html">Обратная связь</a>, письмо в саппорт <a href="mailto:support@domain.com">support@domain.com</a></div>
                    <div class="line"></div>
                    <div style="text-align:center; padding-bottom:10px;"><a href="/index.php?action=mobiledisable">перейти на полную версию сайта</a></div>
                </td>
            </tr>
        </table>

    </div>

    <noindex>
        {banner_metrika} {AJAX} [not-group=1,4,6,7]
        <!--noindex-->
        <div id="amsb"></div>
        <!--/noindex-->
        <!--noindex-->
        <script type="text/javascript" src="//am15.net/sb.php?s=69892"></script>
        <!--/noindex-->[/not-group]
    </noindex>

    <script type="text/javascript" src="/engine/classes/js/search.js"></script>
    <script>
        $(document).ready(function() {
            $('.delay').show();
        });

    </script>
</body>
[not-group=1,7]
<script type='text/javascript' src='http://trafmag.com/sitecode-04281.js'></script>[/not-group]

</html>
