<style type="text/css">
    .categoryCodeTitle{
        padding: 10px 0;
        font-size: 12px;
        font-weight: bold;    
    }
</style>
<div id="paymentTypeYandex">
    {$elem.__payment_type->formView()}
</div>
<div id="categoryCodeYandex" style="display: none;">
    <div class="categoryCodeTitle">
        {t}Ниже характеристика отвечающая за категорию товаров по версии Яндекс для банков<br/>
        Смотрите <a href="https://money.yandex.ru/i/forms/types_of_products.xls" target="_blank">https://money.yandex.ru/i/forms/types_of_products.xls</a><br/>
        (Значением характеристики должно быть числовое из списка выше){/t}
    </div>
    {$elem.__category_code->formView()}
</div>
<script type="text/javascript">
   $(document).ready(function(){
       /**
       * Смена списка Типа метода оплаты
       */
       $("#paymentTypeYandex select").on('change', function(){
           $("#categoryCodeYandex").toggle(($(this).val()=='KV'));
       }); 
       $("#categoryCodeYandex").toggle(($("#paymentTypeYandex select").val()=='KV'));  
   });
</script>