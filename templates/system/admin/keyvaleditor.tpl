{* редактор ключ => значение *}
{addjs file="jquery.tablednd.js" basepath="common"}
{addjs file="jquery.keyvaleditor.js" basepath="common"}
<div class="keyval-container" data-var="{$field_name}">
    <table class="keyvalTable{if empty($arr)} hidden{/if}">
        <thead>
            <tr>
                <th></th>
                <th class="kv-head-key">{t}Параметр{/t}</th>
                <th class="kv-head-val">{t}Значение{/t}</th>
            </tr>
        </thead>
        <tbody>
            {if is_array($arr)}
            {foreach from=$arr key=prop_key item=prop_val}
            <tr>
                <td class="kv-sort">
                    <div class="ksort"></div>
                </td>
                <td class="kv-key"><input type="text" name="{$field_name}[key][]" value="{$prop_key}"></td>
                <td class="kv-val"><input type="text" name="{$field_name}[val][]" value="{$prop_val}"></td>
                <td class="kv-del"><a class="remove"></a></td>
            </tr>
            {/foreach}
            {/if}
        </tbody>
    </table>
    <a href="javascript:;" class="add-pair">{t}{$add_button_text|default:"Добавить"}{/t}</a>
</div>