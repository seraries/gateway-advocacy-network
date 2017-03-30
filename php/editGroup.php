<?php
$postdata = file_get_contents("php://input");
$postdata = json_decode($postdata);

$name = $postdata->name;
$about = $postdata->about;
$zip = $postdata->zip;
$issues = $postdata->issues;
$contact = $postdata->contact;
$contactEmail = $postdata->contactEmail;
$link = $postdata->link;

file_put_contents("editTest.txt", "At start of file");
require_once('dbconnect.php');

// Use prepared statement to avoid sql injection attack
$updateSql = $conn->prepare("UPDATE groups SET about=?, zip=?, issues=?, contact=?, contactEmail=?, link=? WHERE name=?"); 

// "s" means the database expects a string
$updateSql->bind_param("sssssss", $about, $zip, $issues, $contact, $contactEmail, $link, $name);

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

echo $array;

$conn->close();

?>