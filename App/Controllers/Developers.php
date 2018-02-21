<?php
namespace App\Controllers;
/*
 * Developers page
 */
use \Core\View;
class Developers extends \Core\Controller{

	protected function before(){
		return true;
	}

	/**
	 * Show /developers page
	 */
	public function developersAction(){
		View::renderTemplate('Page/developers.html',['title' => 'Developers' ]);
	}

	public function indexAction(){
		self::developersAction();
	}

}

?>

