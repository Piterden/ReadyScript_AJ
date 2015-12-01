
{if count($list)>0}
<div class="mini-paginator before-table">
    <a class="pag_left" href="JavaScript:;" gopage="{if $paginator.page>1}{$paginator.page-1}{else}1{/if}">&nbsp;</a>
    <input type="text" value="{$paginator.page}" size="3" class="pag_page">
    <a class="pag_right" href="JavaScript:;" gopage="{if $paginator.page<$paginator.totalPages}{$paginator.page+1}{else}{$paginator.totalPages}{/if}">&nbsp;</a> из {$paginator.totalPages} | всего записей: {$paginator.total} | показывать по <input type="text" class="pag_pagesize" value="{$paginator.pageSize}" size="3">
    <input type="button" value="OK" class="pag_submit">
</div>
{/if}
{if count($list)>0}
<table class="product-list">
<thead>
    <tr>
        <th {if !$hideProductCheckbox}colspan="2"{/if}>Название</th>
        <th>№</th>
        <th class="textright">Артикул</th>
    </tr>
</thead>
<tbody>
{foreach from=$list item=item}
<tr data-id="{$item.id}">
    {if !$hideProductCheckbox}
    <td class="chk">
        <input type="checkbox" value="{$item.id}" data-weight="{$item.weight}" catids="{foreach from=$products_dirs[$item.id] item=cat},{$cat}{/foreach},">
    </td>
    {/if}
    <td class="title">{$item.title}</td>
    <td class="no">{$item.id}</td>
    <td class="barcode" align="right">{$item.barcode}</td>
</tr>
{/foreach}
</tbody>
</table>
{else}
<br><br><br><br><br><br><br><br>
<table width="100%">
<tr>
    <td align="center" class="no-goods">{t}Нет товаров{/t}</td>
</tr>
</table>
{/if}
{if count($list)>0}
<div class="mini-paginator">
    <a class="pag_left" href="JavaScript:;" gopage="{if $paginator.page>1}{$paginator.page-1}{else}1{/if}">&nbsp;</a>
    <input type="text" value="{$paginator.page}" size="3" class="pag_page">
    <a class="pag_right" href="JavaScript:;" gopage="{if $paginator.page<$paginator.totalPages}{$paginator.page+1}{else}{$paginator.totalPages}{/if}">&nbsp;</a> из {$paginator.totalPages} | всего записей: {$paginator.total} | показывать по <input type="text" class="pag_pagesize" value="{$paginator.pageSize}" size="3">
    <input type="button" value="OK" class="pag_submit">
</div>
{/if}

{* Блок, который будет перенесен в полоску кнопок внизу диалога *}
<div class="hidden">
    <div class="to-dialog-buttonpane" style="float:left;">
        <br>
        Тип цены:
        <select name="costtype">
        {foreach from=$costtypes item=cost}
            <option value="{$cost->id}">{$cost->title}</option>
        {/foreach}
        </select>
    </div>
</div>
