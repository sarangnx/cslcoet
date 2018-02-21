<?php
namespace App\Controllers\Admin;
/*
 * Home page of Admin Section
 */
use \App\Auth;
use \Core\View;
class Home extends \Core\Controller{

	public function before(){
		if(!Auth::isLoggedIn()){
			Auth::rememberPage();
			$this->redirect("/admin/login/");
		}
		return true;
	}

	public function indexAction(){
		View::renderTemplate("Admin/index.html",[
			'title'  => 'Admin'
	]);
	}

}

?>