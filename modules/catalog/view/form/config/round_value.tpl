{$el=$elem.__update_price_round_value}
<input type="text" maxlength="{$el->getMaxLength()}" disabled="disabled" value="{$el->get()}" name="{$el->getName()}"/>

<script type="text/javascript">
    $(function(){
       //Если округлять опция включена 
       if ($("[name='update_price_round']").prop('checked')){
         $("[name='update_price_round_value']").prop('disabled',false);  
       } 
       $("[name='update_price_round']").on('click',function(){
           //Если округлять опция включена 
           if ($(this).prop('checked')){
              $("[name='update_price_round_value']").prop('disabled',false); 
           }else{
              $("[name='update_price_round_value']").prop('disabled',true); 
           }
       });
    });
</script>