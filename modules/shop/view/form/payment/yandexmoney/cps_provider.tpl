<div id="terminalType" style="display:none;">
   {include file=$field->getOriginalTemplate()} 
</div>
<div id="teminalMessage">
    Только для оплаты терминалами
</div>


<script type="text/javascript">
   $(document).ready(function(){
       //Проверим были ли выбраны терминалы
       if ($("select[name='data[payment_type]']").val()=='GP'){
          $("#terminalType").show(); 
          $("#teminalMessage").hide(); 
       }
        
       $(".admin-style").on('change',"select[name='data[payment_type]']",function(){ 
           if ($(this).val()=='GP'){
              $("#terminalType").show();
              $("#teminalMessage").hide();   
           }else{
              $("#terminalType").hide();  
              $("#teminalMessage").show(); 
              $("select[name='data[cps_provider]'] option").removeAttr('selected');  
           }
       });
   });
</script>