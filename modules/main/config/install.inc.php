<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Main\Config;

class Install extends \RS\Module\AbstractInstall
{
    function install()
    {
        $result = parent::install();
        if ($result) {
            //Устанавливаем стартовые виджеты
            $widget = new \Main\Model\Orm\Widgets();
            $user_id = 1;
            
            //Виджет Комментарии
            $widget->getFromArray(array(
                'user_id' => $user_id,
                'col' => 'center',
                'position' => 0,
                'class' => 'comments-widget-newlist'
            ))->insert();
            
            //Виджет Новости компании
            $widget->getFromArray(array(
                'id' => null,
                'user_id' => $user_id,
                'col' => 'center',
                'position' => 1,
                'class' => 'main-widget-readyscriptnews'
            ))->insert();
            
            //Виджет Обновления
            $widget->getFromArray(array(
                'id' => null,
                'user_id' => $user_id,
                'col' => 'right',
                'position' => 0,
                'class' => 'siteupdate-widget-checkupdates'
            ))->insert();
            
            //Виджет Последние авторизации
            $widget->getFromArray(array(
                'id' => null,
                'user_id' => $user_id,
                'col' => 'right',
                'position' => 1,
                'class' => 'users-widget-authlog'
            ))->insert();
            
        }
        return $result;
    }
    
    /**
    * Добавляет демонстрационные данные
    * 
    * @return bool
    */
    function insertDemoData()
    {
        //Заполняем демо значения предприятия
        $site_config = \RS\Config\Loader::getSiteConfig(\RS\Site\Manager::getSiteId());
        $site_config['logo'] = $site_config['__logo']->addFromUrl($this->mod_folder.'/config/demo/logo.png');
        $site_config['slogan'] = t('Ваш интернет-магазин');
        $site_config['firm_name'] = t('ООО Ваше предприятие');
        $site_config['firm_inn'] = t('2308001234');
        $site_config['firm_kpp'] = t('231001001');
        $site_config['firm_bank'] = t('ОАО Интернет банк');
        $site_config['firm_bik'] = t('1234567890');
        $site_config['firm_rs'] = t('12345678909876543210');
        $site_config['firm_ks'] = t('65432101234567890987');
        $site_config['firm_director'] = t('Иванов П.С.');
        $site_config['firm_accountant'] = t('Семенова Н.В.');
        $site_config['facebook_group'] = 'http://fb.com';
        $site_config['vkontakte_group'] = 'http://vk.com';
        $site_config['twitter_group'] = 'http://twitter.com';
        $site_config->update();
        return true;
    }
    
    /**
    * Возвращает true, если модуль может вставить демонстрационные данные
    * 
    * @return bool
    */
    function canInsertDemoData()
    {
        return true;
    }    
}
