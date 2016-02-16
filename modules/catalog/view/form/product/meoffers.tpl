{addcss file="{$mod_css}offer.css" basepath="root"}
{addjs file="jquery.tablednd.js" basepath="common"}
{addjs file="{$mod_js}meoffer.js?v=5" basepath="root"}
{addjs file="tmpl.min.js" basepath="common"}
{assign var=currencies value=$elem->getCurrencies()}
{static_call var=default_currency callback=['\Catalog\Model\CurrencyApi', 'getBaseCurrency']}

<div id="offersME">
    <table class="otable">                                              
        <tr class="editrow">
            <td class="ochk" width="20">
                <input title="{t}Отметьте, чтобы применить изменения по этому полю{/t}" type="checkbox" class="doedit" name="doedit[]" value="{$elem.__offer_caption->getName()}" {if in_array($elem.__offer_caption->getName(), $param.doedit)}checked{/if}></td>
            <td class="otitle">{$elem.__offer_caption->getTitle()}</td>
            <td>
                <div class="multi_edit_rightcol coveron">
                    <div class="cover"></div>
                    {include file=$elem.__offer_caption->getRenderTemplate(true) field=$elem.__offer_caption}
                </div>        
            </td>
        </tr>    
        <tr class="editrow">
            <td class="ochk" width="20">
                <input title="{t}Отметьте, чтобы применить изменения по этому полю{/t}" type="checkbox" class="doedit" name="doedit[]" value="{$elem.___offers_->getName()}" {if in_array($elem.___offers_->getName(), $param.doedit)}checked{/if}></td>
            <td class="otitle"></td>
            <td>
                <div class="multi_edit_rightcol coveron">
                    <div class="cover"></div>
                    {include file="multioffers_form.tpl"}
                </div>        
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">
    $.allReady(function() {
        $('#offersME').offerme();
    });    
</script>    