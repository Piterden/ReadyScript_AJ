{if $CONFIG.facebook_group || $CONFIG.vkontakte_group || $CONFIG.twitter_group}
    <div class="social">
        {if $CONFIG.facebook_group}
            <a href="{$CONFIG.facebook_group}" class="fb"></a>
        {/if}
        {if $CONFIG.vkontakte_group}
            <a href="{$CONFIG.vkontakte_group}" class="vk"></a>
        {/if}
        {if $CONFIG.twitter_group}
            <a href="{$CONFIG.twitter_group}" class="tw"></a>
        {/if}
    </div>
    <p class="footerImage"><img src="{$THEME_IMG}/footer_image.png" alt=""/></p>
{/if}