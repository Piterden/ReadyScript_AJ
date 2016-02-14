<?php

/**
 * ReadyScript (http://readyscript.ru)
 *
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Shop\Model\Orm;

use \RS\Orm\Type;

/**
 * Адреса доставок пользователя
 */
class Address extends \RS\Orm\OrmObject
{
    protected static $table = 'order_address';
    public function _init()
    {
        parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(),
            'user_id' => new Type\Integer(array('
        		maxLength' => '11',
                'default' => 0,
                'description' => t('Пользователь'),
            )),
            'order_id' => new Type\Integer(array(
                'maxLength' => '11',
                'default' => 0,
                'description' => t('Заказ пользователя'),
            )),
            'zipcode' => new Type\String(array(
                'maxLength' => '20',
                'description' => t('Индекс'),
            )),
            'country' => new Type\String(array(
                'maxLength' => '100',
                'description' => t('Страна'),
            )),
            'region' => new Type\String(array(
                'maxLength' => '100',
                'description' => t('Регион'),
            )),
            'city' => new Type\String(array(
                'maxLength' => '100',
                'description' => t('Город'),
            )),
            'address' => new Type\String(array(
                'maxLength' => '255',
                'description' => t('Адрес'),
            )),
            'region_id' => new Type\Integer(array(
                'description' => t('ID региона'),
            )),
            'country_id' => new Type\Integer(array(
                'description' => t('ID страны'),
            )),
            'deleted' => new Type\Integer(array(
                'maxLength' => 1,
                'description' => t('Удалён?'),
                'default' => 0,
                'CheckboxView' => array(1, 0,
                ),
            )),
        ));
    }
    public function beforeWrite($flag)
    {
        $this->updateRegionTitles();
    }

    /**
     * Возвращает полный адрес в одну строку
     *
     * @return string
     */
    public function getLineView()
    {
        $keys = array('zipcode', 'country', 'region', 'city', 'address',
        );
        $parts = array();
        foreach ($this->getValues() as $key => $val) {
            if (in_array($key, $keys) && !empty($val)) {
                $parts[] = $val;
            }

        }
        return trim(implode(', ', $parts), ',');
    }

    /**
     * Обновляет названия страны и регина в зависимости от имеющихся id страны или региона
     */
    public function updateRegionTitles()
    {
        $regionApi = new \Shop\Model\RegionApi();
        $country = $regionApi->getOneItem($this['country_id']);
        $this['country'] = $country['title'];

        if (!empty($this['region_id'])) {
            $region = $regionApi->getOneItem($this['region_id']);
            $this['region'] = $region['title'];
        }
    }
}
