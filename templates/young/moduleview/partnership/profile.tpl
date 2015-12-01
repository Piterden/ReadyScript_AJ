{if count($partner->getNonFormErrors())>0}
    <div class="pageError">
        {foreach $partner->getNonFormErrors() as $item}
        <p>{$item}</p>
        {/foreach}
    </div>
{/if}    

{if $result}
    <div class="formResult success">{$result}</div>
{/if}

<form method="POST" enctype="multipart/form-data" class="formStyle profile">
    {$this_controller->myBlockIdInput()}
    <div class="formLine">    
        <label class="fielName">Увеличение стоимости, в %</label><br>
        {$partner->getPropertyView('price_inc_value')}
        <div class="help">Число от 0 до 100</div>
    </div>
    <div class="formLine">    
        <label class="fielName">Логотип</label><br>
        {$partner->getPropertyView('logo')}
        <div class="help">Изображение в форматах GIF, PNG, JPG</div>
    </div>    
    <div class="formLine">    
        <label class="fielName">Слоган</label><br>
        {$partner->getPropertyView('slogan')}
    </div>        
    <div class="formLine">    
        <label class="fielName">Контактная информация</label><br>
        <textarea style="width:100%; height:200px" name="contacts">{$partner.contacts}</textarea>
    </div>            

    <div class="buttons cboth">
        <input type="submit" value="Сохранить">
    </div>
</form>