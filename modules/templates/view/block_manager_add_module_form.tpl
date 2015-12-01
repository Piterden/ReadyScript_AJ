<div class="titlebox" data-dialog-options='{ "width":"1000" }'>{t}Выберите блок, который желаете вставить{/t}</div>
    <div class="module-blocks">
        <div class="left">
            <div class="columntitle"><span>модули</span></div>
            <ul class="modules">
                {foreach from=$controllers_tree item=module key=mod_name name="clist"}
                <li {if $smarty.foreach.clist.first}class="act"{/if}><a data-view="mod-{$mod_name}">{$module.moduleTitle}</a></li>
                {/foreach}
            </ul>
        </div>
        <div class="right">
            <div class="columntitle"><span>блоки</span></div>
            <div class="blocks">
                {foreach from=$controllers_tree item=module key=mod_name name="blist"}
                    <div id="mod-{$mod_name}" class="block-list{if !$smarty.foreach.blist.first} hidden{/if}">
                    {if !empty($module.controllers)}
                        {foreach from=$module.controllers item=block}
                            <a class="item crud-add" href="{adminUrl do=addModuleStep2 block=$block.class}" data-crud-options='{ "onLoadTrigger":"addModule", "beforeCallback": "addSectionId" }'>
                                <div class="limiter">
                                    <span class="name">{$block.info.title|default:$block.short_class}</span>
                                    <span class="info">{$block.info.description}</span>
                                </div>
                            </a>                            
                        {/foreach}
                    {/if}
                </div>
                {/foreach}                
            </div>
        </div>
        <div class="clear"></div>
    
        <script>
            $(function() {
                $('.module-blocks .modules a[data-view]').click(function() {
                    $('.act', $(this).closest('.modules')).removeClass('act');
                    $(this).closest('li').addClass('act');
                    
                    $('.module-blocks .blocks .block-list').addClass('hidden');
                    $('#'+$(this).data('view')).removeClass('hidden');
                });
                
                $(window).bind('addModule', function(e, response) {
                    $('#blockListDialog').dialog('close');
                    if (response.close_dialog) {
                        $(updatable.dom.defaultContainer).trigger('rs-update');
                    }
                });
            });
            
            function addSectionId(options) {
                var dialogOptions = $('#blockListDialog').dialog('option', 'crudOptions');
                options.extraParams = { section_id: dialogOptions.sectionId };
                return options;
            }
        </script>
    </div>
</div>