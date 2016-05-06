{foreach from=$level.section->getBlocks() item=block}
    <div class="block wide {if $block.public} on{/if} {if $level.section.inset_align == 'left'} alignleft{/if}{if $level.section.inset_align == 'right'} alignright{/if}" data-block-id="{$block.id}">
        <span class="drag-block-handler"></span>
        <div class="title">
            <div class="title-wide">
                <span class="help-icon" title="{$block->getBlockInfo('description')}">?</span>
                <span class="name">{$block->getBlockInfo('title')}</span>
            </div>
            <div class="title-small">
                <span class="help-icon" title="<strong>{$block->getBlockInfo('title')}</strong><br>{$block->getBlockInfo('description')}">?</span>
            </div>
        </div>
        
        <div class="rs-list-button container-tools">
            <a class="rs-dropdown-handler">&nbsp;</a>
            <ul class="rs-dropdown">
                <li><a class="iswitch itool" title="{t}Включить/Выключить{/t}"><i></i></a></li>            
                <li><a href="{adminUrl do="editModule" id=$block.id}" class="isettings itool crud-edit" title="{t}Настройка блока{/t}"><i></i></a></li>
                <li><a href="{adminUrl do="delModule" id=$block.id}" class="iremove itool crud-remove-one" title="{t}Удалить блок{/t}"><i></i></a></li>
            </ul>
        </div>
    </div>
{/foreach}