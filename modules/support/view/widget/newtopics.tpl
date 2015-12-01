{if $topics}
<table class="wtable tbcenter">
    <thead>
        <tr>
            <th>Дата</th>
            <th>Тема</th>
        </tr>
    </thead>
    <tbody class="overable">
    {foreach from=$topics item=topic}    
        <tr class="{if $topic.newadmcount>0}highlight{/if}" onclick="location.href='{adminUrl do=false mod_controller="support-supportctrl" id=$topic.id}'">
            {assign var=user value=$topic->getUser()}
            <td style="font-size:11px;">{$topic.updated|date_format:"%d.%m.%Y"}<br>    
                <span style="color:#ACACAC">{$topic.updated|date_format:"%H:%M"}</span>       
            </td>
            <td>
                {$topic.title}
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
{else}
    <div class="empty-widget">
        Нет сообщений
    </div>
{/if}    

{include file="%SYSTEM%/admin/widget/paginator.tpl"}