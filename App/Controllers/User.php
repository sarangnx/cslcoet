<?php
namespace App\Controllers;
/*
 * Teams list
 */
use \Core\View;
use \App\Models\Admin\Team as TeamModel;
class User extends \Core\Controller{

		public function before(){
			$team = htmlspecialchars($this->route_params['var']);
			/*
			 * CheckLock return true if 
			 * lock is active
			 */
			if(TeamModel::checkLock($team)){
				$this->redirect('/');
			}
			return true;
		}

		public function indexAction(){
			$this->redirect('/');
		}

		public function editTeamAction(){
			$team = htmlspecialchars($this->route_params['var']);
			$details = TeamModel::fetchDetails($team);
			View::renderTemplate('Admin/Team/userDetail.html',[
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
			$this->redirect("/user/edit-team/{$name}/");
		}

}

?>