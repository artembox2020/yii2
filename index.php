<?php
//Класс функций парсера
require __DIR__ . '/Parser.class.php';
$parser = new Parser();

//Берем урл
$url = $parser->request_url();

//Формируем урл
$new_url = str_replace('?r=inbox/inbox','',$url);

//Парсим его
parse_str(html_entity_decode($new_url), $out);
$type = array_keys($out);

//Определяем какой тип пакета 
$type_p = substr($type[0], strrpos($type[0], '/') + 1);

//Если тип пакета есть типом пакета инициализации или это пакет состояний?
if($type_p == "in"){
	$parser_data = $parser->in1($new_url);
}
else if($type_p == "data1")
{
	$parser_data = $parser->data1($new_url);
}
?>
