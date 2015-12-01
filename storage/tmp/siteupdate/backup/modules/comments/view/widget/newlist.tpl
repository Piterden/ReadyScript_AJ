{$config=ConfigLoader::byModule('comment')}
<table class="wtable">
    <thead>
        <tr>
            <th>Комментарий</th>        
            <th>Инфо</th>
        </tr>
    </thead>
    <tbody class="overable">
        {foreach from=$list item=item}
        {$item_time=strtotime($item.dateof)}        
        <tr class="crud-edit {if (!$config.need_moderate && ($item_time<$time && $item_time>$day_before_time_int) && !$item.moderated) || ($config.need_moderate && !$item.moderated)}highlight{/if}" data-crud-options='{ "updateThis": true }' data-url="{adminUrl mod_controller="comments-ctrl" do="edit" id=$item.id}">
            <td title="{t}Нажмите, чтобы перейти к редактированию{/t}">{$item.message|teaser:"250"}</td>        
            <td width="45">
                <span class="help-icon" title="{$item.dateof|dateformat:"%datetime"}<br>{$item.type_obj_title}">?</span>
            </td>
        </tr>
        {/foreach}
        {if empty($list)}
        <tr>
            <td colspan="3" align="center">Нет комментариев</td>
        </tr>
        {/if}
    </tbody>
</table>

{include file="%SYSTEM%/admin/widget/paginator.tpl"}