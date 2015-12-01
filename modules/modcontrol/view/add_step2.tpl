{if !$mod_validate}
    <div class="error-list no-bottom-border">
        <div>
            {t}Продолжение установки невозможно, у загруженного модуля имеются следующие ошибки:{/t}
        </div>
        <ul>
            {foreach from=$mod_errors item=item name=err}
            <li>
                <div class="field"><span class="module-title">{$smarty.foreach.err.iteration}</span><i class="cor"></i></div>
                <div class="text">{$item}</div>
            </li>
            {/foreach}
        </ul>
    </div>
{else}
{if $mod_info.already_exists}
<div class="inform-block margvert10">
    {t}Такой модуль уже присутствует в системе. Продолжение установки приведет к обновлению модуля{/t}
</div>
{/if}

<div class="floatwrap">
    {if $mod_info.changelog}
    <div class="right-part">
        <h4>История изменений:</h4>
        <textarea readonly="readonly">{$mod_info.changelog|escape}</textarea>
    </div>
    {/if}
    <h4>Информация о модуле:</h4>
    <table class="new-module-info">
        <tr>
            <td class="ctitle"><span>{t}Название модуля:{/t}</span></td>
            <td>{$mod_info.info.name}</td>
        </tr>  
        <tr>
            <td class="ctitle"><span>{t}Описание модуля:{/t}</span></td>
            <td>{$mod_info.info.description}</td>
        </tr>    
        <tr>
            <td class="ctitle"><span>Автор:</span></td>
            <td>{$mod_info.info.author}</td>
        </tr>      
        <tr>
            <td class="ctitle"><span>Версия модуля:</span></td>
            <td>{$mod_info.info.version}</td>
        </tr>
        {if $mod_info.already_exists}
        <tr>
            <td class="ctitle"><span>Текущая версия модуля:</span></td>
            <td>{$mod_info.current_version}</td>
        </tr>
        {/if}
        <tr>
            <td colspan="2" class="last"></td>
        </tr>
    </table>    
    <form method="POST" class="crud-form">
        {if !$mod_info.already_exists && $mod_info.can_insert_demo_data}
        <h4>Параметры установки</h4>
        <input type="checkbox" name="insertDemoData" value="1" id="install_demo"> <label for="install_demo">Установить демонстрационные данные</label>
        {/if}
    </form>
    
</div>

{/if}