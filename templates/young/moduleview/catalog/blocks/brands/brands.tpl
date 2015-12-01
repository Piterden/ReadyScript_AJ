{if !empty($brands)}
    <div class="brandLine">
        <p class="blockTitle"><span class="top">Бренды</span></p>
           <ul> 
               {foreach $brands as $brand}
                   <li {$brand->getDebugAttributes()}> 
                     {if $brand.image} 
                        <a href="{$brand->getUrl()}">
                            <img src="{$brand->__image->getUrl(130,100,'axy')}" alt="{$brand.title}"/>
                        </a>
                     {/if}
                   </li>
               {/foreach}
           </ul>
       </div>
    </div>
{/if}