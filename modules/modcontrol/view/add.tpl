{if (!$is_empty_tmp)}
<div class="mod-exists inform-block">
    {t}Во временной папке есть файлы.{/t} <a href="{adminUrl do=addStep2}">{t}Подробнее...{/t}</a>
</div>
{/if}
<div class="formbox">
    <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">
        <div class="notabs">
            <table class="otable">
                <tr>
                    <td class="otitle">Файл модуля, zip</td>
                    <td><input type="file" name="module"></td>
                </tr>
            </table>
        </div>
    </form>
</div>