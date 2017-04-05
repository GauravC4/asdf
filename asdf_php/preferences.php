<?php

$pref_input = $_POST['pref_input']; 

if($pref_input == 0){
	show_pref();
}else{
	input_pref();
}

function show_pref(){

	$prof_id = $_POST['prof_id']; $user_type = $_POST['user_type'];

	include 'db.php';

$conn = mysqli_connect($host, $user, $pass, $database);  

if ($conn) {

		if($user_type == "admin"){
			$sql = 'SELECT * FROM `prof_preferences` WHERE ptype != 3'; 
		}else if($user_type == "user"){
			 $sql = 'SELECT * FROM `prof_preferences` WHERE prof_id = "'.$prof_id.'" AND ptype != 3';
		}else{
			$sql = 'SELECT * FROM `prof_preferences` WHERE ptype != 3 AND prof_id IN (SELECT prim FROM prof_profile WHERE dept = "'.$user_type.'")';
		}
		
		$retval=mysqli_query($conn, $sql);    
		if(mysqli_num_rows($retval) > 0){
  		
  			$i = 0; $dateschecked = array();
    		while($row = mysqli_fetch_assoc($retval)){

    			if(!in_array($row["pdate"], $dateschecked)){

    				array_push($dateschecked, $row["pdate"]);

	    			$sql2 = 'SELECT * FROM prof_preferences WHERE prof_id="'.$row["prof_id"].'" AND pdate="'.$row['pdate'].'" AND pslot != "'.$row['pslot'].'"';
	    			$retval2 = mysqli_query($conn,$sql2);
	    			if(mysqli_num_rows($retval2)>0){

	    				$row2 = mysqli_fetch_array($retval2);
	    				if($row["pslot"] == 1){
	    					$pref_slot1_leave_pref = $row["ptype"]; $pref_slot2_leave_pref = $row2["ptype"];
	    				}else{
	    					$pref_slot1_leave_pref = $row2["ptype"]; $pref_slot2_leave_pref = $row["ptype"];
	    				}

	    				$arr[$i]["pref_id"] = $row["prim"]; $arr[$i]["pref_date"] = $row["pdate"];
	    				$arr[$i]["pref_slot1_leave_pref"] = $pref_slot1_leave_pref; $arr[$i]["pref_slot2_leave_pref"] = $pref_slot2_leave_pref;

	    			}else{

	    				$arr[$i]["pref_id"] = $row["prim"]; $arr[$i]["pref_date"] = $row["pdate"];
	    				$arr[$i]["pref_slot1_leave_pref"] = $row["ptype"]; $arr[$i]["pref_slot2_leave_pref"] = "0";
	    			}

	    			$i++;
    			}
			}

		}else{
			$arr[0]["pref_id"] = "none";
		}

		mysqli_close($conn);	

}else{
	$arr[0]["pref_id"] = "conn_error";
}

echo json_encode($arr);

}

function input_pref(){

	$pref_id = $_POST['pref_id']; $slot = $_POST['slot']; $option_position = $_POST['option_position'];

	include 'db.php';

	$conn = mysqli_connect($host, $user, $pass, $database);  

	if ($conn) {

		$sql = 'SELECT * FROM prof_preferences WHERE prim = "'.$pref_id.'"';
		$ret = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($ret);

		$sql2 = 'UPDATE prof_preferences SET ptype="'.$option_position.'" WHERE prof_id="'.$row["prof_id"].'" AND pdate="'.$row["pdate"].'" AND pslot="'.$slot.'"';
		mysqli_query($conn, $sql2);

		if(mysqli_commit($conn)){
			echo "success";
		}else{
			echo "error";
		}
	
		mysqli_close($conn);

	}else{
		echo "conn_error";
	}

}

?>