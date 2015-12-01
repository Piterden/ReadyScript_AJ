{if $url->request('dialogMode', $smarty.const.TYPE_INTEGER)}
            <div class="titlebox">{t}Выберите шаблон{/t}</div>
            <div class="crud-ajax-group">            
            <div class="updatable select-product-box" data-url="{adminUrl only_themes=$only_themes}">
{/if}                
                <div class="columns" data-dialog-options='{ "width": "1010px" }'>
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
                                                            <li><a class="call-update no-update-hash" href="{adminUrl mod_controller="templates-selecttemplate" path="theme:{$key}" only_themes=$only_themes}">{$item.title}</a></li>
                                                            {/foreach}
                                                        </ul>
                                                    </li>
                                                    {if count($root_sections.modules)}
                                                    <li class="modules">
                                                        <div class="name">{t}Модули{/t}</div>
                                                        <ul>
                                                            {foreach from=$root_sections.modules key=key item=item}
                                                            <li><a class="call-update no-update-hash" href="{adminUrl mod_controller="templates-selecttemplate" path="module:{$key}" only_themes=$only_themes}">{$item.title}</a></li>
                                                            {/foreach}                                                        
                                                        </ul>
                                                    </li>
                                                    {/if}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>                        
                                    
                                 <div class="folderpath">
                                    <span class="backcolor">&nbsp;</span>
                                    <a class="root call-update no-update-hash" title="корневая папка" href="{adminUrl mod_controller="templates-selecttemplate" path="{$list.epath.type}:{$list.epath.type_value}/" only_themes=$only_themes}"></a>
                                    {foreach from=$list.epath.sections item=section key=key name="fp"}
                                        <a class="call-update no-update-hash" href="{adminUrl mod_controller="templates-selecttemplate" path="{$key}" only_themes=$only_themes}">{$section}</a> /
                                    {/foreach}
                                    <span class="filetypes">*.{foreach from=$list.allow_extension item=one_ext name="extlist"}{if !$smarty.foreach.extlist.first},{/if}{$one_ext}{/foreach}</span>
                                    <a class="rt makedir" data-url="{adminUrl mod_controller="templates-filemanager" do="makedir" path=$list.epath.public_dir file="noname.tpl"}" data-crud-options='{ "updateElement":".select-product-box", "ajaxParam": { "noUpdateHash": true } }'>папку</a>
                                    <span class="rt">|</span>
                                    <a class="rt crud-add maketpl" href="{adminUrl mod_controller="templates-filemanager" do="add" path=$list.epath.public_dir file="noname.tpl"}" data-crud-options='{ "updateElement":".select-product-box", "ajaxParam": { "noUpdateHash": true } }'>создать шаблон</a>
                                </div>                                    
                        </div>
                            
                        {if count($list.items) || count($list.epath.sections)}
                        <div class="file-list-container" data-current-folder="{$list.epath.public_dir}">
                            <ul class="file-list">
                                {if count($list.epath.sections)}
                                    <li class="dir"><a class="call-update no-update-hash" href="{adminUrl mod_controller="templates-selecttemplate" path=$list.epath.parent only_themes=$only_themes}">..</a></li>
                                {/if}                            
                                {foreach from=$list.items item=item}
                                    {if $item.type == 'dir'}
                                        <li class="item dir" data-path="{$item.link}" data-name="{$item.name}">
                                            <a class="call-update no-update-hash" href="{adminUrl mod_controller="templates-selecttemplate" path=$item.link only_themes=$only_themes}">{$item.name}</a>
                                            <span class="tools">
                                                <div class="rs-list-button">
                                                    <a class="tool rs-dropdown-handler"></a>
                                                    <ul class="rs-dropdown">
                                                        <li class="first"><a class="rename" data-old-value="{$item.name}" data-url="{adminUrl mod_controller="templates-filemanager" do="rename" path=$item.link}" data-crud-options='{ "updateElement":".select-product-box", "ajaxParam": { "noUpdateHash": true } }'>{t}переименовать{/t}</a></li>
                                                        <li><a class="delete" href="{adminUrl mod_controller="templates-filemanager" do="delete" path=$item.link}" data-crud-options='{ "updateElement":".select-product-box", "ajaxParam": { "noUpdateHash": true } }'>{t}удалить{/t}</a></li>
                                                    </ul>
                                                </div>                                                
                                            </span>                                                                                        
                                        </li>
                                    {else}
                                         <li class="item file {$item.ext}{if "{$item.name}.{$item.ext}"==$start_struct.filename} current{/if}" data-path="{$item.link}" data-name="{$item.name}.{$item.ext}">
                                            <a class="canselect">{$item.name}.<span class="ext">{$item.ext}</span></a>
                                            
                                            <span class="tools">
                                                <a href="{adminUrl mod_controller="templates-filemanager" do="edit" path=$item.path file=$item.filename}" class="tool edit crud-edit" data-crud-options='{ "updateElement":".select-product-box", "ajaxParam": { "noUpdateHash": true } }'></a>
                                                <div class="rs-list-button">
                                                    <a class="tool rs-dropdown-handler"></a>
                                                    <ul class="rs-dropdown">
                                                        <li><a class="rename" data-old-value="{$item.name}.{$item.ext}" data-url="{adminUrl mod_controller="templates-filemanager" do="rename"}" data-crud-options='{ "updateElement":".select-product-box", "ajaxParam": { "noUpdateHash": true } }'>{t}переименовать{/t}</a></li>
                                                        <li><a class="delete" href="{adminUrl mod_controller="templates-filemanager" do="delete" path=$item.link}" data-crud-options='{ "updateElement":".select-product-box", "ajaxParam": { "noUpdateHash": true } }'>{t}удалить{/t}</a></li>
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
                    </div>
                </div> <!-- .columns -->

{if $url->request('dialogMode', $smarty.const.TYPE_INTEGER)}
    </div>
</div>
    <p>
        <input type="checkbox" id="use-relative"><label for="use-relative">Не привязывать к конкретной теме</label>
    </p>
{/if}