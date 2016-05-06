{addcss file="common/jquery.lightbox.packed.css" basepath="common"}
{addcss file="%templates%/uploadfiles.css"}
{addjs file="jquery.lightbox.min.js" basepath="common"}
{addjs file="{$mod_js}tplmanager.js" basepath="root"}
{if !$url->isAjax()}
<div class="crud-ajax-group">
    <div id="content-layout">
        <div class="viewport">
            <div class="updatable" data-url="{adminUrl}">
{/if}
                <div id="clienthead">
                    <div class="c-head  top-p">
                        <h2>{$elements.formTitle} {if isset($elements.topHelp)}<a class="help">?</a>{/if}</h2>
                        <div class="buttons">
                            {if $elements.topToolbar}
                                {$elements.topToolbar->getView()}
                            {/if}
                        </div>
                        <br class="clear">
                    </div>
                    <div class="c-help">
                        {$elements.topHelp}
                    </div>                                        
                    
                    <div class="sepline"></div>
                </div>
                <div class="columns">
                    <div class="common-column tmanager">
                        <div class="margvert10">
                               <div class="category-filter">
                                        <div class="category-selector">
                                            <span class="current backcolor">
                                                {if $list.epath.type == 'theme'}
                                                    {t}Тема{/t}:{$root_sections.themes[$list.epath.type_value].title}
                                                {else}
                                                    {t}Модуль{/t}:{$root_sections.modules[$list.epath.type_value].title}
                                                {/if}
                                            </span><a class="dropdown-handler">&nbsp;</a>
                                            <div class="list">
                                                <ul class="f-root-ul">
                                                    <li class="themes">
                                                        <div class="name">{t}Темы{/t}</div>
                                                        <ul>
                                                            {foreach from=$root_sections.themes key=key item=item}
                                                            <li><a class="call-update" href="{adminUrl mod_controller="templates-filemanager" path="theme:{$key}"}">{$item.title}</a></li>
                                                            {/foreach}
                                                        </ul>
                                                    </li>
                                                    <li class="modules">
                                                        <div class="name">{t}Модули{/t}</div>
                                                        <ul>
                                                            {foreach from=$root_sections.modules key=key item=item}
                                                            <li><a class="call-update" href="{adminUrl mod_controller="templates-filemanager" path="module:{$key}"}">{$item.title}</a></li>
                                                            {/foreach}                                                        
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>                        
                                    
                                 <div class="folderpath">
                                    <span class="backcolor">&nbsp;</span>
                                    <a class="root call-update" title="корневая папка" href="{adminUrl mod_controller="templates-filemanager" path="{$list.epath.type}:{$list.epath.type_value}/"}"></a>
                                    {foreach from=$list.epath.sections item=section key=key name="fp"}
                                        <a class="call-update" href="{adminUrl mod_controller="templates-filemanager" path="{$key}"}">{$section}</a> /
                                    {/foreach}
                                    <span class="filetypes">*.{foreach from=$list.allow_extension item=one_ext name="extlist"}{if !$smarty.foreach.extlist.first},{/if}{$one_ext}{/foreach}</span>
                                </div>                                    
                        </div>
                            
                        {if count($list.items) || count($list.epath.sections)}
                        <div class="file-list-container" data-current-folder="{$list.epath.public_dir}">
                            <ul class="file-list">
                                {if count($list.epath.sections)}
                                    <li class="dir"><a class="call-update" href="{adminUrl mod_controller="templates-filemanager" path=$list.epath.parent}">..</a></li>
                                {/if}                            
                                {foreach from=$list.items item=item}
                                    {if $item.type == 'dir'}
                                        <li class="item dir" data-path="{$item.link}" data-name="{$item.name}"><a class="call-update" href="{adminUrl mod_controller="templates-filemanager" path=$item.link}">{$item.name}</a>
                                            <span class="tools">
                                                <div class="rs-list-button">
                                                    <a class="tool rs-dropdown-handler"></a>
                                                    <ul class="rs-dropdown">
                                                        <li class="first"><a class="rename" data-old-value="{$item.name}" data-url="{adminUrl mod_controller="templates-filemanager" do="rename" path=$item.link}">{t}переименовать{/t}</a></li>
                                                        <li><a class="delete" href="{adminUrl mod_controller="templates-filemanager" do="delete" path=$item.link}">{t}удалить{/t}</a></li>
                                                    </ul>
                                                </div>
                                            </span>
                                        </li>
                                    {else}
                                         <li class="item file {$item.ext}" data-path="{$item.link}" data-name="{$item.name}.{$item.ext}">
                                            {if isset($allow_edit_ext[$item.ext])}
                                            <a class="crud-edit" href="{adminUrl mod_controller="templates-filemanager" do="edit" path=$item.path file=$item.filename}">{$item.name}.<span class="ext">{$item.ext}</span></a>
                                            {else}
                                            <a rel='lightbox-image-tour' href="{$list.epath.relative_rootpath}/{$item.filename}">{$item.name}.<span class="ext">{$item.ext}</span></a>
                                            {/if}
                                            <span class="tools">
                                                {if isset($allow_edit_ext[$item.ext])}
                                                <a href="{adminUrl mod_controller="templates-filemanager" do="edit" path=$item.path file=$item.filename}" class="tool edit crud-edit"></a>
                                                {/if}
                                                <div class="rs-list-button">
                                                    <a class="tool rs-dropdown-handler"></a>
                                                    <ul class="rs-dropdown">
                                                        <li class="first"><a target="_blank" href="{adminUrl mod_controller="templates-filemanager" do="ajaxDownload" path=$item.link}">{t}скачать{/t}</a></li>
                                                        <li><a class="rename" data-old-value="{$item.name}.{$item.ext}" data-url="{adminUrl mod_controller="templates-filemanager" do="rename"}">{t}переименовать{/t}</a></li>
                                                        <li><a class="delete" href="{adminUrl mod_controller="templates-filemanager" do="delete" path=$item.link}">{t}удалить{/t}</a></li>
                                                    </ul>
                                                </div>
                                            </span>
                                        </li>   
                                    {/if}
                                {/foreach}
                            </ul>

                            <div class="clear"></div>
                        </div>
                            {else}
                                <div class="empty-folder">
                                    {t}Пустой каталог{/t}
                                </div>
                            
                            
                            {/if}                        
                                            
                        <div class="footerspace"></div>
                    </div>
                </div> <!-- .columns -->

{if !$url->isAjax()}
            </div> <!-- .updatable -->
        </div> <!-- .viewport -->
    </div> <!-- #content -->

</div>
{/if}

<script>

$.contentReady(function() {
    $("a[rel='lightbox-image-tour']").lightBox({
        showDetails:false
    });
});

</script>