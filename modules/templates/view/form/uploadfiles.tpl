{addcss file="%templates%/uploadfiles.css"}
{addjs file="fileupload/jquery.fileupload.js"}
{addjs file="fileupload/jquery.iframe-transport.js"}
{addjs file="%templates%/jquery.uploadtemplatefiles.js"}
<div id="upload-files" data-upload-url="{$router->getAdminUrl('uploadFile', ['path' => $path])}">
    <div class="notice-box upload-files-notice">
        Папка для загрузки: <strong>{$path}</strong><br>
        Для загрузки допускаются файлы со следующими расширениями:
        {implode(',', $allow_ext)}
    </div>
    <form class="upload-files-dnd crud-form" enctype="multipart/form-data" method="POST">
        <input type="file" name="files[]" multiple class="inputUploadFile" style="display:none">
        Перетащите сюда файлы или <a class="selectUploadFile">выберите файлы на диске</a>
    </form>

    <table class="upload-files-table hidden">
        <thead>
            <tr>
                <th>Файл</th>
                <th>Размер</th>
                <th>Статус</th>
                <th><a class="cancel-all">отменить все</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    $.allReady(function() {
        $('#upload-files').uploadTemplateFiles();
    });
</script>