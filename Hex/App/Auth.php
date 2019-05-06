<?php

namespace Hex\App;

use Hex\Abstracts\Singleton;
use Checker;
use User;
use Model;

class Auth extends Singleton
{
	protected static $instance;
	
	protected static $currentUser;

	private static $guest = true;

	private static $authByFields = array('login', 'email');
	
	const AUTH_TIME = 60 * 60 * 24; // 1 Day
	const AUTH_TIME_REMEMBER = 60 * 60 * 24 * 31; // 31 Day

	public static function logined()
	{
		return (! self::$guest);
	}

	public static function attempt($login, $password)
	{
		$errors = array();

		$login = trim($login);
		$password = trim($password);

		if ($login == '') 
			$errors[] = array('field' => 'login', 'message' => lang('Вы не ввели логин или email'));
		
		if ($password == '') 
			$errors[] = array('field' => 'password', 'message' => lang('Вы не ввели пароль'));
		
		if (count($errors))
			return $errors;

		$user = User::find($login, self::$authByFields);

		if ($user == false)
			return array('field' => 'login', 'message' => lang('Такой пользователь не зарегистрирован'));

		if ($user->password !== Security::encryptPassword($password))
			return array('field' => 'password', 'message' => lang('Вы ввели неправильный пароль'));
		
		if ($user->blocked())
			return array('field' => 'login', 'message' => lang('Пользователь заблокирован'));

		return $user;
	}

	public static function login($user, $remember = false)
	{
		if (! ($user instanceof User))
			$user = User::find($user);

		$info = array(
			'ID' => $user->ID,
			'auth_time' => time(),
			'remember' => $remember
		);

		Model::$session->set('user', $info)->save();

		self::$guest = false;
	}

	public static function getCurrentUser()
	{
		$user = self::$currentUser;
		
		if ($user !== null) 
			return $user;

		if (! $user)
			$user = new User((int) Model::$session->user['ID']);
	
		if (! $user->real() or $user->blocked()) {
			$user = new User();
			$user->status = User::STATUS_GUEST;
			$user->ID = 0;

			self::$guest = true;
		} else {
			self::$guest = false;
		}

		self::$currentUser = $user;

		return $user;
	}
	
	public static function checkAuthTime()
	{
		if (! self::logined())
			return false;

		$time = self::getAuthTime();
		
		if(
			($time < time() - self::AUTH_TIME and ! self::remember())
			or
			($time < time() - self::AUTH_TIME_REMEMBER)
		){
			self::quit();
		}
	}

    public static function check()
    {
        return self::checkAuthTime();
	}
	
	public static function getAuthTime()
	{
		return isset(Model::$session->user['auth_time']) ? Model::$session->user['auth_time'] : false;
	}
		
	public function remember()
	{
		return (bool) Model::$session->user['remember'];
	}

	public static function quit()
	{
		unset(Model::$session->info['user']);

		Model::$session->Save();
	}
	


	protected static $facebook = array(
		'client_id' => '1742373209390085',
		'client_secret' => '9bb75b66e36f40334504c54c58abd175'
	);

	protected static $google = array(
		'client_id' => '303463365605-iuml25756p6nuq2a2li4mmc5mvi4hoo9.apps.googleusercontent.com',
		'client_secret' => 'mInv0bXcay6rxlk6csmYJeZg'
	);

	public static function socialLoginGetUrl($network)
	{	
		// Facebook
		if ($network == "facebook") {
			$client_id = self::$facebook['client_id'];
			$client_secret = self::$facebook['client_secret'];
			$redirect_uri = 'http://' . Model::$conf->host . '/html-block/' . \Application::$language->name . '/user/facebook_auth/';
			
			$url = 'https://www.facebook.com/dialog/oauth';

			$params = array(
				'client_id'     => $client_id,
				'redirect_uri'  => $redirect_uri,
				'response_type' => 'code',
				'scope'         => 'email'
			);
			
			$url = $url . '?' . urldecode(http_build_query($params));
		}
		
		// Google
		if ($network == "google") {
			$client_id = self::$google['client_id'];
			$client_secret = self::$google['client_secret'];
			$redirect_uri = 'http://' . Model::$conf->host . '/html-block/' . \Application::$language->name . '/user/google_auth/';
			
			$url = 'https://accounts.google.com/o/oauth2/auth';

			$params = array(
				'redirect_uri'  => $redirect_uri,
				'response_type' => 'code',
				'client_id'     => $client_id,
				'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
			);
			
			$url = $url . '?' . urldecode(http_build_query($params));
		}
		
		return $url;
	}
	
	public static function socialLogin($network)
	{	
		ini_set('error_reporting', E_ALL - E_NOTICE);
		ini_set('display_errors', 1);

		// Facebook
		if($network == "facebook"){
			$client_id = self::$facebook['client_id'];
			$client_secret = self::$facebook['client_secret'];
			$redirect_uri = 'http://' . Model::$conf->host . '/html-block/' . \Application::$language->name . '/user/facebook_auth/';
		
			if(isset($_GET['code'])){
				$result = false;

				$params = array(
					'client_id'     => $client_id,
					'redirect_uri'  => $redirect_uri,
					'client_secret' => $client_secret,
					'code'          => $_GET['code']
				);

				$url = 'https://graph.facebook.com/oauth/access_token';
				
				$tokenInfo = false;
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($params));
				$response = curl_exec($ch);
				curl_close($ch);
				$content = $response;

				//parse_str($content, $tokenInfo);
				
				$tokenInfo = json_decode($content, true);
	
				if(count($tokenInfo) > 0 and isset($tokenInfo['access_token'])) {
					$params = array('access_token' => $tokenInfo['access_token']);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/me?fields=id,name,email&'.urldecode(http_build_query($params)));
					$response = curl_exec($ch);
					curl_close($ch);

					$userInfo = json_decode($response, true);

					if(isset($userInfo['id'])){
						$result = array("network" => "facebook", "network_ID" => $userInfo["id"], "fullname" => $userInfo["name"], "email" => $userInfo["email"]);
					}
				}
			}
		}
		
		// Google
		if($network == "google"){
			$client_id = self::$google['client_id'];
			$client_secret = self::$google['client_secret'];
			$redirect_uri = 'http://' . Model::$conf->host . '/html-block/' . \Application::$language->name . '/user/google_auth/';
		
			if(isset($_GET['code'])){
				$result = false;

				$params = array(
					'client_id'     => $client_id,
					'client_secret' => $client_secret,
					'redirect_uri'  => $redirect_uri,
					'grant_type'    => 'authorization_code',
					'code'          => $_GET['code']
				);

				$url = 'https://accounts.google.com/o/oauth2/token';

				$tokenInfo = false;
				
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				$response = curl_exec($curl);
				curl_close($curl);

				$tokenInfo = json_decode($response, true);
				
				if(count($tokenInfo) > 0 and isset($tokenInfo['access_token'])) {
					$params = array('access_token' => $tokenInfo['access_token']);

					$userInfo = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo' . '?' . urldecode(http_build_query($params))), true);
					if(isset($userInfo['id'])){
						$result = array("network" => "google", "network_ID" => $userInfo["id"], "fullname" => $userInfo["name"], "email" => $userInfo["email"]);
					}
				}
			}
		}

		return $result;
	}	

	public static function attemptSocial($data)
	{
		$errors = array();

		$user_ID = \Database::value("SELECT ID FROM `User` WHERE ((`network` = '" . $data['network'] . "' AND `network_ID` = '" . $data['network_ID'] . "') OR `email` = '" . $data['email'] . "' ) AND block <> 1");
		
		if (! $user_ID) {
			$params = new \Parameters($data);
			$params->status = \User::STATUS_ACTIVATED;

			$errors = new \Parameters();

			$user = \User::create('User', 'create_social', $params, $errors, true);

			if (count($errors))
				return $errors;
		} else {
			$user = User::find($user_ID);
		}

		if ($user == false)
			return array('field' => 'login', 'message' => lang('Такой пользователь не зарегистрирован'));
	
		if ($user->blocked())
			return array('field' => 'login', 'message' => lang('Пользователь заблокирован'));

		return $user;
	}
}