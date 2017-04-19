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
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/engine/classes/js/jquery.spasticNav.js"></script>
    <script type="text/javascript" src="/engine/classes/js/dle_js.js"></script>
    <script type="text/javascript" src="/engine/classes/js/jsfunctions.js"></script>
    <!--[if lt IE 9]>
<script src="/engine/classes/js/html5shiv.js"></script>
<![endif]-->

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
                a.setAttribute("src", "http://s.luxadv.com/t/lb" + j + ".js?" + g());
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

    </script>
    <script type="text/javascript" src="//vk.com/js/api/openapi.js?109"></script>
</head>

<body>
    <noindex>
        <img style="border:0; left:0; position:absolute; top:0; z-index:999;" src="/6.png" alt="" title="" />
        <script type="text/javascript">
            VK.init({
                apiId: 4265726,
                onlyWidgets: true
            });

        </script>
    </noindex>

    <div id="slova">{slova}</div>

    <div id="nav_updown">
        <table width="1000">
            <tr>
                <td valign="top" width="994" colspan="2">
                    <table width="100%" style="margin-bottom:5px;">
                        <tr>
                            <td rowspan="2" width="294" height="175">
                                <a href="/" title="Скачать бесплатно мп3 музыку на domain.com"><img src="{THEME}/images/download_mp3.jpg" width="294" height="170" title="Бесплатно скачать мп3 музыку на domain.com" alt="Бесплатно скачать mp3 музыку на domain.com" /></a>
                            </td>
                            <td valign="top" height="65">{login}
                                <div class="line_min"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {mservice_echoletter}
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
                                        <li id="selected"><a href="/" title="Скачать mp3 на сайте domain.com" style="color:#FC5F5F">domain.com</a></li>
                                        <li><a href="/music/mp3top.html" title="Скачать ТОП лучших mp3 2014 года" style="color:#3FD0DB">ТОП mp3 2014 года</a></li>
                                        <li><a href="/music/new_mp3.html" title="Скачать Новинки mp3" style="color:#1ECE00">Новинки mp3</a></li>
                                        <li><a href="/music/search.html" title="Поиск mp3 на сайте" style="color:#FF00B2">Поиск mp3 на сайте</a></li>
                                        <li><a href="/music/euro2014.html" title="Скачать все песни Евровидение 2014" style="color:#F6FF05">Евровидение 2014</a></li>
                                        <li><a href="/music/top_club2013.html" title="Скачать ТОП 100 клубных треков 2013" style="color:#CB00FF">ТОП 100 клубных треков 2013</a></li>
                                    </nobr>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <script type="text/javascript">
                        $('#nav').spasticNav();

                    </script>

                </td>
            </tr>

            <tr>
                <td valign="top" width="742">
                    <table width="100%" class="quicksearch">
                        <tr>
                            <td width="735" colspan="3" style="padding-left:50px;">
                                <input id="RadioBox1" type="radio" class="livesearchclass" name="button" value="1" checked="checked" />
                                <label for="RadioBox1" class="LabelSelectedLive">По исполнителю</label>
                                <input id="RadioBox2" type="radio" class="livesearchclass" name="button" value="2" />
                                <label for="RadioBox2">По названию трека</label>
                            </td>
                        </tr>
                        <tr>
                            <td width="7"><img src="{THEME}/images/sch_07.gif" width="7" height="48"></td>
                            <td width="728" class="stroka">
                                <input type="text" size="70" maxlength="100" autocomplete="off" id="search" value=" Быстрый поиск mp3 ..." />
                                <div id="box"></div>
                            </td>
                            <td width="7"><img border="0" src="{THEME}/images/sch_10.gif" width="7" height="48"></td>
                        </tr>
                    </table>

                    <div id="content" style="height:100%;">
                        <noindex>
                            <div class="adv">
                                <noscript>
                                    <div class="nojs"><span>Для полной функциональности этого сайта необходимо включить JavaScript в вашем браузере!</span></div>
                                </noscript>
                                {banner_adv_center}
                            </div>
                        </noindex>

                        {info}{content}

                        <noindex>
                            <div class="adv">
                                {banner_adv_footer}
                            </div>
                        </noindex>

                    </div>

                </td>
                <td valign="top" width="248" rowspan="2">
                    <div class="box">
                        <div class="tit">ТОП 20 <span><span>исполнителей</span></span>
                        </div>
                        <div class="fov">
                            <div class="box2">{mservice_best}</div>
                        </div>
                    </div>

                    <div class="box">
                        <noindex>
                            <div class="adv">
                                {banner_adv_right_vertical}
                            </div>
                        </noindex>
                    </div>

                    <div class="box">
                        <noindex>
                            <div class="adv">
                                {banner_adv_right_flip}
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
                        <div class="tit"><span><span>Навигация</span></span>
                        </div>
                        <div class="fov">
                            <div class="box2_navi">
                                &raquo; <a href="/music/downloadstop24hr.html" title="ТОП 50 скачиваемых mp3 треков за сутки">ТОП 50 скачиваемых mp3 треков за сутки</a>
                                <br /> &raquo; <a href="/music/downloadstopweek.html" title="ТОП 50 скачиваемых mp3 треков за неделю">ТОП 50 скачиваемых mp3 треков за неделю</a>
                                <br /> &raquo; <a href="/music/genres-albums.html" title="Все жанры альбомов">Все жанры альбомов</a>
                                <br /> &raquo; <a href="/music/happy-new-year.html" title="Новогодние песни">Новогодние песни</a>
                                <br /> &raquo; <a href="https://vk.com/domain" target="_blank" title="domain.com ВКонтакте">domain.com ВКонтакте</a>
                                <br />
                            </div>
                        </div>
                    </div>

                    <div class="box">
                        <div class="tit"><a href="/music/downloadstop24hr.html"><span><span>Самые скачиваемые mp3</span></span></a> треки за сутки</div>
                        <div class="fov">
                            <div class="box2">{mservice_down24hr}</div>
                        </div>
                    </div>

                    <div class="box">
                        <noindex>
                            <div class="adv">
                                {banner_adv_right_vertical3}
                            </div>
                        </noindex>
                    </div>

                </td>
            </tr>

            <tr>
                <td valign="bottom">
                    <div class="mservice_footer_info">
                        На сайте <a href="/" title="Скачать музыку мп3 бесплатно">domain.com</a> расположен большой архив мп3 музыки разных стилей и направлений. У нас Вы можете совершенно бесплатно и без регистрации скачать необходимую вам mp3 композицию и/или онлайн прослушать музыкальный файл. Наш сервис domain.com совершенно бесплатный, пользователи размещают песни сами. <a href="/" title="Бесплатно скачать послушать мп3">Онлайн прослушивание и скачивание mp3 песен</a> - бесплатно. Размещайте и/или скачивайте необходимые вам файлы на нашем сайте.
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
                    <div style="clear:left; padding:10px 0 20px 30px;">Используя ресурс <a href="/" title="Скачать мп3 музыку бесплатно без регистрации прямые ссылки">domain.com</a>, Вы соглашаетесь с <a href="/music/rules.html" title="Правила пользования мп3 сайтом domain.com">правилами пользования сайтом</a>. Информация для <a href="/pravo.html" title="Информация для правообладателей">правообладателей</a>.</div>
                </td>
            </tr>
        </table>

    </div>

    <div id="top-link">
        <a href="#top"></a>
    </div>

    <noindex>
        {banner_metrika} {AJAX}
    </noindex>
    <script type="text/javascript" src="/engine/classes/js/button_down.js"></script>
    <script type="text/javascript" src="/engine/classes/js/search.js"></script>
    <script type="text/javascript" src="/engine/classes/js/drplayer.js"></script>
    <script>
        $(document).ready(function() {
            $('.adv_delay').show();
        });

    </script>
</body>

</html>
