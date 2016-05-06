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
* Предварительный заказ товара
*/
class Reservation extends \RS\Orm\OrmObject
{
    protected static
        $table = 'product_reservation';
    protected
        $product;
        
    function _init()
    {
        parent::_init()->append(array(
            'product_id' => new Type\Integer(array(
                'description' => t('ID товара'),
                'checker' => array('chkEmpty', 'Не задан товар')
            )),
            'product_title' => new Type\Varchar(array(
                'description' => t('Название товара')
            )),
            'offer' => new Type\Varchar(array(
                'description' => t('Комплектация товара'),
                'Template' => 'form/reservationfield/offer.tpl'
            )),
            'multioffer' => new Type\Varchar(array(
                'description' => t('Многомерная комплектация товара'),
                'Template' => 'form/reservationfield/multioffer.tpl'
            )),
            'multioffers' => new Type\ArrayList(array(
                'descirption' => t('Многомерная комплектация товара - массив'),
                'visible' => false
            )),
            'amount' => new Type\Integer(array(
                'description' => t('Количество')
            )),
            'phone' => new Type\Varchar(array(
                'maxLength' => 50,
                'description' => t('Телефон пользователя')
            )),
            'email' => new Type\Varchar(array(
                'description' => t('E-mail пользователя'),
                'checker' => array(array(__CLASS__, 'checkContacts'), 'Укажите телефон или E-mail')
            )),            
            'dateof' => new Type\Datetime(array(
                'description' => t('Дата заказа')
            )),
            'user_id' => new Type\User(array(
                'description' => t('ID пользователя')
            )),
            'kaptcha' => new Type\Captcha(array(
                'description' => t('Защитный код'),
                'enable' => false
            )),
            'status' => new Type\Enum(array('open', 'close'), array(
                'allowempty' => false,
                'description' => t('Статус'),
                'listFromArray' => array(array(
                    'open' => 'открыт',
                    'close' => 'закрыт'
                ))
            )),
            'comment' => new Type\Text(array(
                'description' => t('Комментарий администратора')
            ))
        ));
    }    
    
    public static function checkContacts($_this, $value, $error_text)
    {
        if ($_this['phone'] || $_this['email']) return true;
        return $error_text;
    }
    
    function beforeWrite($flag)
    {
        if ($flag == self::INSERT_FLAG) {
            $this['dateof'] = date('c');
            $this['user_id'] = \RS\Application\Auth::getCurrentUser()->id;
        }
        
        $this['multioffer'] = serialize($this['multioffers']);
        $this['product_title'] = $this->getProduct()->title;
    }
    
    function afterWrite($flag)
    {
        if ($flag == self::INSERT_FLAG) {
            $notice = new \Shop\Model\Notice\Reservation;
            $notice->init($this);
            \Alerts\Model\Manager::send($notice); 
        }        
    }
    
    function afterObjectLoad()
    {
        $this['multioffers'] = @unserialize($this['multioffer']);
    }
    
    function getProduct()
    {
        if (empty($this->product)){
          $this->product =  new \Catalog\Model\Orm\Product($this['product_id']);
        }   
        return $this->product;
    }
    
    /**
    * Возращает массив мульти комплектаций
    * 
    */
    function getArrayMultiOffer()
    {
       $arr = array();
       if (!empty($this['multioffers'])){
          foreach ($this['multioffers'] as $offer){
              $parse = explode(":",$offer);
              $arr[trim($parse[0])] = trim($parse[1]);
          } 
       }
       
       return $arr;
    }
}
?>
