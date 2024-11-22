<?php
    include("head.php");
    $Depreid = $_GET['Depreid'];
    $cusid = $_GET['cusid'];
    
    $date = date("Y-m-d H:i");
    
    $sql = "UPDATE tbldepreciation SET 
    Clear_Date ='".$date."' ,  clear_by_userid = -2 WHERE Depreid = $Depreid";

    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";

        header('Location: depreciation.php?param='.$cusid);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
 
?>