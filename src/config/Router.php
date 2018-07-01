<?php
/**
 * Created by PhpStorm.
 * User: Kovács Nikolett
 * Date: 2018. 06. 29.
 * Time: 12:06
 */

class Router {

	protected $routes = [];
	protected $params = [];

	protected $controller = '';
	protected $action = '';

	/**
	 * Add a route
	 * @param string $route
	 * @param array $params
	 */
	public function addRoute($route, $params = []){
		/**
		 * remove forward slahes */
		$route = preg_replace('/\//', '\\/', $route);

		/**
		 * add start end end delimiters */
		$route = '/^'. $route . '(\&.*)?$/i';

		$this->routes[$route] = $params;
	}
	/**
	 * Get all routes
	 * @return array of routes
	 */
	public function getRoutes() {
		return $this->routes;
	}
	/**
	 * Returns if url can be found in routes
	 *
	 * @param $url
	 * @return boolean
	 */
	public function matchUrl($url){

		foreach( $this->routes as $route => $params ){

			if ( preg_match($route, $url, $matches) ) {

				foreach ($matches as $key => $value){

					if ( is_string($key) ){
						$params[$key] = $value;
					}

				}

				$this->params = $params;
				return true;
			}

		}
		return false;
	}
	/**
	 * Get route parameters
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}
	/**
	 * Calling controllers and actions based on routes
	 *
	 * @param $url
	 */
	public function dispatch($url){

		if( $this->matchUrl($url) ) {

			$controller = $this->params['controller'];
			$action = $this->convertStringToCamelCase($this->params['action']);

			if ( class_exists( $controller) ){

				$ctrl = new $controller;
				if ( is_callable([$ctrl,$action]) ){

					if ( isset($this->params['param']) ){
						$ctrl->$action($this->params['param']);
					}else{
						$ctrl->$action();
					}

				}else {
					echo "$action not found in $controller controller";
				}

			}else {
				echo "$controller controller not exists";
			}

		}
		else{
			/**
			 * Fallback to Homepage */
			$this->fallbackHome();
		}
	}
	public function fallbackHome(){
		header('Location: /');
		return;
	}
	/**
	 * Converts string to CamelCase format
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public function convertStringToCamelCase ($string) {
		$str = str_replace('-', '', ucwords($string, '-'));
		$str = lcfirst($str);
		return $str;
	}


}