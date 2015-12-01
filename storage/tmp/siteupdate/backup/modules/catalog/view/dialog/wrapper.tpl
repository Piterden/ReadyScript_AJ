<div class="column-left">
    <div class="admin-category">
        <ul>
            <li class="endminus" qid="0"><img src="{$Setup.IMG_PATH}/adminstyle/minitree/folder.png">
            <input type="checkbox" value="0" {if $hideGroupCheckbox}style="display:none"{/if}><a class="act">Все</a>
            {include file="dialog/tree_branch.tpl" dirlist=$treeList open=true}
            </li>
        </ul>
    </div>
    
</div>
<div class="column-right">
    <form class="filter" onsubmit="return false;">
        <table width="100%">
            <tr>
                <td width="20">№:</td>
                <td width="60"><input type="text" class="field-id"></td>
                <td width="60">Название:</td>
                <td><input type="text" class="field-title"></td>
                <td width="60">Артикул:</td>
                <td><input type="text" class="field-barcode"></td>                
                <td>
                    <button type="submit" class="set-filter" title="фильтровать"></button>
                </td>
                <td>
                    <a class="clear-filter" title="очистить фильтр"></a>
                </td>
            </tr>
        </table>
        <input type="submit" style="display:none">
    </form>

    <div class="productblock">
        <div class="loader">
            <div class="overlay">&nbsp;</div>
            <div class="loooading">
                <div class="back"></div>
                <span>Загрузка...</span>
            </div>
        </div>    
        <div class="product-container">
        {$products}
        </div>
    </div>
</div>