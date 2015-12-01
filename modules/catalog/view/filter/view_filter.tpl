{assign var=name value=$fitem->getName()}
{assign var=value value=$fitem->getValue()}

{if $prop.type == 'int'}
<span class="filter-inp{$prop.id}">
    <input type="text" name="{$name}[{$prop.id}][from]" value="{$value[$prop.id].from}" class="pr-int"> - 
    <input type="text" name="{$name}[{$prop.id}][to]" value="{$value[$prop.id].to}" class="pr-int"> 
</span>

{elseif $prop.type == 'list'}

<ul class="pr-list">
    {foreach from=$prop->valuesArr() item=val name="pval"}
    <li><input id="f_{$prop.id}_{$smarty.foreach.pval.iteration}" type="checkbox" value="{$val}" name="{$name}[{$prop.id}][]" {if is_array($value[$prop.id]) && in_array($val, $value[$prop.id])}checked{/if} class="pr-list">
        <label for="f_{$prop.id}_{$smarty.foreach.pval.iteration}" class="pr-list-label">{$val}</label>
    </li>
    {/foreach}
</ul>

{elseif $prop.type == 'bool'}

<select name="{$name}[{$prop.id}]" class="pr-bool">
    <option value="" {if $value[$prop.id] == ''}selected{/if}>Не важно</option>
    <option value="1" {if $value[$prop.id] == '1'}selected{/if}>Есть</option>
    <option value="0" {if $value[$prop.id] === '0'}selected{/if}>Нет</option>
</select>

{else}

<input type="text" name="{$name}[{$prop.id}]" value="{$value[$prop.id]}" class="pr-str">

{/if}