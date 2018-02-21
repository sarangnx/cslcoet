<?php
namespace App\Controllers\Admin;

use \Core\View;
use \App\Models\Admin\PointTable as PointTableModel;
use \App\Auth;
class PointTable extends \Core\Controller{

	public function before(){
		if(!Auth::isLoggedIn()){
			Auth::rememberPage();
			$this->redirect("/admin/login/");
		}
		return true;
	}

		public function indexAction(){
			$points = PointTableModel::fetchPointTable();
			$match  = PointTableModel::fetchMatch();
			View::renderTemplate('Admin/PointTable/point-table.html',[
				'pointTable' => $points ,
				'match'      => $match ,
				'title'      => 'Point Table'
		]);
		}

		public function updateAction(){
			if(isset($_POST['match1'])){
				if( ( $_POST["match1_team1"]!="" ) && ( $_POST["match1_team2"]!="" ) ){
					$data['goal1'] = $_POST['match1_team1'];
    				$data['goal2'] = $_POST['match1_team2'];
    				$data['team1'] = $_POST['match1_id1'];
    				$data['team2'] = $_POST['match1_id2'];
    				$data['match'] = $_POST['match1'];
    				PointTableModel::updateTable($data);
				}
			}
			if(isset($_POST['match2'])){
				if( ( $_POST["match2_team1"]!="" ) && ( $_POST["match2_team2"]!="" ) ){
					$data['goal1'] = $_POST['match2_team1'];
    				$data['goal2'] = $_POST['match2_team2'];
    				$data['team1'] = $_POST['match2_id1'];
    				$data['team2'] = $_POST['match2_id2'];
    				$data['match'] = $_POST['match2'];
    				PointTableModel::updateTable($data);
				}
			}
			$this->redirect('/admin/point-table/');
		}

}
?>