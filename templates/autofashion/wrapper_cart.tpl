{extends file="%THEME%/wrapper.tpl"}
{block name="content"}
    <div class="container cartWrapper">
        {$app->blocks->getMainContent()}
    </div>
{/block}
{block name="fixedCart"}{/block} {* Исключаем плавающую корзину *}