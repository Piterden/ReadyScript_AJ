{$devices=['lg' => '_lg', 'md' => '', 'sm' => '_sm', 'xs' => '_xs']}
{foreach from=$item item=level name="sections"}
    <div class="area {if $level.section.element_type == 'row'}row{else}{*
        *}{include file="%templates%/gs/bootstrap/attribute.tpl" field="width"}{*
        *}{include file="%templates%/gs/bootstrap/attribute.tpl" field="prefix" name="offset-"}{*
        *}{include file="%templates%/gs/bootstrap/attribute.tpl" field="pull" name="pull-"}{*
        *}{include file="%templates%/gs/bootstrap/attribute.tpl" field="push" name="push-"}{/if}" data-section-id="{$level.section.id}">
        <div class="commontools">            
            {if $level.section.element_type == 'row'}Строка{else}
            <span class="section-width" data-xs-width="{$level.section.width_xs}"
                                        data-sm-width="{$level.section.width_sm}"
                                        data-md-width="{$level.section.width}"
                                        data-lg-width="{$level.section.width_lg}">&nbsp;</span>
            {/if}
            <span class="drag-handler"></span>
            
            <div class="rs-list-button section-tools">
                <a class="rs-dropdown-handler">&nbsp;</a>
                <ul class="rs-dropdown">
                    {if $level.section->canInsertModule()}
                        <li><a class="iplusmodule itool crud-add" data-url="{adminUrl do=addModule section_id=$level.section.id}" title="{t}Добавить модуль{/t}" data-crud-options='{ "dialogId": "blockListDialog", "sectionId": "{$level.section.id}" }'><i></i></a></li>
                    {/if}
                    {if $level.section->canInsertSection()}
                        <li><a class="iplusrow itool crud-add" href="{adminUrl do=addSection parent_id=$level.section.id page_id=$currentPage.id element_type="row"}" title="{t}Добавить строку{/t}"><i></i></a></li>                    
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
                {include file="%templates%/gs/bootstrap/section.tpl" item=$level.childs}
            {else}
                {include file="%templates%/gs/blocks.tpl" level=$level}
            {/if}
        </div>
    </div>
{/foreach}