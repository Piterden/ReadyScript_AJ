{if !$url->isAjax()}
    <div id="content-layout">
        <div class="viewport">
{/if}
    <div class="titlebox">{t}Виджеты{/t}</div>
    <div class="widget-collection overable" data-dialog-options='{ "width": "1010px" }'>
    {foreach from=$list item=item key=wid}
            <!-- Начало: виджет -->
            <div class="item{if !empty($item.use)} already-exists{/if}" data-wclass="{$item.short_class}">
                <div class="item-head">
                    <a class="add" title="{t}Добавить виджет{/t}"></a>
                    <a class="remove" title="{t}Скрыть виджет{/t}"></a>
                    <div class="title">{$item.title}</div>
                </div>
                <div class="item-body">
                    <table class="preview-table">
                        <tr>
                            <td class="key">Описание:</td>
                            <td>{$item.description}</td>
                        </tr>
                        <tr>
                            <td class="key">Модуль:</td>
                            <td>{$item.module}</td>
                        </tr>                        
                    </table>
                </div>
            </div>
            <!-- Конец: виджет -->
    {/foreach}
    </div>

{if !$url->isAjax()}
    </div> <!-- .viewport -->
</div> <!-- .content -->
{/if}