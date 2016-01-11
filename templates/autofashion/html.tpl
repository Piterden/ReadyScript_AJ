<!DOCTYPE {$app->getDoctype()}>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<html>
<head {$app->getHeadAttributes(true)}>
{$app->meta->get()}
<title>{$app->title->get()}</title>
{foreach from=$app->getCss() item=css}
	{$css.params.before}<link {if $css.params.type !== false}type="{$css.params.type|default:"text/css"}"{/if} href="{$css.file}" {if $css.params.media!==false}media="{$css.params.media|default:"all"}"{/if} rel="{$css.params.rel|default:"stylesheet"}">{$css.params.after}
{/foreach}
<script>
    var global = {$app->getJsonJsVars()};
</script>
{foreach from=$app->getJs() item=js}
	{$js.params.before}<script type="{$js.params.type|default:"text/javascript"}" src="{$js.file}"></script>{$js.params.after}
{/foreach}
{if $app->getJsCode()!=''}
	<script language="JavaScript">{$app->getJsCode()}</script>
{/if}
{$app->getAnyHeadData()}
</head>
<body {if $app->getBodyClass()!= ''}class="{$app->getBodyClass()}"{/if}>
    mdskmvkfmvklsdmfblkm
    {$body}
    {* Нижние скрипты *}
    {foreach from=$app->getJs('footer') item=js}
	    {$js.params.before}<script type="{$js.params.type|default:"text/javascript"}" src="{$js.file}"></script>{$js.params.after}
    {/foreach}
</body>
</html>
