<?php
namespace App\Controllers;
/*
 * Quarter Finals,
 * Semi Finals &
 * FInals
 */
use \Core\View;
//use \App\Models\Finals as FinalsModel;
class Finals extends \Core\Controller{

	public function indexAction(){
		View::renderTemplate('Page/finals.html',
			[
				'title' => 'Finals' 
		]);
	}

}

?>