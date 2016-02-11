<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Menu\Model\Orm;

use \RS\Orm\Type;

class Menu extends \RS\Orm\OrmObject
{
	const TYPELINK_LINK = 'link', // Ссылка (пункт меню - это любая ссылка)
TYPELINK_ARTICLE = 'article', // Статья (внутри шаблонов можно будет вставить модуль marticle
	                              // с параметров autoid="true", будет вставлена соответствующая статья)
	TYPELINK_EMPTY = 'empty', // Пустая (пункт меню будет просто рендерить заданные шаблоны)
TYPELINK_SEPARATOR = 'separator', // Разделитель

	ARTICLE_CATEGORY_ALIAS = 'menu'; // alias категории статей, в которой создаются связанные статьи
	protected static $table = "menu";
	protected $article_category_title = 'Привязанные к меню';
	protected static $act_path; // Путь до корневого элемента активного пункта меню
	protected function _init()
	{
		parent::_init ();
		
		$properties = $this->getPropertyIterator ()->append ( array(t ( 'Основные' ),'site_id' => new Type\CurrentSite (),'menutype' => new Type\String ( array('maxLength' => '70','description' => 'Тип меню','visible' => false 
		) ),'title' => new Type\String ( array('maxLength' => '150','description' => 'Название','Checker' => array(array(__CLASS__,'checkEmptyDependTypelink' 
		),'Необходимо заполнить поле название' 
		),'specVisible' => false,'attr' => array(array('data-autotranslit' => 'alias' 
		) 
		) 
		) ),'hide_from_url' => new Type\Integer ( array('maxLength' => 1,'description' => t ( 'Не использовать для построения URL' ),'hint' => t ( 'При активации данной опции у дочерних элементов не будет текущей секции' ),'checkboxView' => array(1,0 
		),'specVisible' => false 
		) ),'alias' => new Type\String ( array('maxLength' => '150','description' => 'Симв. идентификатор','specVisible' => false,'meVisible' => false,'Checker' => array(array(__CLASS__,'checkEmptyDependTypelink' 
		),'Необходимо заполнить поле Символьный идентификатор' 
		),'hint' => t ( 'Символьный идентификатор используется для формирования адреса страницы для пункта меню' ) 
		) ),'parent' => new Type\Integer ( array('maxLength' => '11','description' => 'Родитель','List' => array(array('\Menu\Model\Api','selectList' 
		) 
		) 
		) ),'public' => new Type\Integer ( array('maxLength' => '1','description' => 'Публичный','CheckboxView' => array(1,0 
		),'specVisible' => false 
		) ),'typelink' => new Type\String ( array('maxLength' => '20','description' => 'Тип элемента','ListFromArray' => array(array(self::TYPELINK_ARTICLE => 'Статья',self::TYPELINK_LINK => 'Ссылка',self::TYPELINK_EMPTY => 'Страница' 
		) 
		),'Attr' => array(array('size' => 0 
		) 
		),'hint' => t ( '<strong>Статья</strong> - позволяет задать произвольный текст(или HTML) на странице данного пункта меню<br>' . '<strong>Ссылка</strong> - предназначена для переадресации на любую страницу, при нажатии на пункт меню<br>' . '<strong>Страница</strong> - означает, что Вы можете сконструировать страницу для данного пункта меню в разделе Конструктор сайта' ),'meVisible' => false,'specVisible' => false 
		) ),'__setlink__' => new Type\Mixed ( array('description' => '','template' => '%menu%/form/menu/link.tpl','visible' => true,'meVisible' => false,'specVisible' => false 
		) ),'link' => new Type\String ( array('maxLength' => '255','description' => 'Ссылка','hint' => t ( 'Относительная или абсолютная ссылка, например: /news/ или http://readyscript.ru' ),'visible' => false 
		) ),'link_template' => new Type\Template ( array('description' => t ( 'Шаблон' ),'visible' => false 
		) ),'target_blank' => new Type\Integer ( array('maxLength' => 1,'description' => t ( 'Открывать ссылку в новом окне' ),'checkboxView' => array(1,0 
		),'default' => 0,'visible' => false 
		) ),'acontent' => new Type\Richtext ( array('description' => 'Статья','runtime' => true,'visible' => false 
		) ),'sortn' => new Type\Integer ( array('maxLength' => '11','description' => 'Порядк. №','visible' => false 
		) ),'img' => new Type\String ( array('maxLength' => '255','description' => 'Картинка','visible' => false 
		) ),'closed' => new Type\Integer ( array('maxLength' => '1','runtime' => true,'visible' => false 
		) ) 
		) );
		
		$this->addIndex ( array('site_id','alias','parent' 
		), self::INDEX_UNIQUE )->addIndex ( array('parent','sortn' 
		) )->addIndex ( 'site_id' );
	}
	
	/**
	 * Возвращает отладочные действия, которые можно произвести с объектом
	 * 
	 * @return RS\Debug\Action[]
	 */
	public function getDebugActions()
	{
		return array(new \RS\Debug\Action\Edit ( \RS\Router\Manager::obj ()->getAdminPattern ( 'edit', array(':id' => '{id}' 
		), 'menu-ctrl' ) ),new \RS\Debug\Action\Delete ( \RS\Router\Manager::obj ()->getAdminPattern ( 'del', array(':chk[]' => '{id}' 
		), 'menu-ctrl' ) ),new \RS\Debug\Action\Create ( \RS\Router\Manager::obj ()->getAdminPattern ( 'add', array(':pid' => '{id}' 
		), 'menu-ctrl' ), t ( 'создать подменю' ) ) 
		);
	}
	public static function checkEmptyDependTypelink($coreobj, $value, $real_errtext)
	{
		if ($coreobj['typelink'] == self::TYPELINK_SEPARATOR) return true;
		return ! empty ( $value ) ? true : $real_errtext;
	}
	function beforeWrite($save_flag)
	{
		if ($save_flag == self::INSERT_FLAG && ! isset ( $this['sortn'] )) {
			$q = \RS\Orm\Request::make ()->select ( 'MAX(sortn) max_sort' )->from ( $this )->where ( array('parent' => $this['parent'],'menutype' => $this['menutype'] 
			) );
			
			if ($this['menutype'] != 'admin') {
				$q->where ( array('site_id' => $this['site_id'] 
				) );
			}
			
			$this['sortn'] = $q->exec ()->getOneField ( 'max_sort', - 1 ) + 1;
		}
		
		if ($this['typelink'] == self::TYPELINK_SEPARATOR) {
			$this['title'] = '---------------------';
			$this['public'] = 1;
		}
		
		if ($this['menutype'] == 'admin') {
			$this['site_id'] = 1;
		}
		
		if ($this['id'] && $this['parent'] == $this['id']) {
			return $this->addError ( t ( 'Неверно указан родительский элемент' ), 'parent' );
		}
	}
	function afterWrite($save_flag)
	{
		if ($this['typelink'] == self::TYPELINK_ARTICLE) {
			if (! $article = $this->getLinkedArticle ()) {
				// Создаем, если не существует категорию статей
				$category = new \Article\Model\Orm\Category ();
				$category['alias'] = self::ARTICLE_CATEGORY_ALIAS;
				$category['title'] = $this->article_category_title;
				$category['parent'] = 0;
				if (! $category->insert ()) {
					$category['id'] = \RS\Orm\Request::make ()->select ( 'id' )->from ( $category )->where ( array('site_id' => \RS\Site\Manager::getSiteId (),'alias' => self::ARTICLE_CATEGORY_ALIAS 
					) )->exec ()->getOneField ( 'id' );
				}
				
				// Создаем связанную статью
				$article = new \Article\Model\Orm\Article ();
				$article['alias'] = $this->getLinkedArticleAlias ();
				$article['title'] = $this['title'];
				$article['content'] = $this['acontent'];
				$article['parent'] = $category['id'];
				$article['dateof'] = date ( 'Y-m-d H:i:s' );
				$article->insert ();
			}
			
			if ($save_flag == self::UPDATE_FLAG) {
				// Изменяем связанную статью
				if ($article) {
					$article['content'] = $this['acontent'];
					$article['title'] = $this['title'];
					$article->update ();
				}
			}
		}
	}
	
	/**
	 * Возвращает алиас для связанной статьи
	 */
	function getLinkedArticleAlias()
	{
		return '#menu_' . $this['id'];
	}
	function getLinkedArticleId()
	{
		$article = $this->getLinkedArticle ();
		return $article['id'];
	}
	
	/**
	 * Возвращает привязанную статью или false
	 * 
	 * @return \Article\Model\Orm\Article
	 */
	function getLinkedArticle()
	{
		$article = new \Article\Model\Api ();
		return $article->getByAlias ( $this->getLinkedArticleAlias () );
	}
	
	/**
	 * Загружает в поле acontent связанную статью,
	 * если статья не найдена, то присваевает typelink значение empty
	 */
	function loadLinkedArticle()
	{
		$article = $this->getLinkedArticle ();
		if ($article) {
			$this['acontent'] = $article['content'];
		} else if ($this['typelink'] == self::TYPELINK_ARTICLE) {
			$this['typelink'] = self::TYPELINK_EMPTY;
		}
	}
	
	/**
	 * Проверяет, есть ли права для работы с данным пунктом меню у пользователя
	 */
	function checkUserRights(\Users\Model\Orm\User $user = null)
	{
		if ($user === null) $user = \RS\Application\Auth::getCurrentUser ();
		$access_menu = $user->getMenuAccess ();
		
		if ($this['menutype'] == 'user' && in_array ( FULL_USER_ACCESS, $access_menu )) return true;
		if (in_array ( $this['id'], $access_menu )) return true;
		return false;
	}
	
	/**
	 * Возвращает URL в зависимости от типа пункта меню
	 */
	function getHref()
	{
		if ($this['typelink'] != self::TYPELINK_LINK) {
			return \RS\Router\Manager::obj ()->getUrl ( 'menu.item_' . $this['id'] );
		} else {
			return str_replace ( '%ADMINPATH%', \Setup::$FOLDER . '/' . \Setup::$ADMIN_SECTION, $this['link'] );
		}
	}
	function isAct()
	{
		if (! isset ( self::$act_path )) {
			$api = \Menu\Model\Api::getInstance ();
			$curItem = $api->getCurrentMenuItem ();
			self::$act_path = array();
			if ($curItem['id']) {
				$list = $api->getPathToFirst ( $curItem['id'] );
				foreach($list as $item) {
					self::$act_path[] = $item['id'];
				}
			}
		}
		
		if ($this['typelink'] == self::TYPELINK_LINK) {
			return \RS\Http\Request::commonInstance ()->server ( 'REQUEST_URI' ) === $this['link'];
		} else {
			return (in_array ( $this['id'], self::$act_path ));
		}
	}
	
	/**
	 * Возвращает клонированный объект меню
	 * 
	 * @return Menu
	 */
	function cloneSelf()
	{
		/**
		 *
		 * @var \Menu\Model\Orm\Menu
		 */
		$clone = parent::cloneSelf ();
		unset ( $clone['alias'] );
		return $clone;
	}
}

