{addjs file="%templates%/tplmanager.js" basepath="root"}
{addjs file="%templates%/selecttemplate.js" basepath="root"}
<span style="white-space:nowrap;">
<input name="{$field->getFormName()}" value="{$field->get()}" {if $field->getMaxLength()>0}maxlength="{$field->getMaxLength()}"{/if} {$field->getAttr()} />&nbsp;<a class="selectTemplate" title="{t}Выберите шаблон из списка{/t}"></a>
</span>
{include file="%system%/coreobject/type/form/block_error.tpl"}
<script>
    $.allReady(function() {
        $('input[name="{$field->getFormName()}"]').selectTemplate({
            dialogUrl: '{adminUrl mod_controller="templates-selecttemplate" do=false only_themes=$field->getOnlyThemes()}',
            handler: '.selectTemplate'
        })
    });
</script>