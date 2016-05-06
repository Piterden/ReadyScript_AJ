<ul class="deliveryTypeAdditionalHTML">
    <li>
        <a href="{$router->getAdminUrl('orderQuery',['order_id'=>$order.order_num,'type'=>'delivery','method'=>'getCallCourierHTML'])}" class="crud-edit">
            {t}Вызов курьера{/t}
        </a>
    </li>
    <li>
        <a href="{$router->getAdminUrl('orderQuery',['order_id'=>$order.order_num,'type'=>'delivery','method'=>'getStatus'])}" class="crud-edit">
            {t}Получить статусы заказа{/t}
        </a>
    </li>
    <li>
        <a href="{$router->getAdminUrl('orderQuery',['order_id'=>$order.order_num,'type'=>'delivery','method'=>'getInfo'])}" class="crud-edit">
            {t}Отчёт - информация по заказу{/t}
        </a>
    </li>
    <li>
        <a href="{$router->getAdminUrl('orderQuery',['order_id'=>$order.order_num,'type'=>'delivery','method'=>'getPrintDocument'])}" class="crud-edit">
            {t}Печатная форма квитанции к заказу{/t}
        </a>
    </li>
    <li style="margin-top: 30px;">
        <a href="{$router->getAdminUrl('orderQuery',['order_id'=>$order.order_num,'type'=>'delivery','method'=>'deleteOrder'])}" class="crud-edit">
            {t}Удалить заказ из СДЕК{/t}
        </a>
    </li>
    
</ul>