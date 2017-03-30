<?php

// this gets $conn from db using config info
//TO DO: REFACTOR THIS AND getResources INTO ONE FILE THAT TAKES INPUT (POST) OF
// STRING THAT SAYS WHICH TABLE TO GET INFO FROM--RESOURCES OR GROUPS
require_once('dbconnect.php');
file_put_contents("create.txt", " at start of file");

$sql = "SELECT * FROM groups";
$result = $conn->query($sql);

$array = array();

if ($result->num_rows > 0) {
    // get each row and store in array
    while($row = $result->fetch_assoc()) {
        $array[] = $row;
    }
} 
else {
    // echo "0 results";
}

$array = json_encode($array);
file_put_contents("create.txt", $array, FILE_APPEND);
echo $array;

$conn->close();

?>