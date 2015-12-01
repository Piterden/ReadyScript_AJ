<form method="POST" action="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="reserveForm">
        <input type="hidden" name="product_id" value="{$product.id}">
        <h2 class="dialogTitle" data-dialog-options='{ "width": "400" }'>Заказать</h2>
        <p class="prodtitle">{$product.title} {$product.barcode}</p>    
        <p class="infotext">
            В данный момент товара нет в наличии. Заполните форму и мы оповестим вас о поступлении товара.
        </p>    
        {if $reserve->hasError()}<div class="error">{implode(', ', $reserve->getErrors())}</div>{/if}
        <table class="formTable dialogTable">
            <tr>
                <td class="key">Кол-во</td>
                <td class="value"><input type="text" name="amount" class="amount" value="{$reserve.amount}">
                    <div class="qpicker">
                        <a class="inc"></a>
                        <a class="dec"></a>
                    </div>                            
                </td>
            </tr>
            <tr>
                <td class="key">Телефон</td>
                <td class="value"><input type="text" name="phone" class="inp" value="{$reserve.phone}"></td>
            </tr>            
            <tr>
                <td class="key"><small>или</small> E-mail</td>
                <td class="value"><input type="text" name="email" class="inp" value="{$reserve.email}"></td>
            </tr>
            
            {if $product->isMultiOffersUse()}
                <tr>
                    <td class="key">{$product.offer_caption|default:'Комплектация'}</td>
                    <td class="value">
                    </td>
                </tr>
                {assign var=offers_levels value=$product.multioffers.levels} 
                {foreach $offers_levels as $level}
                    <tr>
                        <td class="key">{$level.title|default:$level.prop_title}</td>
                        <td class="value">
                            <select name="multioffers[{$level.prop_id}]" data-prop-title="{$level.title|default:$level.prop_title}">
                               {foreach $level.values as $value}
                                   {capture assign=use_value}{$level.title|default:$level.prop_title}: {$value.val_str}{/capture}
                                   <option {if $reserve.multioffers[$level.prop_id] == $use_value}selected{/if} value="{$use_value}">{$value.val_str}</option> 
                               {/foreach}
                            </select>
                        </td>
                    </tr>
                {/foreach}
            {elseif $product->isOffersUse()}
                {assign var=offers value=$product.offers.items}
                <tr>
                    <td class="key">{$product.offer_caption|default:'Комплектация'}</td>
                    <td class="value">
                        <select name="offer">
                           {foreach $offers as $offer}
                               <option value="{$offer.title}" {if $reserve.offer == $offer.title}selected{/if}>{$offer.title}</option> 
                           {/foreach}
                        </select>
                    </td>
                </tr>                
            {/if}
            {if !$is_auth && ModuleManager::staticModuleEnabled('kaptcha')}
            <tr>
                <td class="key">Введите код, указанный на картинке</td>
                <td class="value">
                    <img height="42" width="100" src="{$router->getUrl('kaptcha', ['rand' => rand(1, 9999999)])}">
                    <input type="text" name="kaptcha" class="kaptcha">
                </td>
            </tr>
            {/if}
        </table>
        <button type="submit" class="formSave">Оповестить меня</button>
</form>

<script>
    $(function() {
        $('.reserveForm .inc').off('click').on('click', function() {
            var amountField = $(this).closest('.reserveForm').find('.amount');
            amountField.val( (+amountField.val()|0)+1 );
        });
        
        $('.reserveForm .dec').off('click').on('click', function() {
            var amountField = $(this).closest('.reserveForm').find('.amount');
            var val = (+amountField.val()|0);
            if (val>1) {
                amountField.val( val-1 );
            }
        });        
    });
</script>