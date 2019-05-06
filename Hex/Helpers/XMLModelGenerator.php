<?php

namespace Hex\Helpers;

use KernelSettings as Settings;
use SimpleXMLElement;

class XMLModelGenerator
{
	public static function create($name, $default = true, $fields = array(), $force = false)
	{
		$path = Settings::get('metaPath') . '/' . $name . '.xml';

		if ($force or ! file_exists($path)) {
			$content = self::arrayToXML('model', self::generateXMLModel($name, $default, $fields));

			file_put_contents($path, $content);
		}
	}

	public static function generateXMLModel($name, $default = true, $fields = array())
	{
		$content = array(
			'@attributes' => array(
				'name' => $name
			)
		);

		$content['table'] = self::generateTable($name, $default, $fields);

		$content['form'] = array(
			self::generateForm('edit', $default, $fields),
			self::generateForm('create', $default, $fields)
		);

		return $content;
	}

	public static function generateTable($name, $default = true, $fields = array())
	{
		$content = array(
			'@attributes' => array(
				'name' => $name
			)
		);

		if ($default)
			$content['@attributes']['extends'] = 'Default';

		if (count($fields)) {
			$content['item'] = array();

			$fields = self::prepareTableFields($fields);
			
			foreach ($fields as $field) {
				array_push(
					$content['item'], 
					self::generateField($field)
				);
			}
		}

		return $content;
	}

	public static function generateForm($name, $default = true, $fields = array())
	{
		$content = array(
			'@attributes' => array(
				'name' => $name
			)
		);

		if ($default)
			$content['@attributes']['extends'] = 'Default';

		if (count($fields)) {
			$content['item'] = array();

			$fields = self::prepareFormFields($fields);
			
			foreach ($fields as $field) {
				array_push(
					$content['item'], 
					self::generateField($field)
				);
			}
		}

		return $content;
	}

	public static function generateField($attrebutes)
	{
		return array(
			'@attributes' => $attrebutes
		);
	}

	public static function prepareTableFields($fields)
	{
		foreach ($fields as &$field) {
			// Min length
			if (isset($field['min_length'])) {
				unset($field['min_length']);
			}

			// Required
			if (isset($field['required'])) {
				unset($field['required']);
			}

			// Seo
			if (isset($field['seo'])) {
				unset($field['seo']);
			}
		}

		return $fields;
	}

	public static function prepareFormFields($fields)
	{
		// Type
		foreach ($fields as &$field) {
			switch ($field['type']) {
				case 'VARCHAR':
				case 'DATE':
				case 'TEXT':
					$field['type'] = 'String';
					break;

				case 'INT':
					$field['type'] = 'Int';
					break;

				case 'FLOAT':
					$field['type'] = 'Float';
					break;

				case 'DOUBLE':
					$field['type'] = 'Double';
					break;

				case 'BOOL':
					$field['type'] = 'bool';
					break;

				case 'DATETIME':
					$field['type'] = 'Datetime';
					break;

				case 'DATE':
					$field['type'] = 'Date';
					break;
				
				default:
					$field['type'] = 'String';
					break;
			}

			// Length
			if ($field['length'] > 0) {
				$field['max_length'] = $field['length'];
				unset($field['length']);
			}

			// Multilang
			if (isset($field['multilang'])) {
				unset($field['multilang']);
			}

			// Unique
			if (isset($field['unique'])) {
				unset($field['unique']);
			}

			// Error message
			if (isset($field['err_mess'])) {
				unset($field['err_mess']);
			}
		}

		return $fields;
	}

	public static function arrayToXML($root_node, $content)
    {
		$xml = Array2XML::createXML($root_node, $content);
		return $xml->saveXML();
    }
}
