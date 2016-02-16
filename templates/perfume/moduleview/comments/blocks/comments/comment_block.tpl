{addjs file="{$mod_js}comments.js" basepath="root"}

<div class="commentBlock">
    <a name="comments"></a>
    <div class="commentFormBlock{if !empty($error) || !$total} open{/if}">
        {if $mod_config.need_authorize == 'Y' && !$is_auth}
            <span class="needAuth">Чтобы оставить отзыв необходимо <a href="{$router->getUrl('users-front-auth', ['referer' => $referer])}" class="inDialog">авторизоваться</a></span>
        {else}
            <a href="#" class="colorButton handler" onclick="$(this).closest('.commentBlock').toggleClass('open');return false;">Написать отзыв и оценить товар</a>
            <div class="caption">
                Оставить отзыв о товаре
                <a class="close"></a>
            </div>                                                
            <form method="POST" class="formStyle" action="#comments">
                {if !empty($error)}
                    <div class="errors">
                        {foreach $error as $one}
                        <p>{$one}</p>
                        {/foreach}
                    </div>
                {/if}                            
                {$this_controller->myBlockIdInput()}
                <div class="oh">
                    <div class="rating">
                        <p>Ваша оценка</p>
                        <input class="inp_rate" type="hidden" name="rate" value="{$comment.rate}">                        
                        <div class="starsBlock">
                            <i></i>
                            <i></i>
                            <i></i>
                            <i></i>
                            <i></i>
                        </div>
                        <div class="desc">{$comment->getRateText()}</div>
                    </div>                                    
                    <div class="formWrap">
                        <i class="corner"></i>
                        <textarea name="message">{$comment.message}</textarea>
                    </div>
                    {if $already_write}<div class="already">Разрешен один отзыв на товар, предыдущий отзыв будет заменен</div>{/if}                    
                </div>
                <p class="name">
                    <label>Ваше имя</label>
                    <input type="text" name="user_name" value="{$comment.user_name}">
                </p>
                {if !$is_auth && ModuleManager::staticModuleEnabled('kaptcha')}
                <div class="captcha">
                    <label class="fielName">&nbsp;</label><br>
                    <img src="{$router->getUrl('kaptcha')}">
                    <input type="text" name="captcha" class="inpCap"> 
                    <span class="fielName">Введите код, указанный на картинке</span>
                </div>
                {/if}                 
                <div class="buttons">
                    <input type="submit" value="Оставить отзыв">
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
        <a data-pagination-options='{ "appendElement":".commentList" }' data-href="{$router->getUrl('comments-block-comments', ['_block_id' => $_block_id, 'cp' => $paginator->page+1, 'aid' => $aid])}" class="button oneMore ajaxPaginator">еще комментарии...</a>
    {/if}    
</div>
<script type="text/javascript">
    $(function() {
        $('.commentBlock').comments({
            stars: '.starsBlock i',
            rateDescr: '.rating .desc'
        });
    });
</script>