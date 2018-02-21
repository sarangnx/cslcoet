<?php
namespace App\Controllers;
/*
 * Main Home Page of The Website
 */
use \Core\View;
use \App\Models\Home as HomeModel;
class Home extends \Core\Controller{

	protected function before(){
		return true;
	}

	/**
	 * show /index. Home page.
	 */

	public function indexAction(){
		$pool = HomeModel::fetchPool();
		$fixtures = HomeModel::fetchFixtures();
		View::renderTemplate('Home/index.html',
			[
				'title' => 'Home' ,
				'pool' => $pool,
				'fixtures' => $fixtures,
		]);
	}

	public function homeAction(){
		self::indexAction();
	}


}

?>