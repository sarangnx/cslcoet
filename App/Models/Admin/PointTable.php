<?php
namespace App\Models\Admin;
use PDO;
class PointTable extends \Core\Model{

	protected static $WIN  = 3;
	protected static $DRAW = 1;
	protected static $LOSE = 0;

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
			return $result;
		}
		catch(PDOException $e){
		
		}
	}
	public static function fetchMatch(){
		try{
			$db = static::getDB();
			date_default_timezone_set("Asia/Kolkata");
			$date=date("Y-m-d H:i:s");
			$stmt = $db->prepare("SELECT * FROM fixtures WHERE match_date <= :matchdate AND status!='updated' LIMIT 2");
			$stmt->bindParam(':matchdate',$date,PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$teams = static::fetchTeams();
			$match = [];
			foreach($result as $key => $value){
				$match[$key]['match_num']		= $value['match_num'];
				$match[$key]['team_1_id']       = $value['team_1_id'];
				$match[$key]['team_2_id']       = $value['team_2_id'];
				$match[$key]['team_1_name']     = $teams[$value['team_1_id']]['team_name'];
				$match[$key]['team_2_name']     = $teams[$value['team_2_id']]['team_name'];
				$date                           = date_create($value['match_date']);
				$match[$key]['match_date']      = date_format($date,"d/m/Y");
				$match[$key]['datetime']        = date_format($date,"Y/m/d H:i:s");
  				$match[$key]['match_date_full'] = date_format($date,"j M Y h:i A");
  				$match[$key]['team_1_goals']    = $value['team_1_goals'];
  				$match[$key]['team_2_goals']    = $value['team_2_goals'];
			}
			return $match;
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


	/*
	 *  Updates fixtures table
	 *  Pass data to findWinner() method.
	 *  Calls SortTable() method.
	 */
	public static function updateTable($data){

		try{
			$db = static::getDB();
			$qry="UPDATE fixtures SET team_1_goals=:goal1,team_2_goals=:goal2,status='UPDATED'
				  WHERE match_num=:num AND status!='UPDATED'";
			$stmt=$db->prepare($qry);
			$stmt->bindParam(':goal1',$data['goal1'],PDO::PARAM_INT);
			$stmt->bindParam(':goal2',$data['goal2'],PDO::PARAM_INT);
			$stmt->bindParam(':num',$data['match'],PDO::PARAM_INT);
			$stmt->execute();
			echo $stmt->rowCount();
			if($stmt->rowCount()){
				echo "hi";
				static::findWinner($data);
				static::sortTable();
			}

		}
		catch(PDOException $e){
		
		}

	}

	/*
	 * Sort the point_table 
	 * old_position = position
	 * position = new position based on sort
	 */

	public static function sortTable(){	

		try{
			$db = static::getDB();
			$stmt=$db->query("SELECT * FROM point_view");
			$point_table = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$count=count($point_table);

			/*
			 *  Sorting Table
			 *     by points
			 *      |_ by goal difference
			 *         |_ by goal for
			 *            |_ by goal against
			 *               |_ by name
			 */

			for($i=0;$i<$count;$i++){
    			for($j=0;$j<$count-1;$j++){
        			/* SORT BY POINTS */
        			if($point_table[$j]["points"] < $point_table[$j+1]["points"]){
          				$temp=$point_table[$j];
          				$point_table[$j]=$point_table[$j+1];
          				$point_table[$j+1]=$temp;
        			}
        			else if($point_table[$j]["points"] === $point_table[$j+1]["points"]){
           				/* SORT BY GOAL DIFFERENCE */
           				if($point_table[$j]["gd"] < $point_table[$j+1]["gd"]){
              				$temp=$point_table[$j];
              				$point_table[$j]=$point_table[$j+1];
              				$point_table[$j+1]=$temp;
           				}
           				else if($point_table[$j]["gd"] === $point_table[$j+1]["gd"]){
              				/* SORT BY GOAL FOR */
              				if($point_table[$j]["gf"] < $point_table[$j+1]["gf"]){
                 				$temp=$point_table[$j];
                 				$point_table[$j]=$point_table[$j+1];
                 				$point_table[$j+1]=$temp;
              				}
              				else if($point_table[$j]["gf"] === $point_table[$j+1]["gf"]){
                				/* SORT BY GOAL AGAINST */
                 				if($point_table[$j]["ga"] > $point_table[$j+1]["ga"]){
                      				$temp=$point_table[$j];
                      				$point_table[$j]=$point_table[$j+1];
                      				$point_table[$j+1]=$temp;

                 				}
                 				else if($point_table[$j]["ga"] === $point_table[$j+1]["ga"]){
                      				/* SORT BY NAME */
                      				if(strcasecmp($point_table[$j]["team_name"],$point_table[$j+1]["team_name"])>0){
                          				$temp=$point_table[$j];
                          				$point_table[$j]=$point_table[$j+1];
                          				$point_table[$j+1]=$temp;
                       				}
                 				}
              				}
           				}
        			}
    			}
			}

			/*
			 * Reassigning position
			 */
			for($i=0;$i<$count;$i++){
				$point_table[$i]["old_position"]=$point_table[$i]["position"];
  				$point_table[$i]["position"]=$i+1;
			}

			/*
			 *  Updating changes to database
			 */
			$stmt=$db->prepare("UPDATE point_table SET position=:position,old_position=:old_position WHERE team_id=:team_id");
			foreach ($point_table as $key => $value) {
				$stmt->bindParam('old_position',$value['old_position'],PDO::PARAM_INT);
				$stmt->bindParam('position',$value['position'],PDO::PARAM_INT);
				$stmt->bindParam('team_id',$value['team_id'],PDO::PARAM_STR);	
				$stmt->execute();
			}

		}
		catch(PDOException $e){
		
		}	

	}


	/*
	 *  Decide the outcome of a match
	 *  Match Details (goals,team_id and match_number) are passed to the method
	 *  Calls updatePoint() method on the given data
	 *  Updates point_table using updatePoint() method.
	 */
	public static function findWinner($data){
		if( $data['goal1'] > $data['goal2'] ){
			static::updatePoint($data['team1'],1,0,0,$data['goal1'],$data['goal2'],$data['goal1'] - $data['goal2'],self::$WIN);
			static::updatePoint($data['team2'],0,0,1,$data['goal2'],$data['goal1'],$data['goal2'] - $data['goal1'],self::$LOSE);
		}
		else if( $data['goal1'] < $data['goal2'] ){
			static::updatePoint($data['team1'],0,0,1,$data['goal1'],$data['goal2'],$data['goal1'] - $data['goal2'],self::$LOSE);
			static::updatePoint($data['team2'],1,0,0,$data['goal2'],$data['goal1'],$data['goal2'] - $data['goal1'],self::$WIN);
		}
		else if( $data['goal1'] == $data['goal2'] ){
			static::updatePoint($data['team1'],0,1,0,$data['goal1'],$data['goal2'],0,self::$DRAW);
			static::updatePoint($data['team2'],0,1,0,$data['goal2'],$data['goal1'],0,self::$DRAW);			
		}

	}
	/*
	 * Update points table
	 * Update 
	 *     number of matches played,
	 *	   number of matches won,
	 *	   number of matches lost,
	 *	   number of matches drawn,
	 *	   goals scored,
	 *	   goals scored by opposite teams,
	 *     goal difference
	 *     points
	 */

	public static function updatePoint($id,$won,$draw,$lost,$gf,$ga,$gd,$points){

		try{
			$db = static::getDB();
			$qry = "UPDATE point_table SET
					played = played + 1,
					won = won + :won,
					drawn = drawn + :draw,
					lost = lost + :lost,
					gf = gf + :gf,
					ga = ga + :ga,
					gd = gd + :gd,
					points = points + :points
					WHERE team_id = :id
					";
			$stmt = $db->prepare($qry);
			$stmt->bindParam(':won',$won,PDO::PARAM_INT);
			$stmt->bindParam(':lost',$lost,PDO::PARAM_INT);
			$stmt->bindParam(':draw',$draw,PDO::PARAM_INT);
			$stmt->bindParam(':gf',$gf,PDO::PARAM_INT);
			$stmt->bindParam(':ga',$ga,PDO::PARAM_INT);
			$stmt->bindParam(':gd',$gd,PDO::PARAM_INT);
			$stmt->bindParam(':points',$points,PDO::PARAM_INT);
			$stmt->bindParam(':id',$id,PDO::PARAM_STR);
			$stmt->execute();
		}
		catch(PDOException $e){
		
		}
	}


}
?>