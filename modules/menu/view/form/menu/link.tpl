<div id="link_form" class="nodisp">
    {$elem.__link->getTitle()}<br>
    {include file=$elem.__link->getRenderTemplate() field=$elem.__link}
    <br><br>
    {include file=$elem.__target_blank->getRenderTemplate() field=$elem.__target_blank}
    <label>{$elem.__target_blank->getTitle()}</label>
    
</div>
<div id="link_article" class="nodisp">
    {$elem.__acontent->getTitle()}<br>
    {include file=$elem.__acontent->getRenderTemplate() field=$elem.__acontent}
</div>    

<div id="link_empty" class="nodisp">
    {t}Шаблон{/t}<br>
    {include file=$elem.__link_template->getRenderTemplate() field=$elem.__link_template}
<div>

<script>
    $.allReady(function() {
        $('[name="typelink"]').change(function() {
            var type = $(this).val();
            var form = $(this).closest('form');
            
            $('#link_article, #link_form, #link_empty').hide();
            
            if (type == 'link') {
                $('#link_form').show();
            } 
            if (type == 'article') {
                $('#link_article').show();
                $('textarea[name="acontent"]', form).trigger('became-visible');
            }
            if (type == 'empty') {
                $('#link_empty').show();
            }
            $(this).trigger('contentSizeChanged');
        }).change();
    });
</script>