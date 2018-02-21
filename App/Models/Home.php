<?php
namespace App\Models;
use PDO;
class Home extends \Core\Model{

	public static function fetchPool(){
		/**
		 * Get Connection Object
		 * execute fetch statement
		 * @return $result
		 */
		try{
			$db = static::getDB();
			$stmt = $db->query("SELECT distinct pool FROM point_view ORDER BY pool ");
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$stmt2 = $db->prepare("SELECT * FROM point_view WHERE pool=:POOL ORDER BY position ASC");
			$stmt2->bindParam(':POOL',$val);
			$pools = [];
			foreach($result as $key => $value){
				$val=$value['pool'];
				$stmt2->execute();
				$res = $stmt2->fetchAll(PDO::FETCH_ASSOC);
				$pools[$val] = $res;
			}
			return $pools;
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
			$stmt = $db->query("SELECT * FROM fixtures WHERE status!='UPDATED' ORDER BY match_date ASC LIMIT 8");
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