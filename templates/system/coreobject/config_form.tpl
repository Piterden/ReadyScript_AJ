{* Шаблон, для генерации шаблона ORM объекта *}
<div class="formbox" {$elem->getClassParameter('formbox_attr_line')}>
    {literal}{if $elem._before_form_template}{include file=$elem._before_form_template}{/if}{/literal}

    {assign var=groups value=$prop->getGroups(false, $switch)}
    {if count($groups)>1}
    {* Форма с вкладками *}
    <div class="tabs">
        <ul class="tab-container">
        {foreach from=$groups key=i name=tabs item=item}
            <li class="{if $smarty.foreach.tabs.first} act first{/if}"><a data-view="tab{$i}">{literal}{$elem->getPropertyIterator()->getGroupName({/literal}{$i})}</a></li>
        {/foreach}
        </ul>
        <form method="POST" action="{literal}{urlmake}{/literal}" enctype="multipart/form-data" class="crud-form">    
            <input type="submit" value="" style="display:none">
            {foreach from=$groups key=i item=data name=tab}
            <div class="frame{if !$smarty.foreach.tab.first} nodisp{/if}" data-name="tab{$i}">
                {if count($data.items)}
                    {assign var=issetUserTemplate value=false}                
                    {foreach from=$data.items key=name item=item}
                        {if get_class($item) == 'RS\Orm\Type\UserTemplate'}
                            {literal}{include file=$elem.__{/literal}{$name}{literal}->getRenderTemplate() field=$elem.__{/literal}{$name}}
                            {assign var=issetUserTemplate value=true}
                        {/if}
                    {/foreach}
                    {if !$issetUserTemplate}
                        <table class="otable">
                            {foreach from=$data.items key=name item=item}
                            {literal}
                            <tr>
                                <td class="otitle">{$elem.__{/literal}{$name}{literal}->getTitle()}&nbsp;&nbsp;{if $elem.__{/literal}{$name}{literal}->getHint() != ''}<a class="help-icon" title="{$elem.__{/literal}{$name}{literal}->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__{/literal}{$name}{literal}->getRenderTemplate() field=$elem.__{/literal}{$name}{literal}}</td>
                            </tr>
                            {/literal}
                            {/foreach}
                        </table>
                    {/if}
                {/if}
            </div>
            {/foreach}
        </form>
    </div>
    {else}
        {* Простая форма, без вкладок*}
        <form method="POST" action="{literal}{urlmake}{/literal}" enctype="multipart/form-data" class="crud-form">
            <input type="submit" value="" style="display:none">
            <div class="notabs">
                {foreach from=$groups key=i item=data}
                    {foreach from=$data.items key=name item=item}
                        {if get_class($item) == 'RS\Orm\Type\UserTemplate'}
                            {literal}{include file=$elem.__{/literal}{$name}{literal}->getRenderTemplate() field=$elem.__{/literal}{$name}}
                            {assign var=issetUserTemplate value=true}
                        {/if}
                    {/foreach}
                {/foreach}
                
                {if !$issetUserTemplate}
                    <table class="otable">
                        {foreach from=$groups key=i item=data}
                            {foreach from=$data.items key=name item=item}
                                {literal}
                                <tr>
                                    <td class="otitle">{$elem.__{/literal}{$name}{literal}->getTitle()}&nbsp;&nbsp;{if $elem.__{/literal}{$name}{literal}->getHint() != ''}<a class="help-icon" title="{$elem.__{/literal}{$name}{literal}->getHint()|escape}">?</a>{/if}</td>
                                    <td>{include file=$elem.__{/literal}{$name}{literal}->getRenderTemplate() field=$elem.__{/literal}{$name}{literal}}</td>
                                </tr>{/literal}
                            {/foreach}
                        {/foreach}
                    </table>
                {/if}
            </div>
        </form>
    {/if}
</div>