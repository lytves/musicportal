<div class="mservice_viewtrack">
    <center>На этой странице Вы можете бесплатно скачать mp3 файл и слушать онлайн в хорошем качестве трек
        <br /><b>{artist} - {title} mp3</b></center>
    <div class="line" style="margin:5px 0;"></div>
    <table width="100%">
        <tr>
            <td width="150" valign="top">{track-logo}</td>
            <td>

                <table width="100%" cellspacing="3" cellpadding="3">
                    <tr>
                        <td style="padding-top:3px;"><img src="{THEME}/images/artist.png" align="top" /> &nbsp; Исполнитель: <strong>{artist3}</strong></td>
                    </tr>
                    <tr>
                        <td><img src="{THEME}/images/category.png" align="top" /> &nbsp; Название: <strong>{title}</strong></td>
                    </tr>

                    {genre_mp3}

                    <tr>
                        <td style="padding-top:3px;">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td><img src="{THEME}/images/best20.png" align="top" /> &nbsp; Рейтинг: &nbsp;</td>
                                    <td>{rating}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {album}

                    <tr>
                        <td height="10"></td>
                    </tr>

                    <tr>
                        <td style="padding-top:3px;">
                            <table width="100%">
                                <tr>
                                    <td width="55%"><img src="{THEME}/images/uploader.png" align="top" /> &nbsp; Загрузил: {uploader}</td>
                                    <td style="padding-left:3px;"><img src="{THEME}/images/views.png" align="top" /> &nbsp; Просмотров: {views}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;">
                            <table width="100%">
                                <tr>
                                    <td width="55%"><img src="{THEME}/images/calendar.png" align="top" /> &nbsp; {date}</td>
                                    <td style="padding-left:3px;"><img src="{THEME}/images/downloads.png" align="top" /> &nbsp; Скачиваний: {downcount}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <table width="100%" border="0">
                                <tr>
                                    <td style="padding-top:3px;"><img src="{THEME}/images/filesize.png" align="top" /></td>
                                    <td width="10"></td>
                                    <td style="white-space:nowrap">О файле:</td>
                                    <td width="20"></td>
                                    <td width="30%">
                                        <div class="file-info" style="background:none repeat scroll 0 0 #FBDB85;"><i>{filetype}</i><b style="left:34%;">/</b><span>{filesize}</span></div>
                                    </td>
                                    <td width="50%">
                                        <div class="file-info" style="background:none repeat scroll 0 0 #E2EDF2;"><i>{lenght} мин</i><b style="left:40%; padding-left:5px;">/</b><span>{bitrate}</span></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    [comment]
                    <tr>
                        <td><img src="{THEME}/images/comment.png" align="top" /> &nbsp; Комментарий от автора:
                            <p style="margin:5px 0;">{comment}</p>
                        </td>
                    </tr>[/comment] {viewEdit} {favorites}{favorites_art}

                </table>
            </td>
        </tr>
    </table>
</div>
<div class="line" style="margin-top:5px;"></div>
<div class="mservice_viewtrack">{artist} - {title} слушать онлайн mp3
    <p style="margin:5px 0 0;">{player}</p>

    <noindex>
        <table>
            <tr>

                <td>Поделиться:</td>
                <td width="10"></td>
                <td>

                </td>
                <td width="20"></td>
                <td>


                </td>
                <td width="20"></td>
                <td>

                </td>
            </tr>
        </table>
    </noindex>
</div>

<div class="line"></div>

<div class="mservice_viewtrack">
    <center style="color:red; font-size:13pt; font-weight:bold;">К сожалению, трек временно не доступен для онлайн прослушивания и скачивания!</center>
</div>

<div class="mservice_viewtrack">
    <noindex>{adv}</noindex>
</div>

<div style="clear:both;"></div>
{clip-title}{clip-online}{clip-end} {title-more-tracks}{more-tracks}{alltrackslink}{more-tracks-end} {allalbums} {top-tracks}

<div id="message_alert"></div>

<input type="hidden" type="text" name="hidden_inp" value="">
