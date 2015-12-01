<div class="offers-photo-select-dialog">
    <span class="gray">Выбрано фотографий: {count($photos_id)}</span>
    <form method="POST" id="offers-photo-form">
        {foreach $photos_id as $photo_id}
        <input type="hidden" name="photos_id[]" value="{$photo_id}">
        {/foreach}    

    {if $dialog_data.params}
        <div class="params-row">
            <div class="param-title">
               {t}Выбор по параметрам комплектаций{/t} <a class="help-icon" title="{t}Выберите параметры по которым будут отобраны комплектации{/t}">?</a>
            </div>
            <div class="items">
                {* Сюда будут вставлены параметры по которым можно будет сразу выбирать комплектации*}
                {foreach $dialog_data.params as $key => $params}
                    <span class="item">{$key}: <select data-name="{$key}">
                        <option value="">{t}-не выбрано-{/t}</option>
                    {foreach $params as $param}
                        <option value="{$param}">{$param}</option>
                    {/foreach}
                    </select>
                    </span>
                {/foreach}
            </div>          
            <div class="params-buttons">
                <a class="button apply-photo-offer-filter">Отметить</a>
                <a class="button clear-photo-offer-filter">Снять отметки</a>
            </div>
        </div>
        {/if}
        <div class="offer-photo-select">
            <span class="gray">Удерживая CTRL, вы можете отметить несколько комплектаций</span>
            <select multiple="multiple" class="photo-select" name="offers_id[]">
                {* Сюда будет вставлены варианты комплектаций *}
                {foreach $dialog_data.offers as $key => $offer}
                <option value="{$key}" {if in_array($key, $dialog_data.selected)}selected{/if} data-params='{json_encode($offer.params)}'>{$offer.title}</option>
                {/foreach}
            </select>
        </div>
        <div class="offer-photo-actions">
            <a class="button save">Назначить</a>
            <a class="offer-photo-clear">Отменить связь фото с комплектациями</a>
        </div>
    </form>
</div>