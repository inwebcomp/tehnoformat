<?php

namespace Hex\Html;

use Hex\Abstracts\Singleton;
use Application;

class Form extends Singleton
{
	protected static $instance;

	protected static $params = array();

	public static function open($params = array())
	{
		$attrs = array();

		if (self::param('styled'))
			$params['class'] = isset($params['class']) ? $params['class'] . ' form' : 'form';

		$attrs['id'] = isset($params['id']) ? $params['id'] : self::generateFormID();
		self::setParam('id', $attrs['id']);
		
		$attrs['method'] = isset($params['method']) ? $params['method'] : 'post';

		$attributes = array_merge($attrs, $params);

		$attributes = self::constructAttributes($attributes);

		echo '<form' . $attributes . '>' . "\n" . Application::$security->csrfField();

		return self::getInstance();
	}

	public static function close()
	{
		echo '</form>';

		self::clearParams();
	}
	
	public static function setParam($key, $value)
	{
		return (self::$params[$key] = $value);
	}
	
	public static function param($key)
	{
		return isset(self::$params[$key]) ? self::$params[$key] : false;
	}
		
	public static function clearParams()
	{
		self::$params = array();
	}

	public static function generateFormID()
	{
		return 'form-' . rand(0, 100);
	}

	public static function getID()
	{
		return self::param('id');
	}
	
	public function styled()
	{
		self::setParam('styled', true);

		return $this;
	}

	public function useParamsArray()
	{
		self::setParam('useParamsArray', true);

		return $this;
	}

	protected static function constructAttributes($params)
	{
		$attributes = '';
		
		if (is_array($params) ) {
			foreach ($params as $key => $value) {
				if (in_array($key, array('icon')) or $value === null)
					continue;

				$attributes .= ' ' . $key . '="' . addcslashes($value, '"') . '"';
			}
		}

		return $attributes;
	}
	
	public static function input($type, $name, $value = '', $params = array())
	{
		$attributes = self::prepareInputAttributes($type, $name, $value, $params);
		$attributes = self::constructAttributes($attributes);
		
		$html = '<input' . $attributes . ' />';
		
		if (self::param('styled'))
			return self::styledField($html, $params);

		echo $html;
	}
	
	public static function textareaInput($name, $value = '', $params = array())
	{
		$attributes = self::prepareTextareaAttributes($name, $value, $params);
		$attributes = self::constructAttributes($attributes);
		
		$html = '<textarea' . $attributes . '>' . $value . '</textarea>';
		
		if (self::param('styled'))
			return self::styledField($html, $params);

		echo $html;
	}
	
	public static function checkboxInput($label, $name, $value = 1, $selectedValue = '', $params = array())
	{
		$attributesA = self::prepareCheckboxAttributes($name, $value, $selectedValue, $params);
		$attributes = self::constructAttributes($attributesA);
		
		ob_start();
			self::label($label, $name);
			$html = ob_get_contents();
		ob_end_clean();

		if (self::param('styled')) {
			$html .= '<div class="checkbox">' . "\n" .
				'<input' . $attributes . ' />' . "\n" .
				'<label class="checkbox__label" for="' . $attributesA['id'] . '"></label>' . "\n" .
			'</div>';
		} else {
			$html .= '<input' . $attributes . ' />';
		}
	
		$params['classes'] = 'form__field--checkbox';

		if (self::param('styled'))
			return self::styledField($html, $params);

		echo $html;
	}
		
	public static function styledField($input, $params = array())
	{
		$required = (isset($params['required']) and $params['required']) ? ' form__field--required' : '';
		
		$classes = (isset($params['classes']) and $params['classes']) ? ' ' . $params['classes'] : '';

		if ($params['error_message'] !== null and $params['error_message'] !== false) {
			$error_message = '<div class="form__field__error">' . $params['error_message'] . '</div>'."\n";
			$error = ' form__field--error';
		} else {
			$error_message = '';
			$error = '';
		}

		if (isset($params['icon']) and $params['icon'] !== null and $params['icon'] !== false) {
			$icon = '<i class="form__field__icon icon icon--' . $params['icon'] . '"></i>'."\n";
		} else $icon = '';

		$html = '<div class="form__field' . $required . '' . $error . '' . $classes . '">'."\n".
		$icon.
		$input."\n".
		$error_message.
		'</div>';

		echo $html;
	}

	public static function prepareTextareaAttributes($name, $value = '', $params = array())
	{
		$attrs = array();

		$attrs['name'] = self::param('useParamsArray') ? 'params[' . $name . ']' : $name;
		
		$attrs['id'] = self::getID() . '-field-' . $name;

		if (self::param('styled'))
			$params['class'] = isset($params['class']) ? $params['class'] . ' form__field__textarea' : 'form__field__textarea';

		//

		$attributes = array_merge($attrs, $params);

		return $attributes;
	}

	public static function prepareCheckboxAttributes($name, $value = 1, $selectedValue = '', $params = array())
	{
		$attrs = array();

		$attrs['name'] = self::param('useParamsArray') ? 'params[' . $name . ']' : $name;
		
		$attrs['id'] = self::getID() . '-field-' . $name;
		
		$attrs['type'] = 'checkbox';

		$attrs['value'] = $value;

		if ($value == $selectedValue)
			$attrs['checked'] = 'checked';

		if (self::param('styled'))
			$params['class'] = isset($params['class']) ? $params['class'] . ' form__field__checkbox' : 'form__field__checkbox';

		//

		$attributes = array_merge($attrs, $params);

		return $attributes;
	}

	public static function prepareInputAttributes($type, $name, $value = '', $params = array())
	{
		$attrs = array();

		if ($type !== null and $type !== 'select')
			$attrs['type'] = $type;

		$attrs['name'] = self::param('useParamsArray') ? 'params[' . $name . ']' : $name;

		if ($value !== '')
			$attrs['value'] = $value;
		
		$attrs['id'] = self::getID() . '-field-' . $name;

		if (self::param('styled')) {
			$styledClass = ($type == 'select') ? 'form__field__' . $type : 'form__field__input';
			$params['class'] = isset($params['class']) ? $params['class'] . ' ' . $styledClass : $styledClass;
		}

		//

		$attributes = array_merge($attrs, $params);

		return $attributes;
	}
		
	public static function text($name, $value = '', $params = array())
	{
		return self::input('text', $name, $value, $params);
	}

	public static function number($name, $value = '', $params = array())
	{
	return self::input('number', $name, $value, $params);
	}

	public static function textarea($name, $value = '', $params = array())
	{
		return self::textareaInput($name, $value, $params);
	}
	
	public static function email($name, $value = '', $params = array())
	{
		return self::input('email', $name, $value, $params);
	}
	
	public static function password($name, $value = '', $params = array())
	{
		return self::input('password', $name, $value, $params);
	}
	
	public static function checkbox($label, $name, $value = 1, $selectedValue = '', $params = array())
	{
		return self::checkboxInput($label, $name, $value, $selectedValue, $params);
	}
	
	public static function label($value, $for = false, $params = array(), $required = false)
	{
		$for = $for ? ' for="' . self::getID() . '-field-' . $for . '"' : '';

		if (self::param('styled'))
			$params['class'] = isset($params['class']) ? $params['class'] . ' form__label' : 'form__label';

		if ($required)
			$params['class'] = $params['class'] . ' form__label--required';

		$attributes = self::constructAttributes($params);

		echo '<label' . $for . $attributes . '>' . $value . '</label>';
	}
	
	public static function button($label, $params = array(), $type = null)
	{
		$attrs = array();

		$attrs['class'] = 'button' . (self::param('styled') ? ' form__button' : '');

		if ($type !== null)
			$attrs['type'] = $type;

		$attributes = array_merge($attrs, $params);

		$attributes = self::constructAttributes($attributes);
		
		if (isset($params['icon']) and $params['icon'] !== null and $params['icon'] !== false) {
			$icon = '<i class="button__icon icon icon--' . $params['icon'] . '"></i>'."\n";
			$useIcon = true;
		} else $icon = '';

		$label = isset($useIcon) ? '<span>' . $label . '</span>' : $label;

		$html = '<button' . $attributes . '>' . $label . $icon . '</button>';

		echo $html;
	}
	
	public static function submit($label, $params = array())
	{
		return self::button($label, $params, 'submit');
	}
	
	
	
	public static function select($name, $options = array(), $value = '', $params = array())
	{
		$attributes = self::prepareInputAttributes('select', $name, $value, $params);
		$attributes = self::constructAttributes($attributes);
		
		$html = '<select' . $attributes . '>';

		$html .= self::createSelectOptions($options, $value);

		$html .= '</select>';

		
		if (self::param('styled'))
			return self::styledField($html, $params);

		echo $html;
	}

	public static function createSelectOptions($options = array(), $selectedValue = '')
	{
		$html = '';

		foreach ($options as $value => $title) {
			$html .= '<option value="' . $value . '"' . ($value == $selectedValue ? ' selected' : '') . '>' . $title . '</option>';
		}

		return $html;
	}
}