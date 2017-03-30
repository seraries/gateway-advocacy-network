<?php
$postdata = file_get_contents("php://input");
$postdata = json_decode($postdata);

$name = $postdata->name;
$about = $postdata->about;
$mainIssue = $postdata->mainIssue;
$otherIssues = $postdata->otherIssues;
$link = $postdata->link;

file_put_contents("editTest.txt", "At start of file");
require_once('dbconnect.php');

// Use prepared statement to avoid sql injection attack
$updateSql = $conn->prepare("UPDATE resources SET about=?, mainIssue=?, otherIssues=?, link=? WHERE name=?"); 

// "s" means the database expects a string
$updateSql->bind_param("sssss", $about, $mainIssue, $otherIssues, $link, $name);

if ($updateSql->execute() === TRUE) {
    file_put_contents("editTest.txt", " record updated!! ", FILE_APPEND);
} else {
    // echo "Error: " . $sql . "<br>" . $conn->error;
    // TO-DO: The line above created a ng-repeat dupes error, instead I want to 
    // let user know insert failed 
}
$updateSql->close();
file_put_contents("editTest.txt", "At end of file", FILE_APPEND);

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

?>