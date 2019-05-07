<?php

ini_set('error_reporting', E_ALL - E_NOTICE - E_DEPRECATED - E_WARNING);
ini_set('display_errors', 1);

mb_internal_encoding("UTF-8");

$time_spent = $initial_time  = microtime(true);
$debug = array();
$mem = memory_get_usage();

include("kernel/include.php");

$params = array(
	"multilang" => true, // Мультиязычный сайт
	"count_visits" => false, // Следить за посещаемостью
	"rates" => false, // Использовать цены
	"use_filters" => false, // Использовать фильтрацию в каталоге
	"users" => false, // Использовать пользователей в лицевой части сайта
	"followers" => false, // Работать с подписчиками
	"cart" => false, // Использовать корзину
	"shares" => false, // Использовать акции
	"orders" => false, // Использовать заказы
	"parameters" => false, // Использовать параметры у товаров
	"use_cache" => false
);

Application::Initialize($params);
// ----------------------------------------------------------------------------

?>

<?php


//if (Model::$user->login == 'Saneock') {
//    Language::OptimizeTablesToNewLanguage(2);
//}

//exit();
if (Application::$section == 'frontend' and Application::$returnType == 'html' and Model::$user->login == 'Saneock') {
	$duplicated = array_count_values(Database::$queries);

	foreach ($duplicated as $query => $count) {
		if ($count <= 1) {
			unset($duplicated[$query]);
		}
	}
?>

	<div class="debug-panel">
		<p>Запросов: <b><?=count(Database::$queries)?></b></p>
		<p>Дублируются: <b><?=(count(Database::$queries) - count(array_unique(Database::$queries)))?></b></p>
	</div>

	<style>
		.debug-panel {
			position: fixed;
			top: 20%;
			right: 0;
			background-color: #fff;
			border: 1px solid #CCC;
			border-radius: 6px 0 0 6px;
			padding: 16px;
		}
	</style>


	<?php // print_r($duplicated); ?>

<?php } ?>