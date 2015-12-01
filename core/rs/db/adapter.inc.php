<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Db;

/**
* Класс работы с базой данных
*/
class Adapter
{
    protected static $_conn; //Указатель на соединение с базой
    
    public static function init()
    {
        if (\Setup::$LOG_SQLQUERY_TIME) {
            $log_file = \Setup::$PATH.\Setup::$LOG_SQLQUERY_FILE;
            if (!is_dir(dirname($log_file))) \RS\File\Tools::makePath(dirname($log_file));
            $str = "\n-------- Page: ".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']." \n\n";
            file_put_contents($log_file, $str, FILE_APPEND);
        }
        
        if (self::connect()) {
            self::sqlExec("USE `".\Setup::$DB_NAME."`");
        }
    }
    
    /**
    * Открывает соединение с базой данных
    */
    public static function connect()
    {
        self::$_conn = @mysql_connect(\Setup::$DB_HOST, \Setup::$DB_USER, \Setup::$DB_PASS);

        if(self::$_conn){
            self::sqlExec("SET names utf8");
        }

        return self::$_conn;
    }
    
    /**
    * Закрывает соединение с базой данных
    */
    public static function disconnect()
    {
        return mysql_close(self::$_conn);        
    }
        
    /**
    * Выполняет sql запрос
    * 
    * @param mixed $sql - SQL запрос.
    * @param array | null $values - массив со знчениями для замены.
    * @return Result
    */
    public static function sqlExec($sql, $values = null)
    {
        if(!self::$_conn){
            if (\Setup::$INSTALLED) {
                throw new Exception(t('Не установлено соединение с базой данных'));
            }
            return new Result(false);
        }

        if ($values !== null) {
            foreach($values as $key => $val) {
                $sql = str_replace("#$key",  self::escape($val) , $sql);
            }
        }
        
        $start_time = microtime(true);
        $resource = mysql_query($sql, self::$_conn);
        
        if (\Setup::$LOG_SQLQUERY_TIME) {
            $log_file = \Setup::$PATH.\Setup::$LOG_SQLQUERY_FILE;
            $str = (microtime(true)-$start_time)." - {$sql}\n";
            file_put_contents($log_file, $str, FILE_APPEND);
        }
        
        // Блок необходим для определения неактуальности кэша
        if (\Setup::$CACHE_ENABLED && \Setup::$CACHE_USE_WATCHING_TABLE) { //Если кэш включен, то пишем информацию о модификации таблиц
            self::invalidateTables($sql);
        }

        $error_no = mysql_errno(self::$_conn);
        if ($error_no != 0){
            //Не бросаем исключения об ошибках - неверено указана БД, не существует таблицы, не выбрана база данных, когда включен режим DB_INSTALL_MODE
            if ( !\Setup::$DB_INSTALL_MODE || !($error_no == 1102 || $error_no == 1146 || $error_no == 1046 || $error_no == 1142) ) {
                throw new Exception($sql.mysql_error(self::$_conn), $error_no);
            }
        }
        return new Result($resource);
    }
    
    /**
    * Парсит sql запрос и делает отметку о изменении таблиц, присутствующих в запросе
    * @param string $sql
    */
    private static function invalidateTables($sql)
    {
        //Находим имя таблицы и базы данных у SQL запроса
        $sql = preg_replace(array('/\s\s+/','/\n/'), ' ', $sql); //Удаляем лишние пробелы и переносы строк
        
        if (preg_match('/^(INSERT|UPDATE|DELETE)/ui', $sql)) {
            if (preg_match('/^INSERT.*?INTO([a-zA-Z0-9\-_`,\.\s]*?)(\(|VALUE|SET|SELECT)/ui', $sql, $match)) {
                $tables_str = $match[1];
            }
            if (preg_match('/^UPDATE (LOW_PRIORITY|IGNORE|\s)*([a-zA-Z0-9\-_`,\.\s]*).*?SET/ui', $sql, $match)) {
                $tables_str = $match[2];
            }
            if (preg_match('/^DELETE(LOW_PRIORITY|QUICK|IGNORE|\s)*(.*?)FROM(.*?)((WHERE|ORDER\sBY|LIMIT).*)?$/ui', $sql, $match)) {
                $tables_str = $match[3];
            }
            
            //Исключительно на время разработки
            if (\Setup::$LOG_SQLQUERY_TIME && !isset($tables_str)) {
                $log_file = \Setup::$PATH.'/logs/parse_query.txt';
                if (!is_dir(dirname($log_file))) \RS\File\Tools::makePath(dirname($log_file));
                $str = (microtime(true)-$start_time)." - {$sql}\n";
                file_put_contents($log_file, $str, FILE_APPEND);                
            }
        
            $tables_arr = preg_split('/[,]+/', $tables_str, -1, PREG_SPLIT_NO_EMPTY);
            foreach($tables_arr as $table) {
                //Очищаем имя базы данных и имя таблицы
                if (preg_match('/(?:(.*?)\.)?(.*?)( (as)?.*?)?$/ui', $table, $match)) {
                    $db = trim($match[1],"` ");
                    $table = trim($match[2],"` ");
                    \RS\Cache\Manager::obj()->tableIsChanged($table, $db);
                }
            }
        }
    }
    
    /**
    * Экранирует sql запрос.
    * 
    * @param string $str SQL запрос
    * @return string экранированный запрос
    */
    public static function escape($str)
    {
        if(!self::$_conn) {
            return $str;
        }
        
        return mysql_real_escape_string($str, self::$_conn);
    }
    
    /**
    * Возвращает значение инкремента для последнего INSERT запроса
    * 
    * @return integer | bool(false)
    */
    public static function lastInsertId()
    {
        return mysql_insert_id(self::$_conn);
    }    

    /**
    * Возвращает число затронутых в результате запросов строк
    * 
    * @return integer
    */    
    public static function affectedRows()
    {
        return mysql_affected_rows(self::$_conn);
    }    
    
    /**
    * Возвращает версию Mysql сервера
    * 
    * @return string | bool(false)
    */
    public static function getVersion()
    {
        return mysql_get_server_info(self::$_conn);
    }
    
}

if (\Setup::$DB_AUTOINIT) {
    Adapter::init();
}