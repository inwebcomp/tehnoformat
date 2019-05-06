<?php
   /* include "../kernel/settings.php";
    try { $conf = KernelSettings::GetInstance(false); }
    catch(Exception $ex) { print $ex->getMessage(); }*/

	$cache 	  = true;
	$cachedir = $_SERVER['DOCUMENT_ROOT'].'/cache/files';
	$cssdir   = $_SERVER['DOCUMENT_ROOT'].'/css';
	$jsdir    = $_SERVER['DOCUMENT_ROOT'].'/jslib';

	/*$cache 	  = true;
	$cachedir = $_SERVER["DOCUMENT_ROOT"]."/cache";
	$cssdir   = $_SERVER["DOCUMENT_ROOT"]."/css";
	$jsdir    = $_SERVER["DOCUMENT_ROOT"]."/jslib";*/
	

	$type = $_GET['type'];
	$elements = explode(',', $_GET['files']);
	switch ($type)
	{
		case 'css':
			$base = $cssdir;
			$meme = "text/css";
			break;
		case 'javascript':
			$base = $jsdir;
			$meme = "text/javascript";
			break;
		default:
			header ("HTTP/1.1 503 Not Implemented");
			exit;
	};
	$lastmodified = 0;
	foreach ($elements as $element)
	{
		$path = $base . '/' . $element;

		if (substr($path, 0, strlen($base)) != $base || !file_exists($path))
		{
			header ("HTTP/1.1 404 Not Found");
			exit;
		}

		$lastmodified = max($lastmodified, filemtime($path));
	}

	$hash = $lastmodified . '-' . md5($_GET['files']);
	header("Etag: " . $hash);
	header('Cache-Control: max-age=2592000');

	$Expires = gmdate("D, d M Y H:i:s GMT", $lastmodified + 2592000);
	header('Expires: '. $Expires);

	$LastModified = gmdate("D, d M Y H:i:s GMT", $lastmodified);
	header('Last-Modified: '. $LastModified);

	
	
	if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && (stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == 'W/"' . $hash . '"' || stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"' . $hash . '"'))
	{
		header ("HTTP/1.1 304 Not Modified");
		header ('Content-Length: 0');
	}
	else
	{
		if ($cache)
		{
			$gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
			$deflate = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate');

			$encoding = $gzip ? 'gzip' : ($deflate ? 'deflate' : 'none');

			if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Opera') &&
						preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches))
			{
				$version = floatval($matches[1]);

				if ($version < 6)
					$encoding = 'none';

				if ($version == 6 && !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1'))
					$encoding = 'none';
			}

			$cachefile = 'cache-' . $hash . '.' . $type . ($encoding != 'none' ? '.' . $encoding : '');

			if (file_exists($cachedir . '/' . $cachefile))
			{
				if ($fp = fopen($cachedir . '/' . $cachefile, 'rb'))
				{

					if ($encoding != 'none')
					{
						header ("Content-Encoding: " . $encoding);
					}
					
					header ("Content-Type: text/" . $type);
					header ("Content-Length: " . filesize($cachedir . '/' . $cachefile));
					header ("X-Content-Type-Options: nosniff");

					fpassthru($fp);
					fclose($fp);
					exit;
				}
			}
		}

		$contents = '';

		foreach ($elements as $element)
		{
			$path = $base . '/' . $element;
			$contents .= "\n\n" . file_get_contents($path);
		}

		header ("Content-Type: $meme");
		header ("X-Content-Type-Options: nosniff");

		if (isset($encoding) && $encoding != 'none')
		{
			$contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
			header ("Content-Encoding: " . $encoding);
			header ('Content-Length: ' . strlen($contents));
			echo $contents;
		}
		else
		{
			header ('Content-Length: ' . strlen($contents));
			echo $contents;
		}

		if ($cache)
		{
			if ($fp = fopen($cachedir . '/' . $cachefile, 'wb'))
			{
				fwrite($fp, $contents);
				fclose($fp);
			}
		}
	}