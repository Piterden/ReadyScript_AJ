{if $api->hasError()}
    <div class="pageError">
        {foreach from=$api->getErrors() item=item}
        <p>{$item}</p>
        {/foreach}
    </div>
{/if}


<form method="POST" id="order-form">
    <input type="hidden" name="payment" value="0">
    <div class="formSection">
        <span class="formSectionTitle">Выберите способ оплаты</span>
    </div>    
    <table class="formTable">
        <tr>                                            
            {foreach from=$pay_list item=item}
            <tr>
                <td class="value fixedRadio topPadd" width="40">
                    <input type="radio" name="payment" value="{$item.id}" id="dlv_{$item.id}" {if $smarty.post.payment==$item.id}checked{/if}>
                </td>
                <td class="value marginRadio topPadd">
                    <label for="dlv_{$item.id}">{$item.title}</label>
                    <div class="help">{$item.description}</div>
                </td>
            </tr>
            {/foreach}
        </tr>
    </table>

    <div class="formSection">
        <span class="formSectionTitle">Укажите сумму пополнения</span>
    </div>    
    <table class="formTable">
        <tr>
            <td class="value">
                <br>
                Сумма: <input name="cost" value="{$smarty.post.cost}"> 
            </td>
            <td class="value"><button type="submit" class="formSave">Пополнить</button></td>
        </tr>
    </table>
    
</form>
<br><br><br>
