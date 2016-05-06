{$groups=$cell->getRow()->getUserGroups(false)}
{if !empty($groups)}
    {foreach $groups as $group}
       {$group.name}{if !$group@last}, {/if}
    {/foreach}
{else}
    Без группы
{/if}
