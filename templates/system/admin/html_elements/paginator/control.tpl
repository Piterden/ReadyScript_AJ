<form method="GET" action="{$pcontrol->action}" class="paginator form-call-update">
    {foreach from=$pcontrol->hidden_fields key=key item=val}
    <input type="hidden" name="{$key}" value="{$val}">
    {/foreach}
    {$pcontrol->element->getView($local_options)}
</form>