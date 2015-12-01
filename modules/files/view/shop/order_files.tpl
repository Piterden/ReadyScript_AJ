{$files={moduleinsert var="files_data" name="\Files\Controller\Admin\Block\Files" link_type="files-shoporder" link_id="{$elem.id}"}}     
<div class="collapse-block{if $files_data.files} open{/if}">
   <div class="collapse-title"><i class="icon"></i><strong>Прикрепленные файлы</strong> (будут видны покупателю на странице просмотра заказа)</div>
   <div class="collapse-content">
       {$files}
   </div>
</div>