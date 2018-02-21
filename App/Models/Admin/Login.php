<?php
namespace App\Models\Admin;
use PDO;
class Login extends \Core\Model{

	/*
	 *  Search For Username in Database
	 *  Return Row as Object if username exists
	 */

	public static function searchUser($uname){
		/**
		 * Get Connection Object
		 * execute fetch statement
		 * @return $result
		 */
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT * FROM users WHERE username = :uname ");
			$stmt->bindParam(':uname',$uname,PDO::PARAM_STR);
			$stmt->setFetchMode(PDO::FETCH_CLASS,get_called_class());
			$stmt->execute();
			return $stmt->fetch();
		}
		catch(PDOException $e){
		
		}
	}

	/*
	 * Get User Details as Object Form self::searchUser()
	 * method and Authenticate the user
	 */

	public static function verify($username,$password){
		$user = static::searchUser($username);
		if($user){
			if(password_verify($password,$user->password)){
				return $user;
			}
		}
		return false;
	}
}

?>
