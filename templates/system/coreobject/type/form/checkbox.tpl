<input type="checkbox" name="{$field->getFormName()}" value="{$field->getCheckboxParam('on')}" {if $field->get()==$field->getCheckboxParam('on')}checked{/if} {$field->getAttr()}>
{include file="%system%/coreobject/type/form/block_hint.tpl"}
{include file="%system%/coreobject/type/form/block_error.tpl"}