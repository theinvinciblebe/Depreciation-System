<?php
    include("Head.php");
   
?>
<!-- //container =align left and right =center// -->
<div class="container">
    <table class="table">
    <thead>
        <tr class="table-info" align="center"> 
        <th scope="col">
        <a href="#">
            <button type="button" class="btn btn-outline-success">
            <i class="bi bi-patch-plus"></i>
            </button>
        </a>
        </th>
        <th scope="col">No</th>
        <th scope="col">Date</th>
        <th scope="col">Principal</th>
        <th scope="col">interest</th>
        <th scope="col">Total</th>
        <th scope="col">Status</th>
        <th scope="col">Action</th>


        </tr>
    </thead>
    <tbody>

        <?php
            $totalPending=0;
            $totalPaid=0;
            $param=0;
            if(isset($_GET['param'])){
                $param =$_GET['param'];
            }

            $sql="SELECT *,if(clear_by_userid,'Paid','Pending') as statusPaid,
                            if(clear_by_userid,'text-success','text-danger') as Textcolor 
                            FROM tbldepreciation WHERE cusid= $param";
            // $result=$conn->query($sql);

            // $sql="SELECT sum(case when clear_by_userid then interest_month+principal end) as totalPaid 
            //             from tblDepreciation where cusid=$param";
            // $result=$conn->query($sql);

            // $sql="SELECT sum(case when clear_by_userid is null then principal end) as totalPending 
            //             from tblDepreciation where cusid=$param";
            $result=$conn->query($sql);
            if($result->num_rows > 0){
                //output data of each row
                $i=1;
                while ($row =$result->fetch_assoc()){
                    // echo 'ID: ' . $row['userid'];
                    // echo ' Name: ' . $row['username']. '<br>';
                ?>
                    <tr class="table-light" align="center">
                    <th scope="row">
                    <th scope="row"><?php echo $i++; ?></th>
                    <td><?php echo $row['Paid_Date']; ?> </td>
                    <td><?php echo $row['Principal']; ?></td>
                    <td><?php echo $row['Interest_Month']; ?> </td>
                    <td><?php echo $row['Principal'] + $row['Interest_Month']; ?></td>
                    <td>
                        <?php 

                          if(is_null($row['clear_by_userid'])){
                            //pending
                            $totalPending+= $row['Principal'];
          
                          }else{
                            //paid
                            $totalPaid+= $row['Principal']+$row['Interest_Month'];
          
                          }

                            echo "<span class='".$row['Textcolor']."'>".$row['statusPaid']."</span>";
                            // echo $row['statusPaid'];

                            // if(is_null($row['clear_by_userid'])){
                            //     echo "<span class='text-danger'>Pending</span>";
                            //     // echo "<p class='text-danger'>Pending</p>";
                            // }else{
                            //     echo "<span class='text-success'>Paid</span>";
                            // }
                            
                        ?>
                    </td>



                    <td>
                        <!-- & connect parameter -->
                        <?php 
    	                if($row['statusPaid']!="Paid"){
                            ?>
                            <a href="clearAmount.php?Depreid=<?php echo $row['Depreid'] ; ?>&cusid=<?php echo $row['cusid'] ; ?>">
                            <button type="button" class="btn btn-danger">
                                <i class="bi bi-trash3-fill"></i>
                                Clear
                            </button>
                            </a>
                        <?php
                        }
                        ?>
                        <!-- <a href="#">Edit</a>
                        | <a href="#">Del</a> -->
                        <!-- <a href="#" style="text-decoration: none:"><button type="button" class="btn btn-outline-info"><i class="bi bi-pencil-square">Edit</i></button></a>
                        <a href="#"><button type="button" class="btn btn-outline-danger"><i class="bi bi-trash">Del</i></button></a> -->

                        <!-- | <a href="#">Update</a>
                        | <a href="#">Add</a> -->
                    </td>

                <?php
                }
            }else{
                echo "0 results";
            }
            $conn->close();
        ?>

                    <!-- </tr>
                    <tr class="table-info" align="center">
                        <th scope="row">Total Paid : </th>
                        if(isset($row['statusPaid'])){
                
                        }
                    </tr>
                    <tr class="table-info" align="center">
                        <th>Total Pending : </th>
                    </tr>
 -->
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
    <table class="table table-info" >
        <tr >
            <th scope="col">Total Paid :</th>
            <th scope="col">$<?php echo $totalPaid ?></th>
        </tr>
        <tr >
            <th scope="col" >Total Pending :</th>
            <th scope="col">$<?php echo $totalPending ?></th>
        </tr>
    </table>
    </table></div>


<?php
    include("footer.php");
?>