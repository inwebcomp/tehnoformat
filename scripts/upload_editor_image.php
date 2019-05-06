<?php

$result = array();

// $uploadDir = 'http' . ($_SERVER['HTTPS'] ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/img/content_images/';
$uploadDir = __DIR__ . '/../img/content_images/';
$uploadDirRelative = '/img/content_images/';

$imageName = basename($_FILES['file']['name']);

$tmp = explode('.', $imageName);

$ext = strtolower(array_pop($tmp));
$name = implode(".", $tmp);

if (! in_array($ext, array('png', 'jpg', 'jpeg', 'svg'))) {
	$result['error']['code'] = 6;
	$result['error']['message'] = "Invalid image type";
}

$n = 0;
$baseName = $name;
while (file_exists($uploadDir . $baseName . ($n == 0 ? '' : '_' . $n) . '.' . $ext)) {
	$name = $baseName . '_' . ++$n;
}

$imageName = $name . '.' . $ext;

$uploadFile = $uploadDir . $imageName;
$uploadFileRelative = $uploadDirRelative . $imageName;

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
	$result['link'] = $uploadFileRelative;
} else {
	$result['error']['code'] = 3;
	$result['error']['message'] = "Error during image upload";
}

echo json_encode($result);