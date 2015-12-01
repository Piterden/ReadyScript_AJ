{foreach from=$element item=item}
<item>
    <title>{$item.fields.title|escape:"xml"}</title>
    <alias>{$item.fields.alias|escape:"xml"}</alias>
    <menutype>{$item.fields.menutype|escape:"xml"}</menutype>
    <public>{$item.fields.public|escape:"xml"}</public>
    <typelink>{$item.fields.typelink|escape:"xml"}</typelink>
    <link>{$item.fields.link|escape:"xml"}</link>
    <meta>
        <header>{$item.fields.header|escape:"xml"}</header>
        <keywords>{$item.fields.keywords|escape:"xml"}</keywords>
        <description>{$item.fields.description|escape:"xml"}</description>
    </meta>
    <templates>
        <body>{$item.fields.tpl_body|escape:"xml"}</body>
        <wrapper>{$item.fields.tpl_wrapper|escape:"xml"}</wrapper>
        <content>{$item.fields.tpl_content|escape:"xml"}</content>
    </templates>
    {if !empty($item.child)}
    <childs>
        {include file="theme_item.tpl" element=$item.child}
    </childs>
    {/if}
</item>
{/foreach}