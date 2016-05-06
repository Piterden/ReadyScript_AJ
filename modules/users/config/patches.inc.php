<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Users\Config;

/**
* Патчи к модулю
*/
class Patches extends \RS\Module\AbstractPatches
{
    function init()
    {
        return array(
            '20054',
            '20027',
            '20049'
        );
    }
    
    function beforeUpdate20054()
    {
        $user = new \Users\Model\Orm\User();
        $user->getPropertyIterator()->append(array(
            t('Основные'),
            'last_visit' => new \RS\Orm\Type\Datetime(array(
                'description' => t('Последний визит')
            )),
        ));
        $user->dbUpdate();
    }
    
    /**
    * Патч к версии 2.0.0.49
    * Принудительно обновляет структуру таблицы пользователя
    */
    function afterUpdate20049()
    {
        $user = new \Users\Model\Orm\User();
        $user->getPropertyIterator()->append(array(
            t('Основные'),
            'ban_expire' => new \RS\Orm\Type\Datetime(array(
                'description' => t('Заблокировать до ...'),
                'template' => '%users%/form/user/ban_expire.tpl'
            )),                    
            'ban_reason' => new \RS\Orm\Type\Varchar(array(
                'description' => t('Причина блокировки'),
                'visible' => false
            )),
        ));
        $user->dbUpdate();
    }
    
    
    /**
    * Патч к версии 2.0.0.27.
    * Обновляет данные в базе для сохранения прав доступа групп к меню 
    * после изменения концепции организации меню в админ. панели
    */
    function afterUpdate20027()
    {
        $access_menu = new \Users\Model\Orm\AccessMenu();
        $access_menu->getPropertyIterator()->append(array(
            'menu_type' => new \RS\Orm\Type\Enum(array('user', 'admin'), array(
                'description' => t('Тип меню'),
                'allowEmpty' => false,
                'default' => 'user'
            )),
        ));
        $access_menu->dbUpdate();
        
        \RS\Orm\Request::make()
            ->update()
            ->from(new \Users\Model\Orm\AccessMenu(), 'A')
            ->join(new \Menu\Model\Orm\Menu(), 'M.id = A.menu_id', 'M')
            ->set('A.menu_type = M.menutype')
            ->exec();

        \RS\Orm\Request::make()
            ->update()
            ->from(new \Users\Model\Orm\AccessMenu(), 'A')
            ->join(new \Menu\Model\Orm\Menu(), 'M.id = A.menu_id', 'M')
            ->set('A.menu_id = M.alias')
            ->where(array('A.menu_type' => 'admin'))
            ->exec();            
        
        \RS\Orm\Request::make()
            ->update(new \Users\Model\Orm\AccessMenu())
            ->set(array('menu_type' => 'admin'))
            ->where(array('menu_id' => '-2'))
            ->exec();
    }    
    
}
?>
