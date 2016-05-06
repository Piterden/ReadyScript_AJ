{addjs file="jquery.deftext.js" basepath="common"}
{addjs file="jquery.userselect.js" basepath="common"}

<input type="text" data-name="{$fitem->getName()}" {if $fitem->getValue()>0} value="{$fitem->getTextValue()}"{/if} {$fitem->getAttrString()} data-request-url="{$fitem->getRequestUrl()}">
{if $fitem->getValue()>0}<input type="hidden" name="{$fitem->getName()}" value="{$fitem->getValue()}">{/if}