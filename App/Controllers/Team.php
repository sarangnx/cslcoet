<?php
namespace App\Controllers;
/*
 * Teams list
 */
use \Core\View;
use \App\Models\Team as TeamModel;
class Team extends \Core\Controller{

	/**
	 * Show /team/ page
	 */

	public function teamAction(){
		$teams = TeamModel::fetchTeams();
		View::renderTemplate('Team/team.html',[
			'title' => 'Team' ,
			'teams' => $teams
		]);
	}
	public function indexAction(){
		self::teamAction();
	}
	public function viewAction(){
		$team = htmlspecialchars($this->route_params['var']);
		$details = TeamModel::fetchDetails($team);
		$stats = TeamModel::fetchStats($team);
		$players = TeamModel::fetchPlayers($stats['team_id']);

		View::renderTemplate('Team/detail.html',[
			'team' => $details,
			'stats' => $stats,
			'players' => $players
		]);
	}

}

?>