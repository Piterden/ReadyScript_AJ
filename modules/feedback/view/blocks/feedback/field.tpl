{if $request} 
  {assign var=postValue value=$request->request($field.alias,'string')} 
{/if}           

{if $field.show_type=='string' || $field.show_type=='email'}   
   <input type="text" name="{$field.alias}" {if $field.length>0}maxlength="{$field.length}"{/if} value="{if $postValue}{$postValue}{else}{$field->getDefault()}{/if}"/>
{elseif $field.show_type=='list'}
   {assign var=valList value=$field->getArrayValuesFromString()}
   
   {if $valList}
      {if !$field.as_radio}  
          <select name="{$field.alias}">  
              {foreach from=$valList item=val}
                  <option value="{$val}" {if $postValue==$val}selected="selected"{/if}>{$val}</option>
              {/foreach}
          </select>
      {else}
          {foreach from=$valList item=val key=k}
              <input id="vlr_{$key}_{$k}" {if $postValue==$val}checked="checked"{/if} type="checkbox" name="{$field.alias}" value="{$val}"/>
              <label for="vlr_{$key}_{$k}">{$val}</label>
          {/foreach}
      {/if}
      
   {else}
      Значения списка не заданы
   {/if}
   
{elseif $field.show_type=='yesno'}
   <select name="{$field.alias}"> 
        <option value="Да" {if $postValue=='Да'}selected="selected"{/if}>Да</option>
        <option value="Нет" {if $postValue=='Нет'}selected="selected"{/if}>Нет</option>
   </select>  
{elseif $field.show_type=='text'}
   <textarea name="{$field.alias}" class="feedTextArea">{if $postValue}{$postValue}{else}{$field->getDefault()}{/if}</textarea>   
{elseif $field.show_type=='file'}
   <input type="file" name="{$field.alias}"/> 
{elseif $field.show_type=='captcha' && ModuleManager::staticModuleEnabled('kaptcha')}
    <div class="captcha">
        <img src="{$router->getUrl('kaptcha')}?rand={rand(0, 99999)}" height="42">
        <input type="text" name="{$field.alias}"/> 
    </div>
{/if}