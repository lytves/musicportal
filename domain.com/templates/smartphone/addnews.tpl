<div class="box_news">
<h3 class="tit">Добавить новость</h3>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" id="table1">
                      <tr class="text">
                        <td width="5%" height="25" nowrap="nowrap">Введите заголовок:</td>
                        <td width="381" colspan="3">
						<input type="text" name="title" value="{title}" maxlength="150" class="bbre" size="60" /></td>
                      </tr>
                      [urltag]<tr class="text">
                        <td height="25" nowrap="nowrap">URL статьи:</td>
                        <td width="381" colspan="3">
						<input type="text" name="alt_name" value="{alt-name}" maxlength="150" size="30" class="bbre"/></td>
                      </tr>[/urltag]
                      <tr class="text">
                        <td height="25">Категория:</td>
                        <td width="381" colspan="3">{category}</td>
                      </tr>
                      <tr class="text">
                        <td height="15" colspan="2">&nbsp;</td>
                      </tr>
                      [not-wysywyg]<tr  class="text">
                        <td height="25">Коды:</td>
                        <td width="500" colspan="3">{bbcode}</td>
                      </tr>[/not-wysywyg]
                      <tr class="text">
                        <td>Краткое содержание:</td>
                        <td width="490" colspan="3">[not-wysywyg]<textarea name="short_story" id="short_story" onclick=setFieldName(this.name) style="border:1px solid #C0C0C0; width:100%; height:150px; font-family:Verdana; font-size:12px; color:#727272; resize:vertical;" />{short-story}</textarea>[/not-wysywyg]{shortarea}</td>
                      </tr>
                      <tr class="text">
                        <td height="10" colspan="2">&nbsp;</td>
                      </tr>
                      <tr class="text"> 
                        <td>Полная новость:<br />(необязательно)</td>
                        <td width="500" colspan="3">[not-wysywyg]<textarea name="full_story" id="full_story" onclick=setFieldName(this.name) style="border:1px solid #C0C0C0; width:100%; height:150px; font-family:Verdana; font-size:12px; color:#727272; resize:vertical;" />{full-story}</textarea>[/not-wysywyg]{fullarea}</td>
                      </tr>
                      <tr class="text">
                        <td height="25">&nbsp;</td>
                        <td width="381" colspan="3">{xfields}</td>
                      </tr>
                      <tr class="text">
                        <td height="10" colspan="2">&nbsp;</td>
                      </tr>
						[sec_code]<tr class="text">
                        <td width="10%">&nbsp;</td>
                        <td colspan="3" width="381">Чтобы мы знали, что вы не робот — введите пожалуйста цифры или буквы с изображения.<br />
&nbsp;</td>
                        </tr>
						<tr class="text">
                        <td height="38"><br />&nbsp;</td>
                        <td height="38" width="9%">
						<p align="center">{sec_code}&nbsp; </td>
                        <td height="38" width="14%" valign="top">
						<p align="center">&nbsp; Введите код: <input type="text" name="sec_code" id="sec_code" class="bbre" /></td>
                        <td height="38" width="67%" valign="top">&nbsp; </td>
                      </tr>[/sec_code]
					  <br />
                       <tr class="text">
                        <td width="10%"></td>
                        <td width="381" colspan="3"><br />
						{admintag}</td>
                      </tr>
                    </table>
<br />

<fieldset><button type="submit" name="add" class="submit2" style="width:230px;" onclick="doAddComments();return false;">Добавить новость</button>&nbsp;&nbsp;<button type="submit" name="nview" class="submit" style="width:230px;" onClick="preview()">Предпросмотр</button></fieldset>

</div>