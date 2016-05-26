<form method="POST" class="formStyle checkoutBox" id="order-form">
    {if $order->hasError()}
        <div class="row">
            <div class="pageError col-sm-14 col-sm-offset-5">
                {foreach $order->getErrors() as $item}
                    <p>{$item}</p>
                {/foreach}
            </div>
        </div>
    {/if}
    <input type="hidden" name="payment" value="0">
    <div class="row">
        <div class="titleWrap col-sm-24 text-center">
            <h3>Способ оплаты</h3>
        </div>
    </div>
    <ul class="vertItems ">
        {foreach $pay_list as $item}
            <li class="paymentItem row{if $item@first} first{/if}">
                <div class="radio col-sm-8">
                    {if !empty($item.picture)}
                        <label for="pay_{$item.id}" class="logoService">
                            <img class="" src="{$item.__picture->getUrl(100, 100, 'xy')}" alt="{$item.title}"/>
                        </label>
                    {/if}
                    <input type="radio" name="payment" value="{$item.id}" id="pay_{$item.id}" {if $order.payment==$item.id}checked{/if}>
                    <span class="back"></span>
                </div>
                <div class="info col-sm-10">
                    <div class="line">
                        <label for="pay_{$item.id}" class="title h3">{$item.title}</label>
                    </div>
                    <div class="clearfix"></div>
                    {if $item.id == 7}
                        <div class="addressBlock">
                            <div class="h4">Выберите пункт выдачи заказа</div>
                        </div>
                    {else}
                        <div class="addressBlock hide">
                            <div class="addressText">
                                {$order->getAddress()->getLineView()|@print_r}
                            </div>
                            <div class="addressLink">
                                <a class="spacing" href="{$router->getUrl('shop-front-checkout', ['Act' => 'address'])}">Изменить адрес</a>
                            </div>
                        </div>
                    {/if}
                    <div class="descr">{$item.description}</div>
                    <div class="additionalInfo"></div>
                </div>
                <div class="priceWrap col-sm-3">
                    <div id="scost_{$item.id}" class="price">
                        {if $something_wrong}
                            <span style="color:red;">{$something_wrong}</span>
                        {else}
                            <span class="help"></span>
                            {static_call var=currencyLiter callback=['\Catalog\Model\CurrencyApi', 'getCurrecyLiter']}
                        {/if}
                    </div>
                </div>
            </li>
        {/foreach}
    </ul>
    <div class="row">
        <div class="buttons col-sm-24 text-center">
            <button type="submit" class="button cornered">Далее</button>
        </div>
    </div>
</form>
{literal}
<script>
$('#order-form').unbind('submit')
    .on('submit', function(e) {
        if ($('[name="payment"]:checked').length == 0) {
            showError('Выберите, пожалуйста, способ оплаты!');
            return false;
        }
});
</script>
{/literal}
