<p>Подождите идёт перенаправление...</p>
<form id="printFormCdek" action="http://gw.edostavka.ru:11443/orders_print.php" method="POST">
    <textarea name="xml_request" style="display:none">{$xml}</textarea>
    <input type="submit" value="Отправить" style="display:none"/>
</form>
<script type="text/javascript">
    $("#printFormCdek").submit();
    setTimeout('$(".ui-dialog-titlebar-close").click()',3000);
</script>