<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Config;
use \RS\Orm\Type;

/**
* @defgroup Catalog Catalog(Каталог товаров)
* Модуль предоставляет возможность управляь списками товаров, валют, характеристик, единиц измерения, типов цен.
*/

/**
* Конфигурационный файл модуля Каталог товаров
* @ingroup Catalog
*/
class File extends \RS\Orm\ConfigObject
{
    public
        $access_bit = array(
            'Чтение', //1-й бит - будет означать Разрешение на Чтение
            'Запись', //2-й бит будет означать разрешение на Запись
            'Оформление заказа' //3-й бит нужен для demo режима. в реальном режиме флаг должен быть включен вместе с флагом "запись"
        );    
    
    function _init()
    {
        parent::_init()->append(array(
            'default_cost' => new Type\Integer(array(
                'description' => t('Цена по умолчанию'),
                'list' => array(array('\Catalog\Model\Costapi', 'staticSelectList'))
            )),
            'old_cost' => new Type\Integer(array(
                'description' => t('Старая(зачеркнутая) цена'),
                'list' => array(array('\Catalog\Model\Costapi', 'staticSelectList'), true)
            )),
            'hide_unobtainable_goods' => new Type\Varchar(array(
                'description' => t('Скрывать товары с нулевым остатком'),
                'listfromarray' => array(array('Y' => 'Да', 'N' => 'Нет')),
                'attr' => array(array('size' => 1))
            )),
            'list_page_size' => new Type\Integer(array(
                'description' => t('Количество товаров на одной странице списка')
            )),
            'items_on_page' => new Type\Varchar(array(
                'description' => t('Количество товаров на странице категории. Укажите через запятую, если нужно предоставить выбор'),
                'hint' => t('Например: 12,48,96')
            )),
            'list_default_order' => new Type\Varchar(array(
                'description' => t('Сортировка по умолчанию на странице со списком товаров'),
                'listFromArray' => array(array(
                    'dateof' => 'По дате',
                    'rating' => 'По рейтингу',
                    'cost' => 'По цене',
                    'title' => 'По наименованию товара',
                    'num' => 'По наличию',
                    'barcode' => 'По артикулу'
                ))
            )),
            'list_default_order_direction' => new Type\Varchar(array(
                'description' => t('Направление сортировки по умолчанию на странице со списком товаров'),
                'listFromArray' => array(array(
                    'desc' => 'По убыванию',
                    'asc' => 'По возрастанию'
                ))
            )),
            'list_default_view_as' => new Type\Varchar(array(
                'description' => t('Отображать по умолчанию товары в каталоге'),
                'listFromArray' => array(array(
                    'blocks' => t('В виде блоков'),
                    'table' => t('В виде таблицы')
                ))
            )),
            'default_weight' => new Type\Real(array(
                'description' => t('Вес одного товара по-умолчанию'),
                'hint' => t('Данное значение можно переустановить в настройках категории или у самого товара')
            )),
            'default_weight_unit' => new Type\Varchar(array(
                'description' => t('Ед. измерения веса'),
            )),
            'default_unit' => new Type\Integer(array(
                'description' => t('Единица измерения по-умолчанию'),
                'default' => 'грамм',
                'List' => array(array('\Catalog\Model\UnitApi', 'selectList'))
            )),
            'concat_dir_meta' => new Type\Integer(array(
                'description' => t('Дописывать мета теги категорий к товару'),
                'hint' => t('Данная опция имеет значение, когда мета данные заданы у товара.'),
                'checkboxView' => array(1,0)
            )),
            'auto_barcode' => new Type\Varchar(array(
                'description' => t('Синтаксис для автогенерации артикула в новом товаре'),
                'maxLength'   => 60,
                'hint' => t('n - след. номер товара<br/>цифра - количество цифр')                                                           
            )),
            'disable_search_index' => new Type\Varchar(array(
                'description' => t('Отключить добавление товаров ко внутреннему поисковому индексу'),
                'checkboxview' => array(1,0),
                'hint' => t('Данный флаг следует устанавливать только при использовании сторонних поисковых сервисов на сайте')
            )),
            'update_price_round' => new Type\Integer(array(
                'description' => t('Округлять цены до целых чисел при пересчёте цен?'),
                'hint' => t('Округляет при пересчёте из других валют и при увеличении/уменьшении в процентах цены'),
                'checkboxview' => array(1,0),
            )),
            'update_price_round_value' => new Type\Integer(array(
                'maxLength' => 11,
                'description' => t('Степень округления'),
                'hint' => t('
                Число передающееся в функцию <b>round(входящее число,степень округления)</b><br/>
                <b>0</b> - округлять до целых (13,5678=14)<br/>
                <b>1</b> - до десятых (13,5678=13,6)<br/>
                <b>2</b> - до сотых (13,5678=13,57)<br/>
                число отрицательное округляет в сторону целых чисел<br/>
                Например -2 округлить число в сторону сотен.
                '),
                'template' => '%catalog%/form/config/round_value.tpl'
            )),
            'cbr_link' => new Type\Varchar(array(
                'description' => t('Альтернативный адрес XML API ЦБ РФ'),
                'maxLength'   => 255,
                'hint' => t('Это url с которого будет получена информация для получения курсов валют.<br/> 
                По умолчанию, если поле пустое - используется внутренняя константа с адресом.<br/>
                http://www.cbr.ru/scripts/XML_daily.asp')                                                           
            )),
            'cbr_percent_update' => new Type\Integer(array(
                'description' => t('Количество процентов, на которое должен отличатся прошлый курс валюты для обновления'),
                'maxLength'   => 11,
                'default' => 0,
                'hint' => t('Если 0, то процент не учитывается')                                                           
            )),
            'use_offer_unit' => new Type\Integer(array(
                'description' => t('Использовать единицы измерения у комлектаций'),
                'maxLength'   => 1,
                'checkboxview' => array(1,0)                                                   
            )),
            'import_photos_timeout' => new Type\Integer(array(
                'description' => t('Время выполнения одного шага импорта фотографий, сек.')
            )),
            'import_yml_timeout' => new Type\Integer(array(
                'description' => t('Время выполнения импорта продуктов из yml, сек.'),
                'default' => 26
            )),
            'show_all_products' => new Type\Integer(array(
                'description' => t('Показывать все товары по маршруту /catalog/?'),
                'checkboxview' => array(1,0),
            )),
            'price_like_slider' => new Type\Integer(array(
                'description' => t('Показывать фильтр по цене в виде слайдера?'),
                'checkboxview' => array(1,0),
            )),
            'search_fields' => new Type\ArrayList(array(
                'description' => t('Поля, которые должны войти в поисковый индекс товара (помимо названия).'),
                'hint' => t('После изменения, переиндексируйте товары (ссылка справа)'),
                'Attr' => array(array('size' => 5, 'multiple' => 'multiple', 'class' => 'multiselect')),
                'ListFromArray' => array(array(
                    'properties' => t('Характеристики'),
                    'barcode' => t('Артикул'),
                    'short_description' => t('Короткое описание'),
                    'meta_keywords' => t('Мета ключевые слова')
                )),     
                'CheckboxListView' => true,
                'runtime' => false,
            )),
            
            t('Купить в один клик'),
                '__clickfields__' => new Type\UserTemplate('%catalog%/form/config/userfield.tpl'),
                'clickfields' => new Type\ArrayList(array(
                    'description' => t('Дополнительные поля'),
                    'runtime' => false,
                    'visible' => false
                )),
                'buyinoneclick' => new Type\Integer(array(
                    'description' => t('Включить отображение?'),
                    'checkboxview' => array(1,0),
                )),
                'dont_buy_when_null' => new Type\Integer(array(
                    'description' => t('Не разрешать покупки в 1 клик, если товаров недостаточно на складе'),
                    'checkboxview' => array(1,0),
                )),
            t('Обмен данными в CSV'),
                'csv_id_fields' => new Type\ArrayList(array(
                    'runtime' => false,
                    'description' => t('Поля для идентификации товара при импорте (удерживая CTRL можно выбрать несколько полей)'),
                    'hint' => t('Во время импорта данных из CSV файла, система сперва будет обновлять товары, у которых будет совпадение значений по указанным здесь колонкам. В противном случае будет создаваться новый товар'),
                    'list' => array(array('\Catalog\Model\CsvSchema\Product','getPossibleIdFields')),
                    'size' => 7,
                    'attr' => array(array('multiple' => true))
                )),
                'csv_offer_product_search_field' => new Type\Varchar(array(
                    'runtime' => false,
                    'description' => t('Поле идентификации товара во время импорта CSV комплектаций'),
                    'hint' => t('Данные в колонке Товар у CSV файла комплектаций будет сравниваться с указанным здесь полем товара для связи'),
                    'list' => array(array('\Catalog\Model\CsvSchema\Offer','getPossibleProductFields')),
                )),
                'csv_offer_search_field' => new Type\Varchar(array(
                    'description' => t('Поле идентификации комплектации'),
                    'list' => array(array('\Catalog\Model\CsvSchema\Offer','getPossibleOffersFields')),
                )),
            t('Бренды'),
                'brand_products_specdir' => new Type\Integer(array(
                    'description' => t('Спецкатегория, из которой выводить товары на странице бренда'),
                    'list' => array(array('\Catalog\Model\DirApi', 'specSelectList'), true)
                    
                )),
                'brand_products_cnt' => new Type\Integer(array(
                    'description' => t('Кол-во товаров из спец. категории<br/> на странице бренда:'),
                )),
            t('Склады'),
                'warehouse_sticks' => new Type\Varchar(array(
                    'description' => t('Градация наличия товара на складах'),
                    'hint' => t('Перечислите через запятую, количество товара,<br/> 
                    которое будет соответствовать 1, 2, 3-м и т.д. "деленям"<br/> наличия данного товара на складе')
                ))
        ));
    }
    
    /**
    * Возвращает значения свойств по-умолчанию
    * 
    * @return array
    */
    public static function getDefaultValues()
    {
        return parent::getDefaultValues() + array(           
            'tools' => array(
                array(
                    'url' => \RS\Router\Manager::obj()->getAdminUrl('ajaxCleanProperty', array(), 'catalog-tools'),
                    'title' => t('Удалить несвязанные характеристики'),
                    'description' => t('Удаляет характеристики и группы, которые не задействованы в товарах или категориях'),
                    'confirm' => t('Вы действительно хотите удалить несвязанные характеристики?')
                ),
                array(
                    'url' => \RS\Router\Manager::obj()->getAdminUrl('ajaxCheckAliases', array(), 'catalog-tools'),
                    'title' => t('Добавить ЧПУ имена товарам и категориям'),
                    'description' => t('Добавит символьный идентификатор (методом транслитерации) товарам и категориям, у которых он отсутствует.'),
                    'confirm' => t('Вы действительно хотите добавить ЧПУ имена товарам и категориям?')                    
                ),
                array(
                    'url' => \RS\Router\Manager::obj()->getAdminUrl('ajaxCleanOffers', array(), 'catalog-tools'),
                    'title' => t('Удалить несвязанные комплектации'),
                    'description' => t('Удалит несвязанные комплектации, которые могли остаться в базе после отмены создания товара'),
                    'confirm' => t('Вы действительно хотите удалить несвязанные комплектации?')                    
                ),
                array(
                    'url' => \RS\Router\Manager::obj()->getAdminUrl('ajaxReIndexProducts', array(), 'catalog-tools'),
                    'title' => t('Переиндексировать товары'),
                    'description' => t('Построит заново поисковый индекс по товарам'),
                    'confirm' => t('Вы действительно хотите переиндексировать все товары?')                    
                )
            )
        );
    }    
    
    function beforeWrite($flag)
    {
        $this->before_state = new self();
        $this->before_state->load();
    }
    
    function afterWrite($flag)
    {
        if ($this['hide_unobtainable_goods'] != $this->before_state['hide_unobtainable_goods']) {
            \Catalog\Model\Dirapi::updateCounts(); //Обновляем счетчики у категорий
        }
    }
    
    /**
    * Возвращает объект, отвечающий за работу с пользовательскими полями.
    * 
    * @return \RS\Config\UserFieldsManager
    */
    function getClickFieldsManager()
    {
        return new \RS\Config\UserFieldsManager($this['clickfields'], null, 'clickfields');
    }  
    
}