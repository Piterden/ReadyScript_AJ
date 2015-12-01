<div class="formbox" data-dialog-options='{ "width":"500" }'>
        <div class="white-help-area">
            {t}Перед загрузкой XML файла со схемой блоков, информация по существующим блокам, секциям, страницам будет удалена.
            Чтобы иметь возможность восстановить текущее состояние структуры блоков, рекомендуется сделать экспорт данных.{/t}
        </div>
        <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">
            <div class="notabs">
                <table class="otable">
                                                                <tr>
                    <td class="otitle">XML файл</td>
                    <td><input type="file" name="file"></td>
                </tr>
                                                                                </table>
            </div>
        </form>
    </div>