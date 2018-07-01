<?php
/**
 * Created by PhpStorm.
 * User: Kovács Nikolett
 * Date: 2018. 06. 28.
 * Time: 17:05
 */

DEFINE('VIEW_DIR', '../src/templates/');

spl_autoload_register(function ($name) {
	if (file_exists( '../src/controllers/' . $name .'.php' )){
		require_once '../src/controllers/' . $name  .'.php';
	}
	else if (file_exists( '../src/models/' . $name  .'.php' )){
		require_once '../src/models/' . $name  .'.php';
	}else if( file_exists('../src/config/' . $name . '.php')){
		require_once '../src/config/' . $name  .'.php';
	}
});

$router = new Router();

$router->addRoute('', [ 'controller' => 'MainController', 'action' => 'index', 'params' => []]);
$router->addRoute('worktime', [ 'controller' => 'WorkTimeController', 'action' => 'workTimeIndex']);
$router->addRoute('tracking', [ 'controller' => 'WorkTimeController', 'action' => 'trackingIndex']);
$router->addRoute('worktime/list', [ 'controller' => 'WorkTimeController', 'action' => 'getList']);
$router->addRoute('worktime/list/json', [ 'controller' => 'WorkTimeController', 'action' => 'getList', 'param' => ['json' => true]]);
$router->addRoute('worktime/add', [ 'controller' => 'WorkTimeController', 'action' => 'create']);
$router->addRoute('worktime/delete', [ 'controller' => 'WorkTimeController', 'action' => 'delete']);

$router->addRoute('tracking/all', [ 'controller' => 'WorkTimeController', 'action' => 'showTrackingTable']);


$url = $_SERVER['QUERY_STRING'];

$router->dispatch($url);

