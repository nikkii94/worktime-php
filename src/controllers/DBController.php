<?php
/**
 * Created by PhpStorm.
 * User: Kovács Nikolett
 * Date: 2018. 06. 28.
 * Time: 17:16
 */

class DBController {

	private $host       = 'localhost';
	private $db_name    = 'worktime';
	private $username   = 'root';
	private $password   = '';
	private $charset    = 'utf8';
	private $dsn        = '';
	private $options    = '';

	public function __construct($opt = []) {

		$this->dsn = "mysql:host=$this->host;dbname=$this->db_name;charset=$this->charset";
		$this->options = $opt;

	}

	public function connect() {

		try{
			$pdo = new PDO($this->dsn, $this->username, $this->password);
		}catch (PDOException $e) {
			return 'Connection failed: ' . $e->getMessage();
		}

		return $pdo;

	}

}