<html>
    <body>
        <p>Ответ на форму №{$mail->getForm()->id} ({$mail->getForm()->title}):</p>

        <table border="1" cellpadding="5" style="border-collapse:collapse;" >
                {foreach from=$mail->getFields() item=field}
                    <tr>
                        <td>
                           {$field.field.title} 
                        </td>
                        <td>
                           {$field.value}
                        </td>
                    </tr>
                {/foreach}
                {assign var=hvalues  value=$mail->getForm()->getHiddenValues()}
                {if $hvalues}
                   {foreach from=$hvalues item=hv key=k}
                        
                        <tr>
                            <td>
                               {$k} 
                            </td>
                            <td>
                               {$hv} 
                            </td>
                        </tr>
                   {/foreach}    
                {/if}
        </table>

        <p>Форма отправлена с сайта "{$mail->host_title}"</p>
        <p>{$mail->host}</p>
    </body>
</html>