{addjs file="{$mod_js}comments.js" basepath="root"}
<section class="comments">
    <div class="head">
        <span class="text">Отзывы({$total})</span>
    </div>
    <div class="writeComment{if !empty($error)} on{/if}">
    <a name="comments"></a>
        {if $mod_config.need_authorize == 'Y' && !$is_auth}
        <span class="needAuth">Чтобы оставить отзыв необходимо авторизоваться</span>
        {else}
        <a class="title rs-parent-switcher">написать отзыв</a>
        <form method="POST">
            {$this_controller->myBlockIdInput()}
            <i class="corner"></i>
            <ul class="adaptForm">
                {if !empty($error)}
                    <li class="error">
                        {foreach from=$error item=one}
                        <p>{$one}</p>
                        {/foreach}
                    </li>
                {/if}
                {if $already_write}<li>Разрешен один отзыв на товар, предыдущий отзыв будет заменен</li>{/if}
                <li>
                    <div class="name">
                        <div class="caption">Имя</div>
                        <div class="field"><input type="text" name="user_name" value="{$comment.user_name|escape}"></div>
                    </div>
                    <div class="ball">
                        <div class="rate">
                            <input class="inp_rate" type="hidden" name="rate" value="{$comment.rate|escape}">
                            <div class="stars">
                                <i></i>
                                <i></i>
                                <i></i>
                                <i></i>
                                <i></i>
                            </div>
                            <div class="descr">{$comment->getRateText()}</div>
                        </div>
                        <div class="caption">Ваша оценка</div>
                    </div> 
                </li>
                <li>
                    <div class="text">
                        <div class="caption">Отзыв</div>
                        <div class="field"><textarea name="message" rows="5">{$comment.message|escape}</textarea></div>
                    </div>
                </li>
                {if !$is_auth && ModuleManager::staticModuleEnabled('kaptcha')}
                    <li>
                        <div class="captchaImg">
                            <img src="{$router->getUrl('kaptcha')}">
                        </div>
                        <div class="captchaField">
                            <input type="text" name="captcha"><br>
                            <span class="subtext">Введите код, указанный на картинке</span>
                        </div>
                    </li>
                {/if}
                <li>
                    <div class="submit">
                        <input type="submit" value="Отправить">
                    </div>
                </li>
            </table>
        </form>
        {/if}
    </div>
    <ul class="commentList">
        {$list_html}
    </ul>  
    {if $paginator->total_pages > $paginator->page}
        <a data-pagination-options='{ "appendElement":".commentList" }' data-href="{$router->getUrl('comments-block-comments', ['_block_id' => $_block_id, 'cp' => $paginator->page+1, 'aid' => $aid])}" class="onemoreEmpty ajaxPaginator">еще комментарии</a>
    {/if}
</section>