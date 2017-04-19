<div class="coment">
<div class="title">{title}</div>
<div class="line"></div>

<table width="32%">
    <tr>
        <td class="text2"><b><u>{question}</u></b></td>
    </tr>
    <tr>
        <td class="text"><br />{list}<br /></td>
    </tr>
    <tr>
        <td class="text" align="center">Всего проголосовало: {votes}</td>
    </tr>
    <tr>
        <td align="center"><br /><input type="button" onclick="doPoll('vote'); return false;" value="Голосовать">&nbsp;<input type="button" onclick="doPoll('results'); return false;" value="Результаты"></td>
    </tr>
</table>
</div>
