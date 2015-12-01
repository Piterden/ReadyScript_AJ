<div class="tools">
    {foreach from=$cell->getActions() item=item}
        {include file=$item->getTemplate() tool=$item}
    {/foreach}
</div>