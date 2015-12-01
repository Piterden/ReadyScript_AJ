<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Main\Controller\Admin;

/**
* Контроллер системных настроек
* @ingroup Main
*/
class Options extends \RS\Controller\Admin\ConfigEdit
{
    function __construct()
    {
        $orm = \RS\Config\Loader::getSystemConfig();
        parent::__construct($orm);
        $orm->setFormTemplate('system_options');        
    }
    
    /**
    * Системные настройки
    */
    function actionIndex()
    {
        $this->view->assign('partName','Настройки');
        $this->app->addJs( $this->mod_js.'cacheclean.js',null, BP_ROOT);
        $this->app->addJsVar('cleanCacheUrl', $this->router->getAdminUrl('cleanCache'));
        return parent::actionIndex();
    }
    
    function helperIndex()
    {
        $helper = parent::helperIndex();
        $helper->setTopTitle(t('Настройка системы'));
        $helper->setTemplate('%main%/crud-options.tpl');
        return $helper;
    }

    
    /**
    * AJAX
    */
    function actionCleanCache()
    {
        $type = $this->url->request('type', TYPE_STRING);
        $this->result->setSuccess( \RS\Cache\Cleaner::obj()->clean($type) );
        return $this->result->getOutput();
    }
    
    /**
    * Исправляет структуру БД
    */
    function actionSyncDb()
    {
        $module_manager = new \RS\Module\Manager();
        $modules = $module_manager->getList(false);
        $count = 0;
        foreach($modules as $item) {
            $objects = $item->getOrmObjects();
            foreach($objects as $orm_object) {
                $orm_object->dbUpdate();
                $count++;
            }
        }

        return $this->result
            ->addSection('noUpdate', true)
            ->addMessage(t('Обновлено %0 таблиц', array($count)));
    }
    
    /**
    * Отправляет тестовое письмо администратору сайта
    */
    function actionTestMail()
    {
        $site_config = \RS\Config\Loader::getSiteConfig();
        if (!$site_config['admin_email']) {
            $this->result->addEMessage(t('Не задан Email администратора в разделе Веб-сайт->Настройка сайта'));
        } else {
            $mailer = new \RS\Helper\Mailer(false);
            $mailer->Subject = t('Проверка отправки писем с сайта');
            $mailer->addEmails($site_config['admin_email']);
            $mailer->isHTML(false);
            $mailer->Body = t('Если вы видите данный текст, значит письмо с сайта %0 успешно доставлено.', array(\Setup::$DOMAIN));
            
            if ($mailer->send()) {
                $this->result->addMessage(t('Письмо успешно отправлено на Email администратора'));
            } else {
                $this->result->addEMessage(t('Ошибка отправки письма: %0', array( $mailer->ErrorInfo )));
            }
        }
    
        return $this->result->addSection('noUpdate', true);
    }
}

