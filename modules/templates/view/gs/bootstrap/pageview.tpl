{$device=$smarty.cookies["page-constructor-device"]}
{if !$device}{$device="md"}{/if}
<ul class="device-selector">
    <li {if $device == 'xs'}class="act"{/if} data-device="xs"><i class="device-xs"></i> {t}Телефон{/t}</li>
    <li {if $device == 'sm'}class="act"{/if} data-device="sm"><i class="device-sm"></i> {t}Планшет{/t}</li>
    <li {if $device == 'md'}class="act"{/if} data-device="md"><i class="device-md"></i> {t}Настольный ПК{/t}</li>
    <li {if $device == 'lg'}class="act"{/if} data-device="lg"><i class="device-lg"></i> {t}Большое устройство{/t}</li>
</ul>
<div class="bg bootstrap">
    <div class="pageview {$device}">
        {include file="%templates%/gs/containers.tpl" 
                 section_tpl="%templates%/gs/bootstrap/section.tpl"}
    </div>   
</div>