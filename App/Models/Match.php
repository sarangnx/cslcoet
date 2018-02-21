<?php
namespace App\Models;
use PDO;
class Match extends \Core\Model{

	public static function fetchPointTable(){
			/**
		 * Get Connection Object
		 * execute fetch statement
		 * @return $result
		 */
		try{
			$db = static::getDB();
			$stmt = $db->query("SELECT * FROM point_view ORDER BY position ASC");
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			/*
			 * POSITIVE - higher rank
			 * NEGATIVE - lower rank
			 * ZERO     - NO change
			 */
			define("_POSITIVE_","fa fa-caret-up"); 
			define("_NEGATIVE_","fa fa-caret-down");
			define("_ZERO_","fa fa-circle");

			foreach($result as $key => $value ){
				if($value['position'] > $value['old_position']){
					$result[$key]['change'] = _NEGATIVE_;
				}
				else if($value['position'] < $value['old_position']){
					$result[$key]['change'] = _POSITIVE_;	
				}
				else{
					$result[$key]['change'] = _ZERO_;
				}
			}
			return $result;
		}
		catch(PDOException $e){
		
		}
	}

	public static function fetchResult(){
		/**
		 * Get Connection Object
		 * execute fetch statement
		 * @return $result
		 */
		try{
			$db = static::getDB();
			$stmt = $db->query("SELECT * FROM fixtures WHERE status='UPDATED' ORDER BY match_date DESC ");
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$teams = static::fetchTeams();
			$return=[];
			foreach($result as $key => $value){
				$return[$key]['team_1_name']     = $teams[$value['team_1_id']]['team_name'];
				$return[$key]['team_2_name']     = $teams[$value['team_2_id']]['team_name'];
				$date                            = date_create($value['match_date']);
				$return[$key]['match_date']      = date_format($date,"d/m/Y");
				$return[$key]['datetime']        = date_format($date,"Y/m/d H:i:s");
  				$return[$key]['match_date_full'] = date_format($date,"j M Y h:i A");
  				$return[$key]['team_1_logo']     = $teams[$value['team_1_id']]['team_logo'];
  				$return[$key]['team_2_logo']     = $teams[$value['team_2_id']]['team_logo'];
  				$return[$key]['team_1_goals']	 = $value['team_1_goals'];
  				$return[$key]['team_2_goals']	 = $value['team_2_goals'];
			}
			return $return;
		}
		catch(PDOException $e){
		
		}
	}

	public static function fetchFixtures(){
		/**
		 * Get Connection Object
		 * execute fetch statement
		 * @return $result
		 */
		try{
			$db = static::getDB();
			$stmt = $db->query("SELECT * FROM fixtures WHERE status!='UPDATED' ORDER BY match_date ASC ");
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$teams = static::fetchTeams();

			$return=[];
			foreach($result as $key => $value){
				$return[$key]['team_1_name']     = $teams[$value['team_1_id']]['team_name'];
				$return[$key]['team_2_name']     = $teams[$value['team_2_id']]['team_name'];
				$date                            = date_create($value['match_date']);
				$return[$key]['match_date']      = date_format($date,"d/m/Y");
				$return[$key]['datetime']        = date_format($date,"Y/m/d H:i:s");
  				$return[$key]['match_date_full'] = date_format($date,"j M Y h:i A");
  				$return[$key]['team_1_logo']     = $teams[$value['team_1_id']]['team_logo'];
  				$return[$key]['team_2_logo']     = $teams[$value['team_2_id']]['team_logo'];
			}
			return $return;
		}
		catch(PDOException $e){
		
		}
	}

	public static function fetchTeams(){
		/**
		 * Get Connection Object
		 * execute fetch statement
		 * @return $result
		 */
		try{
			$db = static::getDB();
			$stmt=$db->query("SELECT * FROM teams");
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $key => $value ){
				$id = $value['team_id'];
				$array[$id]['team_name'] = $value['team_class'];
				$array[$id]['team_logo'] = $value['team_logo'];
			}
			return $array;
		}
		catch(PDOException $e){
		
		}
	}
}
?>