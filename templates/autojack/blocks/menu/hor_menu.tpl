{if $items}
<ul class="topMenu">
    {include file="%THEME%/blocks/menu/branch.tpl" menu_level=$items}
</ul>
{else}
    {include file="theme:autojack/block_stub.tpl"  class="noBack blockSmall blockLeft blockMenu" do=[
        {adminUrl do="add" mod_controller="menu-ctrl"} => t("Добавьте пункт меню")
    ]}
{/if}