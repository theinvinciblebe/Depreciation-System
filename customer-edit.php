<?php
    include("head.php");

    $param=$_GET['param'];
    $sqlGet="SELECT * FROM tblcustomer WHERE cusid=$param" ;
    $result=$conn->query($sqlGet);
    if($result->num_rows > 0){
        while ($row =$result->fetch_assoc()){
            $Getcusname=$row['cusname'] ;
            $Getcutel=$row['custel'] ;
            $GetIDCard=$row['IDCard'] ;
            $GetcusAddress=$row['cusAddress'] ;
            $GetcusPhoto=$row['Photo'];
            $GetPName=$row['ProductName'];
            $GetcPPrice=$row['ProductPrice'];
            $GetcInterest=$row['Interest'] ;
            $GetcDuration=$row['Duration'] ;
            $GetCreate_Date=$row['Create_Date'] ;
            $GetUserid=$row['Userid'] ;
        }
    }else{
        header('Location: customer.php');
    }
    //getcwd() : get current directory
if(isset($_POST['btnsave'])){

    // $cusid = date('YmdHms');
    // $autoid = date('YmdHms');
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

    if($imgname == ""){
        $imgname=$GetcusPhoto;
    }

    $sql = "UPDATE tblcustomer SET cusname='".$txtcusname."' ,
                                    custel='".$txttel."' ,
                                    IDCard='".$txtIDCard ."',
                                    cusAddress='".$txtcusaddress."' ,
                                    Photo='".$imgname."' ,
                                    ProductName='".$txtPname ."',
                                    ProductPrice='".$txtPprice."' ,
                                    Interest='".$txtInterest."' ,
                                    Duration='".$txtDurartion ."',
                                    Create_Date='".$Createdate ."',
                                    Userid='".$txtuserid ."'
            WHERE cusid=$param ";

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
                    placeholder="Input customername" value="<?php echo $Getcusname ; ?>">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Customer Tel</label>
                <input type="text" name="txttel" class="form-control" 
                    placeholder="Input Customer Tel" value="<?php echo $Getcutel ; ?>">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Customer ID Card</label>
                <input type="text" name="txtIDCard" class="form-control" 
                    placeholder="Input customer ID Card" value="<?php echo $GetIDCard ; ?>">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Customer Address</label>
                <input type="text" name="txtcusaddress" class="form-control" 
                    placeholder="Input Customer Address" value="<?php echo $GetcusAddress ; ?>">
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Choose photo</label>
                <br>
                <img src="images/<?php echo $GetcusPhoto; ?>" height ="100" />
                <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
            
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Product Name</label>
                <input type="text" name="txtPname" class="form-control" 
                    placeholder="Input ProductName" value="<?php echo $GetPName ; ?>">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Product Price</label>
                <input type="text" name="txtPprice" class="form-control" 
                    placeholder="Input Product Price" value="<?php echo $GetcPPrice ; ?>">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Interest</label>
                <input type="text" name="txtInterest" class="form-control" 
                    placeholder="Input Product Interest" value="<?php echo $GetcInterest ; ?>">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Duration</label>
                <input type="text" name="txtDuration" class="form-control" 
                    placeholder="Input Duration" value="<?php echo $GetcDuration ; ?>">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">User ID</label>
                <input type="text" name="txtuserid" class="form-control" 
                    placeholder="Input UserID" value="<?php echo $GetUserid ; ?>">
            </div>

            <div class="mb-3">
                <button type="submit" name="btnsave" class="btn btn-primary">Save</button>

            </div>
        </form>
    </div>

<?php
    include("footer.php");
?>