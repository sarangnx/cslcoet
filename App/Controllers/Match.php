<?php
namespace App\Controllers;
/*
 *  Match Controller
 *  Controls data flow to
 *     -> fixtures
 *	   -> results
 *	   -> point-table
 */
use \Core\View;
use \App\Models\Match as MatchModel;
class Match extends \Core\Controller{

	protected function before(){
		return true;
	}

	public function indexAction(){
		self::fixturesAction();
	}

	/**
	 * Show /match/fixtures/ page
	 */

	public function fixturesAction(){

		$fixtures = MatchModel::fetchFixtures();
		View::renderTemplate('Match/fixtures.html',
			[
				'title' => 'Fixtures' ,
				'fixtures' => $fixtures

		]);
	}

	/**
	 * Show /match/results/ page
	 */
	public function resultsAction(){

		$results = MatchModel::fetchResult();
		View::renderTemplate('Match/results.html',
			[
				'title' => 'Match Results' ,
				'results' => $results
		]);
	}

	/**
	 * Show /match/point-table/ page
	 */
	public function pointTableAction(){

		$points = MatchModel::fetchPointTable();
		View::renderTemplate('Match/point-table.html',
			[
				'title'  => 'Point Table' ,
				'pointTable' => $points
		]);
	}





}

?>