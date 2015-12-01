<option value="new">Новая характеристика</option>
{foreach from=$list item=item}
<option value="{$item.alias}|{$item.title}|{$item.type}|{$item.values}|{$item.defval}|{$item.unit}">{$item.title}</option>
{/foreach}