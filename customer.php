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
            <th scope="col">
            <a href="customer-add.php">
                <button type="button" class="btn btn-outline-success">
                <i class="bi bi-patch-plus"></i>
                </button>
            </a>
            </th>
            <th scope="col">cusid</th>
            <th scope="col">cusname</th>
            <th scope="col">custel</th>
            <!-- <th scope="col">IDCard</th>
            <th scope="col">cusAddress</th> -->
            <th scope="col">Photo</th>
            <th scope="col">ProductName</th>
            <th scope="col">ProductPrice</th>
            <!-- <th scope="col">Interest</th>
            <th scope="col">Duration</th>
            <th scope="col">Create_Date</th>
            <th scope="col">Userid</th> -->
            <th scope="col">Action</th>

            </tr>
        </thead>
    <tbody>

        <?php
            $sql="SELECT * FROM tblcustomer";
            $result=$conn->query($sql);
            if($result->num_rows > 0){
                //output data of each row
                $i=1;
                while ($row =$result->fetch_assoc()){
                    // echo 'ID: ' . $row['userid'];
                    // echo ' Name: ' . $row['username']. '<br>';
                ?>
                    <tr class="table-light" align="center">
                    <th scope="row"></th>
                    <td><?php echo $i++ ?> </td>
                    <td>
                        <a href="depreciation.php?param=<?php echo $row['cusid'] ; ?>">
                            <?php echo $row['cusname'] ; ?>
                        </a>
                    </td>   

                    <td><?php echo $row['custel']; ?></td>
                    <td>
                    <img src="images/<?php echo $row['Photo']; ?>" height="50" widgth="50" class="rounded-circle" alt="">
                    </td>
                    <td><?php echo $row['ProductName']; ?></td>
                    <td><?php echo $row['ProductPrice']; ?></td>
                    <!-- <td>
                        <img src="images/<?php echo $row['photo']; ?>" height="50" widgth="50" class="rounded-circle" alt="">
                    </td> -->
                    <td>
                        <!-- <a href="#">Edit</a>
                        | <a href="#">Del</a> -->
                        <a href="customer-edit.php?param=<?php echo $row['cusid']; ?>" style="text-decoration: none:"><button type="button" class="btn btn-outline-info"><i class="bi bi-pencil-square">Edit</i></button></a>
                        <a href="customer-delete.php?param=<?php echo $row['cusid']; ?>"><button type="button" class="btn btn-outline-danger"><i class="bi bi-trash">Del</i></button></a>

                        <!-- | <a href="#">Update</a>
                        | <a href="#">Add</a> -->
                    </td>
                    </tr>
                <?php
                }
            }else{
                echo "0 results";
            }
            $conn->close();
        ?>


        <!-- <tr>
        <th scope="row">2</th>
        <td>Ching</td>
        <td>abc123</td>
        <td>
            <img src="images/excel/7.jpeg" height="50" widgth="50" class="rounded-circle" alt="">
        </td>
        </tr>
        <tr>
        <th scope="row">3</th>
        <td colspan="1">Be</td>
        <td>abc123</td>
        <td>
        <img src="images/excel/12.jpeg" height="50" widgth="50" class="rounded-circle" alt="">
        </td>
        </tr> -->
    </tbody>
    </table>
</div>


<?php
    include("footer.php");
?>