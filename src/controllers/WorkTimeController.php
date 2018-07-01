<?php
/**
 * Created by PhpStorm.
 * User: Kovács Nikolett
 * Date: 2018. 06. 29.
 * Time: 11:52
 */

class WorkTimeController extends MainController {

	private $pdo;

	public function __construct() {

		$db         = new DBController();
		$this->pdo  = $db->connect();
	}

	public function workTimeIndex() {
		$workTime = $this->getList();

		$this->render('worktime.php', [
			'workTimeList' => $workTime,
		]);
	}

	public function trackingIndex() {
		$tracking = $this->showTrackingTable();

		$this->render('tracking.php', [
			'trackingData' => $tracking,
		]);
	}

	public function create() {

		if ( !isset($_POST) || count($_POST) === 0 ){
			echo json_encode([
				'type'      => 'error',
				'message'   => 'Hibás űrlap!'
			]);
			exit;
		}

		$employeeID     = trim($_POST['employee']);
		$date           = trim($_POST['date']);
		$workTimeStart  = trim($_POST['start_time']);
		$workTimeEnd    = trim($_POST['end_time']);
		$hoursWorked    = trim($_POST['total_work_time']);
		$sundayBonus    = trim($_POST['sunday_bonus']);

		if ($employeeID === '' || $date === '' || $workTimeStart === '' ||
		    $workTimeEnd === '' || $hoursWorked === '' || $sundayBonus === '') {
			echo json_encode([
				'type'      => 'error',
				'message'   => 'Néhány adat hiányzik!'
			]); exit;
		}

		$currentDate    = new DateTime($date);
		$startDate      = new DateTime($date .' '. $workTimeStart);
		$endDate        = new DateTime($date .' '. $workTimeEnd);

		/** CHECK FOR CROSSING DAYS  */
		$crossingDay = $endDate < $startDate ? true : false;

		if ( $crossingDay ){
			$endDate = $endDate->modify('+1 day');
		}

		/** CHECK CALCULATED HOURS  */
		$totalWorked        = $endDate->diff($startDate);
		$totalWorkedValue   = $totalWorked->format('%H:%I');

		/** MODIFY TIME TO SAME DAY */
		if ( $crossingDay ){
			$startDate  = $startDate->setTime(0, 0);
			$endDate    = $endDate->setTime($totalWorked->h, $totalWorked->i);
		}

		/** IS SUNDAY */
		$isSunday = intval($currentDate->format('w')) === 0 ? true : false;

		$sundayBonusValue = "00:00";
		if( $isSunday ){
			$sundayBonusValue = $totalWorkedValue;
		}

		if ( is_integer(intval($employeeID)) ){
			$employee = (new Employee($this->pdo))->getSingle($employeeID);

			if ( !$employee ){
				echo json_encode([
					'type'      => 'error',
					'message'   => 'Nincs ilyen dolgozó!'
				]);
				exit;
			}
		}

		$workTime       = new WorkTime($this->pdo);
		$workTimeRow    = $workTime->getSingleByEmployeeAndDate($employeeID, $date);

		if ( ! $workTimeRow || count($workTimeRow) === 0 ){
			// create new work time record
			$data = [
				'date'              => $currentDate->format('Y-m-d'),
				'employee_id'       => $employeeID,
				'start_time'        =>  $startDate->format('H:i'),
				'end_time'          =>  $endDate->format('H:i'),
				'total_work_time'   => $totalWorkedValue,
				'sunday_bonus'      => $sundayBonusValue,
			];
			$insertID = $workTime->insert($data);

			echo json_encode([
				'type'      => 'success',
				'message'   => 'Sikeresen felvéve!',
				'data'      => [
					'insertID' => $insertID
				]
			]);
			exit;

		}

		else {

			$wt_id              = $workTimeRow['id'];
			$wt_employeeID      = $workTimeRow['employee_id'];

			$wt_date            = new DateTime($workTimeRow['date']);
			$wt_startTime       = new DateTime($workTimeRow['start_time']);
			$wt_endTime         = new DateTime($workTimeRow['end_time']);
			$wt_totalWorkTime   = new DateTime($workTimeRow['total_work_time']);
			$wt_sundayBonus     = new DateTime($workTimeRow['sunday_bonus']);

			$setParameters = [];
			if ( $currentDate->format('Y-m-d') !== $wt_date->format('Y-m-d') ){
				$setParameters['date'] = $currentDate->format('Y-m-d');
			}

			if ( $employeeID !== $wt_employeeID ){
				$setParameters['employee_id'] = $employeeID;
			}

			if ( $startDate->format('H:i') !== $wt_startTime->format('H:i') ){
				$setParameters['start_time'] = $startDate->format('H:i');
			}

			if ( $endDate->format('H:i') !== $wt_endTime->format('H:i') ){
				$setParameters['end_time'] = $endDate->format('H:i');
			}

			if ( $totalWorkedValue !== $wt_totalWorkTime->format('H:i') ){
				$setParameters['total_work_time'] = $totalWorkedValue;
			}

			if ( $sundayBonusValue !== $wt_sundayBonus->format('H:i') ){
				$setParameters['sunday_bonus'] = $sundayBonusValue;
			}

			if ( count($setParameters) === 0 ) {
				echo json_encode([
					'type'      => 'error',
					'message'   => 'Nincs frissítendő adat!',
					'data'      => [
						'affectedRows' => 0
					]
				]);
				exit;
			}

			$affectedRows = $workTime->update($setParameters, $wt_id);

			echo json_encode([
				'type'      => 'success',
				'message'   => 'Sikeresen frissítve!',
				'data'      => [
					'affectedRows' => $affectedRows
				]
			]);
			exit;

		}

	}

	public function delete() {

		$rowID      = trim($_POST['id']);
		$employeeID = trim($_POST['employeeID']);
		$date       = trim($_POST['date']);

		$employee = (new Employee($this->pdo))->getSingle($employeeID);

		if ( !$employee ){
			echo json_encode([
				'type'      => 'error',
				'message'   => 'Nincs ilyen dolgozó!'
			]);
			exit;
		}

		$workTime       = new WorkTime($this->pdo);
		$workTimeRow    = $workTime->getSingleByEmployeeAndDate($employeeID, $date);

		if( !$workTimeRow || $rowID !== $workTimeRow['id'] ){
			echo json_encode([
				'type'      => 'error',
				'message'   => 'Nincs bejegyzés a(z) ' . $employee['name'] . ' dolgozóhoz erre a napra: ' . $date . '!'
			]);
			exit;
		}

		$affectedRows = $workTime->delete($workTimeRow['id']);

		echo json_encode([
			'type'      => 'success',
			'message'   => 'Sikeresen törölve!',
			'data'      => [
				'affectedRows' => $affectedRows
			]
		]);
		exit;

	}

	public function getList($param = []) {

		$wt = new WorkTime($this->pdo);
		$result = $wt->getAll();

		$list = [];

		foreach ($result as $row) {

			$row['start_time']      = (new DateTime($row['start_time']))->format('H:i');
			$row['end_time']        = (new DateTime($row['end_time']))->format('H:i');
			$row['total_work_time'] = (new DateTime($row['total_work_time']))->format('H:i');
			$row['sunday_bonus']    = (new DateTime($row['sunday_bonus']))->format('H:i');

			array_push($list, $row );
		}

		if ( isset($param['json']) && $param['json'] === true ){
			echo json_encode( [
				'data' =>  $list
			]); exit;
		}

		return $list;

	}

	public function showTrackingTable() {

		$wtObj          = new WorkTime($this->pdo);
		$workTimeList   = $wtObj->getAll();

		$data   = [];
		$months = array_fill(1, 12, 0);

		foreach ($workTimeList as $wtRow){

			$dateParts  = explode('-', $wtRow['date']);
			$year       = $dateParts[0];
			$employeeID = $wtRow['employee_id'];

			if ( ! array_key_exists($employeeID, $data) ){

				$data[$employeeID] = [
					'name' => $wtRow['name'],
					'data' => []
				];

			}

			if ( ! array_key_exists($year, $data[$employeeID]['data']) ){

				$data[$employeeID]['data'][$year] = [
					'total_worked_hour'     => $months,
					'total_sunday_bonus'    => $months,
					'total_worked_day'      => $months
				];
			}

			$month = intval($dateParts[1]);

			$totalHours     = $this->calculateHours($wtRow['total_work_time']);
			$sundayHours    = $this->calculateHours($wtRow['sunday_bonus']);

			$data[$employeeID]['data'][$year]['total_worked_hour'][$month]  += $totalHours;
			$data[$employeeID]['data'][$year]['total_sunday_bonus'][$month] += $sundayHours;
			$data[$employeeID]['data'][$year]['total_worked_day'][$month]   += 1;

		}

		return $data;

	}

	public function calculateHours($time){
		$total_work_time = explode(':', $time);

		$hours      = intval($total_work_time[0]);
		$minutes    = floatval($total_work_time[1] / 60);

		return floatval(number_format($hours + $minutes, 2));
	}

}