<?php
namespace App\Controllers\Admin;

use \App\Auth;
use \Core\View;
use \App\Models\Admin\AddUser as AddUserModel;
class AddUser extends \Core\Controller{

	public function before(){
		if(!Auth::isLoggedIn()){
			Auth::rememberPage();
			$this->redirect("/admin/login/");
		}
		return true;
	}

	public function indexAction(){
		$users = AddUserModel::fetchUsers();
		View::renderTemplate('Admin/AddUser/index.html',
			[
				'title'     => 'Add User',
				'users'     => $users
			]);
	}

	public function deleteAction(){
		$id = $this->route_params['var'];
		AddUserModel::deleteUser($id);
		$this->redirect('/admin/add-user/');
	}
	public function resetAction(){
		$id = $this->route_params['var'];

	}

	public function generateAction(){
		$key=md5(time());
		AddUserModel::generateTemp($key);
	 	echo "<a href='http://cslcoet.com/admin/add-user/register/{$key}/'>http://cslcoet.com/admin/add-user/register/{$key}/</a>";
	  
	}
	/*
	 *  Action suffix is omitted to let 
	 *  Non Logged in users access the page.
	 *
	 *	adding Action suffix results in calling the 
	 *  before method that checks if user is logged in
	 *  or not
	 *
	 *	Refer \Core\Controller
	 */
	public function register(){
		$id = isset($this->route_params['var'])? $this->route_params['var']:false;
		if($id){
			View::renderTemplate('Admin/AddUser/register.html',[
				'title' => 'Register',
				'id'    => $id
			]);
		}
	}
	
	public function add(){
		AddUserModel::add($_POST);
		$this->redirect("/admin/login/");
	}

}
?>
