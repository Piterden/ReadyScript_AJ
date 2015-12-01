{if $field->get() != ''}
    <img src="{$field->getUrl($field->preview_width, $field->preview_height, $field->preview_resize_type)}"><br>
    <input type="checkbox" value="1" name="del_{$field->getName()}">Удалить<br>
{/if}
<input type="file" name="{$field->getFormName()}">