{addjs file="{$mod_js}jquery.mainmenu.js" basepath="root"}
<div class="preloadback"></div>
<ul class="menu">
    <li><a href="{adminUrl mod_controller=false do=false}" class="home">&nbsp;</a></li>
    {include file="adminmenu_branch.tpl" list=$items}
</ul>

<script>
$(function() { $('.menu').mainmenu(); });
</script>