{* Список категорий из 3-х уровней*}
{if $dirlist}
<ul class="topCategory" id="topCategory">
    {foreach $dirlist as $dir}
    <li class="item_{$dir@iteration}{if !empty($dir.child)} node{/if}" {$dir.fields->getDebugAttributes()}><a href="{$dir.fields->getUrl()}">{$dir.fields.name}</a>
        {if !empty($dir.child)}
            {$cnt=count($dir.child)}
            {$columns=1}
            {if $cnt>3}{$columns=2}{/if}
            {if $cnt>6}{$columns=3}{/if}
            {if $cnt>12}{$columns=4}{/if}
            {* Второй уровень *}
            <ul class="columns{$columns}">
                {foreach $dir.child as $subdir}
                <li><a href="{$subdir.fields->getUrl()}">{$subdir.fields.name}</a>
                    {if !empty($subdir.child)}
                    {* Третий уровень *}
                    <ul>
                        {foreach $subdir.child as $subdir2}
                        <li><a href="{$subdir2.fields->getUrl()}">{$subdir2.fields.name}</a></li>
                        {/foreach}
                    </ul>
                    {/if}
                </li>
                {/foreach}
            </ul>
        {/if}
    </li>
    {/foreach}
</ul>
<script type="text/javascript">
    $(function() {
        
        $('.node > a').click(function() {
            if ($.detectMedia('mobile')) {
                $(this).closest('.node').toggleClass('open');
                return false;
            }
        });        
        
        $('.menuGrid').category({
            menu: '.topCategory',
            topitem: '>li',
            submenu: '>ul',
            correct:-10
        });
    });
</script>
{else}
    {include file="%THEME%/block_stub.tpl"  class="blockCategory" do=[
        [
            'title' => t("Добавьте категории товаров"),
            'href' => {adminUrl do=false mod_controller="catalog-ctrl"}
        ]
    ]}
{/if}