[votelist]
<div class="fov">
<div class="text_box"><u>{title}</u></div>
<form method="post" name="vote" action=''>
<div class="menu">{list}</div>
</div>
<br />
<div class="box3">
<table width="210">
<tr><td width="100" valign="top" align="center">
<input type="hidden" name="vote_action" value="vote">
<input type="hidden" name="vote_id" id="vote_id" value="{vote_id}">
<a class="but" onclick="doVote('vote'); return false;" href="{$link_logout}"><span>Голосовать</span></a>
</td></form><td valign="top" align="center" width="100">
<form method=post name="vote_result" action=''>
<input type="hidden" name="vote_action" value="results">
<input type="hidden" name="vote_id" value="{vote_id}">
<a class="but"onclick="doVote('results'); return false;"  href="{$link_logout}"><span>Результаты</span></a>
</form>
 </td></tr>
</table></div>
[/votelist]



[voteresult]

<div class="text_box"><u>{title}</u></div>
<div class="fov">
<div class="menu">{list}</div>
<div class="menu"><b> Всего проголосовало: {votes}</b> </div>
</div>
</div>
          
[/voteresult]
