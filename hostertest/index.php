<?php 
header('Content-Type: text/html; charset=utf-8');
$Version="0.99.2";?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="ru" />
<title>Запуск теста </title>
<link rel="stylesheet" type="text/css" href="hostertester.css" />
</head>
<body>

<div class="maintester">
<div class="head">
<div id="head_logo"><a href="http://hostertest.ru"><img src="logo.gif" aligh="right" border=0></a></div>
<div id="head_text"><h1><a href="http://hostertest.ru">Хостер-Тестер v<?echo $Version;?></a></h1>
	Скрипт для тестирования хостинга.<br>
	</div>
</div>
<h1>Настройки теста</h1>
<form action=hostertester.php method=get>
<ol>
<li><input type="checkbox" name="test_cpu"    checked="checked" /> Тестировать CPU <br></li>
<li><input type="checkbox" name="test_mysql"  checked="checked" /> Тестировать MySQL <br>
<ul>
<li><input type="text" name="mysql_server"   checked="checked" /> Сервер MySQL <br></li>
<li><input type="text" name="mysql_user"     checked="checked" /> Пользователь MySQL <br></li>
<li><input type="text" name="mysql_password" checked="checked" /> Пароль MySQL <br></li>
<li><input type="text" name="mysql_db"       checked="checked" /> База данных MySQL <br></li>
</ul>
</li>
<li><input type="checkbox" name="test_fs" checked="checked" /> Тестировать файловую систему <br></li>
</ol>
<input type=submit value="Запустить тест">
</form>

<br><br><br><br>
<div class='footer'><small>2009::<a href="http://blogocms.ru">Altesack</a></small></div></div>
</body>
</html>