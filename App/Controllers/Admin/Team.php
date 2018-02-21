<?php
namespace App\Controllers\Admin;

use \Core\View;
use \App\Models\Admin\Team as TeamModel;
use \App\Auth;
class Team extends \Core\Controller{

		public function before(){
			if(!Auth::isLoggedIn()){
				Auth::rememberPage();
				$this->redirect("/admin/login/");
			}
			return true;
		}

		public function indexAction(){
			View::renderTemplate('Admin/Team/index.html',[
				'title' => 'Team'
			]);
		}


		public function teamsAction(){
			$teams = TeamModel::fetchTeams();
			View::renderTemplate('Admin/Team/all.html',[
				'title' => 'Team',
				'teams' => $teams
			]);
		}
		public function editAction(){
			$team = htmlspecialchars($this->route_params['var']);
			$details = TeamModel::fetchDetails($team);
			View::renderTemplate('Admin/Team/detail.html',[
				'title' => 'Team',
				'team' => $details['details'],
				'players' => $details['players']
			]);
		}

		public function saveDetails(){
			$name = rawurlencode($_POST['team_name']);
			$team = urlencode($_POST['team_name']);
			TeamModel::savePlayers($_POST);
			
			TeamModel::upload($_FILES['logo'],$team,"logo");
			TeamModel::upload($_FILES['team_image'],$team,"image");
			$this->redirect("/admin/team/edit/{$name}");
		}
		
		public function lock(){
			$name = $_POST['name'];
			TeamModel::lock($name);
		}
		public function unlock(){
			$name = $_POST['name'];
			TeamModel::unlock($name);
		}
}
?>