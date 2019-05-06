<?php
class Rates extends Model
{
    public static $default_currency;

    public static function GetPrice($price, $currency = false)
    {
        $content = [];

		// $default_currency = self::$default_currency['name'];
		$default_currency = $currency;

        $rates = Application::$rates;

        $price = (float) $price;

		if (! $currency) {
			$currency = $default_currency;
		}

		$content['default'] = [];

		foreach ($rates as $key => $value) {
			$content[$value['name']] = $value;
			$content[$value['name']]['price'] = Currency::FormatPrice($price * $rates[$currency]['value'], $value['value'], $value['display_type'], $value['round']);
		}

		$content['default'] = $content[$default_currency];
		unset($content[$default_currency]);

        if (count($content['default']) == 0) {
            return [];
        }

        return $content;
    }

    public static function GetTodayRates()
    {
        $content = [];

        $rates = self::_GetTodayRates();

        if (count($rates) == 0) {
            self::UpdateRates();
            $rates = self::_GetLastRates();
        }
        if (count($rates) > 0) {
            self::$default_currency = Currency::GetDefaultCurrency();

            foreach ($rates as $key => $value) {
                $values = [];
                $content[$value['name']] = $value;
            }
        }

        return $content;
    }

    public static function _GetTodayRates()
    {
        $content = [];

        $res = self::$db->Query("SELECT r.*, c.*, cml.*, r.created FROM `Rates` r LEFT JOIN Currency c ON c.name = r.name LEFT JOIN Currency_ml cml ON cml.ID = c.ID WHERE r.created = '" . date('Y-m-d') . "' AND cml.lang_ID = " . Application::$language->ID . ' ORDER BY c.pos');
        while ($arr = self::$db->Fetch($res)) {
            $content[] = $arr;
        }

        if (count($content) < Model::$db->Value('SELECT COUNT(*) FROM `Currency`')) {
            $content = [];
        }

        return $content;
    }

    public static function _GetLastRates()
    {
        $content = [];
        $created = self::$db->Value('SELECT created FROM `Rates` GROUP BY created ORDER BY created DESC LIMIT 1');
        $res = self::$db->Query("SELECT r.*, c.*, cml.*, r.created as created FROM `Rates` r LEFT JOIN Currency c ON c.name = r.name LEFT JOIN Currency_ml cml ON cml.ID = c.ID WHERE r.created = '" . $created . "' AND cml.lang_ID = " . Application::$language->ID . ' ORDER BY c.pos');
        while ($arr = self::$db->Fetch($res)) {
            $content[] = $arr;
        }

        return $content;
    }

    private static function UpdateRates()
    {
        global $currency;

        $fp = fsockopen('www.bnm.md', 80, $errNmb, $errStr, 30);
        if (!$fp) {
            return false;
        }

        $request = str_replace('%DATE%', date('d.m.Y'), 'http://www.bnm.md/ru/official_exchange_rates?get_xml=1&date=%DATE%');

        $httpOut = file_get_contents('http://www.bnm.md/ru/official_exchange_rates?get_xml=1&date=' . date('d.m.Y'));

        $xmlParser = xml_parser_create();
        xml_set_element_handler($xmlParser, 'startElement', 'endElement');
        xml_set_character_data_handler($xmlParser, 'characterData');
        xml_parse($xmlParser, $httpOut);
        xml_parser_free($xmlParser);

        $params = new Parameters();
        $params->order = 'pos';

        $currency_list = Currency::GetList('Currency', $params);

        foreach ($currency_list['items'] as $key => $val) {
            if (isset($currency[$val['name']])) {
                self::$db->Query("REPLACE `Rates` SET created = '" . date('Y.m.d') . "', name = '" . Checker::Escape($val['name']) . "', value = '" . Checker::Escape($currency[$val['name']]) . "'");
            } elseif ($val['name'] == 'MDL') {
                self::$db->Query("REPLACE `Rates` SET created = '" . date('Y.m.d') . "', name = 'MDL', value = '1'");
            }
        }
    }
}

    $currency = [];
    $currentTag = '';
    $currentElement = '';

    function startElement($parser, $element, $attr)
    {
        global $currentTag;
        $currentTag = strtolower($element);
    }

    function endElement($parser, $element)
    {
        global $currentTag;
        $currentTag = '';
    }

    function characterData($parse, $data)
    {
        global $currency, $currentTag, $currentElement;
        if ($currentTag == 'charcode') {
            $currentElement = $data;
        }
        if ($currentTag == 'value') {
            $currency[$currentElement] = $data;
        }
    }
