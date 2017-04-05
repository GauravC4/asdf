<?php  

include("db.php");

$conn = mysqli_connect($host, $user, $pass, $database); 

if($conn){

	$sql = 'SELECT * FROM prof_profile';
    $retval = mysqli_query($conn, $sql); $no_of_prof = mysqli_num_rows($retval);

	//allot preferences JS
	$sql = 'SELECT * FROM prof_preferences WHERE ptype = 1 AND prof_id IN (SELECT prim FROM prof_profile WHERE rank = "JS" AND no_of_duty != 0) ORDER BY prim ASC';
	$retval=mysqli_query($conn, $sql);  
	if(mysqli_num_rows($retval) > 0){
    	while($row = mysqli_fetch_assoc($retval)){
    		$prof_id = $row["prof_id"]; $pslot = $row["pslot"]; $pdate = $row["pdate"]; $ptype = $row["ptype"];

    		$sql2 = 'SELECT * FROM exam WHERE edate = "'.$pdate.'" AND eslot = "'.$pslot.'"';
    		$retval2 = mysqli_query($conn, $sql2); //can be more thanb one exams in same slot
    		$done=0; $checked_rooms = array();
    		while($row2 = mysqli_fetch_assoc($retval2) && $done==0){
    			
    			$erooms = explode(",",$row2["room"]); $eprim = $row2["prim"];

    			foreach($erooms as $room){

    				if(!in_array($room, $checked_rooms)){

    					array_push($checked_rooms, $room);
        				$sql2 = 'SELECT * FROM allotment WHERE position = "JS" AND room = "'.$room.'" AND exam_id != "'.$eprim.'"';
        				$sql3 = 'SELECT * FROM allotment WHERE prof_id = "'.$prof_id.'" exam_id IN (SELECT prim  FROM exam WHERE edate="'.$pdate.'") ';

    					if(mysqli_num_rows(mysqli_query($conn, $sql2)) == 0 && mysqli_num_rows(mysqli_query($conn, $sql3)) == 0){

    						$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$eprim.'","'.$room.'","'.$prof_id.'","JS")';
    						mysqli_query($conn, $sql4); mysqli_commit($conn);

    						$sql5 = 'UPDATE prof_profile SET no_of_duty = no_of_duty - 1 WHERE prim = "'.$prof_id.'"';
    						mysqli_query($conn, $sql5); mysqli_commit($conn);

    						$done=1; break;
    					}
    				}
    			}
    		}

    		if($done == 0){
    			//try for reliever,standby

    			$sql5 = 'SELECT * FROM allotment WHERE prof_id = "'.$prof_id.'" AND exam_id IN (SELECT prim  FROM exam WHERE edate="'.$edate.'") ';

    			if(mysqli_num_rows(mysqli_query($conn, $sql5)) == 0){

    				//try for standby
    				$sql5 = 'SELECT * FROM allotment WHERE position = "STANDBY" AND exam_id IN (SELECT prim  FROM exam WHERE edate="'.$edate.'" AND eslot = "'.$eslot.'") ';

    				if(mysqli_num_rows(mysqli_query($conn, $sql5)) == 0){

    	    			$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$eprim.'","'.$room.'","'.$prof_id.'","STANDBY")';
    					mysqli_query($conn, $sql4); mysqli_commit($conn);

    					//allot remaining rooms		
    					$slot_rooms=array(); $slot_exams=array();
    					$sql4 = 'SELECT * FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'"';
    					$ret = mysqli_query($conn,$sql4);
    					while($row4 = mysqli_fetch_assoc($ret)){
    						foreach(explode(",", $row4["room"]) AS $temp){
    							array_push($slot_rooms, $temp); array_push($slot_exams, $row4["prim"]);
    						}
    					}

    					$cnt=0;
    					for($i=0; $i < count($slot_rooms); $i++){
    						if($slot_rooms[$i] != $room){

   								$sql4 = 'SELECT * FROM allotment WHERE position = "STANDBY" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'") AND room = "'.$slot_rooms[$i].'"';

   								if(mysqli_num_rows(mysqli_query($conn, $sql4)) == 0){

    								$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$slot_exams[$i].'","'.$slot_rooms[$i].'","'.$prof_id.'","STANDBY")';
    								mysqli_query($conn, $sql4); mysqli_commit($conn);

    								$cnt++;
    							}
    						}
    					}

    					if($cnt != 0){
    						$sql5 = 'UPDATE prof_profile SET no_of_duty = no_of_duty - 1 WHERE prim = "'.$prof_id.'"';
    						mysqli_query($conn, $sql5); mysqli_commit($conn);
    					}
   					}
   					else{
   						//try for reliever

   						$slot_rooms=array(); $slot_exams=array();
    					$sql4 = 'SELECT * FROM exam WHERE etimediff > 2 AND edate="'.$edate.'" AND eslot="'.$eslot.'"';
    					$ret = mysqli_query($conn,$sql4);
    					while($row4 = mysqli_fetch_assoc($ret)){
    						foreach(explode(",", $row4["room"]) AS $temp){
    							array_push($slot_rooms, $temp); array_push($slot_exams, $row4["prim"]);
    						}
    					}

    					$cnt=0;
    					for($i=0; $i < count($slot_rooms); $i++){
    						if($cnt < 7){

    							$sql4 = 'SELECT * FROM allotment WHERE position = "RELIEVER" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'") AND room = "'.$slot_rooms[$i].'"';

    							if(mysqli_num_rows(mysqli_query($conn, $sql4)) == 0){

    								$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$slot_exams[$i].'","'.$slot_rooms[$i].'","'.$prof_id.'","RELIEVER")';
    								mysqli_query($conn, $sql4); mysqli_commit($conn);

    								$cnt++;
    							}
   							}

   							//allot 1 more room if only 1 left
   							if($cnt == 7 && $i == count($slot_rooms)-2){

    							$sql4 = 'SELECT * FROM allotment WHERE position = "RELIEVER" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'") AND room = "'.$slot_rooms[$i+1].'"';

    							if(mysqli_num_rows(mysqli_query($conn, $sql4)) == 0){

    								$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$slot_exams[$i+1].'","'.$slot_rooms[$i+1].'","'.$prof_id.'","RELIEVER")';
    								mysqli_query($conn, $sql4); mysqli_commit($conn);

    								$cnt++;
    							}
    						}
    					}

    					if($cnt != 0){
    						$sql5 = 'UPDATE prof_profile SET no_of_duty = no_of_duty - 1 WHERE prim = "'.$prof_id.'"';
    						mysqli_query($conn, $sql5); mysqli_commit($conn);
    					}

   					}
   				}    			
    		}
    	}
    }

    //allot preferences SS
	$sql = 'SELECT * FROM prof_preferences WHERE ptype = 1 AND prof_id IN (SELECT prim FROM prof_profile WHERE rank = "SS" AND no_of_duty != 0) ORDER BY prim ASC';
	$retval=mysqli_query($conn, $sql);  
	if(mysqli_num_rows($retval) > 0){
    	while($row = mysqli_fetch_assoc($retval)){
    		$prof_id = $row["prof_id"]; $pslot = $row["pslot"]; $pdate = $row["pdate"]; $ptype = $row["ptype"];

    		$slot_rooms=array(); $slot_exams=array();
    		$sql4 = 'SELECT * FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'"';
    		$ret = mysqli_query($conn,$sql4);
    		while($row4 = mysqli_fetch_assoc($ret)){
    			foreach(explode(",", $row4["room"]) AS $temp){
    				array_push($slot_rooms, $temp); array_push($slot_exams, $row4["prim"]);
    			}
    		}

    		if(count($slot_rooms > 1)){

    			$cnt=0;
    			for($i=0; $i < count($slot_rooms); $i++){

    				if(!in_array($slot_rooms[$i], $checked_rooms) && $cnt < 3){

    					array_push($checked_rooms, $slot_rooms[$i]);
        				$sql2 = 'SELECT * FROM allotment WHERE position = "SS" AND room = "'.$slot_rooms[$i].'" AND exam_id != "'.$slot_exams[$i].'"';
        				$sql3 = 'SELECT * FROM allotment WHERE prof_id = "'.$prof_id.'" exam_id IN (SELECT prim  FROM exam WHERE edate="'.$pdate.'" AND eslot != "'.$pslot.'") ';

    					if(mysqli_num_rows(mysqli_query($conn, $sql2)) == 0 && mysqli_num_rows(mysqli_query($conn, $sql3)) == 0){

    						$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$slot_exams[$i].'","'.$slot_rooms[$i].'","'.$prof_id.'","SS")';
    						mysqli_query($conn, $sql4); mysqli_commit($conn);

    						$cnt++;
    					}
    				}
    			}

    			if($cnt != 0){
    				$sql5 = 'UPDATE prof_profile SET no_of_duty = no_of_duty - 1 WHERE prim = "'.$prof_id.'"';
    				mysqli_query($conn, $sql5); mysqli_commit($conn);
    			}
    		}
    	}
    }

    //allot random JS
    $sql = 'SELECT * FROM exam';
	$retval=mysqli_query($conn, $sql);  
	if(mysqli_num_rows($retval) > 0){
    	while($row = mysqli_fetch_assoc($retval)){

    		$eprim = $row["prim"]; $edate = $row["edate"]; $eslot = $row["eslot"]; $erooms = explode(",",$row["room"]);

    		foreach($erooms as $room){
    			
    			$sql2 = 'SELECT * FROM allotment WHERE position = "JS" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'") AND room = "'.$room.'"';
    			if(mysqli_num_rows(mysqli_query($conn, $sql2)) == 0){

    				$done=0; $prof_tried = array();
    				while($done == 0){
    					$prof_id = rand(1,$no_of_prof);

    					if(!in_array($prof_id, $prof_tried)){

    						array_push($prof_tried, $prof_id);

    						$sql3 = 'SELECT * FROM prof_profile WHERE rank = "JS" AND no_of_duty != 0 AND prim="'.$prof_id.'" AND prim NOT IN (SELECT prof_id FROM prof_preferences WHERE pdate = "'.$edate.'" AND pslot = "'.$eslot.'" AND ptype != 1 AND ptype != 0)'; 

    						$sql4 = 'SELECT * FROM allotment WHERE prof_id = "'.$prof_id.'" AND exam_id IN (SELECT prim  FROM exam WHERE edate="'.$edate.'") ';

    						if(mysqli_num_rows(mysqli_query($conn, $sql3)) != 0 && mysqli_num_rows(mysqli_query($conn, $sql4)) == 0){
    							$done = 1;
    						}
    					}

    					if($done == 1){

    						$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$eprim.'","'.$room.'","'.$prof_id.'","JS")';
    						mysqli_query($conn, $sql4); mysqli_commit($conn);

    						$sql5 = 'UPDATE prof_profile SET no_of_duty = no_of_duty - 1 WHERE prim = "'.$prof_id.'"';
    						mysqli_query($conn, $sql5); mysqli_commit($conn);
    					}

    					if(count($prof_tried) >= $no_of_prof){
    						//no-of-js short of duties
    						break;
    					}

    				}

    			}
    		}
    	}
    }

    //allot SS random
    $sql = 'SELECT * FROM exam';
	$retval=mysqli_query($conn, $sql);  
	if(mysqli_num_rows($retval) > 0){
    	while($row = mysqli_fetch_assoc($retval)){

    		$eprim = $row["prim"]; $edate = $row["edate"]; $eslot = $row["eslot"]; $erooms = explode(",",$row["room"]);

    		foreach($erooms as $room){
    			
    			$sql2 = 'SELECT * FROM allotment WHERE position = "SS" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'") AND room = "'.$room.'"';
    			if(mysqli_num_rows(mysqli_query($conn, $sql2)) == 0){

    				$done=0; $prof_tried = array();
    				while($done == 0){
    					$prof_id = rand(1,$no_of_prof);

    					if(!in_array($prof_id, $prof_tried)){

    						array_push($prof_tried, $prof_id);
    						
    						$sql3 = 'SELECT * FROM prof_profile WHERE rank = "SS" AND no_of_duty != 0 AND prim="'.$prof_id.'" AND prim NOT IN (SELECT prof_id FROM prof_preferences WHERE pdate = "'.$edate.'" AND pslot = "'.$eslot.'" AND ptype != 1 AND ptype != 0)'; 

    						$sql4 = 'SELECT * FROM allotment WHERE prof_id = "'.$prof_id.'" AND exam_id IN (SELECT prim  FROM exam WHERE edate="'.$edate.'" AND eslot != "'.$eslot.'") ';

    						$sql5 = 'SELECT * FROM allotment WHERE prof_id = "'.$prof_id.'" AND position != "SS" AND exam_id IN (SELECT prim  FROM exam WHERE edate="'.$edate.'" AND eslot = "'.$eslot.'") ';

    						if(mysqli_num_rows(mysqli_query($conn, $sql3)) != 0 && mysqli_num_rows(mysqli_query($conn, $sql4)) == 0 && mysqli_num_rows(mysqli_query($conn, $sql5)) == 0){
    							$done=1;
    						}
    					}

    					if($done == 1){

    						//allot max 3 rooms		
    						$slot_rooms=array(); $slot_exams=array();
    						$sql4 = 'SELECT * FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'"';
    						$ret = mysqli_query($conn,$sql4);
    						while($row4 = mysqli_fetch_assoc($ret)){
    							foreach(explode(",", $row4["room"]) AS $temp){
    								array_push($slot_rooms, $temp); array_push($slot_exams, $row4["prim"]);
    							}
    						}

    						if(count($slot_rooms) > 1){

    							$cnt=0;
    							for($i=0; $i < count($slot_rooms); $i++){
    								if($cnt < 3){

    									$sql4 = 'SELECT * FROM allotment WHERE position = "SS" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'") AND room = "'.$slot_rooms[$i].'"';

    									if(mysqli_num_rows(mysqli_query($conn, $sql4)) == 0){

    										$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$slot_exams[$i].'","'.$slot_rooms[$i].'","'.$prof_id.'","SS")';
    										mysqli_query($conn, $sql4); mysqli_commit($conn);

    										$cnt++;
    									}
    								}

    								//allot 1 more room if only 1 left
    								if($cnt == 3 && $i == count($slot_rooms)-2){

    									$sql4 = 'SELECT * FROM allotment WHERE position = "SS" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'") AND room = "'.$slot_rooms[$i+1].'"';

    									if(mysqli_num_rows(mysqli_query($conn, $sql4)) == 0){

    										$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$slot_exams[$i+1].'","'.$slot_rooms[$i+1].'","'.$prof_id.'","SS")';
    										mysqli_query($conn, $sql4); mysqli_commit($conn);

    										$cnt++;
    									}
    								}
	    						}

    							if($cnt != 0){
    								$sql5 = 'UPDATE prof_profile SET no_of_duty = no_of_duty - 1 WHERE prim = "'.$prof_id.'"';
    								mysqli_query($conn, $sql5); mysqli_commit($conn);
    							}
    						}
    					}

    					if(count($prof_tried) >= $no_of_prof){
    						//no-of-ss short of duties
    						break;
    					}
    				}
    			}
    		}
    	}
    }

    //allot RELIEVER random
    $sql = 'SELECT * FROM exam WHERE etimediff > 2';
	$retval=mysqli_query($conn, $sql);  
	if(mysqli_num_rows($retval) > 0){
    	while($row = mysqli_fetch_assoc($retval)){

    		$eprim = $row["prim"]; $edate = $row["edate"]; $eslot = $row["eslot"]; $erooms = explode(",",$row["room"]);

    		foreach($erooms as $room){
    			
    			$sql2 = 'SELECT * FROM allotment WHERE position = "RELIEVER" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'") AND room = "'.$room.'"';
    			if(mysqli_num_rows(mysqli_query($conn, $sql2)) == 0){

    				$done=0; $prof_tried = array();
    				while($done == 0){
    					$prof_id = rand(1,$no_of_prof);

    					if(!in_array($prof_id, $prof_tried)){

    						array_push($prof_tried, $prof_id); 
    						
    						$sql3 = 'SELECT * FROM prof_profile WHERE rank = "JS" AND no_of_duty != 0 AND prim="'.$prof_id.'" AND prim NOT IN (SELECT prof_id FROM prof_preferences WHERE pdate = "'.$edate.'" AND pslot = "'.$eslot.'" AND ptype != 1 AND ptype != 0)'; 

    						$sql4 = 'SELECT * FROM allotment WHERE prof_id = "'.$prof_id.'" AND exam_id IN (SELECT prim  FROM exam WHERE edate="'.$edate.'" AND eslot != "'.$eslot.'") ';

    						$sql5 = 'SELECT * FROM allotment WHERE prof_id = "'.$prof_id.'" AND position != "RELIEVER" AND exam_id IN (SELECT prim  FROM exam WHERE edate="'.$edate.'" AND eslot = "'.$eslot.'") ';

    						if(mysqli_num_rows(mysqli_query($conn, $sql3)) != 0 && mysqli_num_rows(mysqli_query($conn, $sql4)) == 0 && mysqli_num_rows(mysqli_query($conn, $sql5)) == 0){
    							$done=1;
    						}
    					}

    					if($done == 1){

    						//allot max 6 rooms		
    						$slot_rooms=array(); $slot_exams=array();
    						$sql4 = 'SELECT * FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'"';
    						$ret = mysqli_query($conn,$sql4);
    						while($row4 = mysqli_fetch_assoc($ret)){
    							foreach(explode(",", $row4["room"]) AS $temp){
    								array_push($slot_rooms, $temp); array_push($slot_exams, $row4["prim"]);
    							}
    						}

    						if(count($slot_rooms) > 1){ 

    							$cnt=0;
    							for($i=0; $i < count($slot_rooms); $i++){
    								if($cnt < 6){

    									$sql4 = 'SELECT * FROM allotment WHERE position = "RELIEVER" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'") AND room = "'.$slot_rooms[$i].'"';

    									if(mysqli_num_rows(mysqli_query($conn, $sql4)) == 0){

    										$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$slot_exams[$i].'","'.$slot_rooms[$i].'","'.$prof_id.'","RELIEVER")';
    										mysqli_query($conn, $sql4); mysqli_commit($conn);

    										$cnt++;
    									}
    								}

    								//allot 1 more room if only 1 left
    								if($cnt == 6 && $i == count($slot_rooms)-2){

    									$sql4 = 'SELECT * FROM allotment WHERE position = "RELIEVER" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'") AND room = "'.$slot_rooms[$i+1].'"';

    									if(mysqli_num_rows(mysqli_query($conn, $sql4)) == 0){

    										$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$slot_exams[$i+1].'","'.$slot_rooms[$i+1].'","'.$prof_id.'","RELIEVER")';
    										mysqli_query($conn, $sql4); mysqli_commit($conn);

    										$cnt++;
    									}
    								}
    							}

    							if($cnt != 0){
    								$sql5 = 'UPDATE prof_profile SET no_of_duty = no_of_duty - 1 WHERE prim = "'.$prof_id.'"';
    								mysqli_query($conn, $sql5); mysqli_commit($conn);
    							}
    						}
    					}

    					if(count($prof_tried) >= $no_of_prof){
    						//no-of-js short of reliever duties
    						break;
    					}
    				}
    			}
    		}
    	}
    }    

    //allot STANDBY random
    $sql = 'SELECT * FROM exam ORDER BY prim ASC';
	$retval=mysqli_query($conn, $sql);  
	if(mysqli_num_rows($retval) > 0){
    	while($row = mysqli_fetch_assoc($retval)){

    		$eprim = $row["prim"]; $edate = $row["edate"]; $eslot = $row["eslot"]; $erooms = explode(",",$row["room"]);

    		foreach($erooms as $room){
    			
    			$sql2 = 'SELECT * FROM allotment WHERE position = "STANDBY" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'")';
    			if(mysqli_num_rows(mysqli_query($conn, $sql2)) == 0){ echo "<br>eprim : ".$eprim."room : ".$room;

    				$done=0; $prof_tried = array();
    				while($done == 0){
    					$prof_id = rand(1,$no_of_prof);

    					if(!in_array($prof_id, $prof_tried)){

    						array_push($prof_tried, $prof_id); echo "<br>prof_tried : ".$prof_id;
    						
    						$sql3 = 'SELECT * FROM prof_profile WHERE rank = "JS" AND no_of_duty != 0 AND prim="'.$prof_id.'" AND prim NOT IN (SELECT prof_id FROM prof_preferences WHERE pdate = "'.$edate.'" AND pslot = "'.$eslot.'" AND ptype != 1 AND ptype != 0)'; 

    						$sql4 = 'SELECT * FROM allotment WHERE prof_id = "'.$prof_id.'" AND exam_id IN (SELECT prim  FROM exam WHERE edate="'.$edate.'" AND eslot != "'.$eslot.'") ';

    						$sql5 = 'SELECT * FROM allotment WHERE prof_id = "'.$prof_id.'" AND position != "STANDBY" AND exam_id IN (SELECT prim  FROM exam WHERE edate="'.$edate.'" AND eslot = "'.$eslot.'") ';

    						if(mysqli_num_rows(mysqli_query($conn, $sql3)) != 0 && mysqli_num_rows(mysqli_query($conn, $sql4)) == 0 && mysqli_num_rows(mysqli_query($conn, $sql5)) == 0){
    							$done=1;
    						}
    					}

    					if($done == 1){
    	
    	    				$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$eprim.'","'.$room.'","'.$prof_id.'","STANDBY")';
    						mysqli_query($conn, $sql4); mysqli_commit($conn);

    						//allot remaining rooms		
    						$slot_rooms=array(); $slot_exams=array();
    						$sql4 = 'SELECT * FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'"';
    						$ret = mysqli_query($conn,$sql4);
    						while($row4 = mysqli_fetch_assoc($ret)){
    							foreach(explode(",", $row4["room"]) AS $temp){
    								array_push($slot_rooms, $temp); array_push($slot_exams, $row4["prim"]);
    							}
    						}echo "count : ".count($slot_rooms);

    						$cnt=0;
    						for($i=0; $i < count($slot_rooms); $i++){
    							if($slot_rooms[$i] != $room){

    								$sql4 = 'SELECT * FROM allotment WHERE position = "STANDBY" AND exam_id IN (SELECT prim FROM exam WHERE edate="'.$edate.'" AND eslot="'.$eslot.'") AND room = "'.$slot_rooms[$i].'"';

    								if(mysqli_num_rows(mysqli_query($conn, $sql4)) == 0){

    									$sql4 = 'INSERT INTO allotment(exam_id,room,prof_id,position) VALUES("'.$slot_exams[$i].'","'.$slot_rooms[$i].'","'.$prof_id.'","STANDBY")';
    									mysqli_query($conn, $sql4); mysqli_commit($conn);

    									$cnt++;
    								}
    							}
    						}

    						if($cnt != 0){
    							$sql5 = 'UPDATE prof_profile SET no_of_duty = no_of_duty - 1 WHERE prim = "'.$prof_id.'"';
    							mysqli_query($conn, $sql5); mysqli_commit($conn);
    						}
    					
    					}

    					if(count($prof_tried) >= $no_of_prof){
    						//no-of-js short of standby duties
    						break;
    					}
    				}
    			}
    		}
    	}
    }

}else{

	//echo "Connection error";
}

?>