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
                <a href="#" class="button handler" onclick="$(this).closest('.commentFormBlock').toggleClass('open');return false;">Оставить отзыв</a>
                <div class="caption">
                    Оставить отзыв о товаре
                    <a onclick="$(this).closest('.commentFormBlock').toggleClass('open')" class="close iconX" title="закрыть"></a>
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
                    <textarea name="message">{$comment.message}</textarea>
                    {if $already_write}<div class="already">Разрешен один отзыв на товар, предыдущий отзыв будет заменен</div>{/if}                    
                    <div class="rating">
                        <input class="inp_rate" type="hidden" name="rate" value="{$comment.rate}">                        
                        <span>Ваша оценка</span>
                        <div class="starsBlock">
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <span class="desc">{$comment->getRateText()}</span>
                    </div>                                                                
                    <p class="name">
                        <label>Ваше имя</label>
                        <input type="text" name="user_name" value="{$comment.user_name}">
                    </p>
                    {if !$is_auth && ModuleManager::staticModuleEnabled('kaptcha')}
                    <div class="formLine captcha">
                        <div class="fieldName">Введите код, указанный на картинке</div>
                        <img src="{$router->getUrl('kaptcha')}">
                        <input type="text" name="captcha" class="inpCap"> 
                    </div>
                    {/if}                 
                    <input type="submit" value="Оставить отзыв">
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
            rate:'.rating',
            stars: '.starsBlock i',
            rateDescr: '.rating .desc'
        });
    });
</script>