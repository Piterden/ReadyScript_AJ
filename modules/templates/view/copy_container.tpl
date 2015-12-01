<div class="formbox">
    <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">
        <input type="submit" value="" style="display:none">
        <div class="notabs">
            <table class="otable">
                <tr>
                    <td class="otitle">Укажите контейнер-источник</td>
                    <td><select name="from_container">
                    {foreach from=$pages item=page}
                        {if isset($containers[$page.id])}
                        <optgroup label="{$page->getRoute()->getDescription()}">
                        {foreach from=$containers[$page.id] item=container}
                            <option value="{$container.id}">{$container.title}</option>
                        {/foreach}
                        </optgroup>
                        {/if}
                    {/foreach}
                    </select></td>
                </tr>
            </table>
        </div>
    </form>
</div>
