{foreach from=$item item=level name="sections"}
    <div class="grid_{$level.section.width} area {if $level.section.prefix} prefix_{$level.section.prefix}{/if}{if $level.section.suffix} suffix_{$level.section.suffix}{/if}{if $level.section.pull} pull_{$level.section.pull}{/if}{if $level.section.push} push_{$level.section.push}{/if}{if $level.section.parent_id>0}{if $smarty.foreach.sections.first} alpha{/if}{if $smarty.foreach.sections.last} omega{/if}{/if}" data-section-id="{$level.section.id}">
        <div class="commontools">            
            {if $level.section.width>1}Секция {/if}{$level.section.width}
            <span class="drag-handler"></span>
            
            <div class="rs-list-button section-tools">
                <a class="rs-dropdown-handler">&nbsp;</a>
                <ul class="rs-dropdown">
                    {if $level.section->canInsertModule()}
                        <li><a class="iplusmodule itool crud-add" data-url="{adminUrl do=addModule section_id=$level.section.id}" title="{t}Добавить модуль{/t}" data-crud-options='{ "dialogId": "blockListDialog", "sectionId": "{$level.section.id}" }'><i></i></a></li>
                    {/if}
                    {if $level.section->canInsertSection()}
                        <li><a class="iplus itool crud-add" href="{adminUrl do=addSection parent_id=$level.section.id page_id=$currentPage.id}" title="{t}Добавить секцию{/t}"><i></i></a></li>
                    {/if}
                    <li><a class="isettings itool crud-edit" href="{adminUrl do=editSection id=$level.section.id}" title="{t}Редактировать секцию{/t}"><i></i></a></li>
                    <li><a class="iremove itool crud-remove-one" href="{adminUrl do=delSection id=$level.section.id}" title="{t}Удалить{/t}"><i></i></a></li>
                </ul>
            </div>
        </div>    
        {if $level.section.is_clearfix_after}<div class="clearfix-after"></div>{/if}
        <div class="workarea{if !empty($level.childs)} sort-sections{else} sort-blocks{/if}">
            {if !empty($level.childs)}
                {include file="%templates%/gs/gs960/section.tpl" item=$level.childs}
            {else}
                {include file="%templates%/gs/blocks.tpl" level=$level}
            {/if}
        </div>
    </div>
{/foreach}