<?php 

abstract class Update extends databaseObject implements iUpdate
{
	/**
	Переменные обновления
	*/
	public $title;
	public $version;
	public $min_version_required;
	public $max_version_required = '100.0';
	public $author = 'Александр Топало';
	public $description = '';
	public $innovations = array();
	public $warnings = array();
	public $steps;
	public static $base_steps = 4;

	/**
	Другие константы
	*/
	const TMP_DIR = "tmp";
	const FILES_DIR = "files";
	const TMP_FILES_DIR = "tmp/files";
	const TMP_PREPARED_FILES_FILE = "tmp/preparedFiles.txt";
	
	const TMP_CLASS_POSTFIX = "_TMP";
	
	const VERSION_NUMBERS = 4;

	/**
	Проверка на наличие нужных переменных, версии движка и наличие функций, прописанных вне класса. Инициация обновления.
	*/
	public function __construct()
	{
		if(!isset($this->title))
			throw new Exception(get_class($this) . ' missing module property $title');
		if(!isset($this->version))
			throw new Exception(get_class($this) . ' missing module property $version');
		if(!isset($this->min_version_required))
			throw new Exception(get_class($this) . ' missing module property $min_version_required');
		if(!isset($this->steps))
			throw new Exception(get_class($this) . ' missing module property $steps');
			
		$this->CheckVersion();
		
		$this->CheckFunctions();
		
		$this->Init();
	}	
	
	/**
	Приводит версию к нормальному для сравнения виду
	*/
	public static function NormalizeVersion($version)
	{ 
		$parts = explode('.', $version);
		if(count($parts) < self::VERSION_NUMBERS){
			for($i = 1; $i <= self::VERSION_NUMBERS - count($parts); $i++)
				$parts[] = '0';
		}
		
		return implode('.', $parts);
	}
	
	/**
	Проверка версии движка
	*/
	public function CheckVersion()
	{ 
		$min_version = self::NormalizeVersion($this->min_version_required);
		$max_version = self::NormalizeVersion($this->max_version_required);
		$engine_version = self::NormalizeVersion(ENGINE_VERSION);
		
		if(version_compare($engine_version, $min_version, ">=") == false)
			throw new Exception('Your engine version must be '.$min_version.' or higher', self::ERROR_VERSION);
			
		if(version_compare($engine_version, $max_version, ">=") == true)
			throw new Exception('Your engine version must be '.$max_version.' or lower', self::ERROR_VERSION);
	}
	
	/**
	Проверка функций, прописанных вне класса
	*/
	public function CheckFunctions()
	{ 
		// Utils
		if(!class_exists('Utils'))
			throw new Exception('Missing class: Utils', self::ERROR_FUNCTIONS);
			
		if(!method_exists('Utils', 'USort'))
			throw new Exception('Missing method in class Utils: Usort', self::ERROR_FUNCTIONS);
			
		if(!method_exists('Utils', 'GetFileList'))
			throw new Exception('Missing method in class Utils: GetFileList', self::ERROR_FUNCTIONS);
			
		if(!method_exists('Utils', 'RemoveDir'))
			throw new Exception('Missing method in class Utils: RemoveDir', self::ERROR_FUNCTIONS);
			
		if(!method_exists('Utils', 'ClearDir'))
			throw new Exception('Missing method in class Utils: ClearDir', self::ERROR_FUNCTIONS);
			
		if(!method_exists('Utils', 'RecursiveRemoveFile'))
			throw new Exception('Missing method in class Utils: RecursiveRemoveFile', self::ERROR_FUNCTIONS);
			
		if(!method_exists('Utils', 'RusLat'))
			throw new Exception('Missing method in class Utils: RusLat', self::ERROR_FUNCTIONS);
			
		if(!method_exists('Utils', 'UnpackZip'))
			throw new Exception('Missing method in class Utils: UnpackZip', self::ERROR_FUNCTIONS);
			
		if(!method_exists('Utils', 'PackZip'))
			throw new Exception('Missing method in class Utils: PackZip', self::ERROR_FUNCTIONS);
			
		if(!method_exists('Utils', 'ZipFolder'))
			throw new Exception('Missing method in class Utils: ZipFolder', self::ERROR_FUNCTIONS);
			
		// Checker
		if(!class_exists('Checker'))
			throw new Exception('Missing class: Checker', self::ERROR_FUNCTIONS);
			
		// Action
		if(!class_exists('Action'))
			throw new Exception('Missing class: Action', self::ERROR_FUNCTIONS);
			
		if(!method_exists('Action', 'GetParamNames'))
			throw new Exception('Missing method in class Action: GetParamNames', self::ERROR_FUNCTIONS);
			
		if(!method_exists('Action', 'GetParamsCount'))
			throw new Exception('Missing method in class Action: GetParamsCount', self::ERROR_FUNCTIONS);
	}
	
	/**
	Проход по файлам директории
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
	Возвращает метод или функцию в нормальном виде
	*/
	public static function GetNormalizedMethod($content, $method = true)
    {
		if(trim($content) == "")
			throw new Exception('Function code is empty');

		// Парсинг данных функции
		preg_match("/([\s\t]*)((?:public|static|private|protected|final|abstract|\s)*)(?:[\s]*)function ([A-Za-z_0-9]*?)(?:[\s]*)\(([^{}]*)\)([\s\t\n]*){([\s\t\n]*)/im", $content, $match);
		
		$tab = ($method) ? "\t" : '';
		$modifiers = trim($match[2]);
		$function = $match[3];
		$params = $match[4];
		$code_on_new_line = (trim($match[6], " \t\0") == "") ? false : true;
		
		// Очистка от данных функции и скобок
		$content = preg_replace("/[\n]*[\s\t]*}[\s\t]*$/i", "", $content);
		$content = str_replace($match[0], "", $content);
		
		// Создание полного кода функции
		$result = $tab.$modifiers.($modifiers !== '' ? ' ' : '').'function '.$function.'('.$params.")\n".$tab."{\n\t".($method?"\t":'').$content."\n".$tab."}\n";
		
		return $result;
    }
	
	/**
	Получение кода метода
	*/
	public static function GetMethodCode($className, $methodName, $normalize = false)
    {
		$result = false;
		
		$method = new ReflectionMethod($className, $methodName);
		
		$first_line = $method->GetStartLine();
		$last_line = $method->GetEndLine();
		
		$file = file($method->GetFileName());
		
		for($i = $first_line - 1; $i < $last_line; $i++){
			$result .= $file[$i];
		}
		
		if($normalize)
			$result = self::GetNormalizedMethod($result);
		
		return $result;
    }
	
	/**
	Получение кода функции
	*/
	public static function GetFunctionCode($functionName, $normalize = false)
    {
		$result = false;
		
		$function = new ReflectionFunction($functionName);
		
		$first_line = $function->GetStartLine();
		$last_line = $function->GetEndLine();
		
		$file = file($function->GetFileName());
		
		for($i = $first_line - 1; $i < $last_line; $i++){
			$result .= $file[$i];
		}
		
		if($normalize)
			$result = self::GetNormalizedMethod($result, false);
		
		return $result;
    }
	
	/**
	Приводит метод к нормальному виду
	*/
	public static function NormalizeMethod($className, $methodName)
    {
		$method = new ReflectionMethod($className, $methodName);
		
		$old_code = self::GetMethodCode($className, $methodName); // Старый код
		$new_code = self::GetNormalizedMethod($old_code); // Новый код
		
		if($old_code !== $new_code){
			$fileName = $method->GetFileName();
			if(file_exists($fileName)){
				$old_function_content = file_get_contents($fileName);
				$new_function_content = str_replace($old_code, $new_code, $old_function_content);
						
				if(!file_put_contents($fileName, $new_function_content)){
					file_put_contents($fileName, $old_function_content);
					throw new Exception('Rewriting file error');
				}
			}else{
				throw new Exception('Missing '.$className.' class file: '.$fileName);
			}
		}
		
		return true;
    }
	
	/**
	Приводит функцию к нормальному виду
	*/
	public static function NormalizeFunction($functionName)
    {
		$function = new ReflectionFunction($functionName);
		
		$old_code = self::GetFunctionCode($functionName); // Старый код
		$new_code = self::GetNormalizedMethod($old_code, false); // Новый код
		
		if($old_code !== $new_code){
			$fileName = $function->GetFileName();
			if(file_exists($fileName)){
				$old_function_content = file_get_contents($fileName);
				$new_function_content = str_replace($old_code, $new_code, $old_function_content);
						
				if(!file_put_contents($fileName, $new_function_content)){
					file_put_contents($fileName, $old_function_content);
					throw new Exception('Rewriting file error');
				}
			}else{
				throw new Exception('Missing '.$className.' class file: '.$fileName);
			}
		}
		
		return true;
    }
	
	/**
	Переименовывает класс в файле
	*/
	public static function TmpRenameClass($file, $class, $invert = false)
    {
		if(file_exists($file)){
			$old_content = file_get_contents($file);
			if($invert)
				$new_content = preg_replace('/class[\s]+('.$class.self::TMP_CLASS_POSTFIX.')([^{]*)/', 'class '.$class.'$2', $old_content);
			else
				$new_content = preg_replace('/class[\s]+('.$class.')([^{]*)/', 'class $1'.self::TMP_CLASS_POSTFIX.'$2', $old_content);
			
			if($old_content == $new_content)
				throw new Exception(lang('Временное переименование не удалось').': '.$file);
			
			if(!file_put_contents($file, $new_content)){
				file_put_contents($file, $old_content);
				throw new Exception(lang('Ошибка перезаписи файла с классом для временного переименования').': '.$file);
			}
		}else{
			throw new Exception(lang('Отсутствует файл с классом для временного переименования').': '.$file);
		}
		
		return $class.self::TMP_CLASS_POSTFIX;
    }
	

	
	/**
	Очищает папку с файлами обновлений
	*/
	public static function ClearUpdateFilesDir()
    { 
		if(is_dir(Model::$conf->updatePath.'/'.self::FILES_DIR)){
			if(!Utils::ClearDir(Model::$conf->updatePath.'/'.self::FILES_DIR))
				throw new Exception(lang('Ошибка очистки рабочей папки обновления'));
		}
		
		return true;
    }
	
	/**
	Очищает папку с временными файлами обновлений
	*/
	public static function ClearUpdateTmpDir()
    {
		if(is_dir(Model::$conf->updatePath.'/'.self::TMP_DIR)){
			if(!Utils::ClearDir(Model::$conf->updatePath.'/'.self::TMP_DIR))
				throw new Exception(lang('Ошибка очистки папки временных файлов обновления'));
		}
		
		return true;
    }
	
	/**
	Подключение класса обновления
	*/
	public function IncludeUpdate()
    {
		$file = Model::$conf->updatePath.'/'.self::FILES_DIR.'/update.php';
		if(!file_exists($file))
			throw new Exception(lang('В обновлении отсутствует файл update.php'), self::ERROR_UPDATE_STRUCTURE);
			
		include_once($file);
    }
	
	/**
	Вызов класса объекта обновления
	*/
	public function InitUpdateObject()
    {
		try{
			$update = new UpdateObject();
		}catch(Exception $ex){
			throw new Exception(lang('Ошибка в коде обновления').":\n ".$ex->getMessage(), self::ERROR_UPDATE_STRUCTURE);
		}
		return $update;
    }
	
	/**
	Сбор информации об обновлении
	*/
	public function GetUpdateInfo()
    {
		$info = array();
		
		self::IncludeUpdate();
		
		$update = self::InitUpdateObject();
		
		if($update){
			$info["title"] = $update->title;
			$info["description"] = $update->description;
			$info["author"] = $update->author;
			$info["version"] = $update->version;
			$info["min_version_required"] = $update->min_version_required;
			$info["max_version_required"] = $update->max_version_required;
			
			$innovations = array();
			if(is_array($update->innovations) and count($update->innovations)){
				foreach($update->innovations as $innovation){
					$innovations[]["value"] = $innovation;
				}
				$info["innovations_list"] = $innovations;
				$info["innovations"] = $update->innovations;
			}
			
			$warnings = array();
			if(is_array($update->warnings) and count($update->warnings)){
				foreach($update->warnings as $warning){
					$warnings[]["value"] = $warning;
				}
				$info["warnings_list"] = $warnings;
				$info["warnings"] = $update->warnings;
			}
		}

		return $info;
    }

	/**
	Распаковывает обновление
	*/
	public static function Unpack($file)
    {
		$info = array();
		
		if($file and file_exists($file['tmp_name'])){
			
			// Очищаем рабочую и временную папку
			self::ClearUpdateFilesDir();
			self::ClearUpdateTmpDir();
			
			// Распаковываем архив в рабочую папку
			if(Utils::UnpackZip($file['tmp_name'], Model::$conf->updatePath.'/'.Update::FILES_DIR)){
				unlink($file['tmp_name']); // Удаляем архив из глобального хранилища временных файлов
				
				$info = self::GetUpdateInfo();
			}else{
				throw new Exception(lang('Произошла ошибка при распаковке архива'));
			}
			
		}else{
			throw new Exception(lang('Архив не найден'));
		}
		
		return $info;
    }

	/**
	Получение списка шагов
	*/
	public function GetSteps()
	{
		$steps = array();
		
		$steps[] = array();
		
		$params = Update::GetExternalParams();
		if(self::CheckboxChecked($params["backup"]))
			$steps[] = array("value" => lang("Создание частичного бэкапа"));
			
		$steps[] = array("value" => lang("Сканирование файлов"));
		$steps[] = array("value" => lang("Копирование файлов обновления"));
		$steps[] = array("value" => lang("Подготовка файлов"));
		$steps[] = array("value" => lang("Резервирование файлов"));
		
		self::$base_steps = count($steps);
		
		// Получение шагов обновления
		if(is_array($this->steps)){
			foreach($this->steps as $n => $step){
				$n++;
				$steps[] = array("value" => ($step[1] !== '' ? $step[1] : lang('Шаг').' '.$n));
			}
		}
		
		$steps[] = array("value" => lang("Проверка синтаксиса обновления"));
		$steps[] = array("value" => lang("Проверка ошибок обновления"));
		$steps[] = array("value" => lang("Применение обновления"));
		$steps[] = array("value" => lang("Очистка кэша"));
		
		unset($steps[0]);
		
		return $steps;
	}

	/**
	Сохранение подготовленных файлов
	*/
	public function SavePreparedFiles($files)
	{
		if(is_array($files)){
			$preparedFiles = Model::$conf->updatePath.'/'.self::TMP_PREPARED_FILES_FILE;
			if(!file_put_contents($preparedFiles, serialize($files)))
				throw new Exception(lang('Ошибка при записи списка подготовленных файлов'));	
		}else{
			throw new Exception(lang('Список подготовленных файлов пуст'));	
		}
	}
	
	/**
	Получение подготовленных файлов
	*/
	public function GetPreparedFiles()
	{
		$preparedFiles = Model::$conf->updatePath.'/'.self::TMP_PREPARED_FILES_FILE;
		if(!file_exists($preparedFiles))
			throw new Exception(lang('Ошибка при чтении списка подготовленных файлов'));
		
		if(!$result = unserialize(file_get_contents($preparedFiles)))
			throw new Exception(lang('Ошибка при извлечении списка подготовленных файлов'));
			
		return $result;
	}

	/**
	Резервирование файлов
	*/
	public function ReserveFiles()
	{
		$files = self::GetPreparedFiles();
		
		if(count($files) > 0){
			foreach($files as $file){
				// Определение, обязательный ли файл, или нет
				$nonrequiredPos = strpos($file, "@");
				$nonrequired = ($nonrequiredPos !== false and $nonrequiredPos == 0) ? true : false;
				
				if(!file_exists($file) and !$nonrequired)
					throw new Exception(lang('Файл для резервирования не найден').': '.$file);
				
				// Определение нового пути файлу
				$newFile = str_replace(Model::$conf->documentroot, Model::$conf->updatePath.'/'.self::TMP_FILES_DIR, $file);
				
				if($newFile == $file)
					throw new Exception(lang('Не удалось определить новый путь файлу').': '.$file);
				
				// Определение новой директории файла	
				$tmp_file_dir = @explode('/', $newFile);
				if(is_array($tmp_file_dir)){
					unset($tmp_file_dir[count($tmp_file_dir)-1]);
					$newDirectory = implode('/', $tmp_file_dir);
				}else{
					throw new Exception(lang('Не удалось определить директории для файла').': '.$file);
				}
				
				// Рекурсивное создание директорий для файла
				if(!is_dir($newDirectory)){
					if(!mkdir($newDirectory, 0777, true))
						throw new Exception(lang('Не удалось создать директории для файла').': '.$file);
				}
				
				// Копирование файла
				if(!copy($file, $newFile))
					throw new Exception(lang('Ошибка резервирования файла').': '.$file);
			}
		}
	}
	
	/**
	Проверка синтаксиса
	*/
	public function CheckSyntax()
	{
		// Сканирование php файлов, задействованных в обновлении
		$files = Update::ListDirectory(Model::$conf->updatePath.'/'.self::TMP_FILES_DIR, true, array("php"), false, true);
		if(is_array($files)){
			foreach($files as $file){ 
				$content = file_get_contents($file['dir']);
				
				$check = function(){
					if(@eval('return true; '.$content) == false)
						return false;
					else
						return true;
				};
				
				/* Удаление <?php, <?, php?>, ?> для правильного определения ошиблок*/
				$content = preg_replace('/^[\s\t\n]*(\<\?php|\<\?)/', '', $content);
				$content = preg_replace('/(php\?\>|\?\>)[\s\t\n]*$/', '', $content);

				// Определение ошибок
				if($check() == false)
					throw new Exception(lang('Синтаксические ошибки в файле').' '.$file['dir']);
			}
		}
	}
	
	/**
	Обновление файлов
	*/
	public function UpdateFiles()
	{
		self::ClearCache(array('!filesMap.php'));
		
		$files = Update::ListDirectory(Model::$conf->updatePath.'/'.self::TMP_FILES_DIR, true);
		
		if(count($files) > 0){
			foreach($files as $file){
				// Определение нового пути файлу
				$newFile = str_replace(Model::$conf->updatePath.'/'.self::TMP_FILES_DIR, Model::$conf->documentroot, $file['dir']);
				
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
		
		try{
			$this->UpdateEngineInfo();
		}catch(Exception $ex){
			throw new Exception(lang('Произошла ошибка при изменении файла ').'engineInfo.cfg');
		}
	}

	/**
	Выполнение шага обновления
	*/
	public function ExecuteStep($step)
	{
		$steps = $this->steps;
		$step = $step - self::$base_steps - 1;
		
		if(isset($steps[$step])){		
			try{
				$method = $steps[$step][0];
				$reflectionMethod = new ReflectionMethod("UpdateObject", $method);
			}catch(Exception $ex){
				throw new Exception(lang('В файле обновления отсутствует, либо повреждена функция шага ').$method, self::ERROR_UPDATE_STRUCTURE);
			}
			
			eval('$this->'.$method.'();');
		}else{
			throw new Exception(lang('В файле обновления не прописана функция шага ').$step, self::ERROR_UPDATE_STRUCTURE);
		}
	}
	
	/**
	Функция хода обновления
	*/
	public static function ExecuteUpdate($step)
    {
		$result = array();
		
		$step = (int)$step;
		
		// Проверка шага обновления
		if($step <= 0)
			throw new Exception(lang('Потерян шаг обновления'), self::ERROR_FATAL);
			
		self::IncludeUpdate();
		
		// Начало проверки вывода контенда, чтобы избежать ajax ошибки
		ob_start();
		
		$update = self::InitUpdateObject();
		
		$stepsCount = count($update->steps);
		
		$params = Update::GetExternalParams();
		$move_step = (self::CheckboxChecked($params["backup"])) ? 1 : 0;
		
		if($step == 1 and $move_step > 0){ // Создание неполного бэкапа
			$backup = new Backup();
			$backup->BaseBackup();
			$result['link'] = $backup->Pack();
		}if($step == 1 + $move_step) // Создание карты файлов
			self::CreateFilesMap();
		if($step == 2 + $move_step) // Копирование файлов обновления
			self::SetUpdateFiles();
		if($step == 3 + $move_step) // Подготовка файлов
			$update->PrepareFiles();
		elseif($step == 4 + $move_step) // Резервирование файлов
			$update->ReserveFiles();
		elseif($step > self::$base_steps and $step <= $stepsCount + self::$base_steps){ // Действия обновления		
			$update->ExecuteStep($step);
		}elseif($step == $stepsCount + self::$base_steps + 1){ // Проверка синтаксиса
			$update->CheckSyntax();
		}elseif($step == $stepsCount + self::$base_steps + 2){ // Проверка ошибок обновления
			$update->CheckErrors();
		}elseif($step == $stepsCount + self::$base_steps + 3){ // Обновление файлов
			$update->UpdateFiles();
		}elseif($step == $stepsCount + self::$base_steps + 4){ // Очистка кэша
			self::ClearCache();
		}
		
		// Конец проверки вывода контенда
		$out = ob_get_contents();
		ob_end_clean();
		
		if($out)
			throw new Exception(lang('Происходит вывод данных'), self::ERROR_UPDATE_STRUCTURE);
			
		return $result;
    }
	
	/**
	Обновление информационного файла движка
	*/
	public function UpdateEngineInfo()
	{
		$content = $file_content = array();
		
		$content['name'] = Application::$engineInfo['name']; 
		$content['version'] = $this->version; 
		$content['last_update'] = date('d.m.Y'); 	
		
		foreach($content as $key => $value){
			$file_content[] = $key.' = '.$value;
		}
		
		if(!file_put_contents(Model::$conf->documentroot.'/engineInfo.cfg', implode("\n", $file_content)))
			throw new Exception(lang('Произошла ошибка при изменении файла: ').'engineInfo.cfg');
		
		return true;
	}
	
	/**
	Очистка кэша
	*/
	public static function ClearCache($folders = false)
    {
		$files = Update::ListDirectory(Model::$conf->cachePath, true, false, $folders, true);
		if(is_array($files)){
			foreach($files as $file){
				@unlink($file['dir']);
			}
		}
		
		return true;
	}
	
	/**
	Проверяет файл с путями ко всем файлам сайта и их хэшем
	*/
	public static function CheckFilesMap()
    {
		$conf = KernelSettings::GetInstance(false);
		
		if(!file_exists(Model::$conf->cachePath.'/filesMap.php')){
			self::CreateFilesMap();	
		}
	}
	
	/**
	Создаёт файл с путями ко всем файлам сайта и их хэшем
	*/
	public static function CreateFilesMap($basedir = false, $return_as_array = false)
	{
		$content = "<?php\nreturn array(";
		
		$contentArray = array();
		
		$basedir = ($basedir) ? $basedir : Model::$conf->documentroot;
		
		$filesMapFile = Model::$conf->cachePath.'/filesMap.php';
		
		// Папки которые не нужно добавлять в карту файлов
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
		$files = Update::ListDirectory($basedir, true, false, $folders);
		if(is_array($files)){
			foreach($files as $file){
				$relDir = str_replace(Model::$conf->documentroot, '', $file['dir']);
				$hash = ($file['is_dir'] !== 1) ? hash_file('md5', $file['dir']) : '';

				$content .= "\n\t'".$relDir."' => array(\n\t\t'name' => '".$file['name']."',\n\t\t'path' => '".$relDir."',\n\t\t'hash' => '".$hash."'),";
				
				if($return_as_array)
					$contentArray[] = array(
						''.$relDir => array(
							'name' => $file['name'],
							'path' => $relDir,
							'hash' => $hash),
					);
			}
		}
		$content .= "\n);";
		
		if(!$return_as_array)
			file_put_contents($filesMapFile, $content);	
		else
			return $contentArray;
	}
	
	/**
	Получает файл с путями ко всем файлам сайта и их хэшем
	*/
	public static function GetFilesMap()
    {
		$filesMapFile = Model::$conf->cachePath.'/filesMap.php';
		
		if(file_exists($filesMapFile))
			return include_once($filesMapFile);
		else
			return false;
	}
	
	/**
	Подготавливает изменённые файлы к переносу
	*/
	public static function SetUpdateFiles()
    {
		$filesMap = self::GetFilesMap();
		$filesMap = ($filesMap) ? $filesMap : array();
		
		$updateFilesDir = Model::$conf->updatePath.'/'.self::FILES_DIR.'/files';

		// Сканирование файлов и директорий
		$files = Update::ListDirectory($updateFilesDir, true);
		if(is_array($files)){
			foreach($files as $file){
				$relDir = str_replace($updateFilesDir, '', $file['dir']);
				$hash = ($file['is_dir'] !== 1) ? hash_file('md5', $file['dir']) : '';

				$sFile = $filesMap[$relDir];
				if($sFile and count($sFile) > 0){
					if($sFile['hash'] == $hash) continue;
				}
				
				// Определение нового пути файлу
				$newFile = Model::$conf->updatePath.'/'.self::TMP_FILES_DIR.$relDir;
				
				if($file['is_dir'] !== 1){
					// Определение новой директории файла	
					$tmp_file_dir = @explode('/', $newFile);
					if(is_array($tmp_file_dir)){
						unset($tmp_file_dir[count($tmp_file_dir)-1]);
						$newDirectory = implode('/', $tmp_file_dir);
					}else{
						throw new Exception(lang('Не удалось определить директории для файла').': '.$file['dir']);
					}
				}else{
					$newDirectory = $newFile;
				}
				
				// Рекурсивное создание директорий для файла
				if(!is_dir($newDirectory)){
					if(!mkdir($newDirectory, 0777, true))
						throw new Exception(lang('Не удалось создать директории для файла').': '.$file['dir']);
				}
				
				if($file['is_dir'] !== 1){
					// Копирование файла
					if(!copy($file['dir'], $newFile))
						throw new Exception(lang('Ошибка резервирования файла').': '.$file['dir']);
				}
			}
		}
	}
	
	/**
	Записывает в файл внешние параметры обновления
	*/
	public static function SetExternalParams($params)
    {
		$file = Model::$conf->updatePath.'/'.self::FILES_DIR.'/externalParams.php';
		
		if(!file_put_contents($file, serialize($params)))
			throw new Exception(lang('Не удалось записать внешние параметры в файл'));
		else
			return true;
	}	
	
	/**
	Получает внешние параметры обновления
	*/
	public static function GetExternalParams()
    {
		$file = Model::$conf->updatePath.'/'.self::FILES_DIR.'/externalParams.php';
		
		if(file_exists($file)){
			if(!($sarray = file_get_contents($file)))
				throw new Exception(lang('Не удалось получить внешние параметры'));
			else
				return unserialize($sarray);
		}else{
			throw new Exception(lang('Не найден файл с внешними параметрами'));
		}
	}	
	
	/**
	Проверяет статус чекбокса
	*/
	public static function CheckboxChecked($val)
    {
		if($val === 'on' or $val === 1 or $val === '1')
			return true;
		else
			return false;
	}
}