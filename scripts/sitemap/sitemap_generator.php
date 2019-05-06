<?php

$root = '/var/www/admin/data/www/termostar.md';

include_once("$root/kernel/database.php");

require_once("$root/classes/Sitemap/SitemapLog.class.php");
require_once("$root/classes/Sitemap/SitemapBase.class.php");
require_once("$root/classes/Sitemap/Sitemap.class.php");
require_once("$root/classes/Sitemap/SitemapIndex.class.php");
require_once("$root/classes/Sitemap/SitemapBuilder.class.php");

set_time_limit(0);
ini_set('memory_limit', '64M');

$dir = $root;
$tmp_dir = "$root/scripts/sitemap/tmp";
$base_url = 'http://www.termostar.md/';
$gzip = true;
$config = array('path' => $dir , 'tmp_dir' => $tmp_dir, 'base_url' => $base_url, 'gzip' => $gzip, 'gzip_level' => 9);
$builder = new SitemapBuilder($config);
$now = time();
$global_lastmod = 0;


$db = Database::DataBaseConnect();

$lang = "ru";
$lang_ID = 1;
    
$builder->start();
$builder->addUrl(array(
	'loc' => $base_url,
	'lastmod' => $now,
	'priority' => 1.0,
	'changefreq' => 'weekly')
);


// Categories
$objects = $db->ArrayValuesQ("SELECT * FROM Category WHERE block = 0 AND level > 0 ORDER BY level ASC");

foreach($objects as $object){
	$builder->addUrl(array(
		'loc' => $base_url.$lang.'/products/'.$object['name'],
		'lastmod' => max($global_lastmod, strtotime($object['updated'])),
		'priority' => round(1 - ((($object['level'] - 1) / 1.5) / 10), 2),
		'changefreq' => 'weekly'
	));
}


// Pages
$not_in = array('cart', 'favorites', 'compare', 'profile', 'search', 'products', 'article', 'product', 'index');
$objects = $db->ArrayValuesQ("SELECT t.*, tl.* FROM Pages t LEFT JOIN Pages_ml tl ON t.ID = tl.ID WHERE tl.lang_ID = $lang_ID AND t.block = 0");

foreach($objects as $object){
	if(!in_array($object['name'], $not_in)){
		if($object['name'] == 'articles')
			$changefreq = 'daily';
		elseif($object['name'] == 'video')
			$changefreq = 'weekly';
		else
			$changefreq = 'never';

		$builder->addUrl(array(
			'loc' => $base_url.$lang.'/'.$object['name'],
			'lastmod' => max($global_lastmod, strtotime($object['updated'])),
			'priority' => 1,
			'changefreq' => $changefreq
		));
	}
}


// Articles
$objects = $db->ArrayValuesQ("SELECT t.*, tl.* FROM Article t LEFT JOIN Article_ml tl ON t.ID = tl.ID WHERE tl.lang_ID = $lang_ID AND t.block = 0");

foreach($objects as $object){
	$builder->addUrl(array(
		'loc' => $base_url.$lang.'/article/'.$object['name'],
		'lastmod' => max($global_lastmod, strtotime($object['updated'])),
		'priority' => 0.75,
		'changefreq' => 'never'
	));
}


// News
$objects = $db->ArrayValuesQ("SELECT t.*, tl.* FROM News t LEFT JOIN News_ml tl ON t.ID = tl.ID WHERE tl.lang_ID = $lang_ID AND t.block = 0");

foreach($objects as $object){
	$builder->addUrl(array(
		'loc' => $base_url.$lang.'/news/'.$object['name'],
		'lastmod' => max($global_lastmod, strtotime($object['updated'])),
		'priority' => 0.65,
		'changefreq' => 'never'
	));
}


// Products
$objects = $db->ArrayValuesQ("SELECT t.*, tl.* FROM Item t LEFT JOIN Item_ml tl ON t.ID = tl.ID WHERE tl.lang_ID = $lang_ID AND t.block = 0 AND t.category_ID > 0 AND mainitem_ID = '' AND multi = 0");

foreach($objects as $object){
	$builder->addUrl(array(
		'loc' => $base_url.$lang.'/'.$object['name'],
		'lastmod' => max($global_lastmod, strtotime($object['updated'])),
		'priority' => 0.9,
		'changefreq' => 'weekly'
	));
}

$builder->commit();


$currentDir = dirname(__FILE__);
define('ROOT_FOLDER',$currentDir);

$fp = fopen(ROOT_FOLDER.'/log_cron.txt', 'a');

ob_start();

fwrite($fp, date("d.m.Y H:i:s"));

ob_end_clean();

fclose($fp);