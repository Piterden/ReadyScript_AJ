{if !$view_options || $view_options.hint}
{if $field->getHint() != ''}<a class="help-icon" title="{$field->getHint()|escape}">?</a>{/if}
{/if}