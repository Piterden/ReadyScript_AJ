    {if $self.type=='list'}
        {assign var=values value=$self->valuesArr(true)}
        <input type="hidden" name="prop[{$self.id}][value][]" value="" class="h-val">        
        {foreach from=$values item=oneitem key=key name="listitems"}
        <span class="inline-item property-type-list">
            <input type="checkbox" name="prop[{$self.id}][value][]" class="h-val" {$disabled} id="ch_{$self.id}{$key}" value="{$oneitem}" {if is_array($value) && in_array($oneitem,  $value)}checked{/if}>
            <label for="ch_{$self.id}{$key}">{$oneitem}</label>
        </span>
        {/foreach}        
    {elseif $self.type=='bool'}
        <input type="hidden" name="prop[{$self.id}][value]" value="0" class="h-val">
        <input type="checkbox" value="1" {if !empty($value)}checked{/if} name="prop[{$self.id}][value]" class="h-val" {$disabled}>
    {else}
        <input type="text" value="{$value}" name="prop[{$self.id}][value]" class="h-val" {$disabled}>
    {/if}