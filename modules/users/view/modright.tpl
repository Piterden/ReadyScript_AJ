&nbsp;
<a href="JavaScript:;" onclick="$(this).nextAll('input:checkbox').attr('checked','checked')" style="text-decoration:underline" title="Нажмите, чтобы включить все привелегии модуля">Максимум</a>
{assign var=row value=$cell->getRow()}
{foreach from=$row.bits item=checked key=n}
   <input type="checkbox" name="module_access[{$row.class}][]" value="{$n}" title="{$row.accessbit.$n|default:"Не используется"}" {if $checked}checked{/if}>
{/foreach}

<a href="JavaScript:;" onclick="$(this).prevAll('input:checkbox').removeAttr('checked')" style="text-decoration:underline" title="Нажмите, чтобы отключить привилегии модуля">Нет прав</a>