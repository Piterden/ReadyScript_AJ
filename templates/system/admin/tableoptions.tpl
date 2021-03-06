{* Настройка колонок таблицы *}
<p class="caption-help">Выберите колонки, которые должны отображаться<br> в таблице, а также сортировку по-умолчанию</p>
<form class="crud-form">
    <table id="tableOptions" data-table-id="{$elements.tableControl->getId()}">
    {foreach from=$elements.tableControl->getTable()->getCustomizableColumns() key=key item=column}
        <tr data-field="{$key}">
            <td class="chk{if !$column->property.hidden} checked{/if}"><input type="checkbox" class="column" value="{$key}" {if !$column->property.hidden}checked{/if}></td>
            <td>
                {if $column->property['Sortable']}            
                    {if isset($column->property['CurrentSort'])}
                        {if $column->property['CurrentSort'] == $smarty.const.SORTABLE_ASC}
                            {assign var=sortn value="asc"}
                        {else}
                            {assign var=sortn value="desc"}
                        {/if}
                        <input type="hidden" class="current-sort-column" value="{$key}">
                        <input type="hidden" class="current-sort-direction" value="{$column->property['CurrentSort']}">
                    {else}
                        {assign var=sortn value="no"}
                    {/if}
                    <a class="a-underline ch-sort {$sortn}" data-can-be="{$column->property['Sortable']}"><span>{$column->getTitle()}</span></a>
                {else}
                    <span>{$column->getTitle()}</span>
                {/if}
            </td>
        </tr>
    {/foreach}
    </table>
</form>

<script>
    $(function() {
        var $context = $('#tableOptions');
        $context.tableOptions();
        
        var cookie_options = {    
            expires: new Date((new Date()).valueOf() + 1000*3600*24*365*5)
        };
        var columns_key = $context.data('tableId')+'[columns]';
        var sort_key = $context.data('tableId')+'[sort]';
        
        //Сохраняем настройки
        $('.saveToCookie').click(function() {
            //Подготавливаем параметры
            var columns_value = new Array();
            $('.column', $context).each(function() {
                columns_value.push($(this).val()+'='+(this.checked ? 'Y' : 'N'));
            });
            $.cookie(columns_key, columns_value.join(','), cookie_options);
            
            $('.asc, .desc', $context).each(function() {
                var sort_direction = $(this).hasClass('asc') ? 'asc' : 'desc';
                var sort_field = $(this).closest('[data-field]').data('field');
                $.cookie(sort_key, sort_field+'='+sort_direction, cookie_options);
            });
            
            $context.closest('.dialog-window').dialog('close');
            updatable.updateTarget($context);
        });
        
        //Сбрасываем настройки
        $('.reset').click(function() {
            $.cookie(columns_key, null);
            $.cookie(sort_key, null);
            
            $($context).closest('.dialog-window').dialog('close');
            updatable.updateTarget(this);
            
        });        
    });
</script>