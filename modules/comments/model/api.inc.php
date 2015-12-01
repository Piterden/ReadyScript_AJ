<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Comments\Model;

class Api extends \RS\Module\AbstractModel\EntityList
{
    const
        COMMENT_TYPE_FOLDER = '/model/commenttype'; //Путь к папке с классами типов комментариев. Относительно корня модуля
        
    protected
        $config, //Конфиг комментариев
        $replace_by_ip = false; //только 1 комментарий от одного ip на один aid

    function __construct()
    {
        $this->config = \RS\Config\Loader::byModule('comments');
        parent::__construct(new \Comments\Model\Orm\Comment,
        array(
            'loadOnDelete' => true,
            'multisite' => true,
            'defaultOrder' => 'dateof DESC'
        ));
    }
    
    function replaceByIp($bool)
    {
        $this->replace_by_ip = $bool;
        if ($this->config['allow_more_comments']){
            $this->replace_by_ip = false;
        }
    }
    
    /**
    * Возвращает список возможных типов комментариев
    * 
    * @param bool $only_titles - Если true, то в значении массива будет строка с названием, иначе объект типа
    * @return array
    */
    public static function getAllowTypes($only_titles = true)
    {
        $module_api = new \RS\Module\Manager();
        $modules = $module_api->getList();
        
        $list = array();
        foreach($modules as $mod_name => $module) {
            $type_folder = $module->getFolder().'/'.self::COMMENT_TYPE_FOLDER;
            if (file_exists($type_folder)) {
                $files = glob($type_folder.'/*.'.\Setup::$CLASS_EXT);
                foreach($files as $file) {
                    $name = str_replace('.'.\Setup::$CLASS_EXT, '', basename($file));
                    $class_comment_type = str_replace('/', '\\', '\\'.$mod_name.self::COMMENT_TYPE_FOLDER.'\\'.$name);
                    
                    if (class_exists($class_comment_type)) {
                        $instance = new $class_comment_type();
                        if ($instance instanceof \Comments\Model\IType) {
                            $list[$class_comment_type] = $only_titles ? $instance->getTitle() : $instance;
                        }
                    }
                }
                    
            }
        }
        return $list;
    }
    
    /**
    * Возвращает Все существующие типы комментариев
    */
    function getTypeList()
    {
        $res = \RS\Orm\Request::make()
            ->select('type')
            ->from($this->obj_instance)
            ->groupby('type')
            ->exec();

        $result = array();
        $type_instances = array();
        while($row = $res->fetchRow()) {
            if (!isset( $type_instances[$row['type']] )) {
                $type_instances[$row['type']] = class_exists($row['type']) ? new $row['type']() : false;
            }
            $type = $type_instances[$row['type']];
            $result[$row['type']] = ($type !== false) ? $type->getTitle() : $row['type'].'(класс удален)';
        }
        
    	return $result;
	}
    
    
    /**
    * Сохраняет POST с комментарием от текущего пользователя
    */
    function addComment($aid, $type)
    {        
        //Если пользователь не авторизован и модуль каптчи включён, то проверяем капчу
        $captcha_config = \RS\Config\Loader::byModule('kaptcha');
        if (!\RS\Application\Auth::isAuthorize() && $captcha_config['enabled']) {
            $this->getElement()->__captcha->setEnable(true);
        }
        
        $data = array(
            'aid' => $aid,
            'type' => $type,
            'moderated' => 0,
            'dateof' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'useful' => 0,
            'help_yes' => 0,
            'help_no' => 0
        );

        if (\RS\Application\Auth::isAuthorize()) {
            $user = \RS\Application\Auth::getCurrentUser();
            $data['user_id'] = $user['id'];
        }        
        
        if ($this->getElement()->checkData($data)) {
            if ($this->replace_by_ip) {
                //Удаляем коммент с голосами
                \RS\Orm\Request::make()
                    ->delete('A, B')
                    ->from($this->obj_instance)->asAlias('A')
                    ->leftjoin(new Orm\Vote(), 'A.id = B.comment_id', 'B')
                    ->where("A.aid = '#aid' AND A.ip = '#ip'", array(
                        'aid' => $data['aid'],
                        'ip' => $data['ip']
                    ))
                    ->exec();
            }
            
            $this->getElement()->__captcha->setEnable(false);
            return $this->save(null, $data);
        } else {
            return false;
        }
    }
    
    function save($id = null, $user_post = array())
    {
        $ret = parent::save($id, $user_post);
        if ($ret) return $this->onAddComment($this->getElement()); //Если успешно, то вызываем callback
        return $ret;
    }
    
    /**
    * Вызывает callback у типа комментария, если таковой метод имеется.
    * Вызывается когда комментарий добавлен
    */
    function onAddComment(Orm\Comment $comment)
    {
        //Попытаемся вызвать метод onAdd у класса-типа комментариев.
        $type = $comment['type'];
        $type_class = new $type();
        if (method_exists($type_class, 'onAdd')) {
            return $type_class->onAdd($comment);
        }
        return true;
    }
    
    /**
    * Добавляет или убавляет рейтинг комментарию
    * 
    * @param integer $comment_id
    * @param mixed $help - yes или no
    */
    function markHelpful($comment_id, $help)
    {
        $help = ($help == 'yes') ? 1 : -1;
        
        $vote = new Orm\Vote();
        $vote['ip'] = $_SERVER['REMOTE_ADDR'];
        $vote['comment_id'] = $comment_id;
        $vote['help'] = $help;
        $vote->replace();
        
        //Пересчитываем количество положительных и отрицательных оценок у комментария
        \RS\Orm\Request::make()
            ->update($this->obj_instance)
            ->set("help_yes = (SELECT COUNT(*) FROM ".$vote->_getTable()." WHERE comment_id = id AND help=1)")
            ->set("help_no = (SELECT COUNT(*) FROM ".$vote->_getTable()." WHERE comment_id = id AND help='-1')")
            ->set("useful = (SELECT SUM(help) FROM ".$vote->_getTable()." WHERE comment_id = id)")
            ->where(array('id' => $comment_id))
            ->exec();
    }
    
    /**
    * Подсчитывает рейтинг элемента или товара на основе количества комментариев
    * Подсчёт на основе комментария
    * 
    * @param \RS\Orm\OrmObject $object - объект элемента к которому делали комментарий
    * @param \Comments\Model\Orm\Comment $comment - объект комментария
    */
    function recountItemRatingByComment(\RS\Orm\OrmObject $object, \Comments\Model\Orm\Comment $comment)
    {
        $result = \RS\Orm\Request::make()
                ->select('SUM(rate) sum, COUNT(*) cnt')
                ->from($comment)
                ->where(array('aid' => $comment['aid'], 'type' => $comment['type']))
                ->exec()->fetchRow();
        $cnt = isset($result['cnt']) ? $result['cnt'] : 0;
        $rating = round( ($cnt>0) ? (isset($result['sum']) ? $result['sum'] : 0) / $cnt : 0 , 1);
        
        \RS\Orm\Request::make()
            ->update($object)
            ->set(array(
                'rating' => $rating,
                'comments' => $cnt,
            ))
            ->where(array('id' => $comment['aid']))
            ->exec();
    }
    
    
    /**
    * Устанавливаем условие, чтобы загружалась информация о голосованиях.
    */
    function joinVoteInfo()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->queryObj()->leftjoin(new Orm\Vote(), "V.comment_id = {$this->def_table_alias}.id AND V.ip='$ip'", 'V');
    }
    
    /**
    * Устанавливает фильтр по типу комментария
    * 
    * @param Abstracttype $type_object
    * @return Api
    */
    function setCommentTypeFilter(Abstracttype $type_object)
    {
        $this->setFilter('type', '\\'.ltrim(strtolower(get_class($type_object)),'\\'));
        return $this;
    }
    
    function alreadyWrite($aid, $ip = null)
    {   
        if ($ip === null) $ip = $_SERVER['REMOTE_ADDR'];
        
        $count = \RS\Orm\Request::make()->from($this->obj_instance)->where(array('aid' => $aid, 'ip' => $ip))->count();
        return ($count > 0);
    }
    
}

