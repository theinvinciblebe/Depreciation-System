<?php
    include("head.php");

    //getcwd() : get current directory
if(isset($_POST['btnsave'])){

    // $cusid = date('YmdHms');
    $autoid = date('YmdHms');
    $txtcusname= $_POST['txtcusname'];
    $txttel= $_POST['txttel'];
    $txtIDCard= $_POST['txtIDCard'];
    $txtcusaddress= $_POST['txtcusaddress'];
    $txtPname= $_POST['txtPname'];
    $txtPprice= $_POST['txtPprice'];
    $txtInterest= $_POST['txtInterest'];
    $txtDurartion= $_POST['txtDuration'];
    $Createdate = date('YmdHms');
    $txtUserid= $_POST['txtuserid'];
    // $autoid = date('YmdHms');
 
    
    $uploaddir = getcwd() . '/images/';
    
    $uploadfile = $uploaddir . basename($_FILES['fileToUpload']['name']);
    $imgname = $_FILES['fileToUpload']['name'];



    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile)) {
        echo "File is valid, and was successfully uploaded.\n";
    } else {
        echo "Possible file upload attack!\n";
    }

    $sql = "INSERT INTO tblcustomer ( cusid, cusname,  custel, IDCard, cusAddress, Photo, ProductName, ProductPrice, Interest, Duration, Create_Date, Userid)
    VALUES ($autoid,'".$txtcusname."', '".$txttel."', '".$txtIDCard."', '".$txtcusaddress."', '".$imgname."',
    '".$txtPname."', '".$txtPprice."', '".$txtInterest."', '".$txtDurartion."', '".$Createdate."', '".$txtUserid."')";

    $principal=$txtPprice/$txtDurartion;
    $interest_month=$txtPprice * $txtInterest /100;
    $date=date("Y-m-d");
    for($i=1;$i<=$txtDurartion;$i++){

        $post_date=date('Y-m-d',strtotime("$i months", strtotime($date)));

        $sqlDepre = "INSERT INTO tbldepreciation 
            (cusid,Principal,interest_month, paid_Date,Clear_date,clear_by_userid)
            Value ($autoid,$principal,$interest_month,'".$post_date."',null,null)";
        $conn->query($sqlDepre);
    }


    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";

        header('Location: customer.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}

?>


    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Customername</label>
                <input type="text" name="txtcusname" class="form-control" 
                    placeholder="Input customername">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Customer Tel</label>
                <input type="text" name="txttel" class="form-control" 
                    placeholder="Input Customer Tel">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Customer ID Card</label>
                <input type="text" name="txtIDCard" class="form-control" 
                    placeholder="Input customer ID Card">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Customer Address</label>
                <input type="text" name="txtcusaddress" class="form-control" 
                    placeholder="Input Customer Address">
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Choose photo</label>
                <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
            
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Product Name</label>
                <input type="text" name="txtPname" class="form-control" 
                    placeholder="Input ProductName">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Product Price</label>
                <input type="text" name="txtPprice" class="form-control" 
                    placeholder="Input Product Price">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Interest</label>
                <input type="text" name="txtInterest" class="form-control" 
                    placeholder="Input Product Interest">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Duration</label>
                <input type="text" name="txtDuration" class="form-control" 
                    placeholder="Input Duration">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">User ID</label>
                <input type="text" name="txtuserid" class="form-control" 
                    placeholder="Input UserID">
            </div>

            <div class="mb-3">
                <button type="submit" name="btnsave" class="btn btn-primary">Save</button>

            </div>
        </form>
    </div>

<?php
    include("footer.php");
?>