<tr data-id="{$linked_file.id}" class="item">
    <td class="chk"><input type="checkbox" value="{$linked_file.id}" name="files[]"></td>
    <td class="drag drag-handle"><a class="sort dndsort" data-sortid="{$linked_file.id}"></a></td>                            
    <td class="title clickable">{$linked_file.name}</td>
    <td class="description clickable">{$linked_file.description|teaser:"40"|default:'<span class="no">нет</span>'}</td>
    <td class="size clickable">{$linked_file.size|format_filesize}</td>
    <td class="access">
        {foreach $linked_file->getLinkType()->getAccessTypes() as $access_key => $info}
        <label>
            <input type="radio" name="access_file[{$linked_file.id}]" class="access_file" value="{$access_key}" {if $linked_file.access == $access_key}checked{/if}>
            {if is_array($info)}
                <span class="label-text">{$info.title} <span class="hint" title="{$info.hint}">?</i></span>
            {else}
                <span class="label-text">{$info}</span>
            {/if}
        </label>
        {/foreach}
    </td>
    <td class="actions">
        <span class="loader"></span>
        <a class="file-edit icon i-edit" title="{t}редактировать{/t}"></a>
        <a class="file-download icon i-download" href="{$linked_file->getAdminDownloadUrl()}" title="{t}скачать{/t}"></a>
        <a class="file-delete delete">{t}удалить{/t}</a>
    </td>
</tr>