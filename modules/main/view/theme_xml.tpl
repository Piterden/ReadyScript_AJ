<?xml version="1.0" encoding="utf-8"?>
<theme scriptType="{$Setup.SCRIPT_TYPE|escape:"xml"}">
    <name></name>
    <options>
        <templates>
            <body>{$config.tpl_body|escape:"xml"}</body>
            <wrapper>{$config.tpl_wrapper|escape:"xml"}</wrapper>
            <content>{$config.tpl_content|escape:"xml"}</content>
        </templates>
    </options>
    <articles>
        {foreach from=$articles item=article}
        <article id="{$article.id|escape:"xml"}">
            <title>{$article.title|escape:"xml"}</title>
            <alias>{$article.alias|escape:"xml"}</alias>
            <content>{$article.content|escape:"xml"}</content>
            <dateof>{$article.dateof|escape:"xml"}</dateof>
            <short_content>{$article.short_content|escape:"xml"}</short_content>
            <keywords>{$article.keywords|escape:"xml"}</keywords>
            <description>{$article.description|escape:"xml"}</description>
        </article>
        {/foreach}
    </articles>
    <structure>
        {include file="theme_item.tpl" element=$menu_tree}
    </structure>
</theme>