<div class="supportBlock">
    <div class="supportChat">
        {foreach from=$list item=item}
            {assign var=user value=$item->getUser()}
            {if $item.is_admin}
                <div class="answer">
                    <div class="tip"></div>
                    <div class="date">
                        <span><strong>{$user.name} {$user.surname}, администратор.</strong> {$item.dateof|dateformat:"%e %v %Y, в %H:%M"}</span>
                    </div>
                    <div class="text">
                        {$item.message}
                    </div>
                </div>
            {else}
                <div class="quest">
                    <div class="date">
                        <span><strong>Вы писали</strong> {$item.dateof|dateformat:"%e %v %Y, в %H:%M"}</span>
                    </div>
                    <div class="text">
                        {$item.message}
                    </div>
                </div>
            {/if}
        {/foreach}
    </div>
    
    <form method="POST" class="supportForm adaptForm">
    
        {if count($supp->getErrors())>0}
            <div class="error">
                {foreach from=$supp->getErrors() item=err}
                <p>{$err}</p>
                {/foreach}
            </div>
        {/if}    
    
        <label class="caption">Ваше сообщение</label><br>
        <div class="wideForm">
            {$supp.__message->formView()}
        </div>
        <div class="submit">
            <input type="submit" value="Отправить">
        </div>
    </form>
</div>