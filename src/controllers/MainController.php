<?php
/**
 * Created by PhpStorm.
 * User: Kovács Nikolett
 * Date: 2018. 06. 28.
 * Time: 17:57
 */

class MainController {

	public function index(){
		$this->render('index.php', []);
	}

	public function render( $name, $data = [] ){

		if( file_exists( VIEW_DIR . $name) ){
			require_once (VIEW_DIR . $name );
		}
		else {
			echo 'No template for this model!';
		}
	}

}