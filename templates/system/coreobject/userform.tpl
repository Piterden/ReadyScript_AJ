{if $fld.type == 'string'}
    <input type="text" name="{$fld.fieldname}" value="{$values[$fld.alias]}" {if $fld.maxlength>0}maxlength="{$fld.maxlength}"{/if} class="{if $has_error} has-error{/if}" placeholder="{$fld.title}">
{elseif $fld.type == 'list'}
    <select name="{$fld.fieldname}">
        {if $fld.necessary}
        <option value="">Не выбрано</option>
        {/if}
        {foreach from=$options item=option}
        <option{if $option==$values[$fld.alias]} selected{/if}>{$option}</option>
        {/foreach}
    </select>
{elseif $fld.type == 'bool'}
    <input type="checkbox" name="{$fld.fieldname}" value="1" {if $values[$fld.alias]}checked{/if}>
{/if}