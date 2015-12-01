{if $order_info}
    <div class="titlebox">Информация о заказе СДЕК</div>
    <div class="cdekStatuses">
        
        <table border="0">
            <tr class="hr">
                <td>
                   Номер акта приема-передачи: 
                </td>
                <td>
                   {$order_info.ActNumber} 
                </td>
            </tr>
            <tr>
                <td>
                   Номер отправления клиента:
                </td>
                <td>
                   {$order_info.Number} 
                </td>
            </tr>
            <tr class="hr">
                <td>
                   Номер отправления СДЭК:<br/> 
                   (присваивается при импорте заказов) 
                </td>
                <td>
                   {$order_info.DispatchNumber} 
                </td>
            </tr>
            {if isset($order_info.DeliveryDate) && !empty($order_info.DeliveryDate)}
                <tr>
                    <td>
                       Дата доставки:
                    </td>
                    <td>
                       {$order_info.DeliveryDate} 
                    </td>
                </tr>
            {/if}
            {if isset($order_info.RecipientName) && !empty($order_info.RecipientName)}
                <tr class="hr">
                    <td>
                       Получатель при доставке:
                    </td>
                    <td>
                       {$order_info.RecipientName} 
                    </td>
                </tr>
            {/if}
            {if isset($order_info.ReturnDispatchNumber) && !empty($order_info.ReturnDispatchNumber)}
                <tr>
                    <td>
                       Номер возвратного отправления:<br/> 
                       (номер накладной, в которой возвращается товар <br/>
                       интернет-магазину в случае статусов «Не вручен»,<br/> 
                       «Вручен» - «Частичная доставка»)
                    </td>
                    <td>
                       {$order_info.ReturnDispatchNumber} 
                    </td>
                </tr>
            {/if}
        </table>
        <p><b>Текущий статус заказа</b></p>
        <table>
            <tr class="hr">
                <td>
                    Дата статуса:
                </td>
                <td>
                    {$status_date=strtotime($order_info->Status.Date)}
                    {$status_date=date('d.m.Y H:i:s',$status_date)}
                    {$status_date} 
                </td>
            </tr>
            <tr>
                <td>
                    Идентификатор статуса:
                </td>
                <td>
                    {$order_info->Status.Code} 
                </td>
            </tr>
            <tr class="hr">
                <td>
                    Название статуса:
                </td>
                <td>
                    <b class="mainStatus">{$order_info->Status.Description}</b> 
                </td>
            </tr>
            <tr>
                <td>
                    Город изменения статуса,<br/> код города по базе СДЭК:
                </td>
                <td>
                    {$order_info->Status.CityCode} 
                </td>
            </tr>
        </table>
        {if isset($order_info->Status->State)}
            <p><b>История изменения статусов:</b></p>
            <ul class="statusesList">
                {foreach $order_info->Status->State as $state}
                   {$status_date=strtotime($state.Date)}
                   {$status_date=date('d.m.Y H:i:s',$status_date)}
                   <li><b>Статус: {$state.Description}</b> ({$status_date}) [{$state.Code}-{$state.CityCode}] {$state.CityName}</li> 
                {/foreach}
            </ul>
        {/if}
        {* Текущий дополнительный статус *}
        {if isset($order_info->Reason) && !empty($order_info->Reason.Code)}
            <p><b>Текущий дополнительный статус:</b></p>
            <ul class="statusesList">
                {foreach $order_info->Status->Reason as $state}
                   {$status_date=strtotime($state.Date)}
                   {$status_date=date('d.m.Y H:i:s',$status_date)}
                   <li><b>Статус: {$state.Description}</b> ({$status_date}) [{$state.Code}]</li> 
                {/foreach}
            </ul>
        {/if}
        {* Текущая причина задержки *}
        {if isset($order_info->DelayReason) && !empty($order_info->DelayReason.Code)}
            <p><b>Текущая причина задержки:</b></p>
            <ul class="statusesList">
                {foreach $order_info->Status->DelayReason as $state}
                   {$status_date=strtotime($state.Date)}
                   {$status_date=date('d.m.Y H:i:s',$status_date)}
                   <li><b>Статус: {$state.Description}</b> ({$status_date}) [{$state.Code}]</li> 
                {/foreach}
            </ul>
            {* Текущая причина задержки в истории *}
            {if isset($order_info->DelayReason->State)}
                <p><b>История причин задержек:</b></p>
                <ul class="statusesList">
                    {foreach $order_info->Status->DelayReason as $state}
                       {$status_date=strtotime($state.Date)}
                       {$status_date=date('d.m.Y H:i:s',$status_date)}
                       <li><b>Статус: {$state.Description}</b> ({$status_date}) [{$state.Code}]</li> 
                    {/foreach}
                </ul>
            {/if}
        {/if}
        {* Упаковка - присутствуют только в конечном статусе «Вручен» в случае частичной доставки *}
        {if isset($order_info->Package)}
            <p><b>Упаковка {$order_info->Package.Number}:</b></p>
            <span class="small">присутствуют только в конечном статусе «Вручен» в случае<br/> частичной доставки</span>
            <ul class="statusesList">
                {foreach $order_info->Package->Item as $item}
                   <li><b>{$item.WareKey}<b>, доставлено <b>{$item.DelivAmount}</b> шт.</li> 
                {/foreach}
            </ul>
        {/if}
        
        {* Время доставки из расписания на доставку - присутсвует только в случае, если по условиям договора, ИМ самостоятельно предоставляет расписание доставки для СДЭК. Тэг содержит данные по неудачным попыткам доставки в разрезе предоставленного ИМ расписания доставки *}
        {if isset($order_info->Attempt)}
            <p><b>Время доставки из расписания на доставку:</b></p>
            <span class="small">присутсвует только в случае, если по условиям договора,<br/> 
            ИМ самостоятельно предоставляет расписание доставки для СДЭК. Тэг содержит<br/> 
            данные по неудачным попыткам доставки в разрезе предоставленного ИМ расписания<br/> 
            доставки</span>
            <div>
                <b class="red">{$order_info->Attempt.ScheduleDescription}</b> - {$order_info->Attempt.ID} [{$order_info->Attempt.ScheduleCode}] 
            </div>
            <ul class="statusesList">
                {foreach $order_info->Package->Item as $item}
                   <li><b>{$item.WareKey}<b>, доставлено <b>{$item.DelivAmount}</b> шт.</li> 
                {/foreach}
            </ul>
        {/if}
        
        {* История прозвонов получателя *}
        {if isset($order_info->Call->CallGood->Good) || isset($order_info->Call->CallFail->Fail) || isset($order_info->Call->CallDelay->Delay)}
            <p><b>История прозвонов получателя:</b></p>
            {if isset($order_info->Call->CallGood->Good) && !empty($order_info->Call->CallGood)}
                <p><b>История удачных прозвонов:</b></p>
                <ul class="statusesList">
                    {foreach $order_info->Call->CallGood->Good as $call}
                       <li><b>{$call.Date}<b>, договорились о доставке/самозаборе <b>{$call.DateDeliv}</b></li> 
                    {/foreach}
                </ul>
            {/if}
            
            {if isset($order_info->Call->CallFail->Fail) && !empty($order_info->Call->CallFail)}
                <p><b>История неудачных прозвонов:</b></p>
                <ul class="statusesList">
                    {foreach $order_info->Call->CallFail->Fail as $call}
                       <li><b>{$call.ReasonDescription}</b> {$call.Date} [{$call.ReasonCode}]</li> 
                    {/foreach}
                </ul>
            {/if}
            
            {if isset($order_info->Call->CallDelay->Delay) && !empty($order_info->Call->CallDelay)}
                <p><b>История переносов прозвона:</b></p>
                <ul class="statusesList">
                    {foreach $order_info->Call->CallDelay->Delay as $call}
                       <li>{$call.Date} перенесли на <b>{$call.DateNext}</b></li> 
                    {/foreach}
                </ul>
            {/if}
        {/if}
        
    </div>
{else}
    Заказ не был создан или не удалось отправить запрос серверу.
{/if}