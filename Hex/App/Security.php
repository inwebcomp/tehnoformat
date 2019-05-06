<?php

namespace Hex\App;

use Hex\Abstracts\Singleton;
use Application;
use Model;

class Security extends Singleton
{
	protected static $instance;

	private $csrfProtection = false;
	private $tokenLength = 32;
	private $allowed = array();
	private $allowedMatch = array(
		'facebook_auth',
		'google_auth'
	);

	private $token;

	public function __construct()
    {
        if ($this->csrfProtection) {
            if (! $this->assignToken()) {
                $this->token = $this->generateToken();
                $this->saveToken($this->token);
            }

            $this->verifyToken();
        }

        $this->checkSQLInjection();
	}

	public function getToken()
	{
		return $this->token;
	}
	
	public function verifyToken()
	{
		// in_array($_SERVER['REQUEST_METHOD'], array('POST', 'PUT', 'DELETE', 'PATCH'))

		if (in_array($_SERVER['REQUEST_URI'], $this->allowed))
			return true;

		foreach ($this->allowedMatch as $value) {
			if (strpos($_SERVER['REQUEST_URI'], $value) !== false)
				return true;
		}

		if (Application::$returnType !== 'html' and $_POST['csrf-token'] !== $this->token) {
			header("HTTP/1.1 400 Bad Request");
			exit();
		}
	}
	
	protected function assignToken()
	{
		$token = isset(Model::$session->info['csrf-token']) ? Model::$session->info['csrf-token'] : false;

		if ($token)
			return ($this->token = Model::$session->info['csrf-token']);

		return false;
	}
	
	protected function generateToken()
	{
		if (! isset($this->tokenLength)){
		  	$this->tokenLength = 32;
		}
		if (function_exists('random_bytes')) {
			return bin2hex(random_bytes($this->tokenLength));
		}
		if (function_exists('mcrypt_create_iv')) {
			return bin2hex(mcrypt_create_iv($this->tokenLength, MCRYPT_DEV_URANDOM));
		} 
		if (function_exists('openssl_random_pseudo_bytes')) {
			return bin2hex(openssl_random_pseudo_bytes($this->tokenLength));
		}
	}
	
	protected function saveToken($token)
	{
		Model::$session->info['csrf-token'] = $this->token;
		Model::$session->Save();
	}

	public function isCsrfEnabled()
	{
		return $this->csrfProtection;
	}

	public function csrfMeta()
	{
		if (! $this->isCsrfEnabled())
			return false;
		
		return '<meta name="csrf-token" content="' . $this->token . '" />';
	}
		
	public function csrfField()
	{
		if (! $this->isCsrfEnabled())
			return false;
		
		return '<input name="csrf-token" type="hidden" value="' . $this->token . '" />';
	}





	// Passwords
	public static function encryptPassword($password)
	{
		return md5($password);
	}

    private function checkSQLInjection()
    {
        if (preg_match('/[#\'"~]/', $_SERVER['REQUEST_URI'])) {
            header("HTTP/1.1 400 Bad Request");
            exit();
        }
    }
}