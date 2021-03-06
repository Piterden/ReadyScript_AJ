<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Users\Controller\Admin;
use \RS\Html\Table\Type as TableType,
    \RS\Html\Toolbar\Button as ToolbarButton,
    \RS\Html\Toolbar,
    \RS\Html\Filter,
    \RS\Html\Table;

/**
* Контроллр пользователей
* @ingroup Users
*/
class Ctrl extends \RS\Controller\Admin\Crud
{
        
    function __construct()
    {
        parent::__construct(new \Users\Model\Api());
    }
    
    function helperIndex()
    {
        $helper = parent::helperIndex();
        $helper->setTopTitle(t('Пользователи'));
        $edit_pattern = $this->router->getAdminPattern('edit', array(':id' => '@id'));
        $helper->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('id', array('showSelectAll' => true)),            
                new TableType\String('id', '№', array('ThAttr' => array('width' => '50'), 'Sortable' => SORTABLE_BOTH, 'CurrentSort' => SORTABLE_DESC)),
                new TableType\String('login', t('Логин'), array('href' => $edit_pattern, 'Sortable' => SORTABLE_BOTH, 'linkAttr' => array('class' => 'crud-edit'))),
                new TableType\String('surname', t('Фамилия'), array('href' => $edit_pattern, 'Sortable' => SORTABLE_BOTH, 'linkAttr' => array('class' => 'crud-edit'))),
                new TableType\String('name', t('Имя'), array('href' => $edit_pattern, 'Sortable' => SORTABLE_BOTH, 'linkAttr' => array('class' => 'crud-edit'))),
                new TableType\String('midname', t('Отчество'), array('href' => $edit_pattern, 'Sortable' => SORTABLE_BOTH, 'linkAttr' => array('class' => 'crud-edit'))),
                new TableType\Datetime('dateofreg', t('Дата регистрации'), array('Sortable' => SORTABLE_BOTH)),
                new TableType\Datetime('last_visit', t('Последний визит'), array('Sortable' => SORTABLE_BOTH)),
                new TableType\Usertpl('group', t('Группа'), '%users%/form/filter/group.tpl'),
                new TableType\Actions('id', array(
                        new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~'))),
                        new TableType\Action\DropDown(array(
                            array(
                                'title' => t('клонировать пользователя'),
                                'attr' => array(
                                    'class' => 'crud-add',
                                    '@href' => $this->router->getAdminPattern('clone', array(':id' => '~field~')),
                                )
                            ),  
                            array(
                                'title' => t('заказы пользователя'),
                                'attr' => array(
                                    '@href' => $this->router->getAdminPattern(false, array(':f[user_id]' => '~field~'), 'shop-orderctrl'),
                                )
                            ),
                        )) 
                    ),
                    array('SettingsUrl' => $this->router->getAdminUrl('tableOptions'))
                ),                 
        ))));
        
        $group_api  = new \Users\Model\GroupApi();
        $group_list = array('' => 'Любой') + array('NULL' => 'Без группы') + array_diff_key($group_api->getSelectList(), array('guest', 'clients'));
        
        $helper->setFilter(new Filter\Control( array(
            'Container' => new Filter\Container( array( 
                                'Lines' =>  array(
                                    new Filter\Line( array('items' => array(
                                                            new Filter\Type\String('id', t('№'), array('attr' => array('class' => 'w50'))),
                                                            new Filter\Type\String('login',t('Логин')),
                                                            new Filter\Type\String('e_mail',t('E-mail'), array('SearchType' => 'like%')),
                                                            new Filter\Type\String('dispname',t('Ник'), array('SearchType' => 'like%')),
                                                            new Filter\Type\Select('group',t('Группа'), $group_list),
                                                            
                                                        )
                                    ))
                                ),
                                'SecContainers' => array(
                                    new Filter\Seccontainer( array(
                                        'Lines' => array(
                                            new Filter\Line( array('items' => array(
                                                                    new Filter\Type\String('name',t('Имя'), array('SearchType' => 'like%')),
                                                                    new Filter\Type\String('surname',t('Фамилия'), array('SearchType' => 'like%')),
                                                                    new Filter\Type\String('midname',t('Отчество'), array('SearchType' => 'like%')),
                                                                    new Filter\Type\Date('dateofreg_from',t('Дата регистрации, от')),
                                                                    new Filter\Type\Date('dateofreg_to',t('Дата регистрации, до')),                                                                    
                                                           )
                                            ))                                            
                                        )))
                                )
                            )),
            'ToAllItems' => array('FieldPrefix' => $this->api->defAlias(), 'TitleAttr' => array('class' => 'standartkey w60')),
            'beforeSqlWhere' => array($this->api, 'beforeSqlWhereCallback')            
        )));
        
        $helper->setTopToolbar(new Toolbar\Element( array(
            'Items' => array(
                new ToolbarButton\Add($this->router->getAdminUrl('add'),t('добавить'),array(
                        array(
                            'attr' => array(                    
                                'class' => 'button add crud-add'
                            )
                        ),
                )),
                new ToolbarButton\Dropdown(array(
                        array(
                            'title' => t('Импорт/Экспорт'),
                            'attr' => array(
                                'class' => 'button',
                                'onclick' => "JavaScript:\$(this).parent().rsDropdownButton('toggle')"
                            )
                        ),
                        array(
                            'title' => t('Экспорт пользователей в CSV'),
                            'attr' => array(
                                'href' => \RS\Router\Manager::obj()->getAdminUrl('exportCsv', array('schema' => 'users-users', 'referer' => $this->url->selfUri()), 'main-csv'),
                                'class' => 'crud-add'
                            )
                        ),
                        array(
                            'title' => t('Импорт пользователей из CSV'),
                            'attr' => array(
                                'href' => \RS\Router\Manager::obj()->getAdminUrl('importCsv', array('schema' => 'users-users', 'referer' => $this->url->selfUri()), 'main-csv'),
                                'class' => 'crud-add'
                            )
                        ),
                )),
            )
        )));
        
        $helper->setBottomToolbar(new Toolbar\Element(array(
            'Items' => array(
                new Toolbar\Button\Button(null, t('Сбросить пароли'), array(
                    'attr' => array(
                        'class' => 'crud-post',
                        'data-url' => $this->router->getAdminUrl('generatePassword'),
                        'data-confirm-text' => t('Вы действительно хотите сгенерировать новые пароли для выбранных пользователей и отправить их пользователям на почту?')
                    )
                )),
                $this->buttons('delete'),
            )
        )));
        
        return $helper;
    }
    
    function actionAdd($primaryKeyValue = null, $returnOnSuccess = false, $helper = null) 
    {
        $conf_userfields = \RS\Config\Loader::byModule($this)->getUserFieldsManager();
        $conf_userfields->setErrorPrefix('userfield_');
        $conf_userfields->setArrayWrapper('data');
        
        $elem = $this->api->getElement();
                
        $groups = new \Users\Model\Groupapi();
        $glist = $groups->getList(0,0,'name');
        
        $conf_userfields->setValues($elem['data']);
        $elem['conf_userfields'] = $conf_userfields;
        $elem['groups'] = $glist;
        if (isset($primaryKeyValue) && $primaryKeyValue>0){
            $elem['usergroup'] = $this->api->getElement()->getUserGroups();
        }elseif (!isset($primaryKeyValue)){
            $elem['usergroup'] = array();
        }
        
        
        if ($primaryKeyValue) {
            $elem['fio'] = $elem->getFio();
            $this->getHelper()->setTopTitle(t('Редактировать профиль пользователя {fio}'));
        } else {
            $this->getHelper()->setTopTitle(t('Добавить пользователя'));
        }

        $return = parent::actionAdd($primaryKeyValue, $returnOnSuccess, $helper);

        $conf_userfields->setValues($elem['data']);
        
        return $return;
    }
    
    
    /**
    * Метод для клонирования
    * 
    */ 
    function actionClone()
    {
        $this->setHelper( $this->helperAdd() );
        $id = $this->url->get('id', TYPE_INTEGER);
        
        $elem = $this->api->getElement();
        
        if ($elem->load($id)) {
            $clone_id = null;
            if (!$this->url->isPost()) {
                $clone = $elem->cloneSelf();
                $this->api->setElement($clone);
                $clone_id = $clone['id']; 
            }
            unset($elem['id']);
            return $this->actionAdd($clone_id);
        } else {
            return $this->e404();
        }
    }
    
    function actionGeneratePassword()
    {
        $ids = $this->modifySelectAll( $this->url->request('chk', TYPE_ARRAY, array(), false) );        
        $this->result->setSuccess($this->api->generatePasswords($ids));
        
        if ($this->result->isSuccess()) {
            $this->result->addMessage(t('Пароли успешно сброшены'));
        } else {
            $this->result->addEMessage($this->api->getErrorsStr());
        }
        
        return $this->result;
    } 
}

