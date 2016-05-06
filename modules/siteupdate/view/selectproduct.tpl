{include file="head.tpl"}

<div class="sp-block">
    <p class="sp-text">Текущая комплектация системы: <strong>{$Setup.SCRIPT_TYPE}</strong>.<br> 
    Вы можете применить обновление следующих комплектаций продукта:</p>
    <select id="update-product">
        {foreach from=$data.products item=item}
        <option {if $item === $Setup.SCRIPT_TYPE}selected{/if}>{$item}</option>
        {/foreach}
    </select>
    <a href="{adminUrl do="selectProduct"}" class="button save submit">Выбрать</a>
    <br>
    <p>При выборе более старшей комплектации, произойдет обновление системы до соответствующей комплектации.</p>
</div>
