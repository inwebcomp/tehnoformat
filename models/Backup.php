<?php 

class Backup
{
	/**
	Переменные класса
	*/
	public $basedir = '/backups';
	
	public $documentroot = '/';
	
	public static $folderName;
	public static $folderPath;
	public static $filesPath;
	public static $filesList = array();
	
	public $backup;

	/**
	Инициация директории с резервными файлами
	*/
	public function __construct($name = '')
	{
		if(class_exists('Model') and Model::$conf instanceof KernelSettings){
			$this->basedir = Model::$conf->backupsPath;
			$this->documentroot = Model::$conf->documentroot;
		}else{
			$this->basedir = $_SERVER['DOCUMENT_ROOT'].self::$basedir;
			$this->documentroot = $_SERVER['DOCUMENT_ROOT'];
		}
			
		if($name !== ''){
			if(!file_exists($this->basedir.'/'.$name.'.zip'))
				throw new Exception('Backup file '.$name.'.zip doesn\'t exists');
				
			$this->backup['zip_path'] = $this->basedir.'/'.$name.'.zip';
			$this->backup['folder_path'] = $this->basedir.'/'.$name;
		}
	}
	
	/**
	Проход по файлам директории
	
	Функция должна быть идентична функции Update::ListDirectory()
	*/
	public static function ListDirectory($dir, $recursive = false, $extensions = false, $folders = false, $only_files = false)
	{ 
		$files = array();
			
		// Выявляем расширения, которые нужно пропустить и которые нужно принудительно сканировать
		if(is_array($extensions)){
			$miss_extensions = $force_extensions = array();
			foreach($extensions as $extension){
				if(substr($extension, 0, 1) == "!")
					$miss_extensions[] = substr($extension, 1);
				else
					$force_extensions[] = $extension;
			}
			if(count($miss_extensions) == 0) $miss_extensions = false;
			if(count($force_extensions) == 0) $force_extensions = false;
		}

		// Выявляем папки, которые нужно пропустить и которые нужно принудительно сканировать
		if(is_array($folders)){
			$miss_folders = $force_folders = array();
			foreach($folders as $folder){
				if(substr($folder, 0, 1) == "!")
					$miss_folders[] = substr($folder, 1);
				else
					$force_folders[] = $folder;
			}
			if(count($miss_folders) == 0) $miss_folders = false;
			if(count($force_folders) == 0) $force_folders = false;
		}
			
		// Читаем директорию
		if(is_dir($dir)){
			if($dh = opendir($dir)){
				while(($file = readdir($dh)) !== false){
					if($file !== '.' and $file !== '..'){					
						$ext_tmp = explode(".", $file);
						$ext = end($ext_tmp);
						$match_miss_folder = $match_force_folder = false;
					
						 // Проверка, если это нужная папка
						if(is_array($miss_folders))
							foreach($miss_folders as $folder){
								if(!$match_miss_folder)
									$match_miss_folder = (strpos($dir. '/' .$file, $folder) !== false) ? true : false;
							}
						if(is_array($force_folders))
							foreach($force_folders as $folder){
								if(!$match_force_folder)
									$match_force_folder = (strpos($dir. '/' .$file, $folder) !== false) ? true : false;
							}
							
						if( // Нужно ли пропускать файлы в папке и вызывать рекурсию
						((is_array($miss_folders) and !$match_miss_folder) or !is_array($miss_folders)) and
						((is_array($force_folders) and $match_force_folder) or !is_array($force_folders))
						)
							$miss_folder = false;
						else
							$miss_folder = true;	
						
						$is_dir = (is_dir($dir . '/' . $file)) ? true : false;
						if($is_dir or ( // Проверка, если это нужное расширение, либо это папка
						((is_array($miss_extensions) and !in_array($ext, $miss_extensions)) or !is_array($miss_extensions)) and
						((is_array($force_extensions) and in_array($ext, $force_extensions)) or !is_array($force_extensions))
						)){
							if(!$miss_folder and (!$only_files or ($only_files and !$is_dir))){
								$arr = array();
								$arr['dir'] = $dir . '/' . $file;
								$arr['name'] = $file;
								$arr['ext'] = $ext;
								array_pop($ext_tmp);
								$arr['name_only'] = implode(".", $ext_tmp);
								$arr['size'] = filesize($dir . '/' . $file);
	
								if($is_dir){
									$arr['is_dir'] = 1;
									$arr['ext'] = "";
								}
								
								$files[] = $arr;
							}
							
							// Рекурсия
							if($recursive and $is_dir)
								$files = array_merge($files, self::ListDirectory($dir . '/' . $file, $recursive, $extensions, $folders, $only_files));
						}
						
					}
				}
			}
		}
		
		return $files;
	}
	
	/**
	Удаляет папку
	*/
	public static function RemoveDir($dir)
	{
		if (!$dh = @opendir($dir)) return;
    	while (false !== ($obj = readdir($dh)))
    	{
			if ($obj == '.' || $obj == '..') continue;
			if (is_dir($dir . '/' .$obj)) Utils::RemoveDir($dir . '/' . $obj);
			else unlink($dir . '/' .$obj);
    	}
		closedir($dh);
    	@rmdir($dir);
		
		return true;
	}
	
	/**
	Архивирует папку
	*/
	public static function ZipFolder($source, $destination)
	{
		if (!extension_loaded('zip') || !file_exists($source)) {
			return false;
		}

		$zip = new ZipArchive();
		if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
			return false;
		}

		$source = str_replace('\\', '/', realpath($source));

		if (is_dir($source) === true)
		{
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

			foreach ($files as $file)
			{
				$file = str_replace('\\', '/', $file);

				// Ignore "." and ".." folders
				if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
					continue;

				$file = realpath($file);

				if (is_dir($file) === true)
				{
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				}
				else if (is_file($file) === true)
				{
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		}
		else if (is_file($source) === true)
		{
			$zip->addFromString(basename($source), file_get_contents($source));
		}

		return $zip->close();
	}
	
	/**
	Распаковка zip архива
	*/
	public static function UnpackZip($zipPath, $path)
    {
		$zip = new ZipArchive;

   		if($zip->open($zipPath) === true){
			$zip->extractTo($path);
			$zip->close();
			return true;
		}else
			return false;
	}
	
	/**
	Создаёт папку для бэкапа
	*/
	public function CreateFolder($name = '')
	{
		self::$folderName = ($name !== '' ? $name.'_' : '').'backup_'.date('d_m_Y');
		self::$folderPath = $this->basedir.'/'.self::$folderName;
		self::$filesPath = $this->basedir.'/'.self::$folderName.'/files';
		
		if(is_dir(self::$folderPath))
			self::RemoveDir(self::$folderPath);
		
		if(!is_dir(self::$folderPath)){
			mkdir(self::$folderPath, 0777);
		}
		
		mkdir(self::$folderPath.'/files', 0777);
			
		return true;
	}
	
	/**
	Бэкап файлов движка
	
	Сохраняет только файлы движка. Сделав бэкап, вы сможете вернуть в случае ошибки
	только версию движка. Все файлы, составляющие ваш проект, не сохранятся.
	*/
	public function BaseBackup()
	{
		$this->CreateFolder();

		$basedir = $_SERVER['DOCUMENT_ROOT'];
		
		// Папки и файлы, которые не нужно сохранять
		$folders = array(
			'!'.Model::$conf->backupsPath,
			'!'.Model::$conf->cachePath,
			'!'.Model::$conf->languagePath,
			'!'.Model::$conf->tmpPath,
			'!'.Model::$conf->filesPath,
			'!'.Model::$conf->exportPath,
			'!'.Model::$conf->modulesPath,
			'!'.Model::$conf->mediaContent.'/images',
			'!'.Model::$conf->mediaContent.'/content_images',
			'!'.Model::$conf->mediaContent.'/favicons',
			'!'.Model::$conf->mediaContent.'/_thumbs',
			'!'.Model::$conf->updatePath,
			'!'.Model::$conf->overridePath,
			'!'.Model::$conf->contentPath.'/frontend',
			'!'.Model::$conf->metaFrontendPath,
			'!'.Model::$conf->documentroot.'/bootstrap-files',
			'!'.Model::$conf->documentroot.'/engineInfo.cfg',
			'!'.Model::$conf->documentroot.'/settings.cfg',
			'!'.Model::$conf->documentroot.'/local_settings.cfg',
			'!'.Model::$conf->documentroot.'/index.php',
		);
		
		// Сканирование файлов и директорий
		$files = self::ListDirectory($basedir, true, false, $folders);
		if(is_array($files)){
			foreach($files as $file){
				$relDir = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file['dir']);
				$size = ($file['is_dir'] !== 1) ? filesize($file['dir']) : 0;
				$hash = ($file['is_dir'] !== 1) ? hash_file('md5', $file['dir']) : '';
				
				if($file['is_dir'] !== 1){
					if(copy($file['dir'], self::$filesPath.$relDir))
						self::$filesList[] = array(
							'name' => $file['name'],
							'dir' => $relDir,
							'size' => $size,
							'hash' => $hash
						);
					else
						echo 'Error! Can\'t copy file: '.$file['dir']."\n";
				}else{
					mkdir(self::$filesPath.$relDir, 0777);
					
					self::$filesList[] = array(
						'name' => '',
						'dir' => $relDir,
						'size' => 0,
						'hash' => ''
					);
				}
			}
		}
		
		return true;
	}
	
	/**
	Гененирует информацию о бэкапе
	*/
	public function CreatePackInfo($note = '')
	{
		$packageSize = 0;
		
		// Создание filesMap.txt
		$filesMap = self::$folderPath.'/filesMap.php';
		
		$content = "<?php\nreturn array(";
		
		foreach(self::$filesList as $file){
			$content .= "\n\t'".$file['dir']."' => array(\n\t\t'name' => '".$file['name']."',\n\t\t'path' => '".$file['dir']."',\n\t\t'size' => '".$file['size']."',\n\t\t'hash' => '".$file['hash']."'),";
			$packageSize += $file['size'];
		}
		
		$content .= "\n);";
		
		file_put_contents($filesMap, $content);	
		
		
		
		// Создание config.txt
		$configFile = self::$folderPath.'/config.txt';
		
		// Время упаковки бэкапа
		$content = 'Package date: '.date('d.m.Y H:i:s')."\r\n";
		
		// Количество файлов
		$content .= 'Number of files: '.count(self::$filesList)."\r\n";
		
		// Общий вес файлов
		$MB = $packageSize / 8 / 1024 / 1024;
		$content .= 'Package size: '.round($MB, 2).' MB ('.$packageSize." bit)";

		// Примечание
		if($note !== '')
			$content .= "\r\nNote: ".$note;
			
		if(!file_put_contents($configFile, $content))
			throw new Exception("Can't create config file");
	}
	
	/**
	Запаковывает бэкап
	*/
	public function Pack($note = '')
	{
		$this->CreatePackInfo($note);
		
		$zipFile = $this->basedir.'/'.self::$folderName.'.zip';
		
		if(file_exists($zipFile)) unlink($zipFile);
		
		if(self::ZipFolder(self::$folderPath, $zipFile)){
			self::RemoveDir(self::$folderPath);
			return str_replace($_SERVER['DOCUMENT_ROOT'], '', $zipFile);
		}else
			return false;
	}
	
	/**
	Возвращает информацию о файле бэкапа
	*/
	public function GetBackupInfo($filedir = '')
	{
		if(isset($this) and $this->backup)
			$filedir = $this->backup['zip_path'];
		elseif($filedir == '')
			throw new Exception(lang("Can't get file name"));
		
		$filedir_tmp = explode('/', $filedir);
		$file = array_pop($filedir_tmp);
		$dir = implode('/', $filedir_tmp);
		
		if(!file_exists($dir . '/' . $file))
			throw new Exception(lang("Can't get information of non-existing file"));
		
		$ext_tmp = explode('.', $file);
		$ext = array_pop($ext_tmp);
		$name_only = implode(".", $ext_tmp);
		$file_dir = $dir . '/' . $file;
		$size = filesize($dir . '/' . $file);
		$sizeMB = round($size / 8 / 1024 / 1024, 2);
		$createdTimestamp = filectime($dir . '/' . $file);
		$created = date('d.m.Y H:i:s', $createdTimestamp);
		
		$package = array(
			'name' => $name_only,
			'file' => $file,
			'dir' => $file_dir,
			'size' => $size,
			'sizeMB' => $sizeMB,
			'created' => $created,
			'createdTimestamp' => $createdTimestamp
		);
		
		return $package;
	}
	
	/**
	Возвращает список существующих бэкапов
	*/
	public static function GetBackupsList()
	{
		$list = array();
		
		$backup = new Backup();
		$dir = $backup->basedir;

		if(is_dir($dir)){
			if($dh = opendir($dir)){
				while(($file = readdir($dh)) !== false){
					if($file !== '.' and $file !== '..'){			
						$ext_tmp = explode('.', $file);
						$ext = end($ext_tmp);
						if($ext !== 'zip') continue;
						
						$list[] = $backup->GetBackupInfo($dir . '/' . $file);
					}
				}
			}
		}
		
		usort($list, function($a, $b){ return ($a['createdTimestamp'] < $b['createdTimestamp']); });
		
		return $list;
	}
	
	/**
	Разархивация резервной копии
	*/
	public function Unpack()
	{
		$unpackedFolder = $this->backup['folder_path'];
		
		if(is_dir($unpackedFolder)) self::RemoveDir($unpackedFolder);
		
		self::UnpackZip($this->backup['zip_path'], $unpackedFolder);
		
		if(!is_dir($unpackedFolder))
			throw new Excaption('can\'t unpck backup: '.$this->backup['zip_path']);
		
		return $unpackedFolder;
	}
	
	/**
	Устанавливает резервную копию
	*/
	public function Install()
	{	
		$folder = $this->Unpack();
		
		$files = self::ListDirectory($folder.'/files', true);
		
		if(count($files) > 0){
			foreach($files as $file){
				// Определение нового пути файлу
				$newFile = str_replace($folder.'/files', $this->documentroot, $file['dir']);
				
				if($newFile == $file['dir'])
					throw new Exception(lang('Не удалось определить новый путь файлу').': '.$file['dir']);
				
				if(isset($file['is_dir']) and $file['is_dir'] == 1){
					if(!is_dir($newFile)){
						mkdir($newFile, 0777, true);
					}
				}else{
					if(!copy($file['dir'], $newFile))
						throw new Exception(lang('Ошибка обновления файла').': '.$file['dir']);	
				}
			}
		}

		return true;
	}
}