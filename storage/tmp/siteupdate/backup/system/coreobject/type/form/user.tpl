{addjs file="jquery.deftext.js" basepath="common"}
{addjs file="jquery.userselect.js" basepath="common"}

<input type="text" data-name="{$field->getFormName()}" class="user-select" {if $field->get()>0} value="{$field->getUser()->getFio()}"{/if} {$field->getAttr()} data-request-url="{$field->getRequestUrl()}">
{if $field->get()>0}<input type="hidden" name="{$field->getFormName()}" value="{$field->get()}">{/if}