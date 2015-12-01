{foreach from=$item item=level name="sections"}
    <div class="grid_{$level.section.width} area {if $level.section.prefix} prefix_{$level.section.prefix}{/if}{if $level.section.suffix} suffix_{$level.section.suffix}{/if}{if $level.section.pull} pull_{$level.section.pull}{/if}{if $level.section.push} push_{$level.section.push}{/if}{if $level.section.parent_id>0}{if $smarty.foreach.sections.first} alpha{/if}{if $smarty.foreach.sections.last} omega{/if}{/if}" data-section-id="{$level.section.id}">
        <div class="commontools">            
            {if $level.section.width>1}Секция {/if}{$level.section.width}
            <span class="drag-handler"></span>
            
            <div class="rs-list-button container-tools">
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
        <div class="workarea{if !empty($level.childs)} sort-sections{else} sort-blocks{/if}">
            {if !empty($level.childs)}
                {include file="block_manager_section.tpl" item=$level.childs}
            {else}
                {foreach from=$level.section->getBlocks() item=block}
                <div class="block{if $level.section.inset_align == 'left'} alignleft{/if}{if $level.section.inset_align == 'right'} alignright{/if}" data-block-id="{$block.id}">
                    <span class="drag-block-handler"></span>
                    <div class="title">
                        <span class="help-icon" title="{if $level.section.width == 1}<strong>{$block->getBlockInfo('title')}</strong><br>{/if}{$block->getBlockInfo('description')}">?</span>
                        {if $level.section.width > 1}
                            <span class="name">{$block->getBlockInfo('title')}</span>
                        {/if}
                    </div>
                    {if $level.section.width>1}
                        <div class="tools">
                            <a class="isettings itool crud-edit" href="{adminUrl do="editModule" id=$block.id}" title="{t}Редактировать блок{/t}"><i></i></a>
                            <a class="iremove itool crud-remove-one" href="{adminUrl do="delModule" id=$block.id}" title="{t}Удалить{/t}"><i></i></a>
                        </div>
                    {else}
                        <div style="text-align:center;">
                        <div class="rs-list-button container-tools" style="margin-left:9px">
                            <a class="rs-dropdown-handler">&nbsp;</a>
                            <ul class="rs-dropdown">
                                <li><a href="{adminUrl do="editModule" id=$block.id}" class="isettings itool crud-edit"><i></i></a></li>
                                <li><a href="{adminUrl do="delModule" id=$block.id}" class="iremove itool crud-remove-one"><i></i></a></li>
                            </ul>
                        </div>
                        </div>
                    {/if}
                </div>
                {/foreach}
            {/if}
        </div>
    </div>
{/foreach}