{addjs file="{$mod_js}jquery.csvexport.js" basepath="root"}
<div class="export-csv">
    <form class="crud-form" method="POST" action="{adminUrl schema=$schema}">
        <table class="preset-table">
            <tr>
                <td>
                    <label>{t}Возможные колонки для экспорта{/t}</label>
                    <select multiple class="source"></select>
                </td>
                <td class="middle">
                    <input type="button" class="add" value="&rarr;"><br>
                    <input type="button" class="remove" value="&larr;"><br>
                </td>
                <td>
                    <label>{t}Выбранные колонки для экспорта{/t}</label>
                    <select name="columns[]" multiple class="destination selectAllBeforeSubmit">
                        {foreach from=$columns key=id item=column}
                        <option value="{$id}">{$column.title}</option>
                        {/foreach}                    
                    </select>        
                </td>
                <td class="middle">
                    <input type="button" class="up" value="&uarr;"><br>
                    <input type="button" class="down" value="&darr;">            
                </td>
            </tr>
        </table>
    </form>
    <div class="presets">
        Шаблон
        <select class="maps">
            <option value="0">-- Не выбрано --</option>
            {foreach from=$maps item=item}
                {include file="csv/export_csv_option.tpl" map=$item}
            {/foreach}
        </select>
        &nbsp;&nbsp;
        <a class="removeMap" data-url="{adminUrl do="deleteMap"}">Удалить</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="saveMap" data-url="{adminUrl do="saveMap" type="export" schema=$schema}">Сохранить шаблон</a>    
    </div>
</div>

<script>
    $.allReady(function() {
        $('.export-csv', this).csvExport();
    });
</script>