<?php
/**
 * Created by PhpStorm.
 * User: Kovács Nikolett
 * Date: 2018. 06. 28.
 * Time: 17:55
 */

class Employee {

	private $pdo;

	public $id;
	public $name;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function getSingle($id) {

		if ( ! is_integer($id) && ! is_integer((int)$id) ){
			return [];
		}

		$stmt = $this->pdo->prepare('SELECT * from employee WHERE id=:id');
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);

	}

	public function getAll() {

		$result = $this->pdo->query('SELECT * FROM employee', PDO::FETCH_ASSOC);

		return $result->fetchAll();
	}

}