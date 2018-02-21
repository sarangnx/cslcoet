<?php
namespace Core;
use PDO;
use \App\Config;
abstract class Model{

	protected static function getDB(){
		static $db = null;

		if($db == null){
			$host   = Config::DB_HOST;
			$user   = Config::DB_USER;
			$pass   = Config::DB_PASS;
			$dbname = Config::DB_NAME;
			$db = new PDO("mysql:host={$host};dbname={$dbname}",$user,$pass);
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}
		return $db;
	}
}

?>