<?php

use Hex\App\Entity;
use \Hex\App\Auth;

class User extends Entity
{
 	protected $guarded = ['password'];

	const STATUS_BLOCKED = 0;
	const STATUS_REGISTERED = 1;
	const STATUS_ACTIVATED = 2;
	
	const STATUS_GUEST = -1;

	public function blocked()
	{
		return ($this->status == self::STATUS_BLOCKED);
	}

	public function registered()
	{
		return ($this->status >= self::STATUS_REGISTERED);
	}

	public function activated()
	{
		return ($this->status == self::STATUS_ACTIVATED);
	}

	public static function get($ID)
	{
		return Model::$db->Value("SELECT * FROM User WHERE ID = '" . (int) $ID . "'", true);
	}
	
	public static function getByEmail($email)
	{
		return Model::$db->Value("SELECT * FROM User WHERE email = '" . Database::escape($email) . "'", true);
	}
	
	public static function getByRegKey($reg_key)
	{
		return Model::$db->Value("SELECT * FROM User WHERE reg_key = '" . Database::escape($reg_key) . "'", true);
	}
	
	public function getPassword()
	{
		return Database::value("SELECT password FROM User WHERE ID = '" . (int) $this->ID . "'");
	}
	
	public function register($params)
	{
		$content = array();
		
		$params = new Parameters($params);
		$errors = new Parameters();

		if ($user = self::create("User", "register", $params, $errors) and $user instanceof User and $user->registered())
			Auth::login($user);
		
		return $user;
	}

	public function setStatus($status)
	{
		return (bool) Database::query("UPDATE User SET status = '" . $status . "' WHERE ID = '" . (int) $this->ID . "'");
	}

	public function activate($key = null)
	{
		if ($key !== null and ! $this->checkActivationKey($key))
			return false;
		else if (! $this->checkActivationKey($key))
			return false;

		if ($this->status == self::STATUS_ACTIVATED)
			return;

		return $this->setStatus(self::STATUS_ACTIVATED);
	}
	
	public function deactivate()
	{
		return $this->setStatus(self::STATUS_REGISTERED);
	}

	public function generateActivationKey()
	{
		return md5(microtime() . rand());
	}

	public function checkActivationKey($key)
	{
		return ($this->activation_key == $key);
	}

	public function setActivationKey()
	{
		$key = $this->generateActivationKey();

		$result = (bool) Database::query("UPDATE User SET `activation_key` = '" . $key . "' WHERE ID = '" . (int) $this->ID . "'");

		$this->info['activation_key'] = $key;

		return $result;
	}

	public function sendActivationEmail()
	{
		$sent = false;

		$data = array(
			'href' => 'http://' . Model::$conf->host . (Application::$language->name == 'ru' ? '/ru/' : '/') . Pages::getUrlName('activation') . '/' . $this->activation_key
		);

		list($subject, $text) = Mailtemplates::createLetter('activation', $data);

		if ($subject and $text)
			$sent = Mail::send($this->email, $subject, $text);

		return $sent;
	}

	public static function create($modelName, $formName, $params, &$errors, $return = false)
	{
		$content = array();

		$errors3 = User::Validate($params, 'User', $formName);
		
		if ($errors3->err->Val() == 1) {
			$errors3->mess = lang('Произошли ошибки при валидации данных');
			$content = array_merge($content, $errors3->getInfo());
		}

		if (count($content))
			return array_merge($content, $params->getInfo());
		
		$user = parent::create($modelName, $formName, $params, $errors, $return);

		$user->setActivationKey();

		if (! $user->activated())
			$user->sendActivationEmail();

		return $user;
	}

    public function isAdmin()
    {
        return ($this->type == 'developer' || $this->type == 'admin' || $this->type == 'moder');
	}



	// Deprecated

	public function NonAnonymous()
	{
		return Auth::logined();
	}

	public static function getCurrentUser()
	{
		return Auth::getCurrentUser();
	}
}