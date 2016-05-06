{if !empty($brands)}
    <div class="brandLine">
        <h2><span>Бренды</span></h2>
        <div class="wrapWidth">
            <ul> 
               {foreach $brands as $brand}
                   <li {$brand->getDebugAttributes()}> 
                     {if $brand.image} 
                        <a href="{$brand->getUrl()}">
                            <img src="{$brand->__image->getUrl(100,100,'axy')}" alt="{$brand.title}"/>
                        </a>
                     {/if}
                   </li>
               {/foreach}
            </ul>
        </div>
        <a class="onemore" href="{$router->getUrl('catalog-front-allbrands')}">Все бренды</a>
   </div>
   
{/if}