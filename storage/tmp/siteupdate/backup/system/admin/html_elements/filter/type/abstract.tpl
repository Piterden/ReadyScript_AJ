{if $fitem->getTitle()}<label {$fitem->getTitleAttrString()}>{$fitem->getTitle()}</label><br>{/if}
{* Если есть префильтры *}
{foreach from=$fitem->getPrefilters() item=item}
    {$item->getView()}
{/foreach}
{* Конец: Если есть префильтры *}    
{include file=$fitem->tpl}