<?php
$name = file_get_contents("php://input");
file_put_contents("findTest.txt", "At start of file");

require_once('dbconnect.php');

// Use prepared statement to avoid sql injection attack
$selectSql = $conn->prepare("SELECT * FROM resources WHERE name = ?");

$selectSql->bind_param("s", $name);

if ($selectSql->execute()) {
	file_put_contents("findTest.txt", " inside if, stmt was executed ", FILE_APPEND);
}

$result = $selectSql->get_result(); 

$array = array();

if ($result->num_rows > 0) {
	file_put_contents("findTest.txt", " inside if, there is a result ", FILE_APPEND);
    // get each row and store in array
    while($row = $result->fetch_assoc()) {
    	file_put_contents("findTest.txt", " inside while loop ", FILE_APPEND);
        $array[] = $row;
    }
} else {
    echo "0 results";
}

file_put_contents("findTest.txt", "Array before json: ", FILE_APPEND);
file_put_contents("findTest.txt", $array, FILE_APPEND);
$array = json_encode($array);
file_put_contents("findTest.txt", " Array after json: ", FILE_APPEND);
file_put_contents("findTest.txt", $array, FILE_APPEND);
echo $array;

$conn->close();

?>