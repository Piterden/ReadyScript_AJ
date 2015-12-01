<div class="infobox">
    <div class="controller_info">
        <h3>Контроллеры</h3>
        <ul class="content">
            {foreach from=$controller_list item=item}
            <li>{$item}</li>
            {/foreach}
        </ul>
    </div>

    <br />
    <div class="controller_info">
        <h3>Утилиты</h3>
        <ul class="content">
            <li><a href="JavaScript:;" onclick="modTools.exec('?do=modUpdateDb&ajax=1','{$mod_name}');" style="text-decoration:underline">Обновить структуру БД</a></li>
            <li><a href="JavaScript:;" onclick="modTools.exec('?do=modUpdateMenu&ajax=1','{$mod_name}');" style="text-decoration:underline">Создать меню</a></li>
        </ul>
    </div>    
    
</div>
<div class="formbox">
    <div class="result_ajax ok hidden" id="ajaxresult"></div>
    {$mod_form}
</div>
<div class="clear"><!----></div>
