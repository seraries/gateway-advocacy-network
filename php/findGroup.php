<?php
$name = file_get_contents("php://input");
// file_put_contents("findTest.txt", "At start of file");

require_once('dbconnect.php');

// Use prepared statement to avoid sql injection attack
$selectSql = $conn->prepare("SELECT name, about, zip, issues, contact, contactEmail, link FROM groups WHERE name = ?");

$selectSql->bind_param("s", $name);

if ($selectSql->execute()) {
	// file_put_contents("findTest.txt", " inside if, stmt was executed ", FILE_APPEND);
}

$selectSql->store_result();

$selectSql->bind_result($name, $about, $zip, $issues, $contact, $contactEmail, $link); 

$array = array();

if ($selectSql->num_rows > 0) {
	// file_put_contents("findTest.txt", " inside if, there is a result ", FILE_APPEND);
    // get each row and store in array
    while($selectSql->fetch()) {
        $row = (object) ['name' => $name, 'about' => $about, 'zip' => $zip, 'issues' => $issues, 'contactEmail' => $contactEmail, 'link' => $link];
    	// file_put_contents("findTest.txt", " inside while loop ", FILE_APPEND);
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