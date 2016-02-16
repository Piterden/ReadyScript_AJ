{assign var=catalog_config value=$this_controller->getModuleConfig()} 
<div class="oneClickWrapper">
    {if $success}
        <div class="authorization reserveForm">
            <h2 data-dialog-options='{ "width": "400" }'>Заказ принят</h2>
            <p class="title">
                {$product.title}<br>
                Артикул:{$product.barcode}</p>
            <p class="infotext">
                В ближайшее время с Вами свяжется наш менеджер.
            </p>
        </div>    
    {else}
        <form enctype="multipart/form-data" method="POST" action="{$router->getUrl('catalog-front-oneclick',["product_id"=>$product.id])}" class="authorization formStyle reserveForm">
           {$this_controller->myBlockIdInput()}
           <input type="hidden" name="product_name" value="{$product.title}"/>
           <div class="forms">
               <h2 class="dialogTitle" data-dialog-options='{ "width": "400" }'>Купить в один клик</h2>
               <p class="infotext">
                    Оставьте Ваши данные и наш консультант с вами свяжется.
               </p>  
               {if $error_fields}
                   <div class="pageError"> 
                   {foreach $error_fields as $error_field}
                       {foreach $error_field as $error}
                            <p>{$error}</p>
                       {/foreach}
                   {/foreach}
                   </div>
               {/if}
               
               <div class="center">
                  {if $product->isMultiOffersUse()}
                        <div class="formLine">
                            {$product.offer_caption|default:'Комплектация'}
                        </div>
                        {assign var=offers_levels value=$product.multioffers.levels} 
                        {foreach $offers_levels as $level}
                            <div class="formLine">
                                <label class="fielName">{if $level.title}{$level.title}{else}{$level.prop_title}{/if}</label><br>
                                <select name="multioffers[{$level.prop_id}]" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
                                   {foreach $level.values as $value}       
                                       {if $level.title}
                                            {assign var=ltitle value=$level.title}
                                       {else}
                                            {assign var=ltitle value=$level.prop_title}
                                       {/if} 
                                       {$concat="{$ltitle}: {$value.val_str}"}
                                       <option {if in_array($concat, $offer_fields.multioffer )}selected="selected"{/if} value="{$ltitle}: {$value.val_str}">{$value.val_str}</option> 
                                   {/foreach}
                                </select>
                            </div>
                        {/foreach}
                        {* Скрытые комплектации для многомерных комплектаций *}
                        {if $product->isOffersUse()}
                           {foreach $product.offers.items as $key=>$offer}
                              <input value="{$key}" type="hidden" class="hidden_offers" data-info='{$offer->getPropertiesJson()}' data-num="{$offer.num}" {if $catalog_config.dont_buy_when_null && $offer.num<=0}disabled{/if}/>
                           {/foreach}
                        {/if}                          
                   {elseif $product->isOffersUse()}
                        {assign var=offers value=$product.offers.items}
                        <div class="formLine">
                            <label class="fielName">{$product.offer_caption|default:'Комплектация'}</label><br>
                            <select name="offer">
                               {foreach $offers as $offer}
                                   <option value="{$offer.title}" {if $catalog_config.dont_buy_when_null && $offer.num<=0}disabled{/if} {if $offer_fields.offer == $offer.title || $key == $offer_val}selected="selected"{/if}>{$offer.title}</option> 
                               {/foreach}
                            </select>
                        </div>
                   {/if}                          
                           
                   <div class="formLine">
                        <label class="fielName">Ваше имя</label><br>
                        <input type="text" class="inp {if $error_fields}has-error{/if}" value="{if $request->request('name','string')}{$request->request('name','string')}{else}{$click.name}{/if}" maxlength="100" name="name">
                    </div>
                    <div class="formLine">
                        <label class="fielName">Телефон</label><br>
                        <input type="text" class="inp {if $error_fields}has-error{/if}" value="{if $request->request('phone','string')}{$request->request('phone','string')}{else}{$click.phone}{/if}" maxlength="20" name="phone">
                    </div>                
                   {foreach $oneclick_userfields->getStructure() as $fld}
                   <div class="formLine">
                        <label class="fielName">{$fld.title}</label><br>
                        {$oneclick_userfields->getForm($fld.alias)}
                    </div>
                    {/foreach}
                    {if !$is_auth}
                    <div class="formLine captcha">
                        <label class="fielName">Введите код, указанный на картинке</label><br>
                        <img height="42" width="100" src="{$router->getUrl('kaptcha', ['rand' => rand(1, 9999999)])}" alt=""/>
                        <input type="text" name="kaptcha" class="kaptcha">
                    </div>
                   {/if}                    
               </div>
           </div>
           <input type="submit" value="Купить">
           <span class="noProduct">Нет в наличии</span>
           <br><br><br>               
        </form>
    {/if}
</div>

{if $catalog_config.dont_buy_when_null && $product->isMultiOffersUse()}
{* JavaScript, отключающий возможность купить комплектацию, которой нет в наличии *}
    <script type="text/javascript">
        $(function() {
            var click_context = $(".oneClickWrapper");
            
            $('[name^="multioffers["]', click_context).on('change', function(){
                 var _this = this, selected = [], offer  = false;
                 
                 $('[name^="multioffers["]', click_context).each(function(i){
                     selected.push([$(this).data('propTitle'), $('option:selected', this).text()]);
                 });
                 
                 //Найдём нашу выбранную комплектацию
                 $(".hidden_offers", click_context).each(function(i) {
                     var _offer_this = this, info = $(this).data('info'), found = 0;
                        
                     for(var m=0; m < info.length; m++) {
                        for(var j=0; j < selected.length; j++) {
                           if ( (selected[j][0] == info[m][0]) && (selected[j][1] == info[m][1]) ) {
                              found++;
                           }        
                           if (found == selected.length){ //Если удалось найди совпадение, то выходим
                                offer = $(_offer_this);
                                break;
                           }
                        }
                     }
                 });
                 
                 //Если мы нашли комплектацию, и товара нет в наличии
                 var verdict = offer && offer.prop('disabled');
                 $("[type='submit']", click_context).toggle(!verdict).prop('disabled', verdict); 
                 click_context.toggleClass('disabled', verdict);
            });
        });
    </script>
{/if}