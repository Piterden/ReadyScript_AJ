<?php
namespace Wishlist\Controller\Admin;

use \RS\Html\Table\Type as TableType,
    \RS\Html\Toolbar\Button as ToolbarButton,
    \RS\Html\Filter,
    \RS\Html\Table;
    
/**
* Контроллер Управление списком магазинов сети
*/
class Control extends \RS\Controller\Admin\Crud
{
    function __construct()
    {
        // Устанавливаем, с каким API будет работать CRUD контроллер
        parent::__construct(new \Wishlist\Model\WishApi());
    }
    
    function helperIndex()
    {
        $helper = parent::helperIndex(); //Получим helper по-умолчанию
        $helper->setTopTitle('Список желаний'); //Установим заголовок раздела
        $helper->setBottomToolbar($this->buttons(array('delete')));
        //$helper->addCsvButton('wishlist-wish');
        
        // Отобразим таблицу со списком объектов
        $helper->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('id', array('showSelectAll' => true)), //Отображаем флажок "выделить элементы на всех страницах"
                new TableType\Integer('user_id', 'ID пользователя', array('Sortable' => SORTABLE_BOTH)),
                new TableType\String('user_name', 'Имя пользователя', array('Sortable' => SORTABLE_BOTH)),
                new TableType\String('product_name', 'Название товара', array('Sortable' => SORTABLE_BOTH)),
                new TableType\Integer('product_id', 'ID товара', array('Sortable' => SORTABLE_BOTH)),
                new TableType\Actions('id', array(
                    //Опишем инструменты, которые нужно отобразить в строке таблицы пользователю
                    new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~')), null, array(
                        'attr' => array(
                            '@data-id' => '@id'
                        ))),
                    ),
                    //Включим отображение кнопки настройки колонок в таблице
                    array('SettingsUrl' => $this->router->getAdminUrl('tableOptions'))
                ),
        ))));
        
        // Добавим фильтр значений в таблице
        $helper->setFilter(new Filter\Control( array(
            'Container' => new Filter\Container( array( 
                'Lines' =>  array(
                    new Filter\Line( array('items' => array(
                            new Filter\Type\String('user_name', 'Имя пользователя', array('SearchType' => '%like%')),
                        )
                    )),
                    new Filter\Line( array('items' => array(
                            new Filter\Type\String('product_name', 'Название товара', array('SearchType' => '%like%')),
                        )
                    ))
                )
            )),
            'Caption' => 'Поиск по желаниям'
        )));

        // Удалим кнопку Добавить
        $helper['topToolbar']->removeItem('add');

        return $helper;
    }
}
