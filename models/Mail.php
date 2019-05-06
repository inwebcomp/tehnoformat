<?php

use Hex\App\Queue;

class Mail extends databaseObject
{
	public static $mail_type;
	public static $smtp_username;
    public static $smtp_password;
    public static $smtp_host;
    public static $smtp_from;
    public static $smtp_port;
    public static $smtp_charset;
	public static $smtp_ssl;
	
	public function __construct($ID)
	{
        $this->modelName = 'Mail';
        $this->controllerName = 'mail';
		
		parent::__construct($ID);
	}
	
	public static function Send($to, $subject, $message, $from = false, $headers = "", $attachments = array()){
		
		if(!isset(self::$mail_type))
			self::SetParams();
		
		if(self::$mail_type == "smtp"){
			$result = self::SendMailSMTP($to, $subject, $message, $headers, $attachments);
		}elseif(self::$mail_type == "mail"){
			$result = self::SendMailMail($to, $subject, $message, $headers, $attachments);
		}
		
		if($result == true){
			$from = ($from) ? $from : lang("Посетитель");
			$params = $errors = new Parameters();
			$params->from = $from;
			$params->to = $to;
			$params->subject = $subject;
			$params->text = $message;
			
			self::Create("Mail", "create", $params, $errors);
		}else{
			$result = false;	
		}
		
		return $result;
	}
	
	public static function SetParams(){
		
		$paramstmp = Model::$db->ArrayValuesQ("SELECT * FROM `Mailsettings` LIMIT 1");
		$params = $paramstmp[0];
		
		self::$mail_type = $params["type"];
        self::$smtp_username = $params["login"];
        self::$smtp_password = $params["password"];
        self::$smtp_host = $params["server"];
        self::$smtp_from = $params["name"];
        self::$smtp_port = $params["port"];
		self::$smtp_ssl = $params["ssl"];
        self::$smtp_charset = "UTF-8";
		
    }

	public static function SendMailMail($to, $subject, $text, $headers = "", $attachments = array()){
		$contentType = "Content-Type: text/html; charset=utf-8\r\n";
		$contentMail .= $headers . "\r\n";
		
		$subject = "=?utf-8?B?" . base64_encode($subject) . "?=";

		if(mail($to, $subject, $text, $contentType))
			return true;
		else
			return false;
	}
	
	public static function SendMailSMTP($mailTo, $subject, $message, $headers = "", $attachments = array())
	{
		$mailTo = ltrim($mailTo, '<');
		$mailTo = rtrim($mailTo, '>');		

        if(count($attachments) > 0)
            $with_attachments = true;
            
        $EOL = "\r\n";
        $boundary = "--".md5(uniqid(time()));

        $contentMail = '';
        
        /*if($with_attachments)
		    $contentMail .= "--".$boundary.$EOL;*/

        $contentMail .= "Date: " . date("D, d M Y H:i:s") . " UT\r\n";
        $contentMail .= 'Subject: =?' . self::$smtp_charset . '?B?'  . base64_encode($subject) . "=?=\r\n";
		
		$contentMail .= "MIME-Version: 1.0\r\n";

		if($with_attachments)
            $contentMail .= "Content-Type: multipart/mixed; charset=utf-8; boundary=\"".$boundary."\"\r\n";
        else
		    $contentMail .= "Content-type: text/html; charset=utf-8\r\n";

		//$contentMail .= self::$smtp_from . "\r\n";

		$contentMail .= "From: \"".self::$smtp_from."\" <".self::$smtp_username.">\r\n";
		$contentMail .= "To: $mailTo <$mailTo>\r\n";
		$contentMail .= "X-Priority: 1\r\n";
		$contentMail .= $headers . "\r\n\r\n";
        
		if($with_attachments){
            $contentMail .= "--".$boundary.$EOL;
            $contentMail .= "Content-Type: text/html; charset=utf-8\r\n".
                            "Content-Transfer-Encoding: 8bit\r\n\r\n";
        }

        $contentMail .= $message . "\r\n";
        
        if($with_attachments){
            foreach($attachments as $file){ 
                if($file){  
                    $fp = fopen($file, "rb");   
                    if(!$fp)
                        continue; 
                    $attach = fread($fp, filesize($file));   
                    fclose($fp);   
                }  
 
                $name = end(explode("/", $file));
                $contentMail .=  $EOL."--".$boundary.$EOL;   
                $contentMail .= "Content-Type: application/octet-stream; name=\"$name\"$EOL";   
                $contentMail .= "Content-Transfer-Encoding: base64$EOL";   
                $contentMail .= "Content-Disposition: attachment; filename=\"$name\"$EOL";   
                $contentMail .= $EOL; // раздел между заголовками и телом прикрепленного файла 
                $contentMail .= chunk_split(base64_encode($attach));   

                $contentMail .=  $EOL."--".$boundary.$EOL;
            }
        }  


        // Add to queue
        Queue::add('', $mailTo, $subject, $contentMail);


//        try{
//
//            if(!$socket = @fsockopen((intval(self::$smtp_ssl)?"ssl://":"").self::$smtp_host, self::$smtp_port, $errorNumber, $errorDescription, 30)){
//                throw new Exception($errorNumber.".".$errorDescription);
//            }
//            if(!self::_parseServer($socket, "220")){
//                throw new Exception(lang("Ошибка соединения"));
//            }
//
//			$server_name = $_SERVER["SERVER_NAME"];
//            fputs($socket, "EHLO $server_name\r\n");
//            if(!self::_parseServer($socket, "250")){
//                fclose($socket);
//                throw new Exception(lang("Ошибка команды")." EHLO");
//            }
//
//            fputs($socket, "AUTH LOGIN\r\n");
//            if(!self::_parseServer($socket, "334")){
//                fclose($socket);
//                throw new Exception(lang("Ошибка авторизации"));
//            }
//
//            fputs($socket, base64_encode(self::$smtp_username) . "\r\n");
//            if(!self::_parseServer($socket, "334")){
//                fclose($socket);
//                throw new Exception(lang("Ошибка авторизации"));
//            }
//
//            fputs($socket, base64_encode(self::$smtp_password) . "\r\n");
//            if(!self::_parseServer($socket, "235")){
//                fclose($socket);
//                throw new Exception(lang("Ошибка авторизации"));
//            }
//
//            fputs($socket, "MAIL FROM: <".self::$smtp_username.">\r\n");
//            if(!self::_parseServer($socket, "250")){
//                fclose($socket);
//                throw new Exception('Error of command sending: MAIL FROM');
//            }
//
//            fputs($socket, "RCPT TO: <" . $mailTo . ">\r\n");
//            if(!self::_parseServer($socket, "250")){
//                fclose($socket);
//                throw new Exception(lang("Ошибка команды")." RCPT TO");
//            }
//
//            fputs($socket, "DATA\r\n");
//            if(!self::_parseServer($socket, "354")){
//                fclose($socket);
//                throw new Exception(lang("Ошибка команды")." DATA");
//            }
//
//            fputs($socket, $contentMail."\r\n.\r\n");
//            if(!self::_parseServer($socket, "250")){
//                fclose($socket);
//                throw new Exception(lang("Письмо не было доставлено"));
//            }
//
//            fputs($socket, "QUIT\r\n");
//            fclose($socket);
//
//        }catch(Exception $e){
//            return $e->getMessage();
//        }
		
        return true;
    }


    public static function SendMailSMTPFromQueue($ID)
    {
        if(!isset(self::$mail_type))
            self::SetParams();

        $data = Queue::get($ID);

        Queue::touch($ID);

        $mailTo = $data['mail_to'];
        $contentMail = $data['message'];

        if ($mailTo == '' or $contentMail == '')
            return false;

        try{
            if(!$socket = @fsockopen((intval(self::$smtp_ssl)?"ssl://":"").self::$smtp_host, self::$smtp_port, $errorNumber, $errorDescription, 30)){
                throw new Exception($errorNumber.".".$errorDescription);
            }
            if(!self::_parseServer($socket, "220")){
                throw new Exception(lang("Ошибка соединения"));
            }

            $server_name = $_SERVER["SERVER_NAME"];
            fputs($socket, "EHLO $server_name\r\n");
            if(!self::_parseServer($socket, "250")){
                fclose($socket);
                throw new Exception(lang("Ошибка команды")." EHLO");
            }

            fputs($socket, "AUTH LOGIN\r\n");
            if(!self::_parseServer($socket, "334")){
                fclose($socket);
                throw new Exception(lang("Ошибка авторизации"));
            }

            fputs($socket, base64_encode(self::$smtp_username) . "\r\n");
            if(!self::_parseServer($socket, "334")){
                fclose($socket);
                throw new Exception(lang("Ошибка авторизации"));
            }

            fputs($socket, base64_encode(self::$smtp_password) . "\r\n");
            if(!self::_parseServer($socket, "235")){
                fclose($socket);
                throw new Exception(lang("Ошибка авторизации"));
            }

            fputs($socket, "MAIL FROM: <".self::$smtp_username.">\r\n");
            if(!self::_parseServer($socket, "250")){
                fclose($socket);
                throw new Exception('Error of command sending: MAIL FROM');
            }

            fputs($socket, "RCPT TO: <" . $mailTo . ">\r\n");
            if(!self::_parseServer($socket, "250")){
                fclose($socket);
                throw new Exception(lang("Ошибка команды")." RCPT TO");
            }

            fputs($socket, "DATA\r\n");
            if(!self::_parseServer($socket, "354")){
                fclose($socket);
                throw new Exception(lang("Ошибка команды")." DATA");
            }

            fputs($socket, $contentMail."\r\n.\r\n");
            if(!self::_parseServer($socket, "250")){
                fclose($socket);
                throw new Exception(lang("Письмо не было доставлено"));
            }

            fputs($socket, "QUIT\r\n");
            fclose($socket);

        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }

        return true;
    }
    
    private static function _parseServer($socket, $response){
		
        while(@substr($responseServer, 3, 1) != " "){
            if(!($responseServer = fgets($socket, 256)))
				return false;
        }
        if(!(substr($responseServer, 0, 3) == $response))
            return false;
		
        return true;
  
    }
	
	public static function SetRead($ID){
		
		$num = Model::$db->Value("SELECT COUNT(*) FROM `Mail` WHERE ID = '".(int)$ID."' AND `read` = 0");
		
		if($num > 0){
			Model::$db->Query("UPDATE `Mail` SET `read` = 1 WHERE ID = '".(int)$ID."'");
		}
		
    }
}