{assign var=row value=$cell->getRow()}
{if !empty($row['sites'])}Сайтов: <strong>{$row['sites']}</strong><br>{/if}
{if !empty($row['product'])}Продукт: <strong>{$row['product']}</strong><br>{/if}
{if !empty($row['expire_month'])}Срок действия, месяцев: <strong>{$row['expire_month']}</strong><br>{/if}
{if !empty($row['upgrade_to_product'])}Обновление до продукта: <strong>{$row['upgrade_to_product']}</strong><br>{/if}
{if isset($row['expire']) && $row['expire']>0}Действительна до: <strong>{$row['expire']|date_format:"d.m.Y H:i"}</strong><br>{/if}
{if $row['update_expire']>0}Обновление до: <strong>{$row['update_expire']|date_format:"d.m.Y H:i"}</strong>{/if}