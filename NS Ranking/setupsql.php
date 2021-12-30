<?php
include('get_data.php');
$servername="localhost";
$dbUsername="root";
$dbPassword="";
$dbName="nsplayers";

$conn = mysqli_connect($servername,$dbUsername,$dbPassword,$dbName);

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}else{
	echo "All good! <br>";
	$members = getApoCrew();
	$memberNo = count($members);
	foreach ($members as $member) {
		
		$fieldVal1 = mysql_real_escape_string($member["Name"]);
		$fieldVal2 = mysql_real_escape_string($member["Level"]);
	    $fieldVal3 = mysql_real_escape_string($member["Damage"]);
			$sql = "INSERT INTO players (Name, Level, Reps)
		VALUES ('".$fieldVal1."', '".$fieldVal2."', '".$fieldVal3."')";
		if (mysqli_query($conn, $sql)) {
		    echo "New record created successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}

	mysqli_close($conn);
	
}
?>