<div class="form-error"></div>
{* Если загрузка темы не запрещена *}
{if !$smarty.const.CANT_UPLOAD_THEME}
    <form class="uploadTheme" method="POST" style="text-align:left" action="{adminUrl mod_ctroller="templates-selecttheme" do="uploadTheme"}">
    <label>{t}Загрузить новую тему{/t}</label>
    <span href="" class="upload-theme-file button add">
        <input type="file" name="theme" class="fileinput" id="theme-file">
    {t}Выбрать zip-файл{/t}</span>
    <input type="checkbox" name="overwrite" value="1" class="overwrite" id="overwrite">
    <label for="overwrite" class="for_overwrite">{t}Заменить тему, если таковая уже существует{/t}</label>
    </form>
{/if}
<div class="theme-container">
{foreach from=$theme_list key=name item=item}
    {assign var=info value=$item->getInfo()}
    {assign var=shades value=$item->getShades()}
    {if $name==$current.theme}
        {assign var=currentShade value=$current.shade}
    {else}
        {if count($shades)}
            {assign var=firstShade value=reset($shades)}
            {assign var=currentShade value=$firstShade.id}
        {else}
            {assign var=currentShade value=""}
        {/if}
    {/if}
    <div class="theme{if $name==$current.theme} current{/if}{if count($shades)} has-shades{/if}" data-theme-id="{$item->getName()}">
        <div class="preview">
            <a class="img"><img class="image" src="{$item->getPreviewUrl($currentShade)}"></a>
            <a class="title">{$info.name|default:t("Неизвестно")}</a>
        </div>
        {if count($shades)}
        <div class="colors">
            {foreach from=$shades item=shade name=fshade}
            <a class="item{if $currentShade==$shade.id} act{/if}" style="background: {$shade.color}" title="{$shade.title}" data-shade-id="{$shade.id}" data-preview-url="{$item->getPreviewUrl($shade.id)}"><i></i></a>
            {/foreach}
        </div>
        {/if}
    </div>
{/foreach}
<div class="clear"></div>