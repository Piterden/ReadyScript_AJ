<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace RS\Orm\Type;

class File extends AbstractType
{
	protected $sql_notation = 'varchar', $me_visible = false, $max_len = 255;
	public $php_type = "string", $requesttype = "array", $form_template = '%system%/coreobject/type/form/file.tpl', 

	$max_file_size, $allow_file_types; // Путь для сохранения файла
	protected $base = '', //
$path = '', // Относительный путь
$storage = '';
	protected $original_folder = '/storage/files', $uniq, $tmp_arr;
	function __construct(array $options = null)
	{
		$this->original_folder = \Setup::$FOLDER . $this->original_folder;
		$this->setStorage ( \Setup::$ROOT, $this->original_folder );
		
		parent::__construct ( $options );
		
		$this->setChecker ( array($this,'checkSize' 
		), 'Загружен слишком большой файл' );
		$this->setChecker ( array($this,'checkType' 
		), 'Загружен неподдерживаемый формат файла' );
	}
	public function normalizePost()
	{
		if ($this->value && isset ( $this->value['tmp_name'] )) {
			$this->tmp_arr = $this->value;
			$this->value = $this->generateValue ( $this->tmp_arr['name'] );
		} else {
			$this->value = null;
		}
	}
	public function selfSave()
	{
		if (isset ( $this->tmp_arr ) && $this->checkFolder ( $this->storage )) {
			if (! move_uploaded_file ( $this->tmp_arr['tmp_name'], $this->storage . $this->uniq )) {
				$this->value = '';
			}
		}
	}
	
	/**
	 * Создает при необходимости папку.
	 */
	protected function checkFolder($dstFilename)
	{
		return file_exists ( $dstFilename ) ? true : mkdir ( $dstFilename, \Setup::$CREATE_DIR_RIGHTS, true );
	}
	public function validate($value)
	{
		return true;
	}
	public function checkSize($object, $param, $errortext)
	{
		if (empty ( $this->tmp_arr )) return true; // Если файл был не выбран, не проверяем на ошибки
		
		if ($this->max_file_size && $this->tmp_arr['size'] > $this->max_file_size) return $errortext;
		return true;
	}
	public function checkType($object, $param, $errortext)
	{
		if (empty ( $this->tmp_arr )) return true; // Если файл был не выбран, не проверяем на ошибки
		if (! empty ( $this->allow_file_types )) {
			if (! in_array ( $this->tmp_arr['type'], $this->allow_file_types )) return $errortext;
		}
		return true;
	}
	
	/**
	 * Устанавливает хранилище для файлов.
	 */
	public function setStorage($base, $path)
	{
		$this->base = $base;
		$this->path = rtrim ( $path, '/' );
		$this->storage = $this->base . $this->path . '/';
	}
	
	/**
	 * Возвращает ссылку на файл оригинал
	 */
	public function getLink()
	{
		return $this->path . '/' . $this->getRealPath ();
	}
	
	/**
	 * Возвращает абсолютный путь к файлу на диске
	 * 
	 * @return string
	 */
	public function getFullPath()
	{
		return $this->storage . '/' . $this->getRealPath ();
	}
	
	/**
	 * Возвращает внутреннее имя файла
	 */
	public function getRealPath()
	{
		if (preg_match ( '/(.*?)_(.*)/', $this->get (), $match )) {
			return $match[1];
		}
		return false;
	}
	
	/**
	 * Возвращает первоначальное имя файла
	 */
	public function getFileName()
	{
		if (preg_match ( '/(.*?)_(.*)/', $this->get (), $match )) {
			return $match[2];
		}
		return false;
	}
	public function getViewSize()
	{
		$size = filesize ( $this->getFullPath () );
		$ret = number_format ( $size / (1024 * 1024), 2, ',', ' ' ) . ' Мб';
		return $ret;
	}
	public function beforesave()
	{
		$upload_file_info = \RS\Http\Request::commonInstance ()->files ( $this->name );
		// Если передан флаг удаления файла и при этом нового файла не планируется заливать
		if (\RS\Http\Request::commonInstance ()->post ( 'del_' . $this->name, TYPE_INTEGER, false ) && empty ( $file_info )) {
			$this->value = '';
			return true;
		}
	}
	function checkDefaultRequestValue($value)
	{
		if (isset ( $value['error'] ) && $value['error'] == UPLOAD_ERR_NO_FILE) $value = null;
		return $value;
	}
	
	/**
	 * Максимально допустимый размер файла в байтах
	 * 
	 * @param Integer $filesize_in_bytes
	 */
	function setMaxFileSize($filesize_in_bytes)
	{
		$this->max_file_size = $filesize_in_bytes;
	}
	
	/**
	 * Возвращает значение для вставки в базу
	 * 
	 * @param string $original_name
	 * @return string
	 */
	function generateValue($original_name)
	{
		// Определяем тип изображения. Поддерживается png, jpg, gif
		list ( $name, $ext ) = \RS\File\Tools::parseFileName ( $original_name );
		$this->uniq = md5 ( uniqid ( rand (), true ) ) . $ext; // Сохраняем файл без расширения
		return $this->uniq . '_' . $original_name;
	}
	
	/**
	 * Возвращает сгенерированное имя файла
	 * 
	 * @return string
	 */
	function getUniqFilename()
	{
		return $this->uniq;
	}
	
	/**
	 * Возвращает папку на сервере, где хранятся загруженные файлы
	 * 
	 * @return string
	 */
	function getStorageFolder()
	{
		return $this->storage;
	}
	
	/**
	 * Сохраняет файл в хранилище
	 * 
	 * @return string возвращает значение для вставки в базу для текущего свойства
	 */
	function addFromUrl($url)
	{
		$result = '';
		if (file_exists ( $url ) && $this->checkFolder ( $this->storage )) {
			$this->value = $this->generateValue ( basename ( $url ) );
			if (! copy ( $url, $this->storage . $this->uniq )) {
				$this->value = '';
			}
		}
		return $this->value;
	}
}