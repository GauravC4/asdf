<?php

include("db.php");
$username = $_POST['username'];
$response = array();

$conn = mysqli_connect($host, $user, $pass, $database);  

if ($conn) {

	$sql = 'SELECT * FROM `prof_profile` WHERE username = "'.$username.'"';  
	$retval=mysqli_query($conn, $sql);    
	if(mysqli_num_rows($retval) > 0){
  		
  		$i = 0; 
  		//$now = time(); $your_date = strtotime("2010-01-01"); $diff = floor($datediff / (60 * 60 * 24));
    	while($row = mysqli_fetch_assoc($retval)){

    		$response[$i]['prim'] = $row['prim'];
    		$response[$i]['name'] = $row['name'];
    		$response[$i]['rank'] = $row['rank'];
    		$response[$i]['username'] = $row['username'];
    		$response[$i]['password'] = $row['password'];
    		$response[$i]['duty_amount'] = $row['duty_amount'];

	    	/*$alot_prim = $row['prim']; 
	    	$allot_examid = $row['exam_id']; 
	    	$allot_pos = $row['position'];

	    	$sql2 = 'SELECT * FROM `exam` WHERE prim = "'.$allot_examid.'"';
	    	$retval2 = mysqli_query($conn,$sql2); 
	    	$row2 = mysqli_fetch_array($retval2);

	    	$exam_prim = $row['prim']; 
	    	$exam_scode = $row['scode']; 
	    	$exam_sname = $row['sname'];
	    	$exam_date = $row['date']; 
	    	$exam_time = $row['time']; 

	    	$arr[$i]["alot_prim"] = $alot_prim; 
	    	$arr[$i]["alot_examid"] = $alot_examid; 
	    	$arr[$i]["alot_pos"] = $alot_pos;
	    	$arr[$i]["exam_prim"] = $exam_prim; 
	    	$arr[$i]["exam_scode"] = $exam_scode; 
	    	$arr[$i]["exam_sname"] = $exam_sname;
	    	$arr[$i]["exam_date"] = $exam_date; 
	    	$arr[$i]["exam_time"] = $exam_time;
*/
	    	$i++;
		}

	}else{
		$response[0]["prim"] = "none";
	}

	echo json_encode($response);

	mysqli_close($conn);

}else{
	//Connection error	
}

?>