<?php
	/**
	 * Created by PhpStorm.
	 * User: Ziya
	 * Date: 15.10.2019
	 * Time: 7:16
	 */

	/**
	Тестовое задание для PHP программиста
	Написать функцию, реализующую бинарный поиск значения по ключу в текстовом файле.
	Аргументы: имя файла, значение ключа
	Результат: если найдено: значение, соответствующее ключу, если не найдено: undef
	Исходные данные и требования к реализации:
	1. Объем используемой памяти не должен зависеть от размера файла, только от максимального размера записи.
	2. Формат файла: ключ1\tзначение1\x0Aключ2\tзначение2\x0A...ключN\tзначениеN\x0A Где: \x0A - разделитель записей
	(код ASCII: 0Ah) \t - разделитель ключа и значения (табуляция, код ASCII: 09h) Символы разделителей гарантированно
	не могут встречаться в ключах или значениях. Записи упорядочены по ключу в лексикографическом порядке с учетом
	регистра. Все ключи гарантированно уникальные.
	3. Ограничений на длину ключа или значения нет.
	Функция на файле размером 10Гб с записями длиной до 4000 байт должна отрабатывать любой запрос менее чем за 5 секунд.
	 */

	// решение принадлежит https://github.com/lokatop/Test_case_php

	/*
	Задание: написать скрипт который будет искать значение по ключу из данных полученных из обычного текстового файла.
	Реализовать поиск методом бинарного поиска.
	Бинарный поиск как я понимаю это если каждый раз делить по полам и отбросить не нужную часть.
	Допустим если мы ищем число 30 среи цифр от 0го до 100, то сначала делим 100 по полам и получаем 50.
	Потом проверяем в какой половине нахидится искомое значение. т.к. 30 меньше 50ти, мы уже не ищем в диапазоне 50-100,
	потом 50 делим по полам и получаем 25, получается искомое значение между 25 и 50, поэтому мы прибавляем 25 на 50
	и опять делим по полам. Получается 37,5, если округлим то получается 38. Т.к. до этой операции мы выяснили,
	что искомое значение больше, чем 25, следовательно сейчас мы рассмотрим интервал от 25 до 38. т.е.
	25+38=63 далее 63/2 = 31,5.
	25+32=57 57/2=28,5 29:31
	29+31=60 60/2=30
	*/


	/**
	 * функция создания файла
	 * @param $fileName
	 * @param $count
	 */
	function createFile($fileName,$count){
		$file=fopen($fileName,"w");
		for ($i=0;$i<$count;$i++){
			fwrite($file,"ключ".$i."\t"."значение".$i."\x0A");
		}
	}

	/**
	 * //функция установки и получения времени
	 * @param bool $time
	 * @return float|mixed
	 */
	function getTime($time = false)
	{
		return $time === false? microtime(true) : round(microtime(true) - $time, 3);
	}

	/**
	 * Функция бинарного поиска
	 * @param $fileName
	 * @param $desiredValue
	 * @return string
	 */
	function binarySearch($fileName, $desiredValue)
	{
		$file=new SplFileObject($fileName); // создаём объект файла
		$start = 0; //назначили левую граница
		$end = sizeof(file($fileName)) - 1; //вычисление правой границы, конца файла
//		echo "</br> End: ".$end. "</br>";
		while ($start <= $end) { //условие выхода за границы
			$position = floor(($start + $end) / 2);  //вычисление середины массива
			$file->seek($position);//взятие строки с вычесленным номером
//			echo $position."</br>";
			$elem = explode("\t", $file->current());// разбиение строки на пару ключ:значение
			$strnatcmp = strnatcmp($elem[0],$desiredValue); // сравниваем найденное значение с искомым
//			echo $elem[0]." : ";
//			echo $elem[1]."</br>";

			// strnatcmp функция возвращает отрицательное число, если str1 меньше,
			// чем str2, положительное число, если str1 больше, чем str2, и 0 если строки равны.
			if ($strnatcmp>0){
				$end = $position-1;
			}elseif($strnatcmp<0){
				$start = $position+1;
			}else{
				return $elem[1];
			}
		}
		return 'undef'; // не найденно значение
	}

	$fileName = "test.txt"; // имя файла
	$desiredValue=(isset($_POST['val']))? "ключ".$_POST['val']:"ключ500000"; //а тут найдет по умолчанию

	// проверяем существует ли файл и возвращаем тру фолс соответственно
	$checkFile = (file_exists(__DIR__."/$fileName"))?true:false;

	// если нет файла, то создаём
	if($checkFile==false) createFile($fileName,2000000);

	if(isset($_POST['submit'],$_POST['val']) && !empty($_POST['val'])){
		$time=getTime();
		$result=binarySearch($fileName,$desiredValue);
		$time=getTime($time);
		$view = "</br> Поиск ключа - ".$result. "</br>" ."Времени затрачено - ".$time." секунд  ";
	}else{
		$view = " </br> Введите число в поле для ввода ";
	}

?>

<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<title>Бинарный поиск</title>
</head>
<body>
<div class="row">

</div>

<form action="" method="post" class="row" style="margin-top: 30px">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
		<div class="form-group">
			<input type="text" name="val" autocomplete="off" placeholder="напишите число">
			<button class="btn btn-success" name="submit"> Найти </button>
		</div>
	</div>
	<div class="col-lg-3"></div>
</form>
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
		<?=$view?>
	</div>
	<div class="col-lg-3"></div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>