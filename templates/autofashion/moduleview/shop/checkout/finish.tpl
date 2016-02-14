{assign var=catalog_config value=ConfigLoader::byModule('catalog')}
<div class="formStyle checkoutBox">
    <div class="row">
        <div class="col-sm-24 text-center">
            <h2>Спасибо! Ваш заказ успешно оформлен</h2>
        </div>
    </div>
    <div class="row">
        <div class="titleWrap col-sm-24 text-center">
            <h3>Сведения о заказе</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-xs-offset-6">
            <p class="thanks">Следить за изменениями статуса заказа можно в разделе <a href="{$router->getUrl('shop-front-myorders')}" target="_blank">история заказов</a>.
                Все уведомления об изменениях в данном заказе также будут отправлены на электронную почту покупателя.</p>
        </div>
    </div>
    <div class="coInfo">
        {$user=$order->getUser()}
        <div class="grayblock">
            <ul class="vertItems">
                <li class="row byerRow">
                    <div class="labelWrap col-sm-8 text-right">
                        <label class="forByer">Заказчик</label>
                    </div>
                    <div class="infoWrap col-sm-13">
                        <div class="line">{$user.surname} {$user.name}</div>
                    </div>
                </li>
                <li class="row byerRow">
                    <div class="labelWrap col-sm-8 text-right">
                        <label class="forByer">Телефон</label>
                    </div>
                    <div class="infoWrap col-sm-13">
                        <div class="line">{$user.phone}</div>
                    </div>
                </li>
                <li class="row byerRow">
                    <div class="labelWrap col-sm-8 text-right">
                        <label class="forByer">Email</label>
                    </div>
                    <div class="infoWrap col-sm-13">
                        <div class="line">{$user.e_mail}</div>
                    </div>
                </li>
                {$fmanager=$order->getFieldsManager()}
                {if $fmanager->notEmpty()}
                    {foreach $fmanager->getStructure() as $field}
                        <li class="row byerRow {if $field@first}postSep{/if} {if $field@last}preSep{/if}">
                            <div class="labelWrap col-sm-8 text-right">
                                <label class="forByer">{$field.title}</label>
                            </div>
                            <div class="infoWrap col-sm-13">
                                <div class="line">{$fmanager->textView($field.alias)}</div>
                            </div>
                        </li>
                    {/foreach}
                {/if}
                <li class="row byerRow">
                    <div class="labelWrap col-sm-8 text-right">
                        <label class="forByer">Адрес</label>
                    </div>
                    <div class="infoWrap col-sm-13">
                        <div class="city">vdsdv
                            <span class="text-capitalize">{$order.order_extra.delivery.region_id_to|lower}</span><br>
                        </div>
                        <div class="line">
                            {if $order.use_addr=='-1'}
                                {$order.order_extra.delivery.sd_html|unescape:'html'}
                            {else}
                                {$order->getAddress()->getLineView()}
                            {/if}
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="clearBoth"></div>
        {if $order->getPayment()->hasDocs()}
            <div class="docs grayblock">
                <div class="row">
                    <div class="titleWrap col-sm-24 text-center">
                        <h3>Документы на оплату</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-xs-offset-6">
                        <p>Воспользуйтесь следующими документами для оплаты заказа. Эти документы всегда доступны в разделе история заказов.</p>
                    </div>
                    <div class="buttons col-xs-24 text-center">
                        {$type_object=$order->getPayment()->getTypeObject()}
                        {foreach $type_object->getDocsName() as $key => $doc}
                            <a href="{$type_object->getDocUrl($key)}" target="_blank" class="button cornered white">{$doc.title}</a>
                        {/foreach}
                    </div>
                </div>
            </div>
        {/if}
        <div class="buttons col-xs-24 text-center">
            {if $order->canOnlinePay()}
                <a href="{$order->getOnlinePayUrl()}" class="button cornered color">Перейти к оплате</a>
            {else}
                {* <a href="{$router->getRootUrl()}" class="cornered button color">Завершить заказ</a> *}
            {/if}
        </div>
    </div>
</div>
