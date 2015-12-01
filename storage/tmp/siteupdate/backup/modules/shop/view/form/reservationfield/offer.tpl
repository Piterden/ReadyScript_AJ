{assign var=product value=$elem->getProduct()}


{if $product->isOffersUse() && !$product->isMultiOffersUse()} 
   <select name="offer"> 
       {foreach $product.offers.items as $offer}
          <option value="{$offer.title}" {if $elem.offer==$offer.title}selected="selected"{/if}>{$offer.title}</option>
       {/foreach}
   </select> 
{else}
   -
{/if}