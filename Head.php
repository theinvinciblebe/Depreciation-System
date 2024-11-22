<?php

    // $con="MySQL";

    $severname="localhost";
    $username="root";
    $password="";
    $dbname="db_depreciation";

    // change date to default date
    date_default_timezone_set("Asia/Bangkok");
    session_start();
    
    //Create cnnection
    $conn=mysqli_connect($severname,$username,$password,$dbname);

    // Check connection
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }else{
        //echo "Connected Successfully";
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php
            include("Nevbar.php");
    ?>
    

