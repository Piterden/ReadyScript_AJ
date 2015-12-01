<fieldset>
    {if $fline->getOption('is_first') && $fline->getOption('has_second_containers')}
        <span class="item">
            <a class="expand"></a>
        </span>
    {/if}
    {foreach from=$fline->getItems() item=item}
        <span class="item">
            {$item->getView()}
        </span>
    {/foreach}
    {if $fline->getOption('is_first')}
        <span class="item">
            <button type="submit" class="find" title="{t}применить фильтр{/t}"></button>
        </span>
    {/if}
</fieldset>