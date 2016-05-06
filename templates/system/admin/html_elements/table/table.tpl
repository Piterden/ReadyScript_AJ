{addjs file="table.js"}

<table border="0" {$table->getTableAttr()}>
    <thead>
    <tr>
        {foreach from=$table->getColumns() key=col item=item}
            {if !$item->property.hidden}
            <th {$item->getThAttr()}>{include file=$item->getHeadTemplate() cell=$item}</th>
            {/if}
        {/foreach}
    </tr>
    </thead>
    <tbody>
        <tr class="empty no-over nodrop">
            <td colspan="{count($table->getColumns())}"></td>
        </tr>
        {* Вставляем anyrows подряд, если нет записей в таблице *}
            {if isset($anyrows.0) && empty($rows)}
            <tr {$table->getAnyRowAttr($rownum)}>
                {foreach from=$anyrows.0 item=anycell}
                <td {$anycell->getTdAttr()}>
                    {if isset($anycell->property.href)}<a href="{$anycell->property.href}" {$anycell->getLinkAttr()}>{/if}{$anycell->getValue()}{if isset($anycell->property.href)}</a>{/if}
                </td>
                {/foreach}
            </tr>
            {/if}
        {* Вставляем записи в обычном порядке *}
        {foreach from=$rows key=rownum item=row}
        {if isset($anyrows.$rownum)}
        <tr {$table->getAnyRowAttr($rownum)}>
            {foreach from=$anyrows.$rownum item=anycell}
            <td {$anycell->getTdAttr()}>
                {if isset($anycell->property.href)}<a href="{$anycell->property.href}" {$anycell->getLinkAttr()}>{/if}{$anycell->getValue()}{if isset($anycell->property.href)}</a>{/if}
            </td>
            {/foreach}
        </tr>
        {/if}
        <tr {$table->getRowAttr($rownum)}>
            {foreach from=$row key=col item=cell}
            <td {$cell->getTdAttr()}>
                {if isset($cell->property.href)}<a href="{$cell->getHref()}" {$cell->getLinkAttr()}>{/if}
                    {include file=$cell->getBodyTemplate() cell=$cell}                
                {if isset($cell->property.href)}</a>{/if}
            </td>
            {/foreach}
        </tr>
        {/foreach}
        {if empty($anyrows) && empty($rows)}
        <tr>
            {assign var=count value=count($table->getColumns())}
            <td colspan="{$count}" align="center"> Нет элементов
            </td>
        </tr>
        {/if}
    </tbody>
 </table>
