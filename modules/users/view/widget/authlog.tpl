<table class="wtable tbcenter">
    <thead>
        <tr>
            <th>Дата</th>
            <th>Логин/Имя</th>
            <th>IP</th>
        </tr>
    </thead>
    <tbody class="overable">
    {foreach from=$list item=event}
        <tr>
        {assign var=user value=$event->getObject()}
            <td style="font-size:11px;">{$event->getEventDate()|date_format:"%d.%m.%Y"}<br>
            <span style="color:#ACACAC">{$event->getEventDate()|date_format:"%H:%M"}</span>
            </td>
            <td>
                <a style="text-decoration:underline; float:left; line-height:15px; margin-right:10px;" href="{adminUrl mod_controller="users-ctrl" do="edit" id=$user.id}">{$user.login}</a>
                <span style="font-size:10px; float:left; line-height:15px;">
                {$user.name} {$user.surname}</span>
            </td>
            <td>{$event->getIP()}</td>
        </tr>
    {/foreach}
    </tbody>
</table>

{include file="%SYSTEM%/admin/widget/paginator.tpl"}