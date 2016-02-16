<textarea class="tinymce" id="tinymce-{$param.id}" name="{$param.name}" {if isset($param.rows)}rows="{$param.rows}"{/if} {if isset($param.cols)}cols="{$param.cols}"{/if} {if isset($param.style)}style="{$param.style}"{/if}>{$data|escape:"html"}</textarea>
<script>
    $LAB
    .script({json_encode($param.jquery_tinymce_path)})
    .wait(function(){
        var initEditor = function() {
            $('#tinymce-{$param.id}:visible').tinymce({json_encode($param.tiny_options)});
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
                
            setTimeout(function() { initEditor(); }, 10);
        });
    });
</script>