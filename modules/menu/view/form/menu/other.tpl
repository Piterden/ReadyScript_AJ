{include file=$elem.__typelink->getOriginalTemplate() field=$elem.__typelink}
<script type="text/javascript">
    $(function() { 
        var updateTypeForm = function() {
            var type = $('select[name="typelink"]').val();
            $.ajaxQuery({
                url: '{$router->getAdminUrl("getMenuTypeForm")}',
                data: { type: type },
                success: function(response) {
                    try {
                        $('#menu-type-form .tinymce').tinymce().remove();
                    } catch(e) {}
                    $('#menu-type-form').html(response.html);
                }
            })
        }
        $('select[name="typelink"]').change(function() {
            updateTypeForm();
        });
    });
</script>
</td></tr>
<tbody id="menu-type-form">
    {if $type_object = $elem->getTypeObject()}
        {include file="%menu%/form/menu/type_form.tpl"}
    {/if}
</tbody>
<tr><td>