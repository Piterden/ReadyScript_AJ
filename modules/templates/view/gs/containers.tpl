{if !empty($currentPage.template)}
    <div class="pageview-text">
        {t tpl=$currentPage.template link={adminUrl do="editPage" id=$currentPage.id}}Для <a href="%link" class="crud-edit uline">текущей страницы</a> задан шаблон `<strong>%tpl</strong>`. Сборка элементов по сетке в этом случае невозможна.
        Все необходимые для данной странице модули должны быть указаны вручную в данном шаблоне.{/t}
    </div>
{else}
    {if $grid_system == 'none'}
        <div class="pageview-text">
            {t}Тема оформления не использует сетку. Все необходимые для страницы модули должны быть указаны вручную в шаблоне, установленном в <a class="crud-edit uline" href="{adminUrl do="editPage" id=$currentPage.id}">настройках страницы</a>.{/t}
        </div>
    {else}
        {assign var=containers value=$currentPage->getContainers()}
        {foreach from=$containers item=container name="containers"}
        {if !$container.object}
        <div class="inherit">
            {if empty($defaultPage.template) && $container.defaultObject}
                Контейнер "{$container.defaultObject->getTitle()}" используется со страницы по умолчанию. 
                Если Вы хотите придать другой вид этой части страницы, <a class="crud-add make-container" href="{adminUrl do="addContainer" page_id=$currentPage.id type=$container.type context=$context}">создайте новый контейнер</a> или 
                <a data-url="{adminUrl do="copyContainer" page_id=$currentPage.id type=$container.type context=$context}" class="crud-add make-container">скопируйте контейнер</a>, чтобы затем изменить его.
            {else}
                Контейнер будет исключен для данной сраницы.
                Если Вы хотите его использовать, <a class="crud-add make-container" href="{adminUrl do="addContainer" page_id=$currentPage.id type=$container.type context=$context}">создайте контейнер</a>.
            {/if}
        </div>
        {else}
        <div class="{if $grid_system == "bootstrap"}container bs_col{$container.object.columns}{elseif $grid_system == "gs960"}container_{$container.object.columns} col{$container.object.columns}{/if} gs-manager {if $smarty.cookies["page-constructor-disabled-{$container.object.id}"]}grid-disabled{/if}" data-container-id="{$container.object.id}">
            <div class="commontools">            
                {$container.object->getTitle()}
                
                <div class="rs-list-button container-tools">
                    <a class="rs-dropdown-handler">&nbsp;</a>
                    <ul class="rs-dropdown">
                        {if $grid_system == 'bootstrap'}
                            <li class="first"><a class="iplusrow itool crud-add" title="{t}Добавить строку{/t}" href="{adminUrl do=addSection page_id=$currentPage.id parent_id=-$container.object.type element_type="row"}"><i></i></a></li>
                        {else}
                            <li class="first"><a class="iplus itool crud-add" title="{t}Добавить секцию{/t}" href="{adminUrl do=addSection page_id=$currentPage.id parent_id=-$container.object.type}"><i></i></a></li>
                        {/if}
                        <li><a class="isettings itool crud-edit" title="{t}Настройки{/t}" href="{adminUrl do=editContainer id=$container.object.id page_id=$currentPage.id type=$container.object.type}"><i></i></a></li>
                        {if $currentPage.route_id != 'default' || $smarty.foreach.containers.last}
                            <li><a class="iremove itool crud-remove-one" title="{t}Удалить контейнер{/t}" href="{adminUrl do=removeContainer id=$container.object.id}"><i></i></a></li>
                        {/if}
                    </ul>
                </div>                                            
                
                <div class="drag-handler"></div>
                <div class="grid-switcher{if $smarty.cookies["page-constructor-disabled-{$container.object.id}"]} off{/if}" title="Включить/Выключить сетку"></div>                    
            </div>                                    
            <div class="workarea sort-sections"> <!-- Рабочая область контейнера -->
                    {include file=$section_tpl item=$container.object->getSections()}
            </div> <!-- Конец рабочей области контейнера -->
        </div>
        {/if}        
        <div class="gs-sep"></div>
        {/foreach}
        <br>
        <div class="container-tools">
            <a class="crud-add make-container button add" href="{adminUrl do="addContainer" page_id=$currentPage.id type=$currentPage->max_container_type+1}">добавить контейнер</a>
            {if count($containers)}
            <a class="crud-remove-one button delete" href="{adminUrl do=removeContainer id=$container.object.id}">{t}Удалить нижний контейнер{/t}</a>
            {/if}
        </div>
        <div class="footerspace"></div>
    {/if}
{/if}