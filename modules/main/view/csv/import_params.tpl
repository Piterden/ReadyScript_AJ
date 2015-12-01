{addjs file="{$mod_js}jquery.csvexport.js" basepath="root"}
<div class="import-csv-params">
    <form class="crud-form" method="POST" action="{adminUrl do="processImport"}">
        <input type="hidden" name="filename" value="{$filename}">
        <input type="hidden" name="schema" value="{$schema}">
        <input type="hidden" name="referer" value="{$referer}">
        <input type="hidden" name="import_start" value="1">
        <table class="import-table">
            <thead>
                <tr>
                    <td>№</td>
                    <td>Колонка в CSV файле</td>
                    <td>Поле в ReadyScript</td>
                </tr>
            </thead>
            <tbody>
                {foreach from=$csv_columns.csv key=n item=item}
                <tr>
                    <td>{$n+1}</td>
                    <td>{$item}</td>
                    <td>
                        <select name="columns[{$n}_{$item|escape}]" data-name="{$n}_{$item|escape}" class="destination">
                            <option value="">-- не выбрано --</option>
                            {foreach from=$columns key=key item=column}
                            <option value="{$key}" {if $csv_columns.schema[$n]==$key}selected{/if}>{$column.title}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                {/foreach}
            </tbody>
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
        <a class="saveMap" data-url="{adminUrl do="saveMap" type="import" schema=$schema}">Сохранить шаблон</a>    
    </div>
</div>
<script>
    $.allReady(function() {
        $('.import-csv-params', this).csvImport();
    });
</script>