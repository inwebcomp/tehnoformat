<?php

namespace Hex\Helpers;

class Url
{
    public static function normalizeYoutubeUrl($url)
	{
		return str_replace('watch?v=', 'embed/', $url);
	}
}
