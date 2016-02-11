<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace RS\Helper;

/**
 * Класс позволяет сохранять отладочную информацию в файл
 */
class Log
{
	private static $instance = array();
	private $filename;
	
	/**
	 * Конструктор класса.
	 * Инициализоровать класс следует
	 * через статический конструктор: Log::file(...)->
	 * 
	 * @param string $filename полный путь к log-файлу на диске
	 */
	protected function __construct($filename)
	{
		$this->filename = $filename;
		$dir = dirname ( $this->filename );
		if (! is_dir ( $dir )) {
			\RS\File\Tools::makePath ( $dir ); // Создаем директорию, при необходимости
		}
	}
	
	/**
	 * Статический конструктор.
	 * 
	 * @param string $filename полный путь к log-файлу на диске
	 * @return Log
	 */
	public static function file($filename)
	{
		if (! isset ( $instance[$filename] )) {
			$instance[$filename] = new self ( $filename );
		}
		return $instance[$filename];
	}
	
	/**
	 * Дополняет лог файл сообщением $data
	 * 
	 * @param string $data - текст для логирования
	 * @return Log
	 */
	public function append($data)
	{
		file_put_contents ( $this->filename, $data . "\n", FILE_APPEND );
		return $this;
	}
	
	/**
	 * Очищает лог файл
	 * 
	 * @return Log
	 */
	public function clean()
	{
		file_put_contents ( $this->filename, '' );
		return $this;
	}
	
	/**
	 * Удаляет лог файл
	 * 
	 * @return Log
	 */
	public function remove()
	{
		@unlink ( $this->filename );
		return $this;
	}
}

?>
