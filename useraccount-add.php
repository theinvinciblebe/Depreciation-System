<?php
    include("head.php");

    //getcwd() : get current directory
if(isset($_POST['btnsave'])){

    $txtname= $_POST['txtUsername'];
    $txtpass= $_POST['txtpassword'];

    $autoid = date('YmdHms');
 
    
    $uploaddir = getcwd() . '/images/';
    
    $uploadfile = $uploaddir . basename($_FILES['fileToUpload']['name']);
    $imgname = $_FILES['fileToUpload']['name'];

    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile)) {
        echo "File is valid, and was successfully uploaded.\n";
    } else {
        echo "Possible file upload attack!\n";
    }

    $sql = "INSERT INTO tblusername ( userid, username,  userpassword,  Photo)
    VALUES ($autoid,'".$txtname."', '".$txtpass."', '".$imgname."')";

    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";

        header('Location: useraccount.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
    
?>


    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Username</label>
                <input type="text" name="txtUsername" class="form-control" 
                    placeholder="Input username">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Password</label>
                <input type="text" name="txtpassword" class="form-control" 
                    placeholder="Input Password">
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Choose photo</label>
                <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
            
            </div>

            <div class="mb-3">
                <button type="submit" name="btnsave" class="btn btn-primary">Save</button>

            </div>
        </form>
    </div>

<?php
    include("footer.php");
?>