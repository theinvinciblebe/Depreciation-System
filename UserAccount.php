<?php
    include("Head.php");
    // if($_SESSION["uid"]==""){
    //     // header('location:login.php');

    // }
?>
<!-- //container =align left and right =center// -->
<div class="container">
    <table class="table">
    <thead>
        <tr class="table-info" align="center">
        <th scope="col">
        <a href="useraccount-add.php">
            <button type="button" class="btn btn-outline-info">
            <i class="bi bi-patch-plus"></i>
            </button>
        </a>
        </th>
        <th scope="col">UserID</th>
        <th scope="col">UserName</th>
        <th scope="col">Password</th>
        <th scope="col">Photo</th>
        <th scope="col">Action</th>

        </tr>
    </thead>
    <tbody>

        <?php
            $sql="SELECT * FROM tblusername";
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
                    <th scope="row"><?php echo $i++; ?></th>
                    <td><?php echo $row['username']; ?> </td>
                    <td>*****</td>
                    <td>
                        <img src="images/<?php echo $row['Photo']; ?>" height="50" widgth="50" class="rounded-circle" alt="">
                    </td>
                    <td>
                        <!-- <a href="#">Edit</a>
                        | <a href="#">Del</a> -->
                        <a href="useraccount-edit.php?param=<?php echo $row['userid']; ?>" style="text-decoration: none:"><button type="button" class="btn btn-outline-info"><i class="bi bi-pencil-square">Edit</i></button></a>
                        <a href="useraccount-delete.php?param=<?php echo $row['userid']; ?>"><button type="button" class="btn btn-outline-danger"><i class="bi bi-trash">Del</i></button></a>

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
    </table></div>


<?php
    include("footer.php");
?>