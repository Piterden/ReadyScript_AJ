<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Main\Model;

/**
* API по работе с виджетами в админке
*/
class Widgets
{
    protected
        $cur_user_id,
        $column_schema = array('left', 'right', 'center'), //Список колонок на рабочем столе
        $default_add_column = 'center', //Добавляем в колонку 1 по умолчанию, нумерация начинается с 0.
        $obj = '\Main\Model\Orm\Widgets',
        $obj_instance,
        $site_id,
        $widget_folder = 'controller/admin/widget';
    
    function __construct()
    {
        $this->obj_instance = new $this->obj();
        $this->site_id = \RS\Site\Manager::getSiteId();
        $this->setUserId(\RS\Application\Auth::getCurrentUser()->id);
    }
    
    /**
    * Устанавливает текущего пользователя. Выборки будут это учитывать.
    */
    function setUserId($user_id)
    {
        $this->cur_user_id = $user_id;
    }
    
    /**
    * Возвращает список виджетов на главную страницу, разбитый по колонкам
    * 
    * @param integer $total - возвращает общее количество виджетов
    * @return array
    */
    function getMainList(&$total)
    {
        $list = \RS\Orm\Request::make()
            ->from($this->obj_instance)
            ->orderby("col, position")
            ->where(array(
                'site_id' => $this->site_id,
                'user_id' => $this->cur_user_id
            ))
            ->objects(null, 'class');
        
        $total = count($list);
        $cols = array();
        foreach($this->column_schema as $column)
            $cols[$column] = array();
            
        foreach($list as $widget) {
            $full_class_name = Orm\Widgets::staticGetFullClass($widget['class']);
            if (class_exists($full_class_name)) {
                $cols[ $widget['col'] ][] = $widget;                
            } else {
                //Удаляем с рабочего стола несуществующие виджеты
                $this->removeWidget($widget['class']);
            }
        }
        return $cols;
    }
    
    function insertWidget($wclass, $column = null)
    {
        if ($this->noWriteRights()) return false;
        
        if ($this->issetWidget($wclass)) {
            $widgets = new $this->obj();
            $widgets['site_id'] = $this->site_id;
            $widgets['user_id'] = $this->cur_user_id;
            $widgets['class'] = $wclass;
            $widgets['col'] = $this->default_add_column;
            $widgets['position'] = $this->getNextPos($this->default_add_column);
            $widgets->insert();
            return $widgets['id'];
        }
    }
    
    function removeWidget($widget_controller)
    {
        if ($this->noWriteRights()) return false;
        
        \RS\Orm\Request::make()->delete()
            ->from($this->obj_instance)
            ->where(array(
                'class' => $widget_controller,
                'site_id' => $this->site_id,
                'user_id' => $this->cur_user_id
            ))->exec();
    }
    
    /**
    * Перемещение виджета по рабочему столу
    * 
    * @param mixed $id - id виджета
    * @param mixed $to_column - имя новой колонки
    * @param mixed $to_pos - порядковый номер виджета в колонке, начиная с нуля
    */
    function moveWidget($id, $to_column, $to_pos)
    {
        if ($this->noWriteRights()) return false;
        
        $cur_widget = $this->getWidgetRecord($id);
        if (!$cur_widget || ($cur_widget['col'] == $to_column && $cur_widget['position'] == $to_pos)) return false;
        
        $sqls = array();
        if ($cur_widget['col'] != $to_column) { //У виджета сменилась колонка
            
            //Обновляем порядковые номера в связи с уходом виджета из колонки
            $sqls[] = \RS\Orm\Request::make()->update($this->obj_instance)
                    ->set('position = position-1')
                    ->where(array(
                        'site_id' => $this->site_id,
                        'user_id' => $this->cur_user_id,
                        'col' => $cur_widget['col']
                    ))
                    ->where("position>'#cur_pos'", array('cur_pos' => $cur_widget['position']));

            //Последняя позиция в новой колонке            
            $next_position = \RS\Orm\Request::make()->select("MAX(position)+1 as next_pos")
                    ->from($this->obj_instance)->where(array(
                        'site_id' => $this->site_id,
                        'user_id' => $this->cur_user_id,
                        'col' => $to_column
                    ))->exec()->getOneField('next_pos', 0);
            
            //Вставляем виджет в самый низ новой колонки
            $sqls[] = \RS\Orm\Request::make()
                ->update($this->obj_instance)
                ->set(array(
                    'position' => $next_position, 
                    'col' => $to_column))
                ->where(array(
                    'site_id' => $this->site_id,
                    'user_id' => $this->cur_user_id,
                    'id' => $id
                ));

            $cur_widget['position'] = $next_position;
            $cur_widget['col'] = $to_column;
        }
        
        if (!isset($next_position) || $next_position != 0) //Если виджет не один в колонке
        {
            //Определяем направлене перемещения 
            if ($cur_widget['position'] < $to_pos) {
                //Вниз
                $sqls[] = \RS\Orm\Request::make()
                        ->update($this->obj_instance)
                        ->set('position = position-1')
                        ->where(array(
                            'site_id' => $this->site_id,
                            'user_id' => $this->cur_user_id,
                            'col' => $to_column
                        ))
                        ->where("position>'#cur_pos' AND position<='#to_pos'", array('cur_pos' => $cur_widget['position'], 'to_pos' => $to_pos));
            } else { 
                //Вверх
                $sqls[] = \RS\Orm\Request::make()
                        ->update($this->obj_instance)
                        ->set('position = position+1')
                        ->where(array(
                            'site_id' => $this->site_id,
                            'user_id' => $this->cur_user_id,
                            'col' => $to_column
                        ))
                        ->where("position>='#to_pos' AND position<'#cur_pos'", array('cur_pos' => $cur_widget['position'], 'to_pos' => $to_pos));                
            }
            $sqls[] = \RS\Orm\Request::make()
                    ->update($this->obj_instance)
                    ->set(array(
                        'position' => $to_pos
                    ))
                    ->where(array(
                        'site_id' => $this->site_id,
                        'user_id' => $this->cur_user_id,
                        'id' => $id
                    ));
        }
        
        foreach($sqls as $request) {
            $request->exec();
        }
    }
    
    function getNextPos($column)
    {
        return \RS\Orm\Request::make()
            ->select('MAX(position)+1 as max')
            ->from($this->obj_instance)
            ->where(array(
                'col' => $column,
                'site_id' => $this->site_id,
                'user_id' => $this->cur_user_id
            ))->exec()->getOneField('max', 0);
    }
    
    /**
    * Возвращает запись о виджете на рабочем столе
    * @param mixed $id
    */
    function getWidgetRecord($id)
    {
        $widget = \RS\Orm\Request::make()->select('*')
            ->from($this->obj_instance)
            ->where(array(
                'id' => $id,
                'user_id' => $this->cur_user_id,
                'site_id' => $this->site_id,
            ))
            ->object();
        return $widget;
    }
    
    /**
    * Возвраает список всех виджетов в системе.
    */
    function getFullList($appendInfo = false)
    {
        $module_folder_list = scandir(\Setup::$PATH.\Setup::$MODULE_FOLDER);
        $widgets = array();
        foreach($module_folder_list as $item) {
            if ($item != '.' && $item != '..' && $item != '.svn') {
                $widgets += $this->moduleWidgets($item);
            }
        }
        if ($appendInfo) 
            $widgets = $this->appendInfo($widgets);
        
        return $widgets;
    }
    
    /**
    * Добавляет информацию из базы(используется ли виджет) к списку виджетов.
    * @param mixed $widget_list
    */
    protected function appendInfo(array $widget_list)
    {
        if (count($widget_list)) {
            $res = \RS\Orm\Request::make()->select("class") //Проверяем какие классы присутствуют у пользователя на "рабочем столе"
                    ->from($this->obj_instance)
                    ->where(array('user_id' => $this->cur_user_id, 'site_id' => $this->site_id))
                    ->whereIn('class', array_keys($widget_list))
                    ->exec();
            
            while ($row = $res->fetchRow()) {
                $widget_list[$row["class"]]['use'] = 1;
            }
        }
        return $widget_list;
    }
    
    /**
    * Возвращает список виджетов у модуля или пустой массив, если модулей нет.
    */
    function moduleWidgets($module)
    {
        $widget_folder = \Setup::$PATH.\Setup::$MODULE_FOLDER.'/'.$module.'/'.$this->widget_folder;
        $widgets = array();
        if (is_dir($widget_folder)) {
            $files = scandir($widget_folder);
            foreach($files as $file)
                if ($file != '.' && $file != '..' && $file != '.svn') {
                    $filename = basename($file);
                    $widget_name = str_replace(array('.my.'.\Setup::$CLASS_EXT, '.'.\Setup::$CLASS_EXT), '', $filename);
                    $widget_class = $module.'\\'.str_replace('/', '\\', $this->widget_folder).'\\'.$widget_name;
                    
                    if ($this->issetWidget($widget_class)) {
                        $instance = new $widget_class();
                        $info = $instance->getWidgetInfo();
                        $widgets[$info['short_class']] = $info;
                        unset($instance);
                    }
                }
        }
        return $widgets;
    }
    
    function getWidgetOut($widget_controller, $param = array())
    {
        //т.к. $widget_controller может быть получен из GET, проверяем родителя класса.
        if ($full_class_name = $this->issetWidget($widget_controller)) {
            $com = new $full_class_name($param);
            return $com->exec();
        }
        return false;
    }
    
    /**
    * Возвращает полное имя класса контроллера виджета или false, если контроллера не существует
    * 
    * @param mixed $widget_controller
    * @return string | false
    */
    function issetWidget($widget_controller)
    {
        $full_class_name = Orm\Widgets::staticGetFullClass($widget_controller);
        if (class_exists($full_class_name) && is_subclass_of($full_class_name, '\RS\Controller\Admin\Widget')) {
            return $full_class_name;
        }
        return false;
    }
    
    function noWriteRights()
    {
        return \RS\AccessControl\Rights::CheckRightError($this, ACCESS_BIT_WRITE);
    }
    
    /**
    * Возвращает объект виджета по названию класса
    * 
    * @param string $wclass
    * @return Orm\Widgets
    */
    function getWidgetByWClass($wclass)
    {
        return \RS\Orm\Request::make()
                    ->from($this->obj_instance)
                    ->where(array(
                        'class' => $wclass,
                        'site_id' => $this->site_id
                    ))->object();
    }
    
}
