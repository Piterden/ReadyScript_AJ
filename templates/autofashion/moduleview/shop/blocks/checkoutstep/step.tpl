{assign var=steps value=[
    ["key" => "address", "text" => "Покупатель"],
    ["key" => "delivery", "text" => "Доставка"],
    ["key" => "payment", "text" => "Оплата"],
    ["key" => "confirm", "text" => "Подтверждение"]
]}

<ul class="checkoutSteps nstep{$step}">
    {foreach $steps as $n=>$item}
        <li class="step{$n+1} step_{$item.key}{if $step==$n+1} current{/if}{if $step>$n+1} already{/if}">
            {if $n+1>$step || $step>4}<span class="item">{else}
                    <a class="item" href="{$router->getUrl('shop-front-checkout', ['Act' => $item.key])}">
                    {/if}
                    <span class="text h4">
                        {if $n>0}<i class="fa fa-chevron-right"></i>{/if}
                        {$item.text}
                    </span>
                {if $n+1>$step || $step>4}</span>{else}</a>{/if}
        </li>
    {/foreach}               
</ul>