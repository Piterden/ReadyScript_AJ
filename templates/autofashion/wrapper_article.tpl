{extends file="%THEME%/wrapper.tpl"}
{block name="content"}
<div class="container">
    <div class="row">
        <div class="col-md-6 back"></div>
        <div class="col-md-12 breadcrumbs text-center">
            {moduleinsert name="\Main\Controller\Block\Breadcrumbs"}
        </div>
    </div>
</div>
{$app->blocks->getMainContent()}
{/block}