<?php

$loginInfo = file_get_contents("php://input");
$loginInfo = json_decode($loginInfo);
$username = $loginInfo->username;
$password = $loginInfo->password;
//file_put_contents("testData.txt", $loginInfo->username . $loginInfo->password);

// this gets $conn from db using config info
require_once('dbconnect.php');

// Using my own hash equals function since switching to php 5.6 from 5.4 on go daddy 
// was such a hassle and lead to get_result not working.
if(!function_exists('hash_equals')) {
  function hash_equals($str1, $str2) {
    if(strlen($str1) != strlen($str2)) {
      return false;
    } else {
      $res = $str1 ^ $str2;
      $ret = 0;
      for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
      return !$ret;
    }
  }
}

$stmt = $conn->prepare("SELECT hash FROM leaders WHERE username = ?");

$stmt->bind_param('s', $username);

$stmt->execute();

$stmt->store_result();

$stmt->bind_result($hash);
 

// let angular know if this was successful login. Have to use object since angular
// expects json so php must return json.
$resp = (object) ['message' => 'no']; // default value, if it wasn't successful

while ($stmt->fetch()) {
	// Hashing the password with its hash as the salt returns the same hash
	if (hash_equals($hash, crypt($password, $hash)) ) {
		// file_put_contents("testData.txt", "success! ", FILE_APPEND);
		$resp->message = "ok"; // value since it was successful
		$resp = json_encode($resp);
		echo $resp;
	}
        //file_put_contents("testData.txt", $resp, FILE_APPEND);
}
$stmt->free_result();

$stmt->close();

$conn->close();
// file_put_contents("testData.txt", "at end of file ", FILE_APPEND);
?>