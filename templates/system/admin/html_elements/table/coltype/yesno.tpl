{if $cell->getValue()}
    {assign var=extra_class value=" on"}
{else}
    {assign var=extra_class value=" off"}
{/if}
<div {$cell->getAttr(['class' => " {$extra_class}"])}>
    <div class="rs-border">
        <div class="rs-back"></div>
        <div class="rs-handle"></div>        
    </div>
</div>
