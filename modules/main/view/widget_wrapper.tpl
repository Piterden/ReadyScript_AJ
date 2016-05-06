<div class="widget" wid="{$widget.id}" wclass="{$widget.class}">
{$app->autoloadScripsAjaxBefore()}
    <div class="widget-border">
        <div class="widget-head">
            <div class="widget-tools">
                 <a class="close" title="{t}Скрыть виджет{/t}"></a>
            </div>
            <div class="widget-title">{$widget.title}</div>
        </div>  
        <div class="widget-content updatable" data-url="{adminUrl mod_controller=$widget.class do=false}" data-update-block-id="{$widget.class}">
            {$widget.inside_html}
        </div>
    </div>
{$app->autoloadScripsAjaxAfter()}    
</div>