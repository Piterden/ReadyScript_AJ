<textarea name="{$field->getFormName()}" {$field->getAttr()}>{$field->get()}</textarea>
<span class="vat">{include file="%system%/coreobject/type/form/block_hint.tpl"}</span>
{include file="%system%/coreobject/type/form/block_error.tpl"}