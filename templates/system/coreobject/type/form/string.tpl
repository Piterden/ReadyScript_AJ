{$attr=$field->getAttrArray()}
<input name="{$field->getFormName()}" value="{if $field->get()|@strip:''==''}{$field->get()|@strip:''}{else}{$field->get()}{/if}" {if $field->getMaxLength()>0}maxlength="{$field->getMaxLength()}"{/if} {$field->getAttr()} {if !$attr.type}type="text"{/if}/>
{include file="%system%/coreobject/type/form/block_hint.tpl"}
{include file="%system%/coreobject/type/form/block_error.tpl"}
