{assign var=catalog_config value=$this_controller->getModuleConfig()} 
<div class="oneClickWrapper">
    {if $success}
        <div class="reserveForm">
            <h2 class="dialogTitle" data-dialog-options='{ "width": "400" }'>Заказ принят</h2>
            <p class="prodtitle">{$product.title} Артикул:{$product.barcode}</p>
            <p class="infotext">
                В ближайшее время с Вами свяжется наш менеджер.
            </p>
        </div>
    {else}
        <form enctype="multipart/form-data" class="reserveForm" action="{$router->getUrl('catalog-front-oneclick',["product_id"=>$product.id])}" method="POST"> 
           {$this_controller->myBlockIdInput()} 
           <input type="hidden" name="product_name" value="{$product.title}"/>
           <h2 class="dialogTitle" data-dialog-options='{ "width": "400" }'>Купить в один клик</h2>
           <p class="infotext">
                Оставьте Ваши данные и наш консультант с вами свяжется.
           </p>  
           {if $error_fields}
               <div class="pageError"> 
               {foreach from=$error_fields item=error_field}
                   {foreach from=$error_field item=error}
                        <p>{$error}</p>
                   {/foreach}
               {/foreach}
               </div>
           {/if}
           
          <table class="formTable tabFrame">
                {if $product->isMultiOffersUse()}
                    <tr>
                        <td class="key">{$product.offer_caption|default:'Комплектация'}</td>
                        <td class="value">
                        </td>
                    </tr>
                    {assign var=offers_levels value=$product.multioffers.levels} 
                    {foreach $offers_levels as $level}
                        <tr>
                            <td class="key">{if $level.title}{$level.title}{else}{$level.prop_title}{/if}</td>
                            <td class="value">
                                <select name="multioffers[{$level.prop_id}]" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
                                   {foreach $level.values as $value}       
                                       {if $level.title}
                                            {assign var=ltitle value=$level.title}
                                       {else}
                                            {assign var=ltitle value=$level.prop_title}
                                       {/if} 
                                       {$concat="{$ltitle}: {$value.val_str}"}
                                       <option {if in_array($concat, $offer_fields.multioffer)}selected="selected"{/if} value="{$ltitle}: {$value.val_str}">{$value.val_str}</option> 
                                   {/foreach}
                                </select>
                            </td>
                        </tr>
                    {/foreach}
                    {* Скрытые комплектации для многомерных комплектаций *}
                    {if $product->isOffersUse()}
                       {foreach $product.offers.items as $key=>$offer}
                          <input value="{$key}" type="hidden" class="hidden_offers" data-info='{$offer->getPropertiesJson()}' data-num="{$offer.num}" {if $catalog_config.dont_buy_when_null && $offer.num<=0}disabled{/if}/>
                       {/foreach}
                    {/if}
               {elseif $product->isOffersUse()}
                    {assign var=offers value=$product.offers.items}
                    <tr>
                        <td class="key">{$product.offer_caption|default:'Комплектация'}</td>
                        <td class="value">
                            <select name="offer">
                               {foreach $offers as $key=>$offer}
                                   <option value="{$offer.title}" {if $catalog_config.dont_buy_when_null && $offer.num<=0}disabled{/if} {if $offer_fields.offer == $offer.title || $key == $offer_val}selected="selected"{/if}>{$offer.title}</option> 
                               {/foreach}
                            </select>
                        </td>
                    </tr>
               {/if}           
          </table>      
               
           <table class="formTable tabFrame">
               <tbody>
                   <tr class="clickRow">
                        
                       <td class="key">
                          Ваше имя
                       </td>                                     
                       <td class="value">
                          <input type="text" class="inp {if $error_fields}has-error{/if}" value="{if $request->request('name','string')}{$request->request('name','string')}{else}{$click.name}{/if}" maxlength="100" name="name">
                       </td>    
                   </tr>
                   <tr class="clickRow">
                        
                       <td class="key">
                          Ваш телефон
                       </td> 
                       <td class="value">
                          <input type="text" class="inp {if $error_fields}has-error{/if}" value="{if $request->request('phone','string')}{$request->request('phone','string')}{else}{$click.phone}{/if}" maxlength="20" name="phone">
                       </td>      
                   </tr>
                   
                   {foreach from=$oneclick_userfields->getStructure() item=fld}
                    <tr>
                        <td class="key">{$fld.title}</td>
                        <td class="value">
                            {$oneclick_userfields->getForm($fld.alias)}                   
                        </td>
                    </tr>
                    {/foreach}
                   
                   {if !$is_auth && ModuleManager::staticModuleEnabled('kaptcha')}
                    <tr>
                        <td class="key">Введите код, указанный на картинке</td>
                        <td class="value">
                            <img height="42" width="100" src="{$router->getUrl('kaptcha', ['rand' => rand(1, 9999999)])}" alt=""/>
                            <input type="text" name="kaptcha" class="kaptcha">
                        </td>
                    </tr>
                   {/if}
                   
               </tbody>
           </table>
           
           <div class="centerWrap">
              <input type="submit" value="Отправить" class="formSave">
              <span class="unobtainable">Нет в наличии</span>
           </div>
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