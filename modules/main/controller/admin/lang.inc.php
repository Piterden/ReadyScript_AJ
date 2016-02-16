<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Main\Controller\Admin;

class Lang extends \RS\Controller\Admin\Front
{
    /**
    * Диалог создания фалов локализации. Спрашивает идентификатор языка (ru, en, es, de ...)
    * 
    */
    function actionCreateLangFilesDialog()
    {
        if($this->url->isPost()){
            $lang = $this->url->post('lang', TYPE_STRING);
            if(!empty($lang)){
                // Создаем файлы локализации
                \Main\Model\LangApi::createLangFiles($lang);
                $this->result->addMessage(t('Языковые файлы созданы'));
            }
            else{
                $this->result->addEMessage(t('Укажите двухсимвольный идентификатор языка'));
            }
        }
        
        $this->result->setSuccess(true);
        return $this->result->setHtml($this->view->fetch('%main%/lang_create_dialog.tpl'));
    }

}

