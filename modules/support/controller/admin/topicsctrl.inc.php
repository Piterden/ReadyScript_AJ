<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Support\Controller\Admin;

use \RS\Html\Table\Type as TableType,
    \RS\Html\Toolbar\Button as ToolbarButton,
    \RS\Html\Toolbar,
    \RS\Html\Filter,
    \RS\Html\Table;

class TopicsCtrl extends \RS\Controller\Admin\Crud
{
    protected
        $api;
        
    function __construct()
    {
        parent::__construct(new \Support\Model\TopicApi);
    }
    
    function helperIndex()
    {
        $helper = parent::helperIndex();
        $helper->setTopTitle(t('Поддержка'));
        
        $helper->setTable(new Table\Element(array(
            'Columns' => array(
                new \RS\Html\Table\Type\Checkbox('id', array('ThAttr' => array('width' => 20))),
                new \RS\Html\Table\Type\Text('id', '№', array('ThAttr' => array('width' => '50'), 'Sortable' => SORTABLE_BOTH, 'href' => $this->router->getAdminPattern(null, array(':id' => '@id'), 'support-supportctrl'))),
                new TableType\Userfunc('title', t('Тема'), function ($value,$_this){
                   //Если есть не прочтённые сообщения
                   if ( $_this->getRow()->newadmcount > 0 ) { 
                       return "<b>".$value."</b>";
                   }
                   return $value;
                }, 
                    array(
                         'Sortable' => SORTABLE_BOTH, 
                         'href' => $this->router->getAdminPattern(null, array(':id' => '@id'), 'support-supportctrl')
                    )
                ),
                new \RS\Html\Table\Type\Usertpl('user_id', t('Пользователь'), '%support%/table_user_cell.tpl',array(
                         'Sortable' => SORTABLE_BOTH, 
                         'href' => $this->router->getAdminPattern(null, array(':id' => '@id'), 'support-supportctrl')
                    )),
                new \RS\Html\Table\Type\Text('updated', 'Дата', array('Sortable' => SORTABLE_BOTH, 'CurrentSort' => SORTABLE_ASC)),                
                new \RS\Html\Table\Type\Text('newadmcount', 'Новых сообщений', array('TdAttr' => array('align' => 'center'))),
                new TableType\Actions('id', array(
                    new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~')), null, array(
                        'attr' => array(
                            '@data-id' => '@id'
                        ))),
                    )
                ),                
                
            ))
        ));    

        //Инициализируем фильтр
        $filter_control = new \RS\Html\Filter\Control(array(
            'Container' => new \RS\Html\Filter\Container( array( 
                                'Lines' =>  array(
                                    new \RS\Html\Filter\Line( array('items' => array(
                                                            new \RS\Html\Filter\Type\Select('newadmcount','Категория', array('' => 'Все сообщения', '0' => 'Только новые'), array('SearchType' => 'noteq')),
                                                            new \RS\Html\Filter\Type\Text('title','Тема', array('SearchType' => '%like%')),
                                                            new \RS\Html\Filter\Type\User('user_id','Пользователь'),
                                                        )
                                    ))
                                ),
                                'SecContainers' => array(
                                    new Filter\Seccontainer(array(
                                        'Lines' => array(
                                            new Filter\Line( array(
                                                'Items' => array(
                                                    new Filter\Type\Date('updated', t('Дата'), array('showtype' => true))
                                                )
                                            ))
                                        )
                                ))
                                )
                            ))
        ));

        $helper->setFilter($filter_control);
        
        return $helper;
    }

    function actionIndex()
    {
        return parent::actionIndex();
    }
    
    function actionAdd($primaryKey = null, $returnOnSuccess = false, $helper = null)
    {
        $result = parent::actionAdd($primaryKey, $returnOnSuccess, $helper);
        if ($result->isSuccess()) {
            $result->setAjaxWindowRedirect( $this->router->getAdminUrl(false, array('id' => $this->api->getElement()->id), 'support-supportctrl') );
        }
        return $result;
    }
    
}

