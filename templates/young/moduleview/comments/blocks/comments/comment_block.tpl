{addjs file="{$mod_js}comments.js" basepath="root"}
<div class="comments{if !empty($error) || !$total} on{/if}">
    <a name="comments"></a>    
    <div class="commentHead">
        <h3>Отзывы {if $total}({$total}){/if}</h3>
        <a href="#" class="addComment" onclick="$(this).closest('.comments').toggleClass('on');return false;">Написать отзыв</a>
    </div>  
    <div class="writeComment">
        {if $mod_config.need_authorize == 'Y' && !$is_auth}
            <span class="needAuth">Чтобы оставить отзыв необходимо авторизоваться</span>
        {else}
            {if !empty($error)}
                <div class="errors">
                    {foreach $error as $one}
                    <p>{$one}</p>
                    {/foreach}
                </div>
            {/if}                    
        
            <form method="POST" class="formStyle">               
                {$this_controller->myBlockIdInput()}
                <div class="message">
                    <i class="corner"></i>
                    <textarea name="message">{$comment.message}</textarea>
                </div>
                {if $already_write}<div class="already">Разрешен один отзыв на товар, предыдущий отзыв будет заменен</div>{/if}
                <div class="rating">
                    <input class="inp_rate" type="hidden" name="rate" value="{$comment.rate}">
                    <span class="text">Ваша оценка</span> 
                    <span class="rate">
                        <span class="stars">
                            <i></i>
                            <i></i>
                            <i></i>
                            <i></i>
                            <i></i>
                        </span>
                    </span>
                </div>
                <div class="nameBlock">
                    <i class="lines"></i>
                    <div class="oh {if $is_auth}authorized{/if}">
                        <div class="name">
                            <label class="fielName">Ваше имя</label><br>
                            <input type="text" name="user_name" value="{$comment.user_name}">
                        </div>
                        {if !$is_auth && ModuleManager::staticModuleEnabled('kaptcha')}
                        <div class="captcha">
                            <label class="fielName">&nbsp;</label><br>
                            <img src="{$router->getUrl('kaptcha')}">
                            <input type="text" name="captcha" class="inpCap"><br>
                            <span class="fielName">Введите код, указанный на картинке</span>
                        </div>
                        {/if}                    
                    </div>
                </div>
                <div class="buttons">
                    <input type="submit" value="Отправить">
                </div>
            </form>
        {/if}
    </div>
    {if $total}
    <ul class="commentList">
        {$list_html}
    </ul>
    {else}
        <div class="noComments">нет отзывов</div>
    {/if}
    {if $paginator->total_pages > $paginator->page}
        <a data-pagination-options='{ "appendElement":".commentList" }' data-href="{$router->getUrl('comments-block-comments', ['_block_id' => $_block_id, 'cp' => $paginator->page+1, 'aid' => $aid])}" class="oneMore ajaxPaginator">еще комментарии...</a>
    {/if}
</div>
