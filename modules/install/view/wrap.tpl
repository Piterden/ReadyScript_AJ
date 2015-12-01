{strip}
{addjs file="jquery.min.js" basepath="common"}
{addjs file="ui.jquery.all.min.js" basepath="common"}
{addjs file="{$mod_js}install.js" basepath="root"}
{addcss file="{$mod_css}install.css" basepath="root"}
{/strip}
<div class="install action-step{$step}" data-current-step="{$step}">
    <div class="topline">
        <div class="viewport">
            <a href="" class="brand"></a>
            <span class="wizard">{t}Мастер установки{/t}</span>            
            <span class="version">{t}Версия{/t} {$Setup.VERSION}</span>
        </div> <!-- .viewport -->
    </div> <!-- .topline -->
    <div class="viewport">
        <table class="steps">
            <tr>
                {assign var=step value=intval($step)}
                <td width="188" class="step1{if $step==1} current{/if}{if $step==2} pre{/if}{if $step>1} ready{/if}"><span>{t}Лицензионное соглашение{/t}</span></td>
                <td width="188" class="step2{if $step==2} current{/if}{if $step==3} pre{/if}{if $step>2} ready{/if}"><i></i><span>{t}Проверка параметров сервера{/t}</span></td>
                <td width="188" class="step3{if $step==3} current{/if}{if $step==4} pre{/if}{if $step>3} ready{/if}"><span>{t}Конфигурирование системы{/t}</span></td>
                <td width="188" class="step4{if $step==4} current{/if}{if $step==5} pre{/if}{if $step>4} ready{/if}"><span>{t}Установка лицензии{/t}</span></td>
                <td class="step5 last{if $step==5} current{/if}"><span>{t}Завершение{/t}</span></td>
            </tr>
        </table>
    </div>
    
    <div class="viewport">
        <div class="workzone">
            <i class="corner ps{$step}"></i>
            {block name="content"}{/block}
        </div>
    </div>
</div>
{block name="root"}{/block}
<div class="footline">
    <div class="viewport">
        <span class="copy">Copyright &copy; {"NOW"|dateformat:"Y"} ReadyScript</span>
    </div>
</div>