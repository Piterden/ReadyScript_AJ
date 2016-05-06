<div class="daterange">
    {$values=$fitem->getValue()}
    <span class="from">{t('с')}</span> <input type="text" name="{$fitem->getName()}[from]" value="{if isset($values.from)}{$values.from}{/if}" {$fitem->getAttrString()} datefilter="datefilter"/> 
    <span class="to">{t('по')}</span> <input type="text" name="{$fitem->getName()}[to]" value="{if isset($values.to)}{$values.to}{/if}" {$fitem->getAttrString()} datefilter="datefilter"/>
</div>