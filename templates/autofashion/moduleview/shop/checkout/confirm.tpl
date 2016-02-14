{assign var=catalog_config value=ConfigLoader::byModule('catalog')}
<form method="POST" class="formStyle checkoutBox">
    {if $order->hasError()}
        <div class="row">
            <div class="pageError col-sm-14 col-sm-offset-5">
                {foreach $order->getErrors() as $item}
                    <p>{$item}</p>
                {/foreach}
            </div>
        </div>
    {/if}
    <div class="row">
        <div class="titleWrap col-sm-24 text-center">
            <h3>Информация о покупателе</h3>
        </div>
    </div>
    {$user=$order->getUser()}
    <div class="coInfo">
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
            <li class="row byerRow">
                <div class="labelWrap col-sm-8 text-right">
                    <label class="forByer">Адрес</label>
                </div>
                <div class="infoWrap col-sm-13">
                    <div class="city">
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
            <li class="row commentSection">
                <label class="commentLabel col-sm-8 text-right">Комментарий к заказу</label>
                <div class="infoWrap col-sm-13">
                    {$order.__comments->formView()}
                    <span class="commentLable">Например лицо, которое встретит доставку или особенности местности.</span>
                </div>
            </li>
        </ul>
    </div>
    {if $this_controller->getModuleConfig()->require_license_agree}
        <div class="col-xs-24 text-center">
            <input type="checkbox" name="iagree" value="1" id="iagree"> <label for="iagree">{t}Я согласен с <a href="{$router->getUrl('shop-front-licenseagreement')}" class="licAgreement inDialog">условиями предоставления услуг</a>{/t}</label>
        </div>
        <script type="text/javascript">
            $(function() {
                $('.formSave').click(function() {
                    if (!$('#iagree').prop('checked')) {
                        alert('Подтвердите согласие с условиями предоставления услуг');
                        return false;
                    }
                });
            });
        </script>
    {/if}
    <div class="clearBoth"></div>
    <div class="buttons col-xs-24 text-center">
        <button type="submit" class="button cornered formSave wideVer">Подтвердить заказ</button>
    </div>
</form>
