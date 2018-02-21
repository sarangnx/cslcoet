<?php
namespace App\Controllers;
/*
 * About page
 */
use \Core\View;
class About extends \Core\Controller{

	protected function before(){
		return true;
	}

	/**
	 * Show /about page
	 */

	public function aboutAction(){
		View::renderTemplate('Page/about.html',
			[
				'title' => 'About',
				'banner' => false
			]);
	}
	public function indexAction(){
		self::aboutAction();
	}

}

?>