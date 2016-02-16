<form {if $form->getFormName()!= ''}id="{$form->getFormName()}"{/if} action="{$form->getAction()}" method="post" enctype="multipart/form-data" class="{$form->getClass()}">
<input type="submit" value="" style="display:none">
{foreach from=$form->getHiddenFields() key=key item=value}
<input type="hidden" name="{$key}" value="{$value}">
{/foreach}
<div class="crud-form-error">
{if !$form->emptyPostResult()}
    <ul class="errorlist">
        {foreach from=$form->getPostResult() item=item}
        <li>{$item}</li>
        {/foreach}
    </ul>
{/if}
</div>
{$com_form_content}
</form>