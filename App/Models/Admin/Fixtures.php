<?php
namespace App\Models\Admin;
use PDO;
class Fixtures extends \Core\Model{

	public static function save(){

	}

	/*
	 *  Function to return team name,pool and id through ajax request
	 */

	public static function ajaxTeams(){

		try{
			$db = static::getDB();
			$stmt = $db->query("SELECT distinct pool FROM point_view ORDER BY pool ");
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$stmt2 = $db->prepare("SELECT team_id,team_name,team_class FROM point_view WHERE pool=:POOL ORDER BY team_name ASC");
			$stmt2->bindParam(':POOL',$val);
			$pools = [];
			foreach($result as $key => $value){
				$val=$value['pool'];
				$stmt2->execute();
				$res = $stmt2->fetchAll(PDO::FETCH_ASSOC);
				$pools[$val] = $res;
			}
			
			return json_encode($pools);
		}
		catch(PDOException $e){
		
		}
	}


	/*
	 *  Return Full Details of Fixtures
	 */
	public static function fetchFixtures(){

		try{
			$db = static::getDB();
			$stmt = $db->query("SELECT * FROM fixtures ORDER BY match_num ASC");
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$teams = static::fetchTeams();

			
			foreach($result as $key => $value){
				$fixtures[$key]['match_num']       = $value['match_num'];
				$fixtures[$key]['team_1_name']     = $teams[$value['team_1_id']]['team_name'];
				$fixtures[$key]['team_1_id']       = $value['team_1_id'];
				$fixtures[$key]['team_2_name']     = $teams[$value['team_2_id']]['team_name'];
				$fixtures[$key]['team_2_id']       = $value['team_2_id'];
				if($value['match_date']){
					$date                              = date_create($value['match_date']);
					$fixtures[$key]['date']			   = date_format($date,"m/d/Y");
  					$fixtures[$key]['time']			   = date_format($date,"h:i A");
  				}
			}
			return $fixtures;
		}
		catch(PDOException $e){
		
		}
	}


	/*
	 *  Return  team name
	 *    
	 */

	public static function fetchTeams(){

		try{
			$db = static::getDB();
			$stmt=$db->query("SELECT * FROM teams");
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $key => $value ){
				$id = $value['team_id'];
				$array[$id]['team_name'] = $value['team_class'];
			}
			return $array;
		}
		catch(PDOException $e){
		
		}
	}

	/*
	 * Delete From Fixtures database
	 */
	public static function delete($del){
		try{
			$db = static::getDB();
			$stmt=$db->prepare("DELETE FROM fixtures WHERE match_num=:matchnum");

			foreach($del as $key=>$value){
				$stmt->bindParam(":matchnum",$value,PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		catch(PDOException $e){
		
		}
	}

	/*
	 *  Insert Into Fixtures Table
	 */

	public static function insert($ins){
		try{
			$db = static::getDB();
			$stmt=$db->prepare("INSERT INTO fixtures(match_num,match_date,team_1_id,team_2_id) VALUES(:matchnum,:matchdate,:team1,:team2);");

			foreach($ins as $key=>$value){

				$matchnum=$value["match_num"];
				$matchdate=$value["match_date"];
				$matchtime=$value["match_time"]?:"5:00 PM";
				$team1=$value["team_1_id"];
   				$team2=$value["team_2_id"];
				if($matchdate){
					$date="{$matchdate} {$matchtime}";
					$date=date_create_from_format("m/d/Y h:i a",$date);
   					$date=date_format($date,"Y-m-d H:i");
				}
				else{
					$date = NULL;
				}
				$stmt->bindParam(":matchnum",$matchnum,PDO::PARAM_INT);
				$stmt->bindParam(":matchdate",$date,PDO::PARAM_INT);
				$stmt->bindParam(":team1",$team1,PDO::PARAM_INT);
				$stmt->bindParam(":team2",$team2,PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		catch(PDOException $e){
		
		}
		
	}

	/*
	 *  Update Fixtures
	 */
	public static function edit($edit){
		try{
			$db = static::getDB();
			$stmt=$db->prepare("UPDATE fixtures SET match_date=:matchdate,team_1_id=:team1,team_2_id=:team2 WHERE match_num=:matchnum");

			foreach($edit as $key=>$value){

				$matchnum=$value["match_num"];
				$matchdate=$value["match_date"];
				$matchtime=$value["match_time"]?:"5:00 PM";
				$team1=$value["team_1_id"];
   				$team2=$value["team_2_id"];
				if($matchdate){
					$date="{$matchdate} {$matchtime}";
					$date=date_create_from_format("m/d/Y h:i a",$date);
   					$date=date_format($date,"Y-m-d H:i");
				}
				else{
					$date = NULL;
				}
				$stmt->bindParam(":matchnum",$matchnum,PDO::PARAM_INT);
				$stmt->bindParam(":matchdate",$date,PDO::PARAM_INT);
				$stmt->bindParam(":team1",$team1,PDO::PARAM_INT);
				$stmt->bindParam(":team2",$team2,PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		catch(PDOException $e){
		
		}
	}

}
?>