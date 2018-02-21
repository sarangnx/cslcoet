<?php
namespace App\Models\Admin;
use PDO;
class Team extends \Core\Model{

	public static function fetchTeams(){
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
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$players = self::fetchPlayers($result['team_id']);
			return ['details' => $result,'players' => $players];
		}
		catch(PDOException $e){
		
		}
	}

	public static function fetchPlayers($id = 0 ){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT * FROM players WHERE team_id=:teamid ORDER BY s_no");
			$stmt->bindParam(':teamid',$id,PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(PDOException $e){
		
		}
	}

	public static function savePlayers($post){
		try{
			$db = static::getDB();
			$delete = $db->prepare("DELETE FROM players WHERE team_id=:teamid");
			$delete->bindParam(':teamid',$post['id']);
			$delete->execute();
			$stmt = $db->prepare("INSERT INTO players (team_id,s_no,player_name,player_position) VALUES(:tid,:no,:name,:pos) ");
			for($i=0;$i<14;$i++){
				if($post["player_{$i}"]!=""){
				$stmt->bindParam(':tid',$post['id'],PDO::PARAM_STR);
				$stmt->bindParam(':no',$i,PDO::PARAM_INT);
				$stmt->bindParam(':name',$post["player_{$i}"],PDO::PARAM_STR);
				$stmt->bindParam(':pos',$post["position_{$i}"],PDO::PARAM_STR);
				$stmt->execute();	
				}
			}
		}
		catch(PDOException $e){
		
		}
	}

	/*
	 * - Upload image and move it to /images/teams/
	 * - Call save() method on filename to add image name
	 *   to database
	 */

	public static function upload($data,$name,$suffix){

		$FOLDER = $_SERVER['DOCUMENT_ROOT']."/images/teams/";
		$error = [];

		/*
		 *  Proceed if No UPLOAD ERRORS
		 */
		if($data['error'] != UPLOAD_ERR_OK){
			$error['error_code'] = $data['error']; 
			return $error;
		}
		/*
		 *  Check if the MIME type is " image/* "
		 */
		if( preg_match('/image\/(jpg|jpeg|gif|png)/',$data['type']) == 0 ){
			$error['error'] = "Not an Image";
			return $error;
		}
		$ext = pathinfo($data['name'],PATHINFO_EXTENSION);
		$filename = $name."_".$suffix."_".$ext;
		$col = "team_".$suffix;
		$team = urldecode($name);
		self::delete($col,$team);
		if(move_uploaded_file($data['tmp_name'], $FOLDER.$filename)){
        	self::save($col,$filename,$team);		
		}
		return $error;
	}

	/*
	 *  Save image name to database
	 */

	public static function save($col,$image,$team){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE teams set {$col} = :val WHERE team_name LIKE :name ");
			$stmt->bindParam(':val',$image);
			$stmt->bindParam(':name',$team);
			$stmt->execute();
		}
		catch(PDOException $e){
		
		}
	}

	/*
	 * Delete logo / image from folder
	 * And from database
	 */
	public static function delete($col,$team){
		try{
			$val = "%".urlencode($team)."%";
			$db = static::getDB();
			$stmt = $db->prepare("SELECT {$col} FROM teams WHERE $col LIKE :team");
			$stmt->bindParam(':team',$val);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0 ){
				$name = $result[$col];
				$stmt = $db->prepare("UPDATE teams set {$col} = 'null' WHERE team_name LIKE :team");
				$stmt->bindParam(':team',$team);
				
				$filename=$_SERVER['DOCUMENT_ROOT']."/images/teams/{$name}";
				if(is_writable($filename)){
					unlink($filename);
					$stmt->execute();
				}
			}
		}
		catch(PDOException $e){
		
		}
	}
	/*
	 * Check Lock Status
	 * return  1 means Locked 
	 * return  0 means Not Locked
	 */
	public static function checkLock($team){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT edit_lock FROM teams WHERE team_name LIKE :team");
			$stmt->bindParam(':team',$team);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0){
				return $result['edit_lock'];
			}
			return true;
		}
		catch(PDOException $e){
		
		}	
	}
	/*
	 * Lock Team Details From Edit
	 * return  1 means Locked 
	 * return  0 means Not Locked
	 */
	public static function lock($team){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE teams SET edit_lock=1 WHERE team_name LIKE :team");
			$stmt->bindParam(':team',$team);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e){
		
		}	
	}
	public static function unlock($team){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE teams SET edit_lock=0 WHERE team_name LIKE :team");
			$stmt->bindParam(':team',$team);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e){
		
		}	
	}


}
?>