{include file="head.tpl"}

<div class="progress-block" style="display:none">
    <p class="title">Идет обновление...</p>
    <div class="progress-container">
        <div class="progress-bar" style="width:0%"></div>
        <div class="percent">0%</div>
    </div>
    <div class="module">Подготовка к установке обновлений</div>
</div>
<div class="clear"></div>

{if count($data.updateData)}
<h3>{t d=count($data.updateData)}Доступно %d [plural:%d:обновление|обновления|обновлений]{/t}</h3>
<p>{t}Отметье модули, которые необходимо обновить{/t}</p>
<form method="POST" class="update-item-form">
    {$table->getView()}
</form>
{else}
<div class="system-updated">
    <h2>{t}Cистема не нуждается в обновлении{/t}</h2>
    <p>Все модули обновлены до последних версий</p>
</div>
{/if}