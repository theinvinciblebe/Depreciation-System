<?php
    include("head.php");

    //getcwd() : get current directory
if(isset($_POST['btnsave'])){
    $param=$_GET['param'];
    $sql="DELETE FROM tblusername WHERE userid=$param";


    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";

        header('Location: UserAccount.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
    
?>


    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="formFile" class="form-label">Are you sure you want to delete it?</label>

            
            </div>

            <div class="mb-3">
                <button type="submit" name="btnsave" class="btn btn-primary">Yes</button>

            </div>
        </form>
    </div>

<?php
    include("footer.php");
?>