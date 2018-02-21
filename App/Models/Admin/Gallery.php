<?php
namespace App\Models\Admin;
use PDO;
use \App\Config;
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
			$stmt = $db->prepare("SELECT s_no,image FROM gallery ORDER BY upload_date DESC LIMIT :start,:lim");
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
	public static function delete($s_no){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT image FROM gallery WHERE s_no=:s_no");
			$stmt->bindParam(':s_no',$s_no);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount()>0){
				$name = $result['image'];
				$stmt = $db->prepare("DELETE FROM gallery WHERE s_no=:s_no");
				$stmt->bindParam(':s_no',$s_no);
				
				$image=$_SERVER['DOCUMENT_ROOT']."/images/gallery/{$name}";
				$thumb=$_SERVER['DOCUMENT_ROOT']."/images/gallery/thumbs/{$name}";
				if(is_writable($image)&&is_writable($thumb)){
					unlink($image);
					unlink($thumb);
					$stmt->execute();
				}

			}
		}
		catch(PDOException $e){
		
		}
	}

	/*
	 * - Upload image and move it to /images/gallery/
	 * - Create Thumbnail to /images/gallery/thumbs/
	 * - Call save() method on filename to add image name
	 *   to database
	 */

	public static function upload($data){

		$GALLERY = $_SERVER['DOCUMENT_ROOT']."/images/gallery/";
		$THUMB = $_SERVER['DOCUMENT_ROOT']."/images/gallery/thumbs/";

		$imageArray = self::rearrange($data);
		$error = [];

		foreach ($imageArray as $key => $array) {
			/*
			 *  Proceed if No UPLOAD ERRORS
			 */
			if($array['error'] != UPLOAD_ERR_OK){
				$error[$key]['error_code'] = $array['error']; 
				continue;
			}
			/*
			 *  Check if the MIME type is " image/* "
			 */
			if( preg_match('/image\/(jpg|jpeg|gif|png)/',$array['type']) == 0 ){
				$error[$key]['error'] = "Not an Image";
				continue;
			}
			$ext = pathinfo($array['name'],PATHINFO_EXTENSION);
			$date = date("Y-m-d_H-i-s");
			$filename = $date.".".$ext;

			if(move_uploaded_file($array['tmp_name'], $GALLERY.$filename)){
				$image = $GALLERY.$filename;
				$thumbnail = $THUMB.$filename;
				list($width,$height)=getimagesize($image);
				$thumb_width=$width*0.3;    //30%
				$thumb_height=$height*0.3;  //30%

				$dest=imagecreatetruecolor($thumb_width,$thumb_height);

				switch($ext)
				{
					case 'png'  : $source=imagecreatefrompng($image);
									break;
					case 'gif'  : $source=imagecreatefromgif($image);
									break;
					default     : $source=imagecreatefromjpeg($image);
				}
				imagecopyresampled($dest,$source,0,0,0,0,$thumb_width,$thumb_height,$width,$height);
				switch($ext)
				{
					case 'png'  : imagepng($dest,$thumbnail);
									break;
					case 'gif'  : imagegif($dest,$thumbnail);
									break;
					default     : imagejpeg($dest,$thumbnail);
         		}
         		self::save($filename);
				
			}


		}

		return $error;

	
	}

	/*
	 *  Save image name to database
	 */

	public static function save($image){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("INSERT INTO gallery(image) VALUES(:image)");
			$stmt->bindParam(':image',$image);
			$stmt->execute();
		}
		catch(PDOException $e){
		
		}
	}

	/*
	 *  Method to Rearrange $_FILES['images'] to below format :
	 *  [
	 *		0 => {
	 *				[name] => "name.jpg"
     *       		[type] => image/jpeg
     *       		[tmp_name] => /tmp/php66qJPK
     *       		[error] => 0
     *       		[size] => 243273
	 *				
	 *			 },
	 *		1 => {
	 *				[name] => "name2.jpg"
     *       		[type] => image/jpeg
     *       		[tmp_name] => /tmp/php62itK
     *       		[error] => 0
     *       		[size] => 24323
	 *				
	 *			 }
	 *	]
	 */
	protected static function rearrange($imageArray){
		$newarray = [];
	    foreach( $imageArray as $key => $all ){
        	foreach( $all as $i => $val ){
            	$newarray[$i][$key] = $val;    
        	}    
    	}
    	return $newarray;	
	}

}

?>
