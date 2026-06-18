<?php
    include("Head.php");
    // if($_SESSION["uid"]==){
    //     header('location:login.php');

    // }
?>
<!-- //container =align left and right =center// -->
<div class="">
    <table class="table">
    <thead>
        <tr class="table-info" align="center">
        <th scope="col">cusid</th>
        <th scope="col">cusname</th>
        <th scope="col">custel</th>
        <th scope="col">IDCard</th>
        <th scope="col">cusAddress</th>
        <th scope="col">Photo</th>

        </tr>
    </thead>
    <tbody>

        <?php
            // $sql="SELECT cusid,cusname,custel,IDCard,cusAddress,Photo FROM tblcustomer ORDER BY cusname ASC";
            $sql="SELECT * FROM tblcustomer ;";
            $result=$conn->query($sql);
            if($result->num_rows > 0){
                //output data of each row
                $i=1;
                $sum=$result->num_rows;
                while ($row =$result->fetch_assoc()){

                    // echo 'ID: ' . $row['userid'];
                    // echo ' Name: ' . $row['username']. '<br>';
                ?>
                    <tr class="table-light" align="center">
                    <td><?php echo $i++ ?> </td>
                    <td>
                            <?php echo $row['cusname'] ; ?>
                    </td>   
                    <td>
                            <?php echo $row['custel'] ; ?>
                    </td>   
                    <td>
                            <?php echo $row['IDCard'] ; ?>
                    </td>   
                    <td>
                            <?php echo $row['cusAddress'] ; ?>
                    </td>   
                    <td>
                    <img src="images/<?php echo $row['Photo']; ?>" height="50" widgth="50" class="rounded-circle" alt="">
                    </td>   

                <?php
                }
            }else{
                echo "0 results";
            }
            $conn->close();
        ?>
            <tr class="table-info">
                <td></td>
                <td>Total : <?php echo $sum ?></td>
            </tr>
    </tbody>
    </table></div>


<?php
    include("footer.php");
?>