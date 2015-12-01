{strip}
{foreach from=$item item=level name="sections"}
    <div class="grid_{$level.section.width}{if $level.section.prefix} prefix_{$level.section.prefix}{/if}{if $level.section.suffix} suffix_{$level.section.suffix}{/if}{if $level.section.pull} pull_{$level.section.pull}{/if}{if $level.section.push} push_{$level.section.push}{/if}{if $level.section.parent_id>0}{if $smarty.foreach.sections.first} alpha{/if}{if $smarty.foreach.sections.last} omega{/if}{/if} {if $level.section.css_class}{$level.section.css_class}{/if}">
        {if !empty($level.childs)}
            {include file="%system%/gs_section.tpl" item=$level.childs}
        {else}
            {if $layouts.blocks[$level.section.id]}
                {foreach from=$layouts.blocks[$level.section.id] item=block}
                {if $level.section.inset_align != 'wide'}
                <div class="block{if $level.section.inset_align == 'left'} alignleft{/if}{if $level.section.inset_align == 'right'} alignright{/if}">
                {/if}
                    {moduleinsert name=$block.module_controller _params_array=$block->getParams()}
                {if $level.section.inset_align != 'wide'}
                </div>
                {/if}
                {/foreach}
            {/if}
        {/if}
    </div>
{/foreach}
{/strip}