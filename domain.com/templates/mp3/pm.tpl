<div class="box_news">
<h3 class="tit">��������� ��������� --> {pm_title}</h3>
<div class="text" style="padding-top:10px;">[new_pm]<b>�������� ���������</b>[/new_pm] <span style="float:right;">[inbox]<b>�������� ���������</b>[/inbox] | [outbox]<b>������������ ���������</b>[/outbox]</span></div>
<div class="line" style="margin-top: 5px;"></div>

[pmlist]
<div class="title">������ ���������</div>
<div class="line_oren"></div>
<div  class="teg">{pmlist}</div>
[/pmlist]

[newpm]
<div class="title">�������� ���������� ���������</div>
<div class="line_oren"></div>
<div class="text">  

                          <table width="450" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td height="10" colspan="2">&nbsp;</td>
                            </tr>
                            <tr class="text">
                              <td width="70" height="25">����������:</td>
                              <td width="380" height="25"><input type="text" name="name" value="{author}" class="bbre" /></td>
                            </tr>
                            <tr>
                              <td width="70" height="25" class="text">����:</td>
                              <td width="380" height="25"><input type="text" name="subj" value="{subj}" class="bbre" size="50" /></td>
                            </tr>
                      <tr>
                        <td height="10" colspan="2">&nbsp;</td>
                      </tr>
                            <tr>
                              <td colspan="2" class="text">{editor}</td>
                            </tr>
                            <tr>
                              <td height="5" colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="2"><input type="checkbox" name="outboxcopy" value="1" />��������� ��������� � ����� "������������"</td>
                            </tr>

[sec_code]                  <tr>
                              <td width="70" height="25" class="text">���:</td>
                              <td width="380" height="25"><br />{sec_code}</td>
                            </tr>
                            <tr>
                              <td width="70" height="25" class="text">������� ���:</td>
                              <td width="380" height="25"><input type="text" name="sec_code" id="sec_code" style="width:115px" class="f_input" /></td>
                            </tr>
[/sec_code]
                          </table>

                              <fieldset><button type="submit" name="add" class="submit2" style="width:180px;" onclick="doAddComments();return false;">���������</button>&nbsp;&nbsp;<button type="button" class="submit" style="width:180px;" onclick="dlePMPreview()">������������</button></fieldset>
[/newpm]

[readpm]
             
<div class="coment">
<div class="title">{subj}</div>
<div class="line"></div>
<div  class="text"><br />{text}<br /><br />��������: <strong>{author}</strong> | [reply]��������[/reply] | [del]�������[/del]</div>
</div> 
</div>
[/readpm]