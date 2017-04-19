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
    <h3 class="tit">����������� ������ ������������</h3>
    <br />

    <table width="100%">
        <tr>
            <td colspan="2"><strong>������������, ��������� ���������� ����� domain.com!</strong>
                <br />
                <br />����������� �� ����� ����� �������� ��� ����� ����������� ���������� ������ ����������.
                <br />
                <br />������ ��������� ��������� ����������� �� �������:
                <br />- ��������� ����������� ������� ����� ������ (zip-�������);
                <br />- ��������� ���� ����������� ����� � ������� �� ����;
                <br />- �������������� ������� � ����������� �����;
                <br />- ������� � ������� ��������� ������;
                <br />- ��������� ����������� ����� � ��������� mp3, ������� � ��������� ������� � ������������ � �������;
                <br />- ������������� ������� �����;
                <br />- � ������ ������...</td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="reg_inf">������������� ����� ��������� �� ���������� ������� ����� ���������� �� ������������� ��� ����������� ������������ ������!</div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                � ������ ������������� ������� � ������������, �����������, ����������, � �������������� ����� ����� <a href="/feedback.html">�������� �����</a>.
                <div class="line" style="margin:5px 0;"></div>
            </td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25" valign="top" style="padding-top:10px">�����: <font color="#F7A8A8">*</font></td>
            <td>
                <fieldset>
                    <input type="text" name="name" onkeyup="checkUsernameForLength(this);" id='name' class="f_input" maxlength="20" value="{2login}" /><span class="hint2">������� �������� �����, �� ����� 2 ������� � ��������� ��� �� �����������</span></fieldset>
                <div style="margin:10px 0px 10px 30px;">
                    <input style="height:18px; font-family:tahoma; font-size:11px; border:1px solid #DFDFDF; background: #FFFFFF;" title="��������� ����������� ������ ��� �����������" onclick="CheckLogin(); return false;" type="button" value="��������� �����" />
                    <noscript style="padding-left:10px;">�� �������� ��� JavaScript!</noscript>
                </div>
                <div id='result-registration' style="margin-left:10px;"></div>
            </td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25" valign="top" style="padding-top:15px">������: <font color="#F7A8A8">*</font></td>
            <td>
                <fieldset>
                    <input type="text" id='password1' onkeyup="checkPassword(this);" name="password1" class="f_input" maxlength="16" value="{2password}" /><span class="hint2">������� ������, �� ������ 4 �������</span></fieldset>
                <div style="margin:0 0 10px 30px;">
                    <input onchange="if ($('#password1').get(0).type=='text') $('#password1').get(0).type='password'; else $('#password1').get(0).type='text';" name="fff" type="checkbox" value="false">������ ������</div>
            </td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25">��� E-Mail: <font color="#F7A8A8">*</font></td>
            <td>
                <fieldset>
                    <input type="email" id='email' onkeyup="checkEmail(this);" name="email" class="f_input" value="{2email}" /><span class="hint2">������� E-mail, ������ ��� ��������� �������� ������</span></fieldset>
            </td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25"></td>
            <td class="sc" height="25">����������� ������:</td>
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
            <td class="sc" height="25">������������� ���� ������������:</td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25">��� ������������:</td>
            <td>
                <fieldset>{reg_code}</fieldset>
            </td>
        </tr>
        <tr>
            <td class="vp" width="200" height="25">������� ���: <font color="#F7A8A8">*</font></td>
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
                    <button type="submit" name="submit" class="submit2" style="width:230px;" onclick="doAddComments();return false;">������������������</button>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td colspan="2"><font color="#F7A8A8">*</font> ������ ������������ ��� ����������</td>
        </tr>
    </table>
</div>
[/registration] {jquery_login}{jquery_password}{jquery_email}{jquery_reg_quest}
