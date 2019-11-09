<?php
$name = file_get_contents("php://input");
// file_put_contents("findTest.txt", "At start of file");

require_once('dbconnect.php');

// Use prepared statement to avoid sql injection attack
$selectSql = $conn->prepare("SELECT name, about, mainIssue, otherIssues, link FROM resources WHERE name = ?");

$selectSql->bind_param("s", $name);

if ($selectSql->execute()) {
	// file_put_contents("findTest.txt", " inside if, stmt was executed ", FILE_APPEND);
}
$selectSql->store_result();

$selectSql->bind_result($name, $about, $mainIssue, $otherIssues, $link);

$array = array();

if ($selectSql->num_rows > 0) {
	// file_put_contents("findTest.txt", " inside if, there is a result ", FILE_APPEND);
    // get each row and store in array
    while($selectSql->fetch()) {
    	// file_put_contents("findTest.txt", " inside while loop ", FILE_APPEND);
        $row = (object) ['name' => $name, 'about' => $about, 'mainIssue' => $mainIssue, 'otherIssues' => $otherIssues, 'link' => $link];
        $array[] = $row;
    }
} else {
    echo "0 results";
}

$array = json_encode($array);

// file_put_contents("findTest.txt", $array, FILE_APPEND);

echo $array;

$selectSql->free_result();

$selectSql->close();

$conn->close();

?>