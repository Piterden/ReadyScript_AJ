{addjs file="{$mod_js}comments.js" basepath="root"}
<div class="row">
    <div class="titleWrap col-sm-24 text-center">
        <h3>Отзывы</h3>
    </div>
</div>
<div class="commentBlock">
    <a name="comments"></a>
    <div class="row">
        <div class="commentFormBlock col-md-12 col-md-offset-6{if !empty($error) || !$total} open{/if}">
            {if $mod_config.need_authorize == 'Y'}
                <span class="needAuth">Чтобы оставить отзыв необходимо <a href="{$router->getUrl('users-front-auth', ['referer' => $referer])}" class="inDialog">авторизоваться</a></span>
            {else}
                <form method="POST" class="formStyle row" action="#comments">
                    {if !empty($error)}
                        <div class="errors col-xs-24">
                            {foreach $error as $one}
                                {$one}<br>
                            {/foreach}
                        </div>
                    {/if}
                    {$this_controller->myBlockIdInput()}
                    {if $already_write}<div class="already">Разрешен один отзыв на товар, предыдущий отзыв будет заменен</div>{/if}
                    <div class="name col-xs-12">
                        <label>Ваше имя</label>
                        <input type="text" name="user_name" value="{$comment.user_name}">
                    </div>
                    <div class="rating col-xs-12">
                        <input class="inp_rate" type="hidden" name="rate" value="{$comment.rate}">
                        <label>Ваша оценка</label>
                        <div class="starsBlock">
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <span class="desc">{$comment->getRateText()}</span>
                    </div>
                    <div class="message col-xs-24">
                        <textarea name="message" placeholder="Напишите отзыв">{$comment.message}</textarea>
                    </div>
                    {if !$is_auth && ModuleManager::staticModuleEnabled('kaptcha')}
                        <div class="formLine captcha col-xs-16">
                            <label>Введите код с картинки:</label>
                            <img src="{$router->getUrl('kaptcha')}">
                            <input type="text" name="captcha" class="inpCap">
                        </div>
                    {/if}
                    <div class="buttons col-xs-8 text-right">
                        <button type="submit">Оставить отзыв</button>
                    </div>
                </form>
                <div class="clearfix"></div>
            {/if}
        </div>
    </div>
    {if $total}
        <ul class="commentList">
            {$list_html}
        </ul>
    {else}
        <div class="noComments text-center">нет отзывов</div>
    {/if}
    {include file="%THEME%/paginator.tpl"}
</div>

<script type="text/javascript">
    $(function() {
        $('.commentBlock').comments({
            rate: '.rating',
            stars: '.starsBlock i',
            rateDescr: '.rating .desc'
        });
    });
</script>
