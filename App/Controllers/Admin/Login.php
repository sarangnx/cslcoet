<?php
namespace App\Controllers\Admin;
/*
 * Login Form action is submitted here
 */
use \Core\View;
use \App\Models\Admin\Login as LoginModel;
use \App\Auth;
class Login extends \Core\Controller{

	public function indexAction(){
		if(Auth::isLoggedIn()){
			$this->redirect("/admin/");
		}
		View::renderTemplate("Admin/login.html",[
			'title' => 'Login'
		]);
	}

	public function verifyAction(){
		$user = LoginModel::verify($_POST['username'],$_POST['password']);
		if($user){
			Auth::login($user);
			$url=Auth::getRememberedPage();
			$this->redirect($url);
		}
		else{
			View::renderTemplate("Admin/login.html",[
				'message' => 'Invalid Login Details' ,
				'username' => $_POST['username'],
				'title'    => 'Login'
			]);
		}
	}

	public function logoutAction(){
		Auth::logout();
		$this->redirect("/admin/login/");
	}
}

?>