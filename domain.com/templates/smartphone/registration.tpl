[registration]
<script type="text/javascript">
    function checkUsernameForLength(whatYouTyped) {
        var fieldset = whatYouTyped.parentNode;
        var txt = whatYouTyped.value;
        if (txt.length > 1) {
            fieldset.className = "welldone";
            $("#name").css({
                'border': '1px solid #9FD680'
            });
        } else {
            fieldset.className = "";
            $("#name").css({
                'border': '1px solid #9CA0A1'
            });
        }
    }

    function checkPassword(whatYouTyped) {
        var fieldset = whatYouTyped.parentNode;
        var txt = whatYouTyped.value;
        if (txt.length > 3) {
            fieldset.className = "welldone";
            $("#password1").css({
                'border': '1px solid #9FD680'
            });
        } else {
            fieldset.className = "";
            $("#password1").css({
                'border': '1px solid #9CA0A1'
            });
        }
    }

    function checkEmail(whatYouTyped) {
        var fieldset = whatYouTyped.parentNode;
        var txt = whatYouTyped.value;
        var txt2 = txt.split('@');
        for (var i = 0; i < txt2.length; i++) {
            if (txt2[i] == '') txt2.splice(i, 1);
        }
        if (txt2.length == 2) {
            fieldset.className = "welldone";
            $("#email").css({
                'border': '1px solid #9FD680'
            });
        } else {
            fieldset.className = "";
            $("#email").css({
                'border': '1px solid #9CA0A1'
            });
        }
    }

    function checkRegQuestForLength(whatYouTyped) {
        var fieldset = whatYouTyped.parentNode;
        var txt = whatYouTyped.value;
        if (txt.length > 0) {
            $("#reg_quest").css({
                'border': '1px solid #9CA0A1'
            });
        } else {
            $("#reg_quest").css({
                'border': '2px solid #cc0000'
            });
        }
    }

    function addLoadEvent(func) {
        var oldonload = window.onload;
        if (typeof window.onload != 'function') {
            window.onload = func;
        } else {
            window.onload = function() {
                oldonload();
                func();
            }
        }
    }

    function prepareInputsForHints() {
        var inputs = document.getElementsByTagName("input");
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].onfocus = function() {
                this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
            }
            inputs[i].onblur = function() {
                this.parentNode.getElementsByTagName("span")[0].style.display = "none";
            }
        }
    }
    addLoadEvent(prepareInputsForHints);

</script>

<div class="hpbottom">
    <h3 class="tit">Регистрация нового пользователя</h3>
    <br />

    <table width="100%">
        <tr>
            <td colspan="2"><strong>Здравствуйте, уважаемый посетитель сайта domain.com!</strong>
                <br />
                <br />Регистрация на нашем сайте позволит Вам стать полноценным участником нашего сообщества.
                <br />
                <br />Пройдя несложную процедуру регистрации Вы сможете:
                <br />- скачивать музыкальные альбомы одним файлом (zip-архивом);
                <br />- добавлять свои музыкальные файлы и новости на сайт;
                <br />- комментировать новости и музыкальные треки;
                <br />- удобнее и быстрее скачивать музыку;
                <br />- добавлять музыкальные треки в Избранные mp3, альбомы в Избранные альбомы и исполнителей в Любимые;
                <br />- просматривать скрытый текст;
                <br />- и многое другое...</td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="reg_inf">Администрация сайта обязуется не передавать третьим лицам полученные от пользователей при регистрации персональные данные!</div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                В случае возникновения проблем с регистрацией, обращайтесь, пожалуйста, к администратору сайта через <a href="/feedback.html">Обратную связь</a>.
                <div class="line" style="margin:5px 0;"></div>
            </td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25" valign="top" style="padding-top:10px">Логин: <font color="#F7A8A8">*</font></td>
            <td>
                <fieldset>
                    <input type="text" name="name" onkeyup="checkUsernameForLength(this);" id='name' class="f_input" maxlength="20" value="{2login}" /><span class="hint2">Введите желаемый логин, не менее 2 симолов и проверьте его на доступность</span></fieldset>
                <div style="margin:10px 0px 10px 30px;">
                    <input style="height:18px; font-family:tahoma; font-size:11px; border:1px solid #DFDFDF; background: #FFFFFF;" title="Проверить доступность логина для регистрации" onclick="CheckLogin(); return false;" type="button" value="Проверить логин" />
                    <noscript style="padding-left:10px;">Не работает без JavaScript!</noscript>
                </div>
                <div id='result-registration' style="margin-left:10px;"></div>
            </td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25" valign="top" style="padding-top:15px">Пароль: <font color="#F7A8A8">*</font></td>
            <td>
                <fieldset>
                    <input type="text" id='password1' onkeyup="checkPassword(this);" name="password1" class="f_input" maxlength="16" value="{2password}" /><span class="hint2">Введите пароль, не меньше 4 симолов</span></fieldset>
                <div style="margin:0 0 10px 30px;">
                    <input onchange="if ($('#password1').get(0).type=='text') $('#password1').get(0).type='password'; else $('#password1').get(0).type='text';" name="fff" type="checkbox" value="false">Скрыть пароль</div>
            </td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25">Ваш E-Mail: <font color="#F7A8A8">*</font></td>
            <td>
                <fieldset>
                    <input type="email" id='email' onkeyup="checkEmail(this);" name="email" class="f_input" value="{2email}" /><span class="hint2">Введите E-mail, просим вас указывать реальные адреса</span></fieldset>
            </td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25"></td>
            <td class="sc" height="25">Контрольный вопрос:</td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25">{quest} <font color="#F7A8A8">*</font></td>
            <td>
                <fieldset>
                    <input type="text" name="reg_quest" id='reg_quest' onkeyup="checkRegQuestForLength(this);" class="f_input" maxlength="20" /><span></span></fieldset>
            </td>
        </tr>
        [sec_code]
        <tr>
            <td class="vp" width="200" height="25"></td>
            <td class="sc" height="25">Подтверждение кода безопасности:</td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25">Код безопасности:</td>
            <td>
                <fieldset>{reg_code}</fieldset>
            </td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25">Введите код: <font color="#F7A8A8">*</font></td>
            <td>
                <fieldset>
                    <input type="text" name="sec_code" class="f_input" maxlength="10" /><span></span></fieldset>
            </td>
        </tr>
        [/sec_code]
        <tr>
            <td width="200" height="25">&nbsp;</td>
            <td>
                <fieldset>
                    <button type="submit" name="submit" class="submit2" style="width:230px;" onclick="doAddComments();return false;">Зарегистрироваться</button>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td colspan="2"><font color="#F7A8A8">*</font> пункты обязательные для заполнения</td>
        </tr>
    </table>
</div>
[/registration] {jquery_login}{jquery_password}{jquery_email}{jquery_reg_quest}
