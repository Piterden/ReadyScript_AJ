<div class="formbox" data-dialog-options='{ "width":430 }'>
    <form method="POST" action="{urlmake}" class="crud-form">
        <table class="otable">
            <tr>
                <td class="otitle">Пользователь</td>
                <td>{$user->getFio()}</td>
            </tr>
            <tr>
                <td class="otitle">Текущий баланс</td>
                <td>{$user->getBalance()}</td>
            </tr>
            <tr>
                <td class="otitle">
                    {if $negative}
                        Сумма списания
                    {else}
                        Сумма пополнения
                    {/if}
                </td>
                <td>
                    <input type="text" name="amount">
                    <div data-field="amount" class="field-error top-corner"></div>
                </td>
            </tr>
            <tr>
                <td class="otitle">Комментарий</td>
                <td><input size="30" name="reason" value="{$default_reason}"></td>
            </tr>
        </table>
    </form>
</div>