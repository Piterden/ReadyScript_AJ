{addcss file="{$mod_css}package.css" basepath="root"}
{addjs file="jquery.tablednd.js" basepath="common"}
{addjs file="{$mod_js}package.js" basepath="root"}
{addjs file="tmpl.min.js" basepath="common"}
<div id="packageblock">
    <div class="package-tools">
        <div class="package-actions">
            <a class="add-package underline"><span>Добавить комплектацию</span></a>
            <span class="success-text">Комплектация успешно добавлена</span>
        </div>
        <div class="package-form" style="display:none" data-base-currency="{$elem->getCurrency()}">
            <table class="package-table">
                <tr>
                    <td class="key">Название</td>
                    <td><input type="text" class="p-title"><span class="field-error" data-field="title"></span><br>
                        <span class="fieldhelp">Например: "Цвет"</span>
                    </td>
                </tr>
                <tr>
                    <td class="key">Тип</td>
                    <td><select name="p-type" class="p-type">
                            <option value="list">Список</option>            
                            <option value="string">Строка</option>
                        </select><br>
                        <span class="fieldhelp type-list">
                            Во время просмотра товара, пользователь сможет выбирать из списка <br>
                            вариант комплектации товара. Для каждой комплектации может меняться стоимость товара.<br>
                            Например для комплектации "Цвет" могут быть заданы 
                            варианты "Синий + 30% стоимости", "Белый + 0 руб", и.т.д
                        </span>
                        <span class="fieldhelp type-string" style="display:none">
                            Пользователь сможет сам указать комплектацию прямо в корзине,
                            напротив наименования товара.
                        </span>
                    </td>
                </tr>
                <tr class="p-values-block">
                    <td class="key">Возможные значения</td>
                    <td>
                        <table class="package-list-items">
                            <thead>
                            <tr>
                                <td>значение</td>
                                <td>Артикул</td>
                                <td></td>
                                <td>увеличение стоимости</td>
                                <td>единицы</td>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" align="right">
                                        <a href="JavaScript:;" class="add-line underline"><span>Добавить строку</span></a>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <a class="add">Добавить к товару</a>
                        <a class="close">Свернуть</a>
                    </td>
                </tr>                        
            </table>
        </div>
    </div>

    {include file="package_product.tpl"}
</div>

{strip}{literal}
<script type="text/x-tmpl" id="tmpl-line">
    <tr class="line">
        <td>
            <input type="text" class="p-val-name" value="{%=o.name%}">
        </td>
        <td>
            <input type="text" class="p-val-code" value="{%=o.code%}">
        </td>        
        <td width="35">
            <select class="p-val-znak" {% if (o.isFirst) { %}disabled="disabled"{% } %}>
                {% for (var i=0; i<o.list.znak.length; i++) { %}
                <option {% if (o.list.znak[i] == o.znak) { %}selected{% } %}>{%=o.list.znak[i]%}</option>
                {% } %}
            </select>
        </td>
        <td width="45">
            <input type="text" class="p-val" value="{%=o.val%}" {% if (o.isFirst) { %}disabled="disabled"{/if}{% } %}>
        </td>
        <td>
            <select class="p-val-type" {% if (o.isFirst) { %}disabled="disabled"{% } %}>
                {% for (var i=0; i<o.list.type.length; i++) { %}
                <option {% if (o.list.type[i] == o.type) { %}selected{% } %}>{%=o.list.type[i]%}</option>
                {% } %}
            </select>
        </td>
        <td>
            {% if (!o.isFirst) { %}
            <a class="del-line">удалить</a>
            {% } %}
        </td>
        <td>
            <span class="field-error">
                <span class="text"><i class="cor"></i>{%=lang.t('Укажите значение')%}</span>
            </span>
        </td>
    </tr>
</script>

<script type="text/x-tmpl" id="tmpl-value-line">
    <tr class="package-item">
        <td class="sort-td"><div class="sort"></div></td>
        <td class="title-td">
        <input type="hidden" value="{%=o.title%}" name="other[package][{%=o.title%}][title]" class="h-title">
        <input type="hidden" value="{%=o.type%}" name="other[package][{%=o.title%}][type]" class="h-type">
        {%=o.title%}</td>
        <td>{%=lang.t('Тип:')%}{%=o.type_str%}</td>
        <td>
            {% if (o.values) { %}
            <table class="vtable">
                <tbody>
                    {% for (var i=0; i<o.values.length; i++) { %}
                    <tr>
                        <td>
                        <input type="hidden" value="{%=o.values[i].name%}#{%=o.values[i].znak%}#{%=o.values[i].val%}#{%=o.values[i].type%}#{%=o.values[i].code%}" name="other[package][{%=o.title%}][vals][]" class="h-val">
                        {%=o.values[i].name%} ({%=o.values[i].code%})</td>
                        <td class="c2">{%=o.values[i].znak%}{%=o.values[i].val%} {%=o.values[i].type%}</td>
                    </tr>
                    {% } %}
                </tbody>
            </table>        
            {% } %}
        </td>
        <td class="item-tools">
            <a class="edit"></a>
            <a class="delete">{%=lang.t('удалить')%}</a>                            
        </td>
    </tr>
</script>

{/literal}{/strip}