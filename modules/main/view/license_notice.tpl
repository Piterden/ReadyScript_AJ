{if $smarty.const.SCRIPT_TRIAL_STATUS != 'DISABLED' 
    || $smarty.const.SCRIPT_TRIAL_STATUS == 'OVERDUE' 
    || $smarty.const.SITE_LIMIT_ERROR 
    || $smarty.const.SCRIPT_TYPE_ERROR}
<div class="notice-box no-padd" style="margin-top:10px;">
    {if $smarty.const.SCRIPT_TRIAL_STATUS != 'DISABLED'}
    <div class="notice-bg">
        ReadyScript работает в <strong>пробном режиме</strong>. Весь функционал доступен в полном объеме. 
        После окончания пробного периода сайты прекратят свою работу. 
        Исключение составляют сайты, расположенные на доменных именах для разработки - .local и .test, 
        их работа будет продолжена после окончания пробного периода.
    </div>
    {/if}
    <div class="notice-errors">
        {if $smarty.const.SCRIPT_TRIAL_STATUS == 'OVERDUE'}<p>Пробный период истек, необходимо добавить лицензию.</p>{/if}
        {if $smarty.const.SITE_LIMIT_ERROR}<p>Превышено число сайтов, разрешенных в лицензии</p>{/if}
        {if $smarty.const.SCRIPT_TYPE_ERROR}<p>Комплектация продукта не соответствует лицензии</p>{/if}
    </div>
</div>
{/if}

<div class="notice-box no-padd" style="margin-top:10px">
    <div class="notice-bg">
        Текущая комплектация системы: <strong>{$Setup.SCRIPT_TYPE}</strong>. Версия ядра: <strong>{$Setup.VERSION}</strong>
    </div>
</div>