{assign var=bc value=$app->breadcrumbs->getBreadCrumbs()}
{if !empty($bc)}
    <div class="breadcrumb-wrap">
        <ul class="breadcrumb">
            {foreach $bc as $item}
                {if empty($item.href)}
                    <li {if $item@first}class="first"{/if}>
                        <span>{$item.title}</span>
                    </li>
                {else}
                    <li {if $item@first}class="first"{/if}>
                        <a href="{$item.href}">{$item.title}</a>
                    </li>
                {/if}
            {/foreach}
        </ul>
    </div>
{/if}