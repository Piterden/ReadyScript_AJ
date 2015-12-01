{* Шаблон содержимого контейнера для GS960 *}
{strip}
{foreach from=$item item=level name="sections"}
    <div class="{if $level.section.element_type == 'row'}row{else}{*
        *}{include file="%templates%/gs/bootstrap/attribute.tpl" field="width"}{*
        *}{include file="%templates%/gs/bootstrap/attribute.tpl" field="prefix" name="offset-"}{*
        *}{include file="%templates%/gs/bootstrap/attribute.tpl" field="pull" name="pull-"}{*
        *}{include file="%templates%/gs/bootstrap/attribute.tpl" field="push" name="push-"}{/if} {*
        *}{if $level.section.css_class}{$level.section.css_class}{/if}">
    
        {if !empty($level.childs)}
            {include file="%system%/gs/{$layouts.grid_system}/section.tpl" item=$level.childs}
        {else}
            {include file="%system%/gs/blocks.tpl"}
        {/if}
    </div>
    {if $level.section.is_clearfix_after}<div class="clearfix {$level.section.clearfix_after_css}"></div>{/if}
{/foreach}
{/strip}