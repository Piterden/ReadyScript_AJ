<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Sitemap\Config;
use \RS\Orm\Type;

/**
* @defgroup Article Article(Статьи)
* Модуль позволяет создавать статьи по категориям. 
* Данный модуль можно использовать для организации информационных каналов (Например: новости, блог) или хранения одиночных текстовых материалов.
* У категории или у отдельной стати можно задать символьный идентификатор, который в дальнейшем можно использовать для выборки целого списка или одной статьи соответственно.
*/

/**
* Класс конфигурации модуля Статьи
* @ingroup Article
*/
class File extends \RS\Orm\ConfigObject
{
    function _init()
    {
        parent::_init()->append(array(
            'priority' => new Type\Double(array(
                'description' => 'Приоритет страниц по-умолчанию'
            )),
            'changefreq' => new Type\String(array(
                'description' => 'Как часто меняется контент на страницах?',
                'listFromArray' => array(array(
                    'disabled' => 'Не задано',
                    'never' => 'Никогда',
                    'yearly' => 'Ежегодно',
                    'monthly' => 'Ежемесячно',
                    'weekly' => 'Еженедельно',
                    'daily' => 'Ежедневно',
                    'hourly' => 'Каждый час',
                    'always' => 'Всегда',
                ))
            )),
            'set_generate_time_as_lastmod' => new Type\Integer(array(
                'description' => 'Устанавливать дату генерации sitemap в секцию lastmod по-умолчанию',
                'checkboxView' => array(1,0)
            )),
            'lifetime' => new Type\Integer(array(
                'description' => 'Время жизни sitemap файла в минутах',
                'hint' => 'После истечения данного периода файл будет создаваться заново, 0 - означает всегда создавать налету'
            )),
            'add_urls' => new Type\Text(array(
                'description' => 'Добавить следующие адреса (каждый с новой строки)'
            )),
            'exclude_urls' => new Type\Text(array(
                'description' => 'Исключить следующие адреса по маске (каждый с новой строки, применяются регулярные выражения)'
            ))
        ));
        
    }
}
