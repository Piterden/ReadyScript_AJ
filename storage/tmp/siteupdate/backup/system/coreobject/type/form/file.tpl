{if $field->get() != ''}
    <a href="{$field->getLink()}" target="_blank">{$field->getFileName()}</a>&nbsp;
    <input type="checkbox" value="1" name="del_{$field->getName()}">Удалить<br>
{/if}
<input type="file" name="{$field->getFormName()}">