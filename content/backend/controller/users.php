<?php

use Hex\App\Auth;

class controller_users extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'User';
		$this->controllerName = 'users';
		
		// Notifications
		$this->notifications = true;
		
		// Действия
		$this->actions["fast_save"] = false;
	}

	public function _notification(){
		$count = Model::$db->Value("SELECT COUNT(ID) FROM User WHERE DATE(created) > '".date("Y-m-d", time() - 60 * 60 * 24 * 2)."'");	
		
		return ($count > 0) ? $count : NULL; 
	}

	public function save($object, $params)
	{
		$content = array();

		if(trim($params["password"]) !== ""){}else{
			unset($params["password"]);
			unset($params["password2"]);
		}
	
		return crud_controller_tree::save($object, $params);
	}

	public function validate_form()
	{
		$content = array();
		if(Auth::logined())
		{
			$content = Auth::getCurrentUser()->getInfo();
			$content['authFlag'] = 1;
        }
        if(Auth::remember())
        	$content['remember'] = 1;
		
		return $content;
	}

	public function validate($login, $password, $remember)
	{
		// $content = array();
		// $checker = new Checker('String', 'Password', 'Bool');
		// list($login, $password, $remember) = $checker->Get($login, $password, $remember);

		// if (!$login)
		// 	$content['err'] = $content['err_login'] = 1;
		// else
		// 	$content['login'] = $login;

		// if (!$password)
		// 	$content['err'] = $content['err_password'] = 1;

		// if ($login && $password && !User::CurrentUserValidate($login, $password, $remember))
		// 	$content['err'] = $content['err_password'] = $content['err_login'] = 1;
		// else
		// 	$content['reload'] = 1;

		// return array_merge($this->validate_form(), $content);

		$content = array();

		$remember = (bool) $remember;

		$data = Auth::attempt($login, $password);

		if ($data instanceof User) {
			Auth::login($data, $remember);
			$content = $data->getInfo();
		} else	
			$content = $data; // Errors array

		return array_merge($this->validate_form(), $content);
	}

	public function quit()
	{
		return Auth::quit();

		// $content = array();

		// User::CurrentUserQuit();

		// return array_merge($this->validate_form(), $content);
	}

	public function change_password($password, $new_password, $new_password2)
	{
		$content = array();

		$checker = new Checker('Password', 'Password', 'Password');
		list($password, $new_password, $new_password2) = $checker->Get($password, $new_password, $new_password2);

		if (!$password || md5($password) != Model::$user->password)
		{
			$content['err'] = $content['err_password'] = 1;
            $content['err_mess_password'] = 'Текущий пароль неверен.';
        }

		if (!$new_password)
		{
			$content['err'] = $content['err_new_password'] = 1;
			$content['err_mess_new_password'] = _('Пароль должен состоять не менее чем из 6 символов');
		}

		if ($new_password != $new_password2)
		{
			$content['err'] = $content['err_new_password2'] = 1;
			$content['err_mess_new_password2'] = _('Пароли не совпадают');
		}

		if (!isset($content['err']))
		{
			Model::$user->Update(array('password' => md5($new_password)));
			$content['mess'] = _('Пароль изменен успешно');
			$content['ok'] = 1;
		}

		return array_merge($this->settings_form(), $content);
	}




/* Регистрация и активация нового пользователя */

	public function registration_form()
	{
		$content = array();
		
		return $content;
	}

	public function registration($params)
	{
		$content = array();

		$checker = new Checker('Parameters');
		list($params) = $checker->Get($params);
		$errors = new Parameters();

		if (!$user = User::Registration($params, $errors))
		{
          	$content = $errors->GetInfoUnEscape();
        }
        else
        {
			$content['ok'] = 1;
			$content['mess'] = lang('Регистрация прошла успешно');
		}

		return array_merge($this->registration_form(), $content);
	}

    public function activate($reg_key)
    {
    	$content = array();

		if (!User::Activate($reg_key))
		{
			$content['err'] = $content['err_reg_key'] = 1;
			$content['mess'] = $content['err_mess_reg_key'] = _('Регистрационный ключ неверен.');
    	}
    	else
    	{
			$content['mess'] = _('Активация прошла успешно, вы авторизированы.');
    	}

    	return $content;
    }


/* Напоминание пароля */
	public function remember_password_form()
	{
		$content = array();

		return $content;
	}

	public function remember_password($email)
	{
		$content = array();

		$checker = new Checker('Email');
		list($email) = $checker->Get($email);

		if (!$email || User::IsUniqEmail($email))
		{
			$content['err'] = $content['err_email'] = 1;
            $content['email_mess'] = lang('Данный e-mail не зарегистрирован в системе.');
        }

		if (!isset($content['err']))
		{
		    $user = User::GetUserByEmail($email);
		    $user->UpdateRegKey(md5(time()));

			$remember_link = 'http://' . Model::$conf->host . '/remember_password/activate/' . $user->reg_key;

			$mess = 'Link: <a href="'.$remember_link.'">'.$remember_link.'</a>';

			$content['mess'] = lang('На ваш e-mail отправлена ссылка для изменения пароля.');
			$content['rememberPasswordOk'] = 1;

			//$content['mess'] .= $mess;
			Utils::SendMail(Model::$conf->host . ' - ' . lang("Напоминание пароля"), $user->email, $mess);


		}

		return array_merge($this->remember_password_form(), $content);
	}

	public function remember_password_change($reg_key)
	{
   		$content = array();

    	$checker = new Checker('LiteralString');
		list($reg_key) = $checker->Get($reg_key);

		if (!$reg_key)
		{
			$content['err'] = $content['err_reg_key'] = 1;
			$content['reg_key_mess'] = lang('Ключ неверен.');
    	}

    	if (!isset($content['err']) && $user = User::GetUserByRegKey($reg_key))
    	{
            $newPassword = 'p' . time();
            $user->ChangePassword(md5($newPassword));
            $user->UpdateRegKey('');

			$mess = lang("Новый пароль") . ': ' . $newPassword;

			Utils::SendMail(Model::$conf->host . ' - ' . lang("Новый пароль"), $user->email, $mess);

			$content['mess'] = lang('Пароль изменен успешно. Новый пароль выслан на Ваш e-mail.');
    	}
    	else
    	{
    		$content['err'] = $content['err_reg_key'] = 1;
			$content['reg_key_mess'] = lang('Ключ неверен.');
    	}

    	return $content;
	}
    /*********************************/
}

?>