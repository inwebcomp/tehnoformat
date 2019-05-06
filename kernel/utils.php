<?php

class Utils
{
    public function USort(&$arr, $field, $type = "int", $direction = false)
    {
        if ($type == "int") {
            eval("usort(\$arr, function(\$a, \$b){
				return ('" . (int) $direction . "' == 1 ? \$a[\"" . $field . "\"] < \$b[\"" . $field . "\"] : \$a[\"" . $field . "\"] > \$b[\"" . $field . "\"]);
			});");
        } else if ($type == "string") {
            eval("usort(\$arr, function(\$a, \$b){
				return ('" . (int) $direction . "' == 1 ? strcmp(\$b[\"" . $field . "\"], \$a[\"" . $field . "\"]) : strcmp(\$a[\"" . $field . "\"], \$b[\"" . $field . "\"]));
			});	");
        }
    }

    public static function Conv($text, $encoding1 = "utf-8", $encoding2 = "cp1251")
    {
        return iconv($encoding1, $encoding2, $text);
    }

    public static function ucfirst($text)
    {
        return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
    }

    public static function FILEStoNormalArray($files)
    {
        $another_format = [];

        if ($files['name']) {
            foreach ($files['name'] as $key => $name) {
                $another_format[] = [
                    "name"     => $name,
                    "type"     => $files['type'][$key],
                    "tmp_name" => $files['tmp_name'][$key],
                    "error"    => $files['error'][$key],
                    "size"     => $files['size'][$key],
                ];
            }
        } else {
            return $files;
        }

        return $another_format;
    }

    public static function CloseTags($content)
    {
        $position = 0;
        $open_tags = [];
        // Ignore tags
        $ignored_tags = ['br', 'hr', 'img'];

        while (($position = strpos($content, '<', $position)) !== false) {
            // Getting all tags
            if (preg_match("|^<(/?)([a-z\d]+)\b[^>]*>|i", substr($content, $position), $match)) {
                $tag = strtolower($match[2]);
                // Ignore ignored tags :)
                if (in_array($tag, $ignored_tags) == false) {
                    // If tag is opened
                    if (isset($match[1]) AND $match[1] == '') {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]++;
                        else
                            $open_tags[$tag] = 1;
                    }
                    // If tag is closed
                    if (isset($match[1]) AND $match[1] == '/') {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]--;
                    }
                }
                $position += strlen($match[0]);
            } else
                $position++;
        }
        // Close all opened tags
        $open_tags = array_reverse($open_tags);

        foreach ($open_tags as $tag => $count_not_closed) {
            $content .= str_repeat("</{$tag}>", $count_not_closed);
        }

        return $content;
    }

    public static function GetFileList($dir)
    {
        $content = [];
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    $arr = [];
                    if (filetype($dir . '/' . $file) == "file") {
                        $arr['full_dir'] = $dir . '/' . $file;
                        $arr['name'] = $file;
                        preg_match("/.([0-9a-zA-Z]+)$/", $file, $match);
                        $arr['type'] = $match[1];
                        $arr['name_only'] = str_replace("." . $arr['type'], "", $file);
                        $arr['size'] = filesize($dir . '/' . $file);
                        $content[] = $arr;
                    }
                }
                closedir($dh);
            }
        }
        return $content;
    }

    public static function SplitToBlocksArray($array, $num)
    {
        $content = [];

        $arr = [];
        $n2 = 0;
        $n = 1;
        foreach ($array as $key => $value) {
            $splitName = $n2;
            if ($num) {
                if ($n - floor($n / $num) * $num == 0) {
                    $arr[$splitName]["block"][$n] = $array[$n - 1];
                    $n2++;
                } else {
                    $arr[$splitName]["block"][$n] = $array[$n - 1];
                }
            } else {
                if (is_array($arr[$n])) {
                    $arr[$n] = array_merge($array[$n], $arr[$n]);
                } else {
                    $arr[$n] = $array[$n];
                }
            }
            $n++;
        }

        $content = $arr;

        return $content;
    }

    public static function ListDirectory($dir)
    {
        $content = [];

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $n = 0;
                while (($file = readdir($dh)) !== false) {
                    if ($file !== '.' and $file !== '..') {
                        $content[$n]['full_dir'] = $dir . $file;
                        $content[$n]['name'] = preg_replace("/.[a-zA-Z]+$/", "", $file);
                        $content[$n]['type'] = preg_replace("/^[0-9A-Za-z_-]+./", "", $file);
                        $content[$n]['full_name'] = $file;
                        $content[$n]['size'] = filesize($dir . $file);
                        $n++;
                    }
                }
            }
        }

        return $content;
    }

    public static function RemoveDir($dir, $gitignore = false)
    {
        if (! $dh = @opendir($dir)) return;
        while (false !== ($obj = readdir($dh))) {
            if ($obj == '.' || $obj == '..') continue;
            if (is_dir($dir . '/' . $obj)) Utils::RemoveDir($dir . '/' . $obj);
            else if (! $gitignore or ($gitignore and $obj != '.gitignore')) unlink($dir . '/' . $obj);
        }
        closedir($dh);
        @rmdir($dir);

        return true;
    }

    public static function ClearDir($dir, $gitignore = false)
    {
        if (! $dh = @opendir($dir)) return;
        while (false !== ($obj = readdir($dh))) {
            if ($obj == '.' || $obj == '..') continue;
            if (is_dir($dir . '/' . $obj)) Utils::RemoveDir($dir . '/' . $obj);
            else if (! $gitignore or ($gitignore and $obj != '.gitignore')) unlink($dir . '/' . $obj);
        }
        closedir($dh);

        return true;
    }

    public static function RecursiveRemoveFile($dir, $name)
    {
        if (! $dh = @opendir($dir)) return;
        while (false !== ($obj = readdir($dh))) {
            if ($obj == '.' || $obj == '..') continue;

            if ($obj == $name)
                unlink($dir . '/' . $name);

            if (is_dir($dir . '/' . $obj)) Utils::RecursiveRemoveFile($dir . '/' . $obj, $name);
        }
        closedir($dh);

        return true;
    }

    public static function CopyDir($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst, 0777);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::CopyDir($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);

        return true;
    }

    public static function TrimText($text, $wrap = 200, $trimFlag = false)
    {
        $matches = [];
        if ($trimFlag)
            preg_match("/.{" . $wrap . "}/uis", strip_tags($text), $matches);
        else
            preg_match("/.{" . $wrap . "}[^, ]*/uis", strip_tags($text, '<img><br><ul><li><ol>'), $matches);
        return (isset($matches[0])) ? $matches[0] . '...' : $text;
    }

    public static function Array2Xml($content)
    {
        static $doc = '', $stack = [], $listCount = 0, $level = 1;
        $level++;
        foreach ($content as $key => $value) {
            if (is_array($value)) {
                if (! is_numeric($key)) {
                    if (Utils::IsAssociativeArray($value)) //block
                    {
                        array_push($stack, 1);
                        $doc .= '<block name="' . $key . '" id="' . $key . '_' . $level . '">' . "\n";
                    } else //list
                    {
                        array_push($stack, 2);
                        $doc .= '<list name="' . $key . '" id="' . $key . '_' . $level . '">' . "\n";
                    }
                } else {
                    $doc .= '<element index="' . $listCount . '">' . "\n";
                    $listCount++;
                }

                Utils::Array2XML($value);

                //$value - не массив
                if (! is_numeric($key)) {
                    $val = array_pop($stack);
                    if ($val == 3) {
                        $doc .= "</element>" . "\n";
                        $val = array_pop($stack);
                    }
                    if ($val == 1)
                        $doc .= '</block>' . "\n";
                    else if ($val == 2) {
                        $listCount = 0;
                        $doc .= '</list>' . "\n";
                    }
                } else {
                    $doc .= "</element>" . "\n";
                }
            } else {
                if (! is_numeric($key)) {
                    $doc .= '<item name="' . $key . '"><![CDATA[' . $value . ']]></item>' . "\n";
                }
            }
        }
        $level--;
        return $doc;
    }

    public static function IsAssociativeArray($array)
    {
        if (is_array($array) && ! empty($array)) {
            for ($iterator = count($array) - 1; $iterator; $iterator--) {
                if (! array_key_exists($iterator, $array)) {
                    return true;
                }
            }
            return ! array_key_exists(0, $array);
        }
        return false;
    }

    public static function RusLat($text, $joiner = "-")
    {
        $text = htmlspecialchars_decode($text);

        $text = substr($text, 0, 100);
        $rus = ['ё', 'й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', 'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю', ' ', '-', 'ă', 'î', 'ș', 'ț', 'â'];

        $eng = ['yo', 'y', 'c', 'u', 'k', 'e', 'n', 'g', 'sh', 'sch', 'z', 'h', $joiner, 'f', 'i', 'v', 'a', 'p', 'r', 'o', 'l', 'd', 'zh', 'e', 'ya', 'ch', 's', 'm', 'i', 't', '', 'b', 'yu', $joiner, $joiner, 'a', 'i', 's', 't', 'a'];

        $count = count($rus);

        $word = str_replace($rus, $eng, mb_strtolower($text, 'UTF-8'));

        $word = preg_replace('#[^a-z0-9_-]*#isu', '', $word);
        $word = preg_replace('#\s+#isu', ' ', $word);

        $word = preg_replace('#[' . $joiner . ']{2,}#isu', $joiner, $word);

        return $word;
    }

    public static function GetPage($url)
    {
        $header[] = "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-us,en,ru,ru-ru;q=0.5";

        $urls = explode(';', $url);

        $html = '';
        foreach ($urls as $url) {
            if ($url) {
                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10');
                curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                //curl_setopt($curl, CURLOPT_REFERER, 'http://foxtrot.md/catalog.aspx?catalogId=10&classId=139');
                curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
                curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT, 300);

                $html .= curl_exec($curl);
                curl_close($curl);
            }
        }

        return $html;
    }

    public static function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
    {
        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
        $rgbArray = [];
        if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal = hexdec($hexStr);
            $rgbArray['R'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['G'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['B'] = 0xFF & $colorVal;
        } else if (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
            $rgbArray['R'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['G'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['B'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            return false; //Invalid hex color code
        }
        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
    }

    public static function ModifyImage($source, $ext, $destination, $qual = 90, $maxX = 0, $maxY = 0, $cropType = "outter", $bgcolor = null, $zoomfromborder = 0, $margin = [0, 0, 0, 0], $watermark = false)
    {
        switch ($ext) {
            case "jpg":
                $orig = ImageCreateFromJPEG($source);
                break;
            case "jpeg":
                $orig = ImageCreateFromJPEG($source);
                break;
            case "gif":
                $orig = ImageCreateFromGIF($source);
                break;
            case "png":
                $orig = ImageCreateFromPNG($source);
                break;
        }

        $origX = ImageSX($orig);
        $origY = ImageSY($orig);

        if ($cropType == 'outter') {
            if ($origX < $maxX) $maxX = $origX;
            if ($origY < $maxY) $maxY = $origY;
        }

        if (! $maxX && ! $maxY) {
            $maxX = $origX;
            $maxY = $origY;
        }

        $newX = round($origX);
        $newY = round($origY);

        $src[left] = 0;
        $src[top] = 0;
        $src[width] = $newX;
        $src[height] = $newY;
        $dest[left] = 0;
        $dest[top] = 0;

        $dest[width] = $maxX;
        $dest[height] = $maxY;

        $koef = $src[height] / $src[width];

        if (intval($maxY) && intval($maxX)) {
            // if both limits get inner image .
            if ($cropType == 'inner') {
                if (abs($src[width] / $dest[width]) > abs($src[height] / $dest[height])) {
                    $src[width] = $origY / $dest[height] * $dest[width];
                    $src[left] = intval(($origX - $src[width]) / 2);
                } else {
                    $src[height] = $origX / $dest[width] * $dest[height];
                    $src[top] = intval(($origY - $src[height]) / 2);
                }
            } else {
                if (abs($src[width] / $dest[width]) > abs($src[height] / $dest[height])) {
                    $dest[width] = $maxX;
                    $dest[height] = intval($src[height] * ($maxX / $src[width]));
                    $dest[left] = 0;
                    $dest[top] = intval(($maxY - $dest[height]) / 2);
                } else {
                    $dest[width] = intval($src[width] * ($maxY / $src[height]));
                    $dest[height] = $maxY;
                    $dest[left] = intval(($maxX - $dest[width]) / 2);
                    $dest[top] = 0;
                }
            }
        } else {
            if (! intval($maxY)) {
                // if used vertical limit
                if ($maxX < $src[width]) {
                    $koef = $maxX / $src[width];
                    $maxY = $dest[height] = $src[height] * $koef;
                } else {
                    $maxY = $dest[height] = $src[height];
                }
            } else {
                // used horisontal limit
                $koef = $maxY / $src[height];
                $maxX = $dest[width] = $src[width] * $koef;
            }
        }


        if ($bgcolor != null) {
            $fill = true;
        } else {
            $fill = false;
            $bgcolor = ["R" => 255, "G" => 255, "B" => 255];
        }


        $new_im = ImageCreateTrueColor($maxX, $maxY);

        if ($ext == 'png') { //  and ! $fill
            imagesavealpha($new_im, true);
            $fillColor = imagecolorallocatealpha($new_im, 0, 0, 0, 127);
        } else {
            $fillColor = imagecolorallocate($new_im, $bgcolor['R'], $bgcolor['G'], $bgcolor['B']);
        }

        imagefill($new_im, 0, 0, $fillColor);


        if (strpos($zoomfromborder, "%") !== false)
            $zoomfromborder = ($dest[height] > $dest[width]) ? $dest[height] * (intval($zoomfromborder) / 100) : $dest[width] * (intval($zoomfromborder) / 100);

        $k = $dest[height] / $dest[width];
        $dest[width] += $zoomfromborder * 2;
        $dest[height] = $dest[width] * $k;
        $dest[left] -= $zoomfromborder;
        $dest[top] -= $zoomfromborder;

        if ($margin[0] <> 0) $dest[top] += $margin[0];
        if ($margin[1] <> 0) $dest[right] += $margin[1];
        if ($margin[2] <> 0) $dest[bottom] += $margin[2];
        if ($margin[3] <> 0) $dest[left] += $margin[3];


        ImageCopyResampled($new_im, $orig, $dest[left], $dest[top], $src[left], $src[top], $dest[width], $dest[height], $src[width], $src[height]);


        if (is_array($watermark)) {
            self::setWatermark($new_im, $watermark);
        }


        //list($width, $height) = array(imagesx($new_im), imagesy($new_im));
        //$textcolor = imagecolorallocate($new_im, 0, 0, 0);
        //imagestring($new_im, 3, $width-53, $height-17, '4you.lv', $textcolor);

        switch ($ext) {
            case "jpg":
                ImageJPEG($new_im, $destination, $qual);
                break;
            case "jpeg":
                ImageJPEG($new_im, $destination, $qual);
                break;
            case "gif":
                ImageGIF($new_im, $destination, $qual);
                break;
            case "png":
                ImagePNG($new_im, $destination, $qual / 10);
                break;
        }

        @chmod($destination, 0777);
    }

    public static function setWatermark(&$image, $options)
    {
        if (! $options["img"])
            return false;

        $stamp_info = pathinfo($options["img"]);
        $stamp_ext = $stamp_info['extension'];
        $stamp_dir = $stamp_info['dirname'] . '/' . $stamp_info['basename'];

        switch ($stamp_ext) {
            case "jpg":
                $stamp = ImageCreateFromJPEG($stamp_dir);
                break;
            case "jpeg":
                $stamp = ImageCreateFromJPEG($stamp_dir);
                break;
            case "gif":
                $stamp = ImageCreateFromGIF($stamp_dir);
                break;
            case "png":
                $stamp = ImageCreateFromPNG($stamp_dir);
                break;
        }

        $s_width = $os_width = imagesx($stamp);
        $s_height = $os_height = imagesy($stamp);

        $i_width = imagesx($image);
        $i_height = imagesy($image);

        if ($i_width < $s_width) {
            $k = $s_width / $s_height;
            $s_width = $i_width;
            $s_height = $s_width / $k;
        }

        if ($i_height < $s_height) {
            $k = $s_height / $s_width;
            $s_height = $i_height;
            $s_width = $s_height / $k;
        }

        // Default values
        if (! isset($options['top']))
            $options['top'] = 'center';
        if (! isset($options['left']))
            $options['left'] = 'center';

        // Position
        if ($options['top'] == 'center')
            $top = ($i_height - $s_height) / 2;
        else if ($options['top'] == 'bottom')
            $top = $i_height - $s_height;
        else
            $top = (int) $options['top'];

        if ($options['left'] == 'center')
            $left = ($i_width - $s_width) / 2;
        else if ($options['left'] == 'right')
            $left = $i_width - $s_width;
        else
            $left = (int) $options['left'];

        // Size
        $width = $s_width;
        $height = $s_height;

        $a = [$left, $top, 0, 0, $width, $height];

        imagecopyresampled($image, $stamp, $left, $top, 0, 0, $width, $height, $os_width, $os_height);

        imagedestroy($stamp);
    }

    public static function UnpackZip($zipPath, $path)
    {
        $zip = new ZipArchive;

        if ($zip->open($zipPath) === true) {
            $zip->extractTo($path);
            $zip->close();
            return true;
        } else
            return false;
    }

    public static function PackZip($path, $save_path)
    {
        $zip = new ZipArchive;

        $files = Utils::GetFileList($path);
        if ($zip->open($save_path, ZIPARCHIVE::CREATE) === true) {
            if (count($files) > 0) {
                foreach ($files as $key => $info) {
                    $zip->addFile($path);
                }
            }
            $zip->close();

            return true;
        } else
            return false;
    }

    public static function ZipFolder($source, $destination)
    {
        if (! extension_loaded('zip') || ! file_exists($source)) {
            return false;
        }

        $zip = new ZipArchive();
        if (! $zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file) {
                $file = str_replace('\\', '/', $file);

                // Ignore "." and ".." folders
                if (in_array(substr($file, strrpos($file, '/') + 1), ['.', '..']))
                    continue;

                $file = realpath($file);

                if (is_dir($file) === true) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } else if (is_file($file) === true) {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        } else if (is_file($source) === true) {
            $zip->addFromString(basename($source), file_get_contents($source));
        }

        return $zip->close();
    }

    public static function TimeToString($time)
    {
        $return = "";

        $timeInt = strtotime($time);

        $razn = time() - $timeInt;

        if ($razn <= 3600) {
            $min = (int) (($razn / 60));
            if ($min == 0 || $min < 0) return lang("меньше минуты назад");

            if (($min % 10 < 5 && $min % 10 != 0 && ($min / 10 > 2 || $min / 10 < 1)) || ($min == 1)) {
                if ($min % 10 == 1)
                    $return = $min . ' ' . lang("минуту назад");
                else
                    $return = $min . ' ' . lang("минуты назад");
            } else
                $return = $min . ' ' . lang("минут назад");
        } else {
            if ($razn <= 86400) {
                $hour = (int) (($razn / 60) / 60);
                if ($hour == 0) return lang("час назад");

                if (($hour % 10 < 5 && $hour % 10 != 0 && ($hour / 10 > 2 || $hour / 10 < 1)) || ($hour == 1)) {
                    if ($hour % 10 == 1)
                        $return = $hour . ' ' . lang("час назад");
                    else
                        $return = $hour . ' ' . lang("часа назад");
                } else
                    $return = $hour . ' ' . lang("часов назад");
            } else {
                $day = (int) ($razn / 86400);
                if ($day > 1) {
                    $Dday = date("d", $timeInt);
                    $Dyear = date("Y", $timeInt);
                    $Dmonth = date("m", $timeInt);
                    $month["01"] = lang("Январь");
                    $month["02"] = lang("Февраль");
                    $month["03"] = lang("Март");
                    $month["04"] = lang("Апрель");
                    $month["05"] = lang("Май");
                    $month["06"] = lang("Июнь");
                    $month["07"] = lang("Июль");
                    $month["08"] = lang("Август");
                    $month["09"] = lang("Сентябрь");
                    $month["10"] = lang("Октябрь");
                    $month["11"] = lang("Ноябрь");
                    $month["12"] = lang("Декабрь");
                    $return = $month[$Dmonth] . " " . $Dday . ", " . $Dyear;
                } else
                    $return = $day . ' ' . lang("день назад");
            }
        }
        return $return;
    }

    public static function DateToShortDate($date)
    {
        $time = strtotime($date);

        $day = date("j", $time);

        $m = date("m", $time);

        $month['01'] = lang("янв.");
        $month['02'] = lang("фев.");
        $month['03'] = lang("мар.");
        $month['04'] = lang("апр.");
        $month['05'] = lang("мая");
        $month['06'] = lang("июня");
        $month['07'] = lang("июля");
        $month['08'] = lang("авг.");
        $month['09'] = lang("сен.");
        $month['10'] = lang("окт.");
        $month['11'] = lang("ноя.");
        $month['12'] = lang("дек.");

        return $day . " " . $month[$m];
    }

    public static function SecondsToString($time, $types = ["s", "m", "h", "d"])
    {
        $time = intval($time);

        if ($time > 0) {
            $string = "";
            if (in_array("d", $types)) {
                $num = floor($time / 86400);
                $time = $time % 86400;

                if ($num % 10 == 1) $word = lang("день");
                else if ($num % 10 >= 2 and $num % 10 <= 4) $word = lang("дня");
                else if ($num % 10 >= 5) $word = lang("дней");
                else $word = lang("дней");

                $string .= ($string !== "" ? " " : "") . $num . " " . $word;
            }
            if (in_array("h", $types)) {
                $num = floor($time / 3600);
                $time = $time % 3600;

                if ($num % 10 == 1) $word = lang("час");
                else if ($num % 10 >= 2 and $num % 10 <= 4) $word = lang("часа");
                else if ($num % 10 >= 5) $word = lang("часов");
                else $word = lang("часов");

                $string .= ($string !== "" ? " " : "") . $num . " " . $word;
            }
            if (in_array("m", $types)) {
                $num = floor($time / 60);
                $time = $time % 60;

                if ($num % 10 == 1) $word = lang("минута");
                else if ($num % 10 >= 2 and $num % 10 <= 4) $word = lang("минуты");
                else if ($num % 10 >= 5) $word = lang("минут");
                else $word = lang("минут");

                $string .= ($string !== "" ? " " : "") . $num . " " . $word;
            }
            if (in_array("s", $types)) {
                $num = floor($time % 60);
                $time = $time % 60;

                if ($num % 10 == 1) $word = lang("секунда");
                else if ($num % 10 >= 2 and $num % 10 <= 4) $word = lang("секунды");
                else if ($num % 10 >= 5) $word = lang("секунд");
                else $word = lang("секунд");

                $string .= ($string !== "" ? " " : "") . $num . " " . $word;
            }

            //sprintf('%2d '.lang("дней").' %02d часов %02d минут %02d секунд', $time / 86400, ($time % 86400) / 3600, (($time % 86400) % 3600) / 60, (($time % 86400) % 3600) % 60);

            return $string;
        } else
            return "";
    }

    public static function GetAnimationsList()
    {
        $content = [];

        $content[] = ["title" => lang("Импульс"), "value" => "pulse"];
        $content[] = ["title" => lang("Постепенное появление"), "value" => "fadeIn"];
        $content[] = ["title" => lang("Постепенное появление снизу"), "value" => "fadeInUp"];
        $content[] = ["title" => lang("Постепенное появление слева"), "value" => "fadeInLeft"];
        $content[] = ["title" => lang("Постепенное появление справа"), "value" => "fadeInRight"];
        $content[] = ["title" => lang("Увеличение"), "value" => "zoomIn"];
        $content[] = ["title" => lang("Увеличение снизу"), "value" => "zoomInUp"];
        $content[] = ["title" => lang("Переворот по вертикали"), "value" => "flipInX"];
        $content[] = ["title" => lang("Переворот по горизонтали"), "value" => "flipInY"];
        $content[] = ["title" => lang("Поворот слева"), "value" => "rotateInUpLeft"];
        $content[] = ["title" => lang("Поворот справа"), "value" => "rotateInUpRight"];

        return $content;
    }

    public static function escapeLink($text, $nofollow = true)
    {
        return preg_replace('"\b(https?://\S+)"', '<a href="$1" rel="nofollow">$1</a>', $text);
    }

//    public static function timeToHumanDate($time)
//    {
//        if (! is_int($time))
//            $time = strtotime($time);
//
//        if ($time <= 0)
//            return '';
//
//        $Dday = date("d", $time);
//        $Dyear = date("Y", $time);
//        $Dmonth = date("m", $time);
//        $month["01"] = lang("Январь");
//        $month["02"] = lang("Февраль");
//        $month["03"] = lang("Март");
//        $month["04"] = lang("Апрель");
//        $month["05"] = lang("Май");
//        $month["06"] = lang("Июнь");
//        $month["07"] = lang("Июль");
//        $month["08"] = lang("Август");
//        $month["09"] = lang("Сентябрь");
//        $month["10"] = lang("Октябрь");
//        $month["11"] = lang("Ноябрь");
//        $month["12"] = lang("Декабрь");
//
//        return $month[$Dmonth] . " " . $Dday . ", " . $Dyear;
//    }

    public static function timeToHumanDate($time)
    {
        return mb_convert_case(strftime("%e %B %Y", $time), MB_CASE_TITLE);
    }
}