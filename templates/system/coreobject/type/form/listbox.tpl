<select name="{$field->getFormName()}" {$field->getAttr()}>
{rshtml_options options=$field->getList() selected=$field->get()}
</select>
{include file="%system%/coreobject/type/form/block_error.tpl"}