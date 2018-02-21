<?php
namespace App\Models;
use PDO;
class Gallery extends \Core\Model{

	public static function fetchImages($page,$limit){
		$start = ( $page - 1 ) * $limit;
		/**
		 * Get Connection Object
		 * execute fetch statement
		 * @return $result
		 */
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT image FROM gallery ORDER BY upload_date DESC LIMIT :start,:lim");
			$stmt->bindParam(':start',$start,PDO::PARAM_INT);
			$stmt->bindParam(':lim',$limit,PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if($stmt->rowCount()==0){
				return false;
			}

			return $result;
		}
		catch(PDOException $e){
		
		}
	}

	public static function fetchNumber(){
				try{
			$db = static::getDB();
			$stmt = $db->query("SELECT count(*) as total FROM gallery");
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return $result['total'];
		}
		catch(PDOException $e){
		
		}
	}

}

?>
