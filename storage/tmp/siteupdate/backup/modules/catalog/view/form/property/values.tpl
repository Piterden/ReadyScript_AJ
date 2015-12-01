<div id="values_row" {if $elem.type != 'list'}style="display:none"{/if}>
    {$elem.__values->getTitle()}<br>
    {include file=$elem.__values->getRenderTemplate() field=$elem.__values}
    <br>
    <a data-href="{adminUrl do=ajaxGetValues property_id=$elem.id}" class="uline getValues">Получить значения у товаров</a>
</div>

<script>
$(function() {
    $('select[name="type"]').change(function() {
        var type = $(this).val();
        $('#values_row').toggle(type == 'list');
    }).change();
    
    $('#values_row .getValues').click(function() {
        $.ajaxQuery({
            url: $(this).data('href'),
            success: function(response) {
                $('#values_row [name="values"]').text(response.values);
            }
        });
    });
});
</script>