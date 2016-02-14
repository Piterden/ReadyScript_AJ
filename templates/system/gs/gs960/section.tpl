{* Шаблон содержимого контейнера для BootStrap *}
{strip}
    {foreach from=$item item=level name="sections"}
        <div class="grid_{$level.section.width}{if $level.section.prefix} prefix_{$level.section.prefix}{/if}{if $level.section.suffix} suffix_{$level.section.suffix}{/if}{if $level.section.pull} pull_{$level.section.pull}{/if}{if $level.section.push} push_{$level.section.push}{/if}{if $level.section.parent_id>0}{if $smarty.foreach.sections.first} alpha{/if}{if $smarty.foreach.sections.last} omega{/if}{/if} {if $level.section.css_class}{$level.section.css_class}{/if}">
            {if !empty($level.childs)}
                {include file="%system%/gs/{$layouts.grid_system}/section.tpl" item=$level.childs}
            {else}
                {include file="%system%/gs/blocks.tpl"}
            {/if}
        </div>
        {if $level.section.is_clearfix_after}<div class="clearfix {$level.section.clearfix_after_css}"></div>{/if}
    {/foreach}
{/strip}