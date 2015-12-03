<style type="text/css">
    .imlTable td{
       vertical-align:top; 
    }
    
    .imlTable .left{
        width:300px;
    }
    
    .imlTable .left select{
       font-size:11px; 
    }
    
    .imlTable .servicesList{
        width:300px;
        height:220px !important;
    }
    
    .imlTable .center{
        vertical-align:middle;
        width:20px;
    }
    
    
    .imlTable .right select{
        height: auto !important;
        width:350px;
        font-size:11px;
    }
    
    .imlTable a{
        width:16px;
        height:16px;
        display:inline-block;
        margin:5px;
        cursor:pointer;
        background-position:top left;
        background-repeat:no-repeat;
    }
    
    .imlTable .up{
        background-image:url(../../resource/img/adminstyle/arrows/arrow-up.png);
    }
    
    .imlTable .addService{
        background-image:url(../../resource/img/adminstyle/arrows/arrow-left.png);
    }
    
    .imlTable .down{
        background-image:url(../../resource/img/adminstyle/arrows/arrow-down.png);
    }
    
    .imlTable .del{
        background-image:url(../../resource/img/adminstyle/arrows/remove.png);
    }
</style>
<table class="imlTable">
    <tr>
        <td colspan="3"><span class="inlineError" style="visibility:hidden">Добавьте в список элемент из правого списка</span></td>
    </tr>
    <tr>
        <td class="left">
            <div>
                {static_call var=list callback=['\Imldelivery\Model\DeliveryType\Iml','staticGetServices']}
                <select class="servicesList selectAllBeforeSubmit" name="data[service_id][]" multiple="multiple"> 
					{$selected = $elem.service_id} 
					{if !empty($list)}
						{foreach $list as $code => $title}
							{if in_array($code, $selected)}
								<option value="{$code}">{$title}</option>
							{/if}
						{/foreach} 
					{/if}
                </select>
            </div>
        </td>
        <td class="center">
            <a class="up btn" title="Поднять" style="visibility:hidden;"></a>
            <a class="addService" title="Добавить"></a>
            <a class="down btn" title="Опустить" style="visibility:hidden;"></a>
            <a class="del btn" title="Удалить" style="visibility:hidden;"></a>
        </td>
        <td class="right">
            {$elem->__service_id_all->formView()}
        </td>
    </tr>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        var imlTable = $(".imlTable");
        
        
        function checkImlServiceVisibility(){
            //Проверим есть ли назначенный хоть один тариф
            if ($(".servicesList option",$(".imlTable")).length==0){
               $(".inlineError",$(".imlTable")).css('visibility','visible'); 
            }else{
               $(".inlineError",$(".imlTable")).css('visibility','hidden');  
            } 
        }
        
        /**
        * Удяляет варианты уже выбранные из общего списка
        *
        */
        function shiftServiceSelectRowPresent(){
            var selected = [];
            {if !empty($selected)}
                {foreach $selected as $k=>$code}
                   selected[{$k}] = "{$code}"; 
                {/foreach}
            {/if}
            
            if (selected.length>0){
                for(var i=0;i<selected.length;i++){
                    $(".right select option[value='"+selected[i]+"']",imlTable).remove();
                }
            }
        }
        
        
        /**
        * Добавление в список тарифов с приоритетами
        *
        */
        $(".addService",imlTable).on('click',function(){
            
            var option = $(".right select option:selected", imlTable);
            //Удалим из общего списка
            //if (typeof(option.data('group'))=='undefined'){
            //   option.data('group',option.closest('optgroup').attr('label')); 
            //}
            option.appendTo($(".servicesList", imlTable));
            //Посмотрим нужно ли показывать кнопки
            if ($(".servicesList option", imlTable).length>0){ //Есть такого пункта нет, то добавим его
               $(".btn",imlTable).css('visibility','visible'); 
            }else{
               $(".btn",imlTable).css('visibility','hidden');  
            }
            checkImlServiceVisibility();
            return false;
        });
        
        /**
        * Удаление из списоков тарифов с приоритетами
        *
        */
        $(".del",imlTable).on('click',function(){
            $(".servicesList option:selected", imlTable).each(function(){
                var group = $(this).data('group');
                if ($('.right select optgroup').length > 0) {
                    $(this).prependTo($(".right select optgroup[label*='"+group+"']",imlTable));
                } else {
                    $(this).prependTo($('.right select'),imlTable);
                }
            });
            
            //Посмотрим нужно ли показывать кнопки
            if ($(".servicesList option", imlTable).length>0){ //Есть такого пункта нет, то добавим его
               $(".btn",imlTable).css('visibility','visible'); 
            }else{
               $(".btn",imlTable).css('visibility','hidden');  
            }
            checkImlServiceVisibility();
            return false;
        });
        
        /**
        * Перемещение в списке вверх
        *
        */
        $(".up",imlTable).on('click',function(){
            var selected = $(".servicesList option:selected:eq(0)", imlTable);
            var index    = selected.index();
            
            if (index>0){
               selected.insertBefore($(".servicesList option:eq("+(index-1)+")", imlTable)); 
            }
            return false;
        });
        
        /**
        * Перемещение в списке вниз
        *
        */
        $(".down",imlTable).on('click',function(){
            var selected = $(".servicesList option:selected:eq(0)", imlTable);
            var index    = selected.index()+1;
            
            if (index < ($(".servicesList option", imlTable).length)){
               selected.insertAfter($(".servicesList option:eq("+index+")", imlTable));  
            }
            return false;
        });
        
        shiftServiceSelectRowPresent();
        checkImlServiceVisibility();
        
        //Посмотрим нужно ли показывать кнопки
        if ($(".servicesList option", imlTable).length>0){ //Есть такого пункта нет, то добавим его
           $(".btn",imlTable).css('visibility','visible'); 
        }else{
           $(".btn",imlTable).css('visibility','hidden');  
        }
        
        
});
</script>
