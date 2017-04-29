<?php

// this gets $conn from db using config info
require_once('dbconnect.php');
file_put_contents("create.txt", " at start of file");

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
file_put_contents("create.txt", $array, FILE_APPEND);
echo $array;

$conn->close();

/*
$username = "1gatewayLeader";
$password = "Umbrella314";
file_put_contents("create.txt", "start of file");

$cost = 10;

// Create a random salt
$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

// Prefix information about the hash so PHP knows how to verify it later.
$salt = sprintf("$2a$%02d$", $cost) . $salt;

// Hash the password with the salt
$hash = crypt($password, $salt);

$createSql = "CREATE TABLE if NOT EXISTS leaders (userId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, username VARCHAR(40) NOT NULL, hash VARCHAR(60) NOT NULL, email VARCHAR(50) UNIQUE)";

if ($conn->query($createSql) === TRUE) {
    file_put_contents("create.txt", "Table created successfully", FILE_APPEND);
} else {
    //echo "Error creating table: " . $conn->error;
}

$insertSql = "INSERT INTO leaders (username, hash) VALUES ('$username', '$hash')";

if ($conn->query($insertSql) === TRUE) {
    file_put_contents("create.txt", " new record created successfully", FILE_APPEND);
} else {
    // echo "Error: " . $sql . "<br>" . $conn->error;
}

$name = "Member";
$username = "stlActivist!";
$password = "MOaction4Good";
file_put_contents("create.txt", "start of file");

$cost = 10;

// Create a random salt
$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

// Prefix information about the hash so PHP knows how to verify it later.
$salt = sprintf("$2a$%02d$", $cost) . $salt;

// Hash the password with the salt
$hash = crypt($password, $salt);

$createSql = "CREATE TABLE if NOT EXISTS members (userId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(60), username VARCHAR(50) NOT NULL, hash VARCHAR(60) NOT NULL, email VARCHAR(50) UNIQUE)";

if ($conn->query($createSql) === TRUE) {
    file_put_contents("create.txt", "Table created successfully", FILE_APPEND);
} else {
    //echo "Error creating table: " . $conn->error;
}

$insertSql = "INSERT INTO members (name, username, hash) VALUES ('$name', '$username', '$hash')";

if ($conn->query($insertSql) === TRUE) {
    file_put_contents("create.txt", " new record created successfully", FILE_APPEND);
} else {
    // echo "Error: " . $sql . "<br>" . $conn->error;
}

$createSql2 = "CREATE TABLE if NOT EXISTS groups (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) UNIQUE NOT NULL, about VARCHAR(500) NOT NULL, zip VARCHAR(14) NOT NULL, issues VARCHAR(200), contact VARCHAR(100), contactEmail VARCHAR(100), link VARCHAR(110) DEFAULT '')";

if ($conn->query($createSql2) === TRUE) {
    file_put_contents("create.txt", " Table created successfully ", FILE_APPEND);
} else {
    //echo "Error creating table: " . $conn->error;
}
$name = "Clayton Huddle";
$about = "We are a small group that formed after the Women\'s March with the purpose of supporting each other in becoming informed citizens and effective activists. We\'ve held meetings with MOCs and participated in multiple rallies and events. We are open to all who wish to join us and we hold weekly meetings on Sundays at 4pm.";
$zip ="63105";
$issues = "Health Care, Women\'s Rights, Civic Involvement, Voter Rights, LGBTQIA, Other";
$contact = "Sarah Richardson";
$contactEmail = "mohuddles@protonmail.com";
$link = "http://claytonhuddle.org";

$insertSql = "INSERT INTO groups (name, about, zip, issues, contact, contactEmail, link) VALUES ('$name', '$about', '$zip', '$issues', '$contact', '$contactEmail', '$link')";

if ($conn->query($insertSql) === TRUE) {
    file_put_contents("create.txt", " new record created successfully", FILE_APPEND);
} else {
    // echo "Error: " . $sql . "<br>" . $conn->error;
}
// increased maxlength for zip so that people can enter multiple comma-separated zips.
$alterSql = "ALTER TABLE groups MODIFY COLUMN zip VARCHAR(30)";
if ($conn->query($alterSql) === TRUE) {
    // echo "Table MyGuests created successfully";
    file_put_contents("alterTest.txt", "groups table altered");
} else {
    echo "Error altering table: " . $conn->error;
}

$createSql3 = "CREATE TABLE if NOT EXISTS resources (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(110) UNIQUE NOT NULL, about VARCHAR(500) NOT NULL, mainIssue VARCHAR(50) NOT NULL, otherIssues VARCHAR(200), link VARCHAR(110) DEFAULT '')";

if ($conn->query($createSql3) === TRUE) {
    file_put_contents("create.txt", " Table created successfully ", FILE_APPEND);
} else {
    //echo "Error creating table: " . $conn->error;
}

$name = "Black Lives Matter";
$about = "Black Lives Matter affirms the lives of Black queer and trans folks, disabled folks, Black-undocumented folks, folks with records, women and all Black lives along the gender spectrum. It centers those that have been marginalized within Black liberation movements. It is a tactic to (re)build the Black liberation movement.";
$mainIssue ="Racial Justice";
$otherIssues = "Disability, Women\'s Rights, LGBTQIA, Criminal Justice";
$link = "http://blacklivesmatter.com/";

$insertSql = "INSERT INTO resources (name, about, mainIssue, otherIssues, link) VALUES ('$name', '$about', '$mainIssue', '$otherIssues', '$link')";

if ($conn->query($insertSql) === TRUE) {
    file_put_contents("create.txt", " new record created successfully", FILE_APPEND);
} else {
    // echo "Error: " . $sql . "<br>" . $conn->error;
}

*/

?>