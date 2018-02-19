<?php

class Database{
	private $mysqli;
	
	public function __construct() {
	
		//Настройки подключения
		///////////////////////////////////////////////////////////
		$host = 'mysql314.1gb.ua'; //Хост базы данных
		$login = 'gbua_testserver'; // Пользователь MySqli
		$password = 'a51efb9capsg'; // Пароль MySqli
		$db_name = 'gbua_testserver'; // Имя базы данных
		///////////////////////////////////////////////////////////

		$this->mysqli = new mysqli($host, $login, $password, $db_name);
		$this->mysqli->query("SET NAMES 'utf8'");
	}
 
  public function query($query) {
	return $this->mysqli->query($query);
  }
}