{*addjs file="tiny_mce/tiny_mce.js" basepath="common"}
{literal}
    tinyMCE.init({
        // General options
        //mode : "{/literal}{$param.mode|default:"exact"}{literal}",
        //elements: "{/literal}{$textarea_ids}{literal}",
        mode: "none",
        theme : "advanced",
        language : "ru",
        plugins : "style,contextmenu,paste,directionality,fullscreen,xhtmlxtras",

        // Theme options
        
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,link,unlink,anchor,|,image,code,forecolor,backcolor, fullscreen,images",        
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        {/literal}
        {foreach from=$param.tiny_options key=key item=item}{$key}:{$item},
        {/foreach}
        {literal}
        // Example content CSS (should be your site CSS)
        content_css : "/resource/css/user/tinymce_style.css",
        content_css : "{/literal}{$THEME_CSS}{literal}/style.css",
        relative_urls : false,
        remove_script_host : true
    });
{/literal*}