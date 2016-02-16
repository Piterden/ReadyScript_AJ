{if $Setup.DETAILED_EXCEPTION}
    {addcss file="user/errorblocks.css" basepath="common"}
    <div class="comError">
        <div>
            <strong>{$exception->getMessage()}</strong><br>
            Ошибка в контроллере: {$controllerName}<br>
            <a href="JavaScript:;" onclick="document.getElementById('{$uniq}').style.display='block'; this.style.display = 'none'">подробнее</a>
            <div class="more" id="{$uniq}">
                Код ошибки:{$exception->getCode()}<br>
                Тип ошибки:{$type}<br>
                Файл:{$exception->getFile()}<br>
                Строка:{$exception->getLine()}<br>
                Стек вызова: <pre>{$exception->getTraceAsString()}</pre><br>          
            </div>
                    
        </div>
    </div>
{else}
    <!-- Исключение в модуле {$controllerName} -->
{/if}