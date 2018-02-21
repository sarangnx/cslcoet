<?php
namespace App\Models\Admin;
use PDO;
class AddUser extends \Core\Model{

	public static function fetchUsers(){

		try{
			$db = static::getDB();
			$stmt = $db->query("SELECT userid,username,usergroup FROM users");
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(PDOException $e){
		
		}
	}

	public static function deleteUser($userid){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("DELETE FROM users WHERE userid=:userid AND usergroup!='superuser'");
			$stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e){
		
		}	
	}

	/*
	 *  Generate a temporary space
	 *  for new user registration
	 */
	public static function generateTemp($key){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("INSERT INTO users(userid,usergroup) VALUES(:userid,'temp')");
			$stmt->bindParam(':userid',$key,PDO::PARAM_STR);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e){
		
		}	
	}

	public static function add($value){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE users set username=:username, usergroup='moderator' WHERE userid=:userid");
			$stmt->bindParam(':userid',$value['key'],PDO::PARAM_STR);
			$stmt->bindParam(':username',$value['username'],PDO::PARAM_STR);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e){
		
		}	
	}

}
?>