<?php
    include("head.php");
    
    if(isset($_POST['btnYes'])){

        session_destroy();
    
        header('Location: index.php');
    }
?>
 <div class="container">

        <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            Do you want to logout?
            <button type="submit" name="btnYes" 
                class="btn btn-primary">Yes
            </button>
            <a class="btn btn-primary" href="index1.php" role="button">No</a>


            
                <!-- <button class="btn btn-success" type="submit">Button</button> -->
                <!-- <input class="btn btn-primary" type="reset" value="No"> -->

            <!-- <a href="index.php"
				<button class="login100-form-btn"  >
					Back
			    </button>
			</a> -->
    </a>
        </div>
        
</form>
    </div>