{if !empty($errors)}
    <ul class="deliveryError">
        {foreach $errors as $error}
           <li>{$error}</li>
        {/foreach}
    </ul>
{else}
    {if !empty($pochtomates)}
        <div class="deliveryChooseWrap">
            {$delivery_extra=$url->request('delivery_extra', 'array')}
            <div id="cdekWidjet{$delivery.id}" class="cdekWidjet" data-delivery-id="{$delivery.id}">
                <div class="title">
                    {t}Выберите место получения товара{/t}:
                </div>
                <div class="additionalTitleInfo">
                   {$date_min=$extra_info.deliveryDateMin} 
                   {$date_max=$extra_info.deliveryDateMax} 
                   (Ориентировочная дата доставки 
                   {if $date_min!=$date_max}
                        с {$date_min|dateformat:"@date"} по {$date_max|dateformat:"@date"})
                   {else}
                        {$date_min|dateformat:"@date"})
                   {/if}
                </div>
                
                {$value=json_decode(htmlspecialchars_decode($delivery_extra.value), true)}
                <select id="cdekSelect" name="delivery_extra[value]" class="cdekSelect">
                    {foreach $pochtomates as $pochtomat}
                        <option {if $value && ($value.code==$pochtomat.Code)}selected="selected"{/if} value='{literal}{{/literal}"code":"{$pochtomat['Code']}","addressInfo":"{$pochtomat['City']}, {$pochtomat['Address']}","cityCode":"{$pochtomat['CityCode']}","tariffId":"{$cdek->getTariffId()}"{if isset($pochtomat['cashOnDelivery'])},"cashOnDelivery":"{$pochtomat['cashOnDelivery']}"{/if}}'>{$pochtomat['City']}, {$pochtomat['Address']}</option>
                    {/foreach}
                </select>
                <div id="deliveryInsertInputs">
                    {* Сюда будет вставлена дополнительная информация *}
                </div>
            </div>
        </div>
    {/if}
{/if}


