<?php 
    include("head.php");

?>


<div class="content-wrapper">
  <div class="container">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Customername</th>
          <th scope="col">Tel</th>
          <th scope="col">Address</th>
          <th scope="col">DatePaid</th>
          <th scope="col">Photo</th>
          <th scope="col">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * , cast(Principal+interest_month as decimal(10,2)) as Total from view_customer_paid
          where clear_by_userid is NULL
          and Paid_Date < now()
          ";
          $result = $conn -> query($sql);
          if($result->num_rows>0){
            while($row = $result -> fetch_assoc()){
              ?><tr>
              <td><?php echo $row['cusname'].'<br>'; ?></td>
              <td><?php echo $row['custel'].'<br>'; ?></td>
              <td><?php echo $row['cusAddress'].'<br>'; ?></td>
              <td><?php echo '$'.$row['Paid_Date'].'<br>'; ?></td>
              <td>
                <img src="images/<?php echo $row['Photo'];?>" width="40px" height="40px" class="rounded-circle"/>
              </td>
              <td><?php echo'$'.$row['Total'].'<br>'; ?></td>
              </tr>
              <?php }
          }else{
            echo "No data";
          }
          $conn->close();
        ?>
      </tbody>
    </table>
  </div>  
</div>

<?php 
    include("footer.php");
?>