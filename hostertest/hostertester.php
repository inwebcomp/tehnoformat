<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);
$Version="0.99.3";?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="ru" />
<title>Хостер-Тестер v<?echo $Version;?> </title>
<link rel="stylesheet" type="text/css" href="hostertester.css" />
</head>
<body>
<?php
if ((isset($_GET["a"])) && ($_GET["a"]=="phpinfo")) {
	phpinfo();
	echo "</body></html>";
	die ();
};
?>

<div class="maintester">
<div class="head">
<div id="head_logo"><a href="http://hostertest.ru"><img src="logo.gif" aligh="right" border=0></a></div>
<div id="head_text"><h1><a href="http://hostertest.ru">Хостер-Тестер v<?echo $Version;?></a></h1>
	Скрипт для тестирования хостинга.<br>
	</div>
</div>

<form action="http://hostertest.ru/tests/add" method="post">
<?php
// Получаем входные параметры
if (isset($_GET["test_cpu"])) $test_cpu=$_GET["test_cpu"];
else $test_cpu=false;
if (isset($_GET["test_mysql"])) $test_mysql=$_GET["test_mysql"];
else $test_mysql=false;
if (isset($_GET["test_fs"])) $test_filesystem=$_GET["test_fs"];
else $test_filesystem=false;

if (isset($_GET["mysql_server"])) $mysql_server=$_GET["mysql_server"];
else $mysql_server=false;
if (isset($_GET["mysql_user"])) $mysql_user=$_GET["mysql_user"];
else $mysql_user=false;
if (isset($_GET["mysql_password"])) $mysql_password=$_GET["mysql_password"];
else $mysql_password=false;
if (isset($_GET["mysql_db"])) $mysql_db=$_GET["mysql_db"];
else $mysql_db=false;



//Таймер взял с http://www.eomy.net/forum/taimer-vt558.html
function timer($shift = false)
{
  static $first = 0;
  static $last;

  $now = preg_replace('#^0(.*) (.*)$#', '$2$1', microtime());
  if (!$first) $first = $now;
  $res = $shift ? $now - $last : $now - $first;
  $last = $now;
  return round($res,6);
}
 
function my_query($sql) {
$ret = mysql_query($sql) or die(mysql_error()."</br>Запрос:".$sql);
// $ret = mysql_query($sql) or die(mysql_error()."</br>Запрос:".$sql);
return $ret;
} 

//=========================================================================================
// Общие сведения

echo "<br><h3>Общие сведения</h3><div class=test>".
	 "<table noborder><tr><td><img src='noimg.gif' width=350px height=1px></td></tr>";
	echo '<tr><td>Имя сервера: </td><td>http://'.$_SERVER["HTTP_HOST"].' </td></tr>'; 
	
	$str =phpversion();
	echo '<tr><td>Версия PHP:: </td><td>'.$str.' </td></tr>'; 
	echo '<input type ="hidden" name="phpversion" value="'.$str.'">'; 

	$str =date('Y-m-d G:i:s');
	echo '<tr><td>Дата/время тестирования: </td><td>'.$str.' </td></tr>'; 
	echo '<input type ="hidden" name="date" value="'.$str.'">'; 
	
	echo '<tr><td><a href="hostertester.php?a=phpinfo" target="blank" title="phpinfo() в новом окне">Подробнее о вашем PHP</a> </td></tr>'; 
timer(); 

//=========================================================================================
// Тест CPU

echo "</table></div><h3>Тест CPU</h3><div class=test>".
	 "<table noborder><tr><td><img src='noimg.gif' width=350px height=1px></td></tr>";
if ($test_cpu){
	for ($i=1;$i<1000000;$i++){$a=sin($i);};
	
	$str =timer(1);
	echo '<tr><td>CPU: млн синусов: </td><td>'.$str.' сек. </td></tr>'; 
	echo '<input type ="hidden" name="cpu_mln_sinus" value="'.$str.'">'; 
	
	$a=""; $b="";
	for ($i=1;$i<1000000;$i++){$c= $a.$b;};
	
	$str =timer(1);
	echo '<tr><td>CPU: млн слияний строк через точку: </td><td>'.$str.' сек. </td></tr>'; 
	echo '<input type ="hidden" name="cpu_mln_concats_dot" value="'.$str.'">'; 

	for ($i=1;$i<1000000;$i++){$c= "$a$b";};
	
	$str =timer(1);
	echo '<tr><td>CPU: млн слияний строк в кавычках: </td><td>'.$str.' сек. </td></tr>'; 
	echo '<input type ="hidden" name="cpu_mln_concats_quotes" value="'.$str.'">'; 

	for ($i=1;$i<1000000;$i++){implode("", array($a, $b));};
	
	$str =timer(1);
	echo '<tr><td>CPU: млн слияний строк через массив: </td><td>'.$str.' сек. </td></tr>'; 
	echo '<input type ="hidden" name="cpu_mln_concats_array" value="'.$str.'">'; 
	
}else echo "<i>Тест пропущен</i>";

//=========================================================================================
// Тест MySQL

echo "</table></div><h3>Тест MySQL</h3><div class=test>".
	 "<table noborder><tr><td><img src='noimg.gif' width=350px height=1px></td></tr>";

if ($test_mysql){
	//=====================================
	$link = mysql_connect($mysql_server, $mysql_user, $mysql_password);
	if (!$link) {
		die('Невозможно соединиться: ' . mysql_error());
	}
	mysql_close($link);
	$str =timer(1);
	echo '<tr><td>MySQL: соединение/разъединение:</td><td>'.$str.' сек. </td></tr>'; 
	echo '<input type ="hidden" name="mysql_link" value="'.$str.'">'; 

	//=====================================
	$link = mysql_connect($mysql_server, $mysql_user, $mysql_password);
	if (!$link) {
		die('Невозможно соединиться: ' . mysql_error());
	}
	mysql_select_db($mysql_db);
	my_query('SELECT BENCHMARK(1000000, (select sin(100)))');
	$str =timer(1);
	echo '<tr><td>MySQL: benchmark (млн. синусов ):</td><td>'.$str.' сек. </td></tr>'; 
	echo '<input type ="hidden" name="mysql_mln_sinus" value="'.$str.'">'; 

	//=====================================
	//mysql_select_db($mysql_db);
	my_query('drop table if exists hostertester_test',$link);
	my_query('create table hostertester_test(a int) ENGINE=MyISAM',$link);
	for ($i=1;$i<10000;$i++){	my_query('insert into hostertester_test values ('.$i.')');};
	$str =timer(1);
	echo '<tr><td>MySQL: 10000 вставок строк :</td><td>'.$str.' сек. </td></tr>'; 
	echo '<input type ="hidden" name="mysql_mln_inserts" value="'.$str.'">'; 
	
	//=====================================
	mysql_select_db($mysql_db);
	$result = my_query('select * from hostertester_test where a>0');

	while ($row = mysql_fetch_assoc($result)) ;
	my_query('drop table if exists hostertester_test',$link);
	$str =timer(1);
	echo '<tr><td>MySQL: 10000 select и fetch :</td><td>'.$str.' сек. </td></tr>'; 
	echo '<input type ="hidden" name="mysql_mln_selects" value="'.$str.'">'; 

	//=====================================
	mysql_select_db($mysql_db);
	$result=my_query('select version();');
    if ($row = mysql_fetch_array($result)) {
		$str =$row[0];
		$mysqlver=$str;
		echo '<tr><td>Версия MySQL:</td><td>'.$str.' </td></tr>'; 
		echo '<input type ="hidden" name="mysql_version" value="'.$str.'">'; 	
    }
	mysql_free_result($result);
	//=====================================
	mysql_select_db($mysql_db);
	
	$result1=my_query('show '.($mysqlver[0]=='5'?'global':'').' status like "%Uptime%";');
    $row1 = mysql_fetch_array($result1); 
	$str =$row1[1];
	echo '<tr><td>Время работы сервера:</td><td>'.$str.' сек.,('.round($row1[1]/3600,2).' ч.) </td></tr>'; 
	echo '<input type ="hidden" name="mysql_uptime" value="'.$str.'">'; 
	
	
	$result2=my_query('show  '.($mysqlver[0]=='5'?'global':'').' status like "%Bytes_sent%";');
    $row2 = mysql_fetch_array($result2);
	$str =round($row2[1]/$row1[1],2);
	echo '<tr><td>Выдача байт в секунду в среднем:</td><td> '.$str.' байт ( '.$row2[1].' за весь uptime)</td></tr>'; 
	echo '<input type ="hidden" name="mysql_bytes_sended" value="'.$str.'">'; 

	$result3=my_query('show '.($mysqlver[0]=='5'?'global':'').' status like "%Connections%";');
    $row3 = mysql_fetch_array($result3);
	$str =round($row3[1]/$row1[1],5);
	echo '<tr><td>Соединений в секунду в среднем:</td><td>'.$str.' ( '.$row3[1],' за весь uptime )</td></tr>'; 
	echo '<input type ="hidden" name="mysql_connections" value="'.$str.'">'; 

	$result4=my_query('show '.($mysqlver[0]=='5'?'global':'').' status like "%Com_select%";');
    $row4 = mysql_fetch_array($result4);
	$str =round($row4[1]/$row1[1],5);
	echo '<tr><td>Запросов SELECT в секунду в среднем:</td><td>'.$str.' (  '.$row4[1].' за весь uptime)</td></tr>'; 
	echo '<input type ="hidden" name="mysql_selects" value="'.$str.'">'; 

	mysql_free_result($result1);
	mysql_free_result($result2);
	mysql_free_result($result3);
	mysql_free_result($result4);
	//=====================================
	
	mysql_close($link);
	timer(1);
	
}else echo "<i>Тест пропущен</i>";

//=========================================================================================
// Тест файловой системы
echo "</table></div><h3>Тест файловой системы</h3><div class=test>".
	 "<table noborder><tr><td><img src='noimg.gif' width=350px height=1px></td></tr>";

if ($test_filesystem){

	//=======================================
	$filename = 'hostertester_test.txt';
    if (!$handle = fopen($filename, 'w'))  die ("Не могу открыть файл ($filename) на запись");        

	for ($i=1;$i<1000000;$i++){
		if (fwrite($handle, "1") === FALSE) die ("Не могу произвести запись в файл ($filename)");
	}  
    fclose($handle);
	$str =timer(1);
	echo '<tr><td>FS: Запись в файл </td><td>'.$str.' сек. <br>'; 
	echo '<input type ="hidden" name="fs_mln_writes" value="'.$str.'">'; 
	//=======================================
    if (!$handle = fopen($filename, 'r'))  die ("Не могу открыть файл ($filename) на чтение");        

	while (!feof($handle)) {
		fread($handle, 1); // читаем по 1 байту
	}  
    fclose($handle);
	unlink ($filename);
	$str =timer(1);

	echo '<tr><td>FS: Чтение из файла </td><td>'.$str.' сек. </td></tr>'; 
	echo '<input type ="hidden" name="fs_mln_reads" value="'.$str.'">'; 
    
}else echo "<i>Тест пропущен</i>";

echo "</table></div><br>";

?>

Вы можете послать данные теста на наш сервер. <br>
Это даст вам возможность сравнивать свои данные с другими тестами. <br>
Кроме того это поможет нашему проекту, сделав нашу статистику немного лучше!<br>

<table noborder>
<tr><td>URL сайта (необязательно):</td><td><input type="text" name="server_name" value="http://<?php echo $_SERVER["HTTP_HOST"]?>"></td></tr>
</table>

<p><small>Ваши тестовые данные будут опубликованы в общем доступе. Если вы этого оне хотите - не отправляйте</small></p>
<input type="submit" value="Отправить">
</form>


<div class='footer'><small>2009::<a href="http://blogocms.ru">Altesack</a></small></div></div>
<!-- <?php /*-->
<div class='div_messagebox'>
<br><br><br><br><br><Br><br><br><br><Br><br><br><br><br><br><Br><br><br><br><Br>
<center><h1>По всей видимости на вашем хостинге <bR>не работает PHP.<br><br> Выполнить тест невозможно</h1></center>
<br><br><br><br><br><Br><br><br><br><Br><br><br><br><br><br><Br><br><br><br><Br>
<br><br><br><br><br><Br><br><br><br><Br><br><br><br><br><br><Br><br><br><br><Br>
<br><br><br><br><br><Br><br><br><br><Br><br><br><br><br><br><Br><br><br><br><Br>
<br><br><br><br><br><Br><br><br><br><Br><br><br><br><br><br><Br><br><br><br><Br>
<br><br><br><br><br><Br><br><br><br><Br><br><br><br><br><br><Br><br><br><br><Br>
<br><br><br><br><br><Br><br><br><br><Br><br><br><br><br><br><Br><br><br><br><Br>
<br><br><br><br><br><Br><br><br><br><Br><br><br><br><br><br><Br><br><br><br><Br>
</div>
<!-- */?> -->
</body>
</html>