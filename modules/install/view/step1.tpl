{extends file="%install%/wrap.tpl"}
{block name="content"}
{addjs file="jquery.mcustomscrollbar.min.js" basepath="common"}
{addjs file="jquery.mousewheel.min.js" basepath="common"}
{addjs file="jquery.rsframework.js" basepath="common"}
{addcss file="admin/framework.css" basepath="common"}

<noscript>
<div class="no-javascript">
    {t}Для продолжения установки необходимо включить поддержку JavaScript у Вашего браузера{/t}
</div>
</noscript>

<div class="lang-select">
    <span class="lang-word">{t}Язык{/t}</span>&nbsp;&nbsp;
    <div class="rs-group lang-select-list">
        <span class="rs-active">{$locale_list[$current_lang]}</span>
        <ul class="rs-dropdown">
            {assign var=i value="0"}
            {foreach from=$locale_list key=locale_key item=locale name="loc"}
                {if $current_lang != $locale_key}
                <li {if !$i}class="first"{/if}><a href="{$router->getUrl('install', ['Act' => 'changeLang', 'lang' => $locale_key])}">{$locale}</a></li>
                {assign var=i value=$i+1}
                {/if}                    
            {/foreach}
        </ul>
    </div>
</div>

<h2>{t}Лицензионное соглашение{/t}</h2>

<div class="scroll-block license-text">
    {$license_text}
</div>
<div class="button-line mtop30">
    <a data-href="{$router->getUrl('install', ['step' => '2'])}" class="next disabled">далее</a>
    <input type="checkbox" id="iagree">&nbsp; <label for="iagree"><strong>Я соглашаюсь с условиями лицензионного соглашения</strong></label>
</div>
{/block}