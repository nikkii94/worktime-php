<?php
/**
 * Created by PhpStorm.
 * User: Kovács Nikolett
 * Date: 2018. 06. 28.
 * Time: 18:04
 */

class WorkTime {

	private $pdo;

	public $id;
	public $date;
	public $employeeId;
	public $startTime;
	public $endTime;
	public $totalWorkTime;
	public $sundayBonus;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function getSingle($id) {

		$stmt = $this->pdo->prepare('SELECT * from worktime WHERE :id=?');
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->setFetchMode(PDO::FETCH_ASSOC);

	}

	public function getSingleByEmployeeAndDate($employeeID, $date) {

		$stmt = $this->pdo->prepare('SELECT * from worktime WHERE date=:date AND employee_id=:employee_id');
		$stmt->bindParam(':date', $date, PDO::PARAM_STR, 10);
		$stmt->bindParam(':employee_id', $employeeID, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);

	}

	public function getAll() {

		$result = $this->pdo->query('SELECT worktime.*, employee.name FROM worktime INNER JOIN employee ON worktime.employee_id = employee.id', PDO::FETCH_ASSOC);

		return $result->fetchAll();
	}

	public function getAllGroupBy($group_by) {

		$result = $this->pdo->query("SELECT worktime.*, employee.name FROM worktime INNER JOIN employee ON worktime.employee_id = employee.id GROUP BY $group_by", PDO::FETCH_ASSOC);

		return $result->fetchAll();
	}


	public function getAllFromEmployee($employeeID) {

		$stmt = $this->pdo->prepare('SELECT  * from worktime WHERE employee_id=:employee_id');
		$stmt->bindParam(':employee_id', $employeeID, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);

	}

	public function insert($workTimeData) {

		$stmt = $this->pdo->prepare('INSERT INTO worktime (date, employee_id, start_time, end_time, total_work_time, sunday_bonus) VALUES(:date, :employee_id, :start_time, :end_time, :total_work_time, :sunday_bonus)');
		$stmt->bindParam(':date', $workTimeData['date'], PDO::PARAM_STR, 10);
		$stmt->bindParam(':employee_id', $workTimeData['employee_id'], PDO::PARAM_INT);
		$stmt->bindParam(':start_time', $workTimeData['start_time'], PDO::PARAM_STR, 5);
		$stmt->bindParam(':end_time', $workTimeData['end_time'], PDO::PARAM_STR, 5);
		$stmt->bindParam(':total_work_time', $workTimeData['total_work_time'], PDO::PARAM_STR, 5);
		$stmt->bindParam(':sunday_bonus', $workTimeData['sunday_bonus'], PDO::PARAM_STR, 5);
		$stmt->execute();

		return $this->pdo->lastInsertId();

	}

	public function update($parameters, $rowID) {

		$setParameters = '';
		$keys = array_keys($parameters);

		foreach ($parameters as $k => $v){
			if ( $k === $keys[count($keys) - 1]) {
				$setParameters .= "$k=:$k ";
			}else{
				$setParameters .= "$k=:$k, ";
			}
		}

		$stmt = $this->pdo->prepare('UPDATE worktime SET '.$setParameters.' WHERE id = :id');

		foreach ($parameters as $k => &$v){
			if ( is_integer($v) ){
				$stmt->bindParam(':'.$k, $v, PDO::PARAM_INT);
			}else{
				$stmt->bindParam(':'.$k, $v, PDO::PARAM_STR);
			}
		}

		$stmt->bindParam(':id', $rowID, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->rowCount();

	}

	public function delete($rowID) {

		$stmt = $this->pdo->prepare('DELETE FROM worktime WHERE id = :id LIMIT 1');
		$stmt->bindParam(':id', $rowID, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->rowCount();

	}


}