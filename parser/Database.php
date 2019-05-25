<?php

class Database{
	private $mysqli;
	
	public function __construct() {
	
		//Настройки подключения
		///////////////////////////////////////////////////////////
		$host = 'localhost'; //Хост базы данных
		$login = 'root'; // Пользователь MySqli
		$password = 'root'; // Пароль MySqli
		$db_name = 'test'; // Имя базы данных
		///////////////////////////////////////////////////////////

		$this->mysqli = new mysqli($host, $login, $password, $db_name);
		$this->mysqli->query("SET NAMES 'utf8'");
	}
 
  public function query($query) {
	return $this->mysqli->query($query);
  }
}