{addcss file="{$mod_css}tax.css" basepath="root"}
{addjs file="{$mod_js}jquery.tax.js" basepath="root"}
{addjs file="tmpl.min.js" basepath="common"}

{assign var=tax_regions value=$elem->getTaxRegions()}
<div id="ratesBlock">
    <div class="toolsBlock">
        <div class="actions">
            <a class="add underline"><span>Добавить ставку налога для местоположения</span></a>
            <span class="successText">Ставка успешно добавлена</span>
        </div>
        
        <div class="form">
            <table width="100%" class="some-property-table">
                <tr>
                    <td class="key">Местоположение
                    <div class="fieldhelp">Удерживая CTRL можно выбрать несколько элементов</div></td>
                    <td>
                    <input type="checkbox" id="onlyCountries"> <label for="onlyCountries">Отображать только страны</label><br>
                    <select class="p-regions" size="20" style="height:250px;" multiple="multiple">
                        {foreach from=$tax_regions item=item}
                            <option value="{$item.id}" class="{if $item.parent_id>0}region{/if}">{$item.display_title}</option>
                        {/foreach}
                    </select></td>
                </tr>
                <tr>
                    <td class="key">Ставка налога, %</td>
                    <td><input type="text" class="p-rate"></td>
                </tr>                            
                <tr>
                    <td></td>
                    <td>
                        <a class="addRate">Добавить</a>
                        <a class="close">свернуть</a>
                    </td>
                </tr>                        
            </table>
        </div>
    </div>
    
    <table class="toolsContainer">
        <thead class="head">
            <tr>
                <td>Местоположение</td>
                <td>Ставка, %</td>
            </tr>
        </thead>
        <tbody class="overable" id="rateItems">
            {foreach from=$elem.rates key=region_id item=rate}
            <tr data-id="{$region_id}" class="rateItem">
                <td>{$tax_regions[$region_id].display_title}</td>
                <td><input type="text" name="rates[{$region_id}]" value="{$rate}"></td>
                <td class="item-tools"><a class="p-del">удалить</a></td>
            </tr>            
            {/foreach}
        </tbody>
    </table>    
</div>

{literal}
<script type="text/x-tmpl" id="tmpl-rate-line">
    <tr data-id="{%=o.region_id%}" class="rateItem">
        <td>{%=o.region%}</td>
        <td><input type="text" name="rates[{%=o.region_id%}]" value="{%=o.rate%}"></td>
        <td class="item-tools"><a class="p-del">удалить</a></td>
    </tr>
</script>
{/literal}