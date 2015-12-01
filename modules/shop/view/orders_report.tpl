<div class="orderReportWrapper">
    <span class="titlebox">Статистика по заказам</span>
    {if !empty($order_report_arr)}
       <h2 class="title">Общая статистика</h2> 
       <table>
         <tr>
            <td class="key">Количество заказов:</td>
            <td class="value">{$order_report_arr.all.orderscount}</td>
         </tr>
         <tr>
            <td class="key">Общая сумма заказов:</td>
            <td class="value">{$order_report_arr.all.totalcost|format_price} {$currency.stitle}</td>
         </tr>
         <tr>
            <td class="key">Общая сумма без учёта суммы доставок:</td>
            <td class="value">{$order_report_arr.all.total_without_delivery|format_price} {$currency.stitle}</td>
         </tr>
         <tr>
            <td class="key">Общая сумма за доставку:</td>
            <td class="value">{$order_report_arr.all.deliverycost|format_price} {$currency.stitle}</td>
         </tr>
       </table>
       
       <div class="reportColumns">
           <div class="col">
               {if !empty($order_report_arr.payment)}
                   <h2 class="title">Статистика по типам оплаты</h2> 
                   <h3>Количество заказов:</h3>
                   <table class="reportSubTable">
                     {foreach $order_report_arr.payment as $payment_id=>$item}
                         <tr>              
                            <td class="key">{$payments[$payment_id]}</td>
                            <td class="value">
                                {$item.orderscount}
                            </td>
                         </tr>
                     {/foreach}
                   </table>
                   
                   <h3>Cумма заказов:</h3>
                   <table class="reportSubTable">
                     {foreach $order_report_arr.payment as $payment_id=>$item}
                         <tr>              
                            <td class="key">{$payments[$payment_id]}</td>
                            <td class="value">
                                {$item.totalcost|format_price} {$currency.stitle}
                            </td>
                         </tr>
                     {/foreach}
                   </table>
                   
                   <h3>Cумма без учёта суммы доставок:</h3>
                   <table class="reportSubTable">
                     {foreach $order_report_arr.payment as $payment_id=>$item}
                         <tr>              
                            <td class="key">{$payments[$payment_id]}</td>
                            <td class="value">
                                {$item.total_without_delivery|format_price} {$currency.stitle}
                            </td>
                         </tr>
                     {/foreach}
                   </table>
                   
                   <h3>Cумма за доставку:</h3>
                   <table class="reportSubTable">
                     {foreach $order_report_arr.payment as $payment_id=>$item}
                         <tr>              
                            <td class="key">{$payments[$payment_id]}</td>
                            <td class="value">
                                {$item.deliverycost|format_price} {$currency.stitle}
                            </td>
                         </tr>
                     {/foreach}
                   </table>
               {/if}
           </div>
           
           <div class="col">
               {if !empty($order_report_arr.delivery)}
                   <h2 class="title">Статистика по типам доставки</h2> 
                   <h3>Количество заказов:</h3>
                   <table class="reportSubTable">
                     {foreach $order_report_arr.delivery as $delivery_id=>$item}
                         <tr>              
                            <td class="key">{$deliveries[$delivery_id]}</td>
                            <td class="value">
                                {$item.orderscount}
                            </td>
                         </tr>
                     {/foreach}
                   </table>
                   
                   <h3>Cумма заказов:</h3>
                   <table class="reportSubTable">
                     {foreach $order_report_arr.delivery as $delivery_id=>$item}
                         <tr>              
                            <td class="key">{$deliveries[$delivery_id]}</td>
                            <td class="value">
                                {$item.totalcost|format_price} {$currency.stitle}
                            </td>
                         </tr>
                     {/foreach}
                   </table>
                   
                   <h3>Cумма за доставку:</h3>
                   <table class="reportSubTable">
                     {foreach $order_report_arr.delivery as $delivery_id=>$item}
                         <tr>              
                            <td class="key">{$deliveries[$delivery_id]}</td>
                            <td class="value">
                                {$item.deliverycost|format_price} {$currency.stitle}
                            </td>
                         </tr>
                     {/foreach}
                   </table>
               {/if}
           </div>
       </div>
    {else}
       <p class="empty">Не заданы параметры запроса списка заказов</p>
    {/if}
</div>