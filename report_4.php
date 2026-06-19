<?php
include("head.php");
?>
    <div class="card">
    <div class="card-header">
        <div class="card-title">
            <h3>របាយការណ៍អតិថិជនបានបង់ដាច់</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">

            <div class="container">
                <table class="table">
                    <thead class="table-bordered table-active">
                    <tr align="center">
                        <th scope="col">ID</th>
                        <th scope="col">CusName</th>
                        <th scope="col">CusTel</th>
                        <th scope="col">CusAddress</th>
                        <!-- <th scope="col">Paid_Date</th> -->
                        <th scope="col">Photo</th>
                        <!-- <th scope="col">Total</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = 'SELECT cusid,cusname,custel,cusAddress,Photo FROM tblcustomer WHERE
          cusid NOT IN(
          SELECT cusid
           FROM view_customer_paid 
           WHERE Clear_Date is NULL
           GROUP BY cusid,cusname,custel,cusAddress,Photo
          )';
                    $sum = 0;
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $i = 0;
                        while ($row = $result->fetch_assoc()) {
                            $sum++; ?>
                            <tr align="center">
                                <td><?php echo $row['cusid'] . '<br>'; ?></td>
                                <td><?php echo $row['cusname'] . '<br>'; ?></td>
                                <td><?php echo $row['custel'] . '<br>'; ?></td>
                                <td><?php echo $row['cusAddress'] . '<br>'; ?></td>
                                <td>
                                    <img src="images/<?php echo $row['Photo']; ?>"
                                         title="<?php echo $row['cusname']; ?>" width="40px" height="40px"
                                         class="rounded-circle"/>
                                </td>

                            </tr>
                        <?php }
                    } else {
                        echo "0 results";
                    }

                    $conn->close();
                    ?>

                    </tbody>
                </table>

                Total Customer: <?php echo $sum ?>

            </div>
        </div>
    </div>
<?php
include('footer.php')
?>