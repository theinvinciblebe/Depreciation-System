<?php
    include("Head.php");
?>

<div class="container">
<table class="table">
  <thead>
    <tr align="center">
      <th scope="col">ID</th>
      <th scope="col">CusName</th>
      <th scope="col">CusTel</th>
      <th scope="col">CusAddress</th>
      <th scope="col">Photo</th>
    </tr>
  </thead>
  <tbody>
    <?php
      // $sql = 'SELECT tblcustomer.cusid,tblcustomer.cusname,tblcustomer.custel,tblcustomer.cusAddress,tblcustomer.Photo,
      //         sum(Principal) as Total_Principal FROM tblcustomer,tbldepreciation WHERE tblcustomer.cusid = tbldepreciation.cusid and
      // cusid NOT IN(
      // SELECT cusid
      // FROM view_customer_paid 
      // WHERE Clear_Date is NULL
      // GROUP BY cusid,cusname,custel,cusAddress,Photo
      // )GROUP BY cusid';

      // $sql = 'SELECT tblcustomer.cusid ,
      // tblcustomer.cusname ,
      // tblcustomer.cusAddress ,
      // tblcustomer.custel ,
      // tblcustomer.Photo , paid_date,principal
      // from tblcustomer,tbldepreciation
      // where tblcustomer.cusid = tbldepreciation.cusid
      // GROUP BY cusid';

      $sql='SELECT cusid, cusname, custel,cusAddress, Photo
      FROM view_customer_paid
      WHERE clear_by_userid IS NULL
      GROUP BY cusid, cusname, custel,cusAddress, Photo';

      $sum = 0;
      $result = $conn -> query($sql);
      if($result->num_rows>0){
        $i = 0;
        while($row = $result -> fetch_assoc()){
            $sum++;?>
          <tr align="center">
          <td><?php echo $row['cusid'].'<br>'; ?></td>
          <td><?php echo $row['cusname'].'<br>'; ?></td>
          <td><?php echo $row['custel'].'<br>'; ?></td>
          <td><?php echo $row['cusAddress'].'<br>'; ?></td>
          <td>
            <img src="images/<?php echo $row['Photo'];?>"  title="<?php echo $row['cusname']; ?>" width="40px" height="40px" class="rounded-circle"/>
          <?php }
      }else{
        echo "0 results";
      }

      $conn->close();
    ?>
    
  </tbody>
</table>

Total Customer: <?php echo $sum?>
<?php 
    include('footer.php')
?>