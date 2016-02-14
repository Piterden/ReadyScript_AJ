{if $new_count > 0}
    <div class="messages countBlock">
        <a href="{$router->getUrl('support-front-support')}" class="mail">{$new_count}</a>
    </div>
{/if}