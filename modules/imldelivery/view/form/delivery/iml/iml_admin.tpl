{if !empty($errors)}
    <ul class="deliveryError">
        {foreach $errors as $error}
           <li>{$error}</li>
        {/foreach}
    </ul>
{else}
    {$delivery_extra=$url->request('delivery_extra', 'array')} 
    <p>IML</p>
    <div id="imlInsertBlock"></div>

    <input id="imlInputMap" type="hidden" name="delivery_extra[value]" value="{$delivery_extra.value}"/>
    
{/if}