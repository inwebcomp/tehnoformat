<?php

include_once "../kernel/settings.php";
include_once "../kernel/database.php";

$conf = KernelSettings::GetInstance();
$db = Database::DataBaseConnect($conf->db_type);

if (isset($_REQUEST['name']) && isset($_REQUEST['size']))
{
	$name = addslashes($_REQUEST['name']);
	$size = (int)$_REQUEST['size'];
	$info = $db->Row("SELECT name, size, md5 FROM Uploads WHERE name = '" . $name . "' AND size = " . $size . "");
    if (isset($_REQUEST['index']))
    	$info['index'] = $_REQUEST['index'];

	print json_encode($info);
}
elseif (isset($_REQUEST['hash']))
{
	$hash = addslashes($_REQUEST['hash']);

	$info = $db->Row("SELECT name, size, md5 FROM Uploads WHERE md5 = '" . $hash . "'");
    if (isset($_REQUEST['index']))
    	$info['index'] = $_REQUEST['index'];

	print json_encode($info);
}

?>