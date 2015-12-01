{* Основной шаблон *}
{addcss file="bootstrap.css"} 
{addcss file="bootstrap-theme.css"} 
{addcss file="owl.carousel.css"} 
{addcss file="style.css"} 
{*{addjs file="jquery-1.11.3.min.js"}  подключаем файл ТЕКУЩАЯ_ТЕМА/resource/css/style.css *}
{addjs file="jquery.colorbox-min.js" unshift=true} 
{addjs file="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js" basepath="root" unshift=true} 
{addjs file="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" basepath="root" unshift=true} 
{* {addjs file="bootstrap.min.js"} подключаем файл ТЕКУЩАЯ_ТЕМА/resource/css/style.css *}
{addjs file="script.js"} 
{* Подключаем дополнительный стиль, если выбрана зеленая тема *}
{if $THEME_SHADE == 'green'}{addcss file="style_green.css"}{/if} 
{$app->setDoctype('HTML')}
{$app->blocks->renderLayout()} {* Запускаем рендеринг данной страницы *}
