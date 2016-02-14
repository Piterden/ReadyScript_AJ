{foreach $commentlist as $comment}
    <li class="row" {$comment->getDebugAttributes()}>
        <div class="rate col-md-2 col-md-offset-4">
            <div class="starsSection" title="{$comment->getRateText()}" data-rating="{$comment.rate}">
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o"></i>
            </div>
        </div>
        <div class="info col-md-12">
            <span class="name text-bold">{$comment.user_name}</span>
            <span class="date">{$comment.dateof|dateformat:"@date @time"}</span>
        </div>
        <div class="comment col-md-12">
            <i class="corner"></i>
            <p>{$comment.message}</p>
        </div>
        <div class="clearfix"></div>
    </li>
{/foreach}
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.starsSection').each(function() {
            var rating = $(this).data('rating');
            for (var i = 0; i < rating; i++) {
                $(this).find('i').eq(i).addClass('act');
            }
            ;
        });
    });
</script>