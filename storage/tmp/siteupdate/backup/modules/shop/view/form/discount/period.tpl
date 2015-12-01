<input type="radio" name="period" value="forever" {if $elem.period != 'timelimit'}checked{/if} id="forever"> <label for="forever">Не ограничена по времени</label><br>
<input type="radio" name="period" value="timelimit" {if $elem.period == 'timelimit'}checked{/if} id="timelimit"> <label for="timelimit">Действует до</label>
{include file=$elem.__endtime->getRenderTemplate() field=$elem.__endtime}