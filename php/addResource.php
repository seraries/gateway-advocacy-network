<?php //add a Resource

require_once('dbconnect.php');
$postdata = file_get_contents("php://input");
$postdata = json_decode($postdata);

$name = $postdata->name;
$about = $postdata->about;
$mainIssue = $postdata->mainIssue;
$otherIssues = $postdata->otherIssues;
$link = $postdata->link;

$insertSql = $conn->prepare("INSERT INTO resources (name, about, mainIssue, otherIssues, link) VALUES (?, ?, ?, ?, ?)"); 

// "s" means the database expects a string
$insertSql->bind_param("sssss", $name, $about, $mainIssue, $otherIssues, $link);

if ($insertSql->execute() === TRUE) {
    file_put_contents("addTest.txt", " new record created!! ", FILE_APPEND);
} else {
    // echo "Error: " . $sql . "<br>" . $conn->error;
    // TO-DO: The line above created a ng-repeat dupes error, instead I want to 
    // let user know insert failed 
}
$insertSql->close();

// now send back updated array of resources
$sql = "SELECT * FROM resources";
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

echo $array;

$conn->close();

file_put_contents("addTest.txt", "at end of file", FILE_APPEND);

?>