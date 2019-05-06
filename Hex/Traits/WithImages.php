<?php

namespace Hex\Traits;

use Database;
use Model;
use Checker;
use Utils;

trait WithImages
{
	public function uploadImages($images, $clear = false)
	{
		$return = false;

		if (! is_array($images))
			return false;

		if ($clear)
			$this->deleteAllImages();

		foreach ($images as $image) {
			$tmp = explode('.', $image['name']);
			$ext = array_pop($tmp);
			$name = implode('.', $tmp);

			if (! in_array($ext, array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG')))
				continue;

			$md5 = md5_file($image['tmp_name']);

			$imagePath = Model::$conf->tmpPath . '/' . $md5;

			if (! copy($image['tmp_name'], $imagePath))
				continue;

			if (! Database::query("INSERT INTO Uploads SET name = '" . Database::escape($image['name']) . "', size = " . filesize($imagePath) . ", md5 = '" . $md5 . "'"))
				continue;

			$checker = new Checker("File");
			list($file) = $checker->Get($image['name']);

			if (! $file)
				continue;

			if (! is_array($return))
				$return = array();

			$return[] = $this->saveImages($file, true);
		}

		return $return;
	}

	public function getImagesName()
	{
		$content = array();

		$model = $this->getModelName();

		if (is_dir(Model::$conf->mediaContent . '/images/' . $model . '/' . $this->ID))
		{
			$res = Database::query("SELECT name FROM `Image` WHERE model = '" . $model . "' AND object_ID = '" . $this->ID . "' ORDER BY pos ASC");

			while ($arr = Database::fetch($res))
			{
				$image = array();
				if (!is_file(Model::$conf->mediaContent . '/images/' . $model . '/' . $this->ID . '/' . $arr['name']))
					continue;

				$content[] = $arr['name'];
			}
		}

		return $content;
	}

	public function deleteAllImages()
	{
        $folder = Model::$conf->mediaContent . '/images/' . $this->getModelName() . '/' . $this->ID;

		Utils::removeDir($folder);

		Database::query("DELETE FROM `Image` WHERE model = '" . $this->getModelName() . "' AND object_ID = '" . $this->ID . "'");

		$this->setBaseImage(NULL);

		return true;
	}

	public function setImagesByName($images)
	{
		if (is_array($images)) {
			$array = $this->getImagesName();

			foreach ($array as $image) {
				if (! in_array($image, $images)) {
					$this->deleteImage($image);
				}
			}
		} else {
			$this->deleteAllImages();
		}
	}
	
	public function orderImages($images)
	{
		if (is_array($images)) {
			$array = $this->getImagesName();

			$position = 10;

			foreach ($images as $image) {
				if (in_array($image, $array)) {
					$this->setImagePosition($image, $position);
					$position += 10;
				}
			}
		}
	}

	public function setImagePosition($image, $position)
	{
		return Database::query("UPDATE `Image` SET pos = '" . (int) $position . "' WHERE model = '" . $this->getModelName() . "' AND object_ID = '" . $this->ID . "' AND name = '" . $image . "'");
	}
}