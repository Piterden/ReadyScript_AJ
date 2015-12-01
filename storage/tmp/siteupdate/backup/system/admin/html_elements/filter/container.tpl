{* Контейнер фильтра *}

<div class="formfilter">
    <a class="close"></a>
    {foreach from=$fcontainer->getLines() item=line}
        {$line->getView()}
    {/foreach}                

    <div class="more">
        {foreach from=$fcontainer->getSecContainers() item=sec_cont}
            {$sec_cont->getView()}
        {/foreach}
    </div>
</div>