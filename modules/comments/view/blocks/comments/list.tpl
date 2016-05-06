{foreach from=$commentlist item=comment}
<li {$comment->getDebugAttributes()}>
    <div class="right bg">
        <div class="rating"><span class="value mark{$comment.rate}"></span></div>
        <span class="commentsCount">{$comment->getRateText()}</span>
    </div>
    <div class="left">
        <div class="info">
            <span class="date">{$comment.dateof|dateformat:"@date @time"}</span>
            <span class="user">{$comment.user_name|escape}</span>
        </div>
        <p class="message">{$comment.message|escape}</p>
    </div>
</li>
{/foreach}