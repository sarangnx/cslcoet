<?php
namespace App\Controllers\Admin;

use \Core\View;
use \App\Models\Admin\Fixtures as FixturesModel;
use \App\Auth;
class Fixtures extends \Core\Controller{

	public function before(){
		if(!Auth::isLoggedIn()){
			Auth::rememberPage();
			$this->redirect("/admin/login/");
		}
		return true;
	}
	public function indexAction(){
		$fixtures = FixturesModel::fetchFixtures();
		View::renderTemplate('Admin/Fixtures/fixtures.html',[
			'fixtures' => $fixtures ,
			'title'    => 'Fixtures'
		]);
	}

	public function ajaxTeamsAction(){	
		$teams = FixturesModel::ajaxTeams();
		echo $teams;
	}

	public function saveAction(){

		if(isset($_POST['delete'])){
			$delete=$_POST['delete'];
			FixturesModel::delete($delete);	
		}

		if(isset($_POST['add'])){
			$add=$_POST['add'];
			FixturesModel::insert($add);	
		}

		if(isset($_POST['edit'])){
			$edit=$_POST['edit'];
			FixturesModel::edit($edit);	
		}	
	}
	
}
?>