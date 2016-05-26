{assign var=shop_config value=ConfigLoader::byModule('shop')}
{assign var=check_quantity value=$shop_config.check_quantity}

{*Подгружаем цвета*}
{modulegetvars name="\Colors\Controller\BlockValues" var="colors"}


<div class="authorization formStyle reserveForm complectsPopup">
    <div class="multiComplectations{if !$product->isAvailable()} notAvaliable{/if}{if $product->canBeReserved()} canBeReserved{/if}{if $product.reservation == 'forced'} forcedReserve{/if}" data-id="{$product.id}">
        <div class="row">
            <div class="col-md-24">
                <h1>{$product.title}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 leftColumn">
                <div class="image">
                    {$main_image=$product->getMainImage()}
                    <img src="{$main_image->getUrl(275, 380)}" class="photo" alt="{$main_image.title|default:"{$product.title}"}"/>
                </div>
            </div>
            <div class="col-md-12 rightColumn">
                <div class="fcost">
                    {*assign var=last_price value=$product->getCost('Зачеркнутая цена')}
                    {if $last_price>0}<div class="lastPrice">{$last_price}</div>{/if*}
                    <span class="myCost">{$product->getCost()}</span>{$product->getCurrency()}
                </div>
                {if $product.barcode}
                    <p class="barcode"><span class="cap">Артикул:</span> <strong class="offerBarcode">{$product.barcode}</strong></p>
                    {/if}

                <div class="information">
                    {if $product->isMultiOffersUse()}
                        {* Многомерные комплектации *}
                        {* <span class="pname">{$product.offer_caption|default:'Комплектация'}</span> *}
                        {* Подгрузим у многомерных комплектаций фото к их вариантам *}
                        {$product->fillMultiOffersPhotos()}
                        {* Переберём доступные многомерные комплектации *}
                        <div class="multiOffers">
                            {foreach $product.multioffers.levels as $level}
                            {if !empty($level.values) && $level.title != 'Цвет'}
                                <div class="multiofferTitle">
                                    {if $level.title}
                                        {$level.title}
                                    {else}
                                        {$level.prop_title}
                                    {/if}
                                </div>
                                {if !$level.is_photo && !isset($level.values_photos)} {*Если отображать не как фото (выпадающим списком)*}
                                    <select{if $level.title!='Размер'} class="themed"{/if} name="multioffers[{$level.prop_id}]" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
                                        {foreach $level.values as $value}
                                            <option value="{$value.val_str}">{$value.val_str}</option>
                                        {/foreach}
                                    </select>
                                    <div class="multiofferBlock multiofferBlock{$level.prop_id}" data-wrapper="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
                                        {foreach $level.values as $value key=i}
                                            <div class="moItem{if $i == 1} active{/if}" title="{$value.val_str}" data-value="{$value.val_str}"><div class="moItemInner">{$value.val_str}</div></div>
                                            {/foreach}
                                    </div>
                                    <div class="clearfix"></div>
                                {else}
                                    <div class="multiOfferValues">
                                        <input type="hidden" name="multioffers[{$level.prop_id}]" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}"/>
                                        {foreach $level.values as $value}
                                            {if isset($level.values_photos[$value.val_str])}
                                                <a class="multiOfferValueBlock {if $value@first}sel{/if}" data-value="{$value.val_str}" title="{$value.val_str}"><img src="{$level.values_photos[$value.val_str]->getUrl(40,40,'axy')}"/></a>
                                                {else}
                                                <a class="multiOfferValueBlock likeString {if $value@first}sel{/if}" data-value="{$value.val_str}" title="{$value.val_str}">{$value.val_str}</a>
                                            {/if}
                                        {/foreach}
                                    </div>
                                {/if}
                            {else if !empty($level.values) && $level.title == 'Цвет'}
                                <div class="multiofferTitle">
                                	{if $level.title}{$level.title}{else}{$level.prop_title}{/if}
                            	</div>
                                <select{if $level.title!='Размер'} class="themed"{/if} name="multioffers[{$level.prop_id}]" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
                                    {foreach $level.values as $value}
                                        <option value="{$value.val_str}">{$value.val_str}</option>
                                    {/foreach}
                                </select>
                                <div class="multiofferBlock multiofferBlock{$level.prop_id}" data-wrapper="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
                                    {foreach $level.values as $value key=i}
                                        <div class="moItem{if $i == 1} active{/if}" data-value="{$value.val_str}"><div class="moItemInner" title="{$value.val_str}" style="background-color:#fff;background-image: linear-gradient( -45deg, {$colors.colors[$value.val_str].color2} 0%, {$colors.colors[$value.val_str].color2} 50%, {$colors.colors[$value.val_str].color1} 50%, {$colors.colors[$value.val_str].color1} 50%);">{$value.val_str}</div></div>
                                    {/foreach}
                                </div>
                                <div class="clearfix"></div>
                            {/if}
                        {/foreach}
                    </div>

                    {if $product->isOffersUse()}
                        {foreach from=$product.offers.items key=key item=offer name=offers}
                            <input value="{$key}" type="hidden" name="hidden_offers" class="hidden_offers" {if $smarty.foreach.offers.first}checked{/if} id="offer_{$key}" data-info='{$offer->getPropertiesJson()}' {if $check_quantity}data-num="{$offer.num}"{/if} {if $catalog_config.use_offer_unit}data-unit="{$offer->getUnit()->stitle}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getCost('Зачеркнутая цена', $key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'/>
                        {/foreach}
                        <input type="hidden" name="offer" value="0"/>
                    {/if}

                    {elseif $product->isOffersUse()}
                        {* Простые комплектации *}
                        <div class="packages">
                            <div class="package">
                                <span class="pname">{$product.offer_caption|default:'Комплектация'}</span>
                                <div class="values">
                                    {if count($product.offers.items)>5}
                                        <select name="offer">
                                            {foreach from=$product.offers.items key=key item=offer name=offers}
                                                <option value="{$key}" {if $smarty.foreach.offers.first}checked{/if} {if $check_quantity}data-num="{$offer.num}"{/if} data-change-cost='{ ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getOldCost($key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'>{$offer.title}</option>
                                            {/foreach}
                                        </select>
                                    {else}
                                        {foreach from=$product.offers.items key=key item=offer name=offers}
                                            <div class="packageItem">
                                                <input value="{$key}" type="radio" name="offer" {if $smarty.foreach.offers.first}checked{/if} id="offer_{$key}" {if $check_quantity}data-num="{$offer.num}"{/if} data-change-cost='{ ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getOldCost($key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'>
                                                <label for="offer_{$key}">{$offer.title}</label><br>
                                            </div>
                                        {/foreach}
                                    {/if}
                                </div>
                            </div>
                        </div>
                    {/if}
                </div>

                <div class="amountWrap">
                    <div class="inc"><i class="fa fa-plus"></i></div>
                    <div class="amount">
                        Количество: <input value="1" type="number" name="amount" class="fieldAmount">
                    </div>
                    <div class="dec"><i class="fa fa-minus"></i></div>
                </div>
                <div class="buttons">
                    {* Вывод наличия на складах *}
                    {assign var=stick_info value=$product->getWarehouseStickInfo()}
                    {assign var=stock value=$product->getWarehouseStock()}
                    <div class="restWrap">
                        {foreach from=$stock.1 item=rest key=key}
                            <div class="offerProperty" data-offer="{$key}">
                                {if $rest.stock == 0}
                                    <span class="text-danger"><strong>Нет в наличии</strong></span>
                                    {* Кнопка заказать
                                    <a data-href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="button reserve inDialog">Заказать</a> *}
                                {elseif $rest.stock > 0 && $rest.stock <= 3}
                                    Кол-во на складе: <span class="text-primary"><strong>{$rest.stock}</strong></span>
                                        {else}
                                    Кол-во на складе: <span class="text-success"><strong>много</strong></span>
                                {/if}
                            </div>
                        {/foreach}
                    </div>
                    <a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="cornered button addToCart noShowCart">В корзину</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-24">
                <div class="information">
                    <p class="descr">{$product.short_description|default:''}</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

{literal}
    <script type="text/javascript">
        $(function() {
            $('[name="offer"]').changeOffer();
        });
        $('.multiComplectations .addToCart').on('click', function() {
            $.colorbox.close();
        });
        $('.moItem').on('click', function() {
            var value = $(this).text().trim();
            $(this).addClass('active').siblings('.moItem').removeClass('active');
            $(this).parent().prev('[name^="multioffers["]').val(value).trigger('change');
        });
    </script>
{/literal}
