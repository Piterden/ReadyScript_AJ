{addjs file="jquery.slider.min.js"}
{addjs file="history.min.js" basepath="common"}
{addjs file="{$mod_js}jquery.filter.js" basepath="root"}
{modulegetvars name="\Colors\Controller\BlockValues" var="colors"}
{assign var=catalog_config value=ConfigLoader::byModule('catalog')}

<section class="block filterSection">
    <div class="loadOverlay"></div>
    <form method="GET" class="filters" action="{urlmake f=null bfilter=null p=null}">
        <!-- {if $param.show_brand_filter && count($brands)>1}
            <div class="filter typeMultiselect">
                <h4>{t}Производитель{/t}:</h4>
                <ul>
                    {foreach $brands as $brand}
                    <li>
                        <input type="checkbox" {if is_array($basefilters.brand) && in_array($brand.id, $basefilters.brand)}checked{/if} name="bfilter[brand][]" value="{$brand.id}" class="cb" id="cb_{$brand.id}_{$smarty.foreach.i.iteration}">
                        <label for="cb_{$brand.id}_{$smarty.foreach.i.iteration}">{$brand.title}</label>
                    </li>
                    {/foreach}
                </ul>
            </div>
        {/if} -->
        {foreach $prop_list as $item}
            {if $item.group.title == 'Основные'}
                <div class="filtersGroup {$item.group.title|@translit}">
                {foreach $item.properties|@array_reverse:true as $prop}
                    {if $prop.type == 'int'}
                        <div class="filter typeInterval {$prop.title|@translit}">
                            <h4>{$prop.title}:</h4>
                            <table class="fullwidth typeInterval fromToLine">
                                <tbody>
                                    <tr>
                                        <td class="p50">от, {$prop.unit}</td>
                                        <td></td>
                                        <td class="p50">до, {$prop.unit}</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="textinp fromto" name="f[{$prop.id}][from]" value="{$filters[$prop.id].from|default:$prop.interval_from}" data-start-value="{$prop.interval_from}"></td>
                                        <td class="padd4">&mdash;</td>
                                        <td><input type="text" class="textinp fromto" name="f[{$prop.id}][to]" value="{$filters[$prop.id].to|default:$prop.interval_to}" data-start-value="{$prop.interval_to}"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" data-slider='{ "from":{$prop.interval_from}, "to":{$prop.interval_to}, "step": "{$prop.step}", "round": {$prop->getRound()}, "dimension": " {$prop.unit}", "heterogeneity": [{$prop->getHeterogeneity()}], "scale": [{$prop->getScale()}]  }' value="{$filters[$prop.id].from|default:$prop.interval_from};{$filters[$prop.id].to|default:$prop.interval_to}" class="pluginInput" data-start-value="{$prop.interval_from};{$prop.interval_to}">
                        </div>                
                    {elseif $prop.type == 'list'}
                        <div class="filter typeMultiselect {$prop.title|@translit}">
                            <h4>{$prop.title}:</h4>
                            <div class="multiselectList">
                                {foreach $prop->getAllowedValues() as $key => $value}
                                <div class="multiselectItem">
                                    <input type="checkbox" {if is_array($filters[$prop.id]) && in_array($value, $filters[$prop.id])}checked{/if} name="f[{$prop.id}][]" value="{$value}" class="cb" id="cb_{$prop.id}_{$value@iteration}">
                                    <div class="cb_{$prop.id}_{$value@iteration} multiselectItemInner" title="{$value}" {if $prop.title == 'Цвет'}style="background-color:#fff;background-image: linear-gradient( -45deg, {$colors.colors[$value].color2} 0%, {$colors.colors[$value].color2} 50%, {$colors.colors[$value].color1} 50%, {$colors.colors[$value].color1} 50%);width:40px;height:40px;text-indent:-9999px;"{/if}>{$value}</div>
                                </div>
                                {/foreach}
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    {elseif $prop.type == 'bool'}
                        <div class="filter bool {$prop.title|@translit}">
                            <h4>{$prop.title}:</h4>
                            <select class="yesno" name="f[{$prop.id}]" data-start-value="">
                                <option value="">{t}Неважно{/t}</option>
                                <option value="1" {if $filters[$prop.id] == '1'}selected{/if}>{t}Есть{/t}</option>
                                <option value="0" {if $filters[$prop.id] == '0'}selected{/if}>{t}Нет{/t}</option>
                            </select>
                        </div>
                    {else} {* string *}
                        <div class="filter string {$prop.title|@translit}">
                            <h4>{$prop.title}:</h4>
                            <input type="text" class="textinp string" name="f[{$prop.id}]" value="{$filters[$prop.id]}">
                        </div>
                    {/if}               
                {/foreach}
                {if $param.show_cost_filter && ($moneyArray.interval_to>$moneyArray.interval_from)}
                    <div class="filter typeInterval">
                        <h4>Цена:</h4>
                        <table class="fullwidth fromToLine">
                            <tr>
                                <td class="p50">от</td>
                                <td></td>
                                <td class="p50">до</td>
                            </tr>                
                            <tr>
                                <td><input type="text" class="textinp fromto" name="bfilter[cost][from]" value="{if !$catalog_config.price_like_slider}{$basefilters.cost.from}{else}{$basefilters.cost.from|default:$moneyArray.interval_from}{/if}" data-start-value="{if $catalog_config.price_like_slider}{$moneyArray.interval_from|intval}{/if}"></td>
                                <td class="padd4">&mdash;</td>
                                <td><input type="text" class="textinp fromto" name="bfilter[cost][to]" value="{if !$catalog_config.price_like_slider}{$basefilters.cost.to}{else}{$basefilters.cost.to|default:$moneyArray.interval_to}{/if}" data-start-value="{if $catalog_config.price_like_slider}{$moneyArray.interval_to|intval}{/if}"></td>
                            </tr>                    
                        </table>
                        {if $catalog_config.price_like_slider} {* Если нужно показать как слайдер*}
                            <input type="hidden" data-slider='{ "from":{$moneyArray.interval_from}, "to":{$moneyArray.interval_to}, "step": "{$moneyArray.step}", "round": {$moneyArray.round}, "dimension": " {$moneyArray.unit}", "heterogeneity": [{$moneyArray.heterogeneity}]  }' value="{$basefilters.cost.from|default:$moneyArray.interval_from};{$basefilters.cost.to|default:$moneyArray.interval_to}" class="pluginInput" data-closest=".fromToPrice" data-start-value="{$basefilters.cost.from|default:$moneyArray.interval_from};{$basefilters.cost.to|default:$moneyArray.interval_to}"/>
                        {/if}
                    </div>
                {/if}
                </div>
            {elseif $item.group.title == 'Спецификации'}
                <div class="filtersGroup {$item.group.title|@translit}">
                {foreach $item.properties as $prop}
                    {if $prop.type == 'int'}
                        <div class="filter typeInterval {$prop.title|@translit}">
                            <h4>{$prop.title}:</h4>
                            <table class="fullwidth typeInterval fromToLine">
                                <tbody>
                                    <tr>
                                        <td class="p50">от, {$prop.unit}</td>
                                        <td></td>
                                        <td class="p50">до, {$prop.unit}</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="textinp fromto" name="f[{$prop.id}][from]" value="{$filters[$prop.id].from|default:$prop.interval_from}" data-start-value="{$prop.interval_from}"></td>
                                        <td class="padd4">&mdash;</td>
                                        <td><input type="text" class="textinp fromto" name="f[{$prop.id}][to]" value="{$filters[$prop.id].to|default:$prop.interval_to}" data-start-value="{$prop.interval_to}"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" data-slider='{ "from":{$prop.interval_from}, "to":{$prop.interval_to}, "step": "{$prop.step}", "round": {$prop->getRound()}, "dimension": " {$prop.unit}", "heterogeneity": [{$prop->getHeterogeneity()}], "scale": [{$prop->getScale()}]  }' value="{$filters[$prop.id].from|default:$prop.interval_from};{$filters[$prop.id].to|default:$prop.interval_to}" class="pluginInput" data-start-value="{$prop.interval_from};{$prop.interval_to}">
                        </div>                
                    {elseif $prop.type == 'list'}
                        <div class="filter typeMultiselect {$prop.title|@translit}">
                            <h4>{$prop.title}:</h4>
                            <div class="multiselectList">
                                {foreach $prop->getAllowedValues() as $key => $value}
                                <div class="multiselectItem">
                                    <input type="checkbox" {if is_array($filters[$prop.id]) && in_array($value, $filters[$prop.id])}checked{/if} name="f[{$prop.id}][]" value="{$value}" class="cb" id="cb_{$prop.id}_{$value@iteration}">
                                    <div class="cb_{$prop.id}_{$value@iteration} multiselectItemInner" title="{$value}" {if $prop.title == 'Цвет'}style="background-color:#fff;background-image: linear-gradient( -45deg, {$colors.colors[$value].color2} 0%, {$colors.colors[$value].color2} 50%, {$colors.colors[$value].color1} 50%, {$colors.colors[$value].color1} 50%);width:40px;height:40px;text-indent:-9999px;"{/if}>{$value}</div>
                                </div>
                                {/foreach}
                                <div class="clearfix"></div>
                            </div>
                            <!-- <ul>
                                {foreach $prop->getAllowedValues() as $key => $value}
                                <li><input type="checkbox" {if is_array($filters[$prop.id]) && in_array($value, $filters[$prop.id])}checked{/if} name="f[{$prop.id}][]" value="{$value}" class="cb" id="cb_{$prop.id}_{$value@iteration}">
                                <label for="cb_{$prop.id}_{$value@iteration}">
                                    <div>{$value}</div>    
                                </label></li>
                                {/foreach}
                            </ul> -->
                        </div>
                    {elseif $prop.type == 'bool'}
                        <div class="filter bool {$prop.title|@translit}">
                            <h4>{$prop.title}:</h4>
                            <select class="yesno" name="f[{$prop.id}]" data-start-value="">
                                <option value="">{t}Неважно{/t}</option>
                                <option value="1" {if $filters[$prop.id] == '1'}selected{/if}>{t}Есть{/t}</option>
                                <option value="0" {if $filters[$prop.id] == '0'}selected{/if}>{t}Нет{/t}</option>
                            </select>
                        </div>
                    {else} {* string *}
                        <div class="filter string {$prop.title|@translit}">
                            <h4>{$prop.title}:</h4>
                            <input type="text" class="textinp string" name="f[{$prop.id}]" value="{$filters[$prop.id]}">
                        </div>
                    {/if}               
                {/foreach}
                </div>
            {/if}
        {/foreach}
        
        <div class="buttons">
            <input type="submit" class="button submitFilter" value="Применить">
            <a href="{urlmake f=null p=null bfilter=null}" class="button cleanFilter{if empty($filters) && empty($basefilters)} hidden{/if}">сбросить фильтр</a>
        </div> 
        
        <script type="text/javascript">
            $(function() {
                $('.typeInterval .pluginInput').each(function() {
                    var $this = $(this);
                    var fromTo = $this.siblings('.fromToLine').hide();
                    $this.jslider( $.extend( $(this).data('slider'), { callback: function(value) {
                        var values = value.split(';');
                        $('input[name$="[from]"]', fromTo).val(values[0]);
                        $('input[name$="[to]"]', fromTo).val(values[1]);
                        console.log($('input[name$="[from]"]', fromTo));
                        $this.trigger('change');
                    }}));
                    
                    $('input[name$="[from]"], input[name$="[to]"]', fromTo).change(function() {
                        var from = $('input[name$="[from]"]', fromTo).val();
                        var to = $('input[name$="[to]"]', fromTo).val();
                        $this.jslider('value', from, to);
                        console.log('change',from,to);
                    });
                });

                $('.multiselectItemInner').bind('click', function() {
                    var $this = $(this);
                    $this.parent().toggleClass('active');
                    $this.prev('input').trigger('click');
                    console.log($this);
                });
                
                $('.multiselectItem input').each(function() {
                    if ($(this).attr('checked') == 'checked') {
                        $(this).parent().addClass('active');
                    }
                });

                $('.cleanFilter').bind('click', function() {
                    $('.multiselectItem').removeClass('active');
                });
            });
        </script>        
    </form>
</section>