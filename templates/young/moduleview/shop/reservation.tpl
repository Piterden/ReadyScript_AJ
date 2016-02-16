<form method="POST" action="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="authorization formStyle reserveForm">
        <input type="hidden" name="product_id" value="{$product.id}">
        <div class="forms">
            <h2 data-dialog-options='{ "width": "400" }'>Заказать</h2>
            <p class="title">{$product.title} {$product.barcode}</p>    
            <p class="infotext">
                В данный момент товара нет в наличии. Заполните форму и мы оповестим вас о поступлении товара.
            </p>    
            {if $reserve->hasError()}<div class="error">{implode(', ', $reserve->getErrors())}</div>{/if}
            <div class="center">
                <div class="formLine">
                    <label class="fielName">Количество</label><br>
                    <input type="text" name="amount" class="amount" value="{$reserve.amount}">
                    <div class="incdec">
                        <a class="inc"></a>
                        <a class="dec"></a>
                    </div>                            
                </div>
                <div class="formLine">
                    <label class="fielName">Телефон</label><br>
                    <input type="text" name="phone" class="inp" value="{$reserve.phone}">
                </div>                
                <div class="formLine">
                    <label class="fielName"><small>или</small> E-mail</label><br>
                    <input type="text" name="email" class="inp" value="{$reserve.email}">
                </div>                
                {if $product->isMultiOffersUse()}
                    <div class="formLine">
                        <strong>{$product.offer_caption|default:'Комплектация'}</strong>
                    </div>
                    {assign var=offers_levels value=$product.multioffers.levels} 
                    {foreach $offers_levels as $level}
                        <div class="formLine">
                            <label class="fielName">{$level.title|default:$level.prop_title}</label><br>
                            <select name="multioffers[{$level.prop_id}]" data-prop-title="{$level.title|default:$level.prop_title}">
                               {foreach $level.values as $value}
                                   {capture assign=use_value}{$level.title|default:$level.prop_title}: {$value.val_str}{/capture}
                                   <option {if $reserve.multioffers[$level.prop_id] == $use_value}selected{/if} value="{$use_value}">{$value.val_str}</option> 
                               {/foreach}
                            </select>
                        </div>
                    {/foreach}
                {elseif $product->isOffersUse()}
                    {assign var=offers value=$product.offers.items}
                    <div class="formLine">
                        <label class="fielName">{$product.offer_caption|default:'Комплектация'}</label><br>
                        <select name="offer">
                           {foreach $offers as $offer}
                               <option value="{$offer.title}" {if $reserve.offer == $offer.title}selected{/if}>{$offer.title}</option> 
                           {/foreach}
                        </select>
                    </div>
                {/if}                  
                {if !$is_auth}
                <div class="formLine">
                    <label class="fielName">Введите код, указанный на картинке</label><br>
                    <img height="42" width="100" src="{$router->getUrl('kaptcha', ['rand' => rand(1, 9999999)])}" alt=""/>
                    <input type="text" name="kaptcha" class="kaptcha">
                </div>                    
                {/if}
            </div>
        </div>
        <input type="submit" value="Оповестить меня">
        <br><br><br>
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