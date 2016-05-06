{addcss file="%marketplace%/marketplace.css"}
<iframe id="frame" scrolling="no" src="{$router->getAdminUrl(false, [], 'marketplace-proxy')}"></iframe>
<div id="mp-loader">
    <div class="mp-loading"><img src="{$Setup.IMG_PATH}/adminstyle/big-loader.gif"> <i>M</i>arket<i>P</i>lace</div>
    <div class="mp-subtext">{t}загрузка...{/t}</div>
</div>

<script>
    $(function(){
        // Сохраянем URL ифрейма при переходах
        var iframe = $("iframe#frame");

        // Обработчик сообщения, посылаемого из IFrame
        window.addEventListener("message", function(ev){
            // Если Iframe сообщил о смене адреса
            if(ev.data.url){
                // Сохраняем полученный URL после решетки
                document.location.hash = ev.data.url;
            }
        }, false);

        // Восстанавливаем URL ифрейма из hash (при обновлении страницы)
        if(document.location.hash){
            var url_with_proxy = '{$router->getAdminUrl(false, [], 'marketplace-proxy')}';
            url_with_proxy += '?url='+encodeURIComponent(document.location.hash.substr(1));
            iframe.attr('src', url_with_proxy);
        }
        
        iframe.load(function() {
            $('#mp-loader').hide();
        });
    });
</script>