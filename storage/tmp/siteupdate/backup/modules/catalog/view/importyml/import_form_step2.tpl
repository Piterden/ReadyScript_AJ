<div id="import-yml-result" class="crud-form">
    {if $next_step === false}
    <br>
    <div class="inform-block">Произошла ошибка: {$error}</div>
    <br>
    {else}
        <ul class="step-list">
            {foreach $steps as $n => $step}
            <li>{if $next_step.step < $n && $next_step !== true}
                    {$step.title}
                {elseif $next_step.step == $n && $next_step !== true}
                    <b>{$step.title}</b> ({if $next_step.total}{$next_step.offset}/{$next_step.total}{else}{$next_step.percent|default:"0"}%{/if}) <img src="{$Setup.IMG_PATH}/adminstyle/small-loader.gif" class="vertMiddle">
                {else}
                    {$step.successTitle}
                {/if}
            </li>
            {/foreach}
        </ul>
        {if $next_step === true}
            <div class="notice-box">
                <strong>Статистика</strong><br><br>
                <table>
                    <tr>
                        <td>Добавлено товаров:</td> 
                        <td align="right">{$statistic.inserted_offers}</td>
                    </tr>
                    <tr>
                        <td>Обновлено товаров:</td>
                        <td align="right">{$statistic.updated_offers}</td>
                    </tr>
                    <tr>
                        <td>Добавлено категорий:</td>
                        <td align="right">{$statistic.inserted_categories}</td>
                    </tr>
                    <tr>
                        <td>Обновлено категорий:</td>
                        <td align="right">{$statistic.updated_categories}</td>
                    </tr>
                    <tr>
                        <td>Добавлено изображений:</td>
                        <td align="right">{$statistic.inserted_photos}</td>
                    </tr>
                    <tr>
                        <td>Пропущено изображений(были загружены ранее):</td>
                        <td align="right">{$statistic.already_exists_photos}</td>
                    </tr> 
                     <tr>
                        <td>Не удалось загрузить изображений(<a href="{$log_link}" target="_blank" class="u-link">подробности</a>):</td>
                        <td align="right">{$statistic.not_downloaded_photos}</td>
                     </tr>
                </table>
            </div>
            <br>
        {/if}
        
        {if $next_step !== true}
            <script>
            $.allReady(function() {
                $.ajaxQuery({
                    url: '{adminUrl mod_controller="catalog-importyml" do="ajaxProcess"}',
                    data: { 'step_data': {json_encode($next_step)} },
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
        <div class="link-blog">
            <span class="main-link"><a class="crud-get crud-close-dialog">Закрыть окно и обновить список товаров</a></span>
        </div>        
</div>