<?php
/**
 * Created by PhpStorm.
 * User: Kovács Nikolett
 * Date: 2018. 06. 28.
 * Time: 17:55
 */

class EmployeeController extends MainController {

	private $pdo;

	public function __construct() {
		$db         = new DBController();
		$this->pdo  = $db->connect();
	}

	public function create() {}

	public function edit() {}

	public function getList() {

		$employee   = new Employee($this->pdo);
		$result     = $employee->getAll();

		$list = [];

		foreach ($result as $row) {
			array_push($list, $row );
		}

		echo json_encode($list);
		return;

	}

}