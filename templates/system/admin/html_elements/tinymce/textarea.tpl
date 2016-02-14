<textarea class="tinymce" id="tinymce-{$param.id}" name="{$param.name}" {if isset($param.rows)}rows="{$param.rows}"{/if} {if isset($param.cols)}cols="{$param.cols}"{/if} {if isset($param.style)}style="{$param.style}"{/if}>{$data|escape:"html"}</textarea>
<script>
    $LAB
        .script("{$Setup.JS_PATH}/tiny_mce/jquery.tinymce.min.js")
        .wait(function(){
        var initEditor = function() {
        $('#tinymce-{$param.id}:visible').tinymce({
        script_url:         '{$Setup.JS_PATH}/tiny_mce/tiny_mce.min.js',
            menubar:            false,
            toolbar_items_size: 'small',
            language :          "ru",
            plugins:            ["link image anchor", "searchreplace wordcount visualblocks code fullscreen media",
                "table contextmenu textcolor paste textcolor colorpicker responsivefilemanager"],
            // Theme options
            toolbar1:           "undo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            toolbar2:           "table | cut copy paste | searchreplace | bullist numlist | link unlink anchor responsivefilemanager image media code | forecolor backcolor | visualblocks fullscreen",
            valid_elements :    '*[*]',
            resize:             "both",
            image_advtab:       true,
            filemanager_title:          lang.t("Файловый менеджер"),
            external_plugins:           { "filemanager" : "{$Setup.JS_PATH}/tiny_mce/filemanager/plugin.min.js"},
            filemanager_access_key:     "{sha1("{$Setup.SECRET_KEY}{$Setup.SECRET_SALT}")}",
            external_filemanager_path:  "{$Setup.JS_PATH}/tiny_mce/filemanager/",
    {$path_to_theme_css="{$Setup.FOLDER}{$Setup.SM_RELATIVE_TEMPLATE_PATH}/{$CONFIG->getThemeName()}{$Setup.RES_CSS_FOLDER}/layout.css"}
    {if file_exists("{$Setup.ROOT}{$path_to_theme_css}")}
        content_css : "{$path_to_theme_css}",
    {/if}
        relative_urls :     false,
            cleanup_on_startup: false,
            trim_span_elements: false,
            verify_html:        false,
            cleanup:            false,
            remove_script_host :true
        });
        }

        $(function() {
        $('#tinymce-{$param.id}').closest('.frame').bind('on-tab-open', function() {
        initEditor();
        });
        $('#tinymce-{$param.id}').bind('became-visible', function() {
        initEditor();
        });
        $('#tinymce-{$param.id}').closest('form').on('beforeAjaxSubmit', function() {
        $('#tinymce-{$param.id}:tinymce').each(function() {
        $(this).tinymce().save();
        });
        });
        $('#tinymce-{$param.id}').closest('.dialog-window').on('dialogBeforeDestroy', function() {
        var tiny_instance = $('#tinymce-{$param.id}').tinymce();
        if (tiny_instance) {
        $('#tinymce-{$param.id}').tinymce().remove();
        }
        });
        setTimeout(function() {
        initEditor();
        }, 10);
        });
        });
</script>