<?php

// $con="MySQL";

$severname = "localhost";
$username = "root";
$password = "";
$dbname = "db_depreciation";

// change date to default date
date_default_timezone_set("Asia/Bangkok");
//session_start();

//Create cnnection
$conn = mysqli_connect($severname, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    //echo "Connected Successfully";
}
