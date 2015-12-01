<div id="import-photo-result" class="crud-form">
    {if $error}
    <br>
    <div class="inform-block">Произошла ошибка: {$error}</div>
    {else}
        <ul class="step-list">
            <li>{if $step==1}<strong>{/if}Zip архив с изображениями загружен{if $step==1}</strong>{/if}</li>
            <li>{if $step<2}Распаковка архива{elseif $step == 2}<strong>Идет распаковка... ({$info.zip_done_percent}%) <img src="{$Setup.IMG_PATH}/adminstyle/small-loader.gif" class="vertMiddle"></strong>{else}Архив распакован. Файлов и папок: {$info.zip_num_files}{/if}</li>
            <li>{if $step<3}Импорт фотографий{elseif $step ==3}<strong>Идет импорт фотографий... ({$info.import_done_percent|default:"0"}%) <img src="{$Setup.IMG_PATH}/adminstyle/small-loader.gif" class="vertMiddle"></strong>{else}Изображения импортированы{/if}</li>
        </ul>
        {if $step == 4}
        <div class="inform-block no-vert-padd">
            <h3>Статистика</h3>
            <p>Обработано изображений: <strong>{$info.statistic.touch_images}</strong><br>
            Импортировано фотографий: <strong>{$info.statistic.images_imported}</strong><br>
            Изображения, к которым не нашлось товаров: <strong>{$info.statistic.no_match_images}</strong><br>
            Обновлено товаров: <strong>{$info.statistic.touch_products}</strong></p>
        </div>
        {/if}
        {if $next_url}
        <script>
        $.allReady(function() {
            $.ajaxQuery({
                url: '{$next_url}',
                data: {$params},
                type: 'POST',
                success: function(response) {
                    $('.dialog-window')
                        .html(response.html)
                        .trigger('new-content')
                        .trigger('initContent')
                        .trigger('contentSizeChanged');
                }
            });
        });
        </script>
        {/if}        
    {/if}
        <h4>Выберите следующее действие</h4>
        <div class="link-blog">
            <a href="{$log_url}" target="_blank">Открыть отчет импорта данных</a><br>
            <a href="{adminUrl do=false mod_controller="catalog-importphotos"}" class="crud-add crud-replace-dialog">Повторить импорт с другими условиями</a><br>
            <br><span class="main-link"><a href="{adminUrl do="cleanTmp" mod_controller="catalog-importphotos"}" class="crud-get crud-close-dialog">Удалить временные файлы и закрыть окно</a></span>
        </div>        
</div>