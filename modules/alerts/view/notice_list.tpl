{addjs file="table.js"}
{addjs file="{$tfolders.mod_js}tplmanager.js" basepath="root"}
{addjs file="{$tfolders.mod_js}selecttemplate.js" basepath="root"}
<div class="crud-ajax-group">
    {if !$cfg->sms_sender_login && !$cfg->sms_sender_pass}
        <div class="notice-box no-padd" style="margin-top:10px;">
            <div class="notice-bg">
                {t}Внимание! Отправка SMS невозможна, так как не настроен модуль "Уведомления". Укажите логин и пароль аккаунта для отправки SMS{/t}
            </div>    
        </div>
        <br>
    {/if}
    <div class="crud-form-success text-success"></div>
    <div class="formbox">
    <form method="post" class="crud-form">
        <table class="rs-table">
            <thead>
                <tr>
                    <th>Описание</th>
                    <th>E-Mail</th>
                    <th>SMS</th>
                    <th width="44"></th>                               
                </tr>
            </thead>
            <tbody>
                <tr class="empty no-over">
                    <td colspan="4"></td>
                </tr>            
            {foreach from=$alerts item=item}
                {assign var=tpls value=$item->getDefaultTemplates()}
                <tr>
                    <td class="title">{$item.description}</td>
                    <td>
                        {if $item->hasEmail()}
                            <input type="hidden" name="enable_email[{$item.id}]" value="0">
                            <input class="rs-switch" type="checkbox" name="enable_email[{$item.id}]" value="1" {if $item.enable_email}checked="checked"{/if}>
                        {/if}
                    </td>
                    <td>
                        {if $item->hasSms()}  
                            <input type="hidden" name="enable_sms[{$item.id}]" value="0">
                            <input class="rs-switch" type="checkbox" name="enable_sms[{$item.id}]" value="1" {if $item.enable_sms}checked="checked"{/if}>
                        {/if}
                    </td>
                    <td>
                        <div class="tools">
                            <a data-id="{$item.id}" class="tool edit edit-tpl" title="редактировать шаблоны"></a>
                        </div>                    
                        <input type="hidden" class="tpl-email" name="template_email[{$item.id}]" data-default="{$tpls.email}" value="{$item.template_email}" size="40" {if !$item->hasEmail()}disabled="disabled"{/if}>
                        <input type="hidden" class="tpl-sms" name="template_sms[{$item.id}]" data-default="{$tpls.sms}" value="{$item.template_sms}"  size="40" {if !$item->hasSms()}disabled="disabled"{/if}>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </form>
    </div>
</div>

<div id="notice-tpl-dialog" style="display:none">
    <div class="formbox">
        <table class="otable">
            <tbody>
                <tr>
                    <td class="otitle">Шаблон Email сообщений:</td>
                    <td><input name="template" value="%THEME%/index.tpl" maxlength="255" size="63" type="text" id="tpl-email">
                        <a class="selectTemplate" title="Выбрать шаблон"></a>
                    </td>
                </tr>
                <tr>
                    <td class="otitle">Шаблон SMS сообщений:</td>
                    <td><input name="template" value="%THEME%/index.tpl" maxlength="255" size="63" type="text" id="tpl-sms">
                        <a class="selectTemplate" title="Выбрать шаблон"></a>
                    </td>
                </tr>            
            </tbody>
        </table>
    </div>
</div>

<script>
$(function() {
    $('.edit-tpl').click(function() {
        var context = $(this).closest('tr');
        var title = $(this).closest('tr').find('.title').html(),
            email_inp = $('.tpl-email', context),
            sms_inp = $('.tpl-sms', context);
        
        $('#tpl-email').prop('disabled', email_inp.is(':disabled')).val(email_inp.val());
        $('#tpl-sms').prop('disabled', sms_inp.is(':disabled')).val(sms_inp.val());
        
        $('#notice-tpl-dialog').dialog({
            modal: true,
            width:700,
            title: lang.t('Редактирование шаблона уведомления &laquo;%title&raquo;', { title: title}),
            buttons: [
            {
                text: 'Сбросить',
                click: function() {
                    $('#tpl-email').val(email_inp.data('default'));
                    $('#tpl-sms').val(sms_inp.data('default'));
                }
            },
            {
                text: 'Сохранить',
                click: function() {
                    email_inp.val($('#tpl-email').val());
                    sms_inp.val($('#tpl-sms').val());
                    $('#notice-tpl-dialog').dialog('close');
                }
            }]
        });
    });

    $('#tpl-email, #tpl-sms').selectTemplate({
        dialogUrl: '{adminUrl mod_controller="templates-selecttemplate" do=false only_themes=0}',
        handler: '.selectTemplate'
    })    
});
</script>