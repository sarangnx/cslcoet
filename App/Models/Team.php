<?php
namespace App\Models;
use PDO;
class Team extends \Core\Model{

	public static function fetchTeams(){
		/**
		 * Get Connection Object
		 * execute fetch statement
		 * @return $result
		 */
		try{
			$db = static::getDB();
			$stmt = $db->query("SELECT team_name,team_logo FROM teams ORDER BY team_name ASC");
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(PDOException $e){
		
		}
	}

	public static function fetchDetails($team){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT * FROM teams WHERE team_name LIKE :teamname");
			$stmt->bindParam(':teamname',$team,PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(PDOException $e){
		
		}
	}
    
    /*
     *  Fetch Stats
     */
	public static function fetchStats($team){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT * FROM point_view WHERE team_name LIKE :teamname");
			$stmt->bindParam(':teamname',$team,PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(PDOException $e){
		
		}
	}
	/*
	 *  Fetch Player Names
	 */

	public static function fetchPlayers($teamid){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT * FROM players WHERE team_id = :teamid ORDER BY s_no");
			$stmt->bindParam(':teamid',$teamid,PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(PDOException $e){
		
		}
	}
}
?>