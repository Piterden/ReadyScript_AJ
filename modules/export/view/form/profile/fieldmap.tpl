<div style="min-width: 300px;"></div>

<div class="hidden">
    <table>
    <tr class="example">
        <td title="Обязательное поле">
            <span class="title">Пример назв. поля</span>
            <span class="required hidden" style="color:red;"> * </span>
        </td>
        <td style="padding:0">
            <select>
                <option value="0">- Не выводить -</option>
                <option value="-1">- Задано вручную -</option>
                {foreach from=$elem->getAllPropertyGroups() item=group}
                    <optgroup label="{if $group->title}{$group->title}{else}Основные{/if}">
                        {foreach from=$group->getChildren() item=prop}
                            <option value="{$prop->id}">{$prop->title}</option>
                        {/foreach}
                    </optgroup>
                {/foreach}
            </select>
            <input type="text" class="hidden">
        </td>
    </tr>
    </table>
</div>


<table class="fields">
    <!-- Сюда будут вставлены ряды таблицы с селектами соотвествия полей в выгрузке свойствам товара -->
</table>


<script>
    $.allReady(function(){
        var offerTypes = {$elem->getTypeObject()->getOfferTypesJson()}
        var fieldmap   = {$elem->getTypeObject()->getFieldMapJson()}
        
        $('select[name="data[offer_type]"]').change(function(){
            var offer_type = $(this).val();
            
            // Список полей для этого "Типа описания"
            var fields = offerTypes[offer_type];
            
            var $fields = $(".fields");
            $fields.html("");
            
            // Для каждого поля
            for(var key in fields){
                var name = fields[key].title;
                var $example = $('.example').clone().removeClass("example");                            // Клонируем шаблон TR
                $('.title', $example).html(name);                                                       // Устанавливаем название поля
                $('select', $example).prop('name', 'data[fieldmap]['+key+'][prop_id]');                 // Устанавливаем имя select-а
                // Если поле "обязательное"
                if(fields[key].required){
                    $('.required', $example).attr('title', lang.t('Обязательное поле'));      
                    $('.required', $example).show();
                    $('option[value=0]', $example).remove();  // Удаляем вариант "Не выводить"
                }
                
                try{
                    $('option[value='+fieldmap[key]['prop_id']+']', $example).prop("selected", true);
                }catch(e){}
                
                // Устанавливаем имя поля "Значение по умолчанию" (оно же "Значение")
                $('input', $example).prop('name',  'data[fieldmap]['+key+'][value]');
                
                // Пытаемся заполнить значением из сохраненных данных в fieldmap
                try{
                    $('input', $example).val(fieldmap[key]['value']);
                }
                catch(e){
                    
                }

                $fields.append($example);
                // На изменение select-а выбора соответствия
                $('select', $example).change(function(){
                    // Если выбран пункт "Не выводить"
                    if($(this).val() == 0){
                        $('input', $(this).parent()).hide();
                    }
                    else{
                        $('input', $(this).parent()).show();
                        // Если выбран пункт "Задано вручную"
                        if($(this).val() == -1){
                            $('input', $(this).parent()).deftext({
                                text: lang.t('Значение'),
                                force: true
                            });
                        }
                        else{
                            $('input', $(this).parent()).deftext({
                                text: lang.t('Значение по умолчанию'),
                                force: true
                            });
                        }
                    }
                }).change();
            }
            
            
            $fields.trigger('new-content');
        }).change();
        
    });    
</script>