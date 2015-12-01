{if $order_info}
    <div class="titlebox">{$title}</div>
    <div class="cdekStatuses">
        
        <table border="0">
            <tr class="hr">
                <td>
                   Номер отправления клиента:
                </td>
                <td>
                   {$order_info.Number} 
                </td>
            </tr>
            <tr>
                <td>
                   Дата, в которую был передан заказ в базу СДЭК:
                </td>
                <td>
                   {$order_info.Date} 
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
            <tr>
                <td>
                   Код типа тарифа:
                </td>
                <td>
                   [{$order_info.TariffTypeCode}] {$tariffCode.title}
                </td>
            </tr>
            <tr class="hr">
                <td>
                   Расчетный вес (в граммах):
                </td>
                <td>
                   {$order_info.Weight} 
                </td>
            </tr>
            <tr>
                <td>
                   Стоимость услуги доставки, руб:
                </td>
                <td>
                   {$order_info.DeliverySum} 
                </td>
            </tr>
            {if isset($order_info.DateLastChange) && !empty($order_info.DateLastChange)}
            <tr class="hr">
                <td>
                   Дата последнего изменения суммы по услуге доставки:
                </td>
                <td>
                   {$order_info.DateLastChange} 
                </td>
            </tr>
            {/if}
            <tr>
                <td>
                   Сумма наложенного платежа,<br/> которую необходимо было взять с получателя:
                </td>
                <td>
                   {$order_info.CachOnDelivFac} 
                </td>
            </tr>
            {if isset($order_info.CashOnDeliv) && !empty($order_info.CashOnDeliv)}
            <tr class="hr">
                <td>
                   Сумма наложенного платежа,<br/> которую взяли с получателя, с учетом частичной доставки. <br/>
                   Доступно только для накладных в статусе «Вручен»:
                </td>
                <td>
                   {$order_info.CashOnDeliv} 
                </td>
            </tr>
            {/if}
        </table>
        <p><b>Город отправителя</b></p>
        <table>
            <tr class="hr">
                <td>
                    Код города по базе СДЭК:
                </td>
                <td>
                    {$order_info->SendCity.Code} 
                </td>
            </tr>
            <tr>
                <td>
                    Почтовый индекс города:
                </td>
                <td>
                    {$order_info->SendCity.PostCode} 
                </td>
            </tr>
            <tr class="hr">
                <td>
                    Название города:
                </td>
                <td>
                    {$order_info->SendCity.Name} 
                </td>
            </tr>
        </table>
        <p><b>Город получателя</b></p>
        <table>
            <tr class="hr">
                <td>
                    Код города по базе СДЭК:
                </td>
                <td>
                    {$order_info->RecCity.Code} 
                </td>
            </tr>
            <tr>
                <td>
                    Почтовый индекс города:
                </td>
                <td>
                    {$order_info->RecCity.PostCode} 
                </td>
            </tr>
            <tr class="hr">
                <td>
                    Название города:
                </td>
                <td>
                    {$order_info->RecCity.Name} 
                </td>
            </tr>
        </table>
        {if isset($order_info->AddedService) && !empty($order_info->AddedService.ServiceCode)}
            <p><b>Дополнительные услуги к заказам</b></p>
            <table>
                <tr class="hr">
                    <td>
                        Тип дополнительной услуги:
                    </td>
                    <td>
                        [{$order_info->AddedService.ServiceCode}] {$addTariffCode.title}
                    </td>
                </tr>
                <tr>
                    <td>
                        Сумма услуги, руб:
                    </td>
                    <td>
                        {$order_info->AddedService.Sum} 
                    </td>
                </tr>
            </table>
        {/if}
        
    </div>
{else}
    Заказ не был создан или не удалось отправить запрос серверу.
{/if}