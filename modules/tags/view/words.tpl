{if !empty($word_list)}
    <ul class="taglist">
        {foreach from=$word_list item=item}
        <li><div class="padd">{$item.word|escape}<sup><a class="tagdel" href="JavaScript:;" data-lid="{$item.lid}" title="Удалить">x</a></sup></div></li>
        {/foreach}
    </ul>
{else}
    <div class="notags">
        Не добавлено ни одного тега
    </div>
{/if}
        