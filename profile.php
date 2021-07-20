<?php
include_once ("include/database.php");
include_once ("menubar.php");

$query="SELECT * FROM  users WHERE username='".$_SESSION['username']."'";
$result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result))
{
    $username=$row["username"];
    $name=$row["name"];
    $date=$row["date"];
    $email=$row["email"];
    $phone=$row["phone"];
}
// Inialize session
// Check, if username session is NOT set then this page will jump to login page
?>
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
                        <?php
                        if($_SERVER['REQUEST_METHOD'] == 'POST' ){

                            $status="OK";
// Collect the data from post method of form submission //
                            $name=mysqli_real_escape_string($con,$_POST['name']);
//                            $contr=mysqli_real_escape_string($con,$_POST['country']);
                            $phone=mysqli_real_escape_string($con,$_POST['phone']);
                            $email=mysqli_real_escape_string($con,$_POST['email']);
//                            $gender=mysqli_real_escape_string($con,$_POST['gender']);
//                            $address=mysqli_real_escape_string($con,$_POST['address']);
//collection ends

                            $query3=mysqli_query($con,"update users set `name`='$name', phone='$phone',email='$email' where username = '".$_SESSION['username']."'");

                            echo $message = "Profile Update Successfully";
                            print "
				<script language='javascript'>
				 let message = 'Profile Update Successfully: ';
                                    alert(message);
window.location = 'dashboard.php';
</script>
";
                        }
                        ?>
<div class="content-wrapper">
    <div class="container-fluid">

        <!-- Title & Breadcrumbs-->
        <div class="row page-breadcrumbs">
            <div class="col-md-12 align-self-center">
<!--                <h4 class="theme-cl">Profile</h4>-->
<!--                <img src="images/profile/pic1.png" width="20" alt=""/>-->

            </div>
        </div>
                        <form action="profile.php" method="POST">

                            <?php if(isset($error) != NULL):?>
                                <p><?php echo $error; ?></p>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-xl-12">
                                    <h5 class="form-title">Basic Information</h5>
                                </div>
                                <div class="form-group col-xl-12">
                                    <div class="media align-items-center mb-3">
                                        <div class="media-body">
                                            <h5 class="mb-0"><?php echo $name; ?></h5>
<!--                                            <p>Max file size is 20mb</p>-->
<!--                                            <div class="jstinput">	<a href="javascript:void(0);" class="avatar-view-btn browsephoto openfile">Browse</a>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xl-6">
                                    <label class="mr-sm-2">Name</label>
                                    <input class="form-control" type="text" value="<?php echo $name; ?>" name="name" placeholder="" required>
                                </div>
                                <div class="form-group col-xl-6">
                                    <label class="mr-sm-2">Email</label>
                                    <input class="form-control" type="email" value="<?php echo $email; ?>" name="email" placeholder="" required>
                                </div>
                                <div class="form-group col-xl-6">
                                    <label class="mr-sm-2">Mobile Number</label>
                                    <input class="form-control no_only" type="text" value="<?php echo $phone; ?>" name="phone" placeholder="" required>
                                </div>
                                <div class="form-group col-xl-6">
                                    <label class="mr-sm-2">Username</label>
                                    <input type="text" class="form-control datepicker" type="text" name="dob" value="<?php echo $username; ?>"  readonly>
                                </div>
                                <!--    <div class="col-xl-12">-->
                                <!--        <h5 class="form-title">Address</h5>-->
                                <!--    </div>-->
                                <!--    <div class="form-group col-xl-12">-->
                                <!--        <label class="mr-sm-2">Address</label>-->
                                <!--        <input type="text" class="form-control" name="address" value="">-->
                                <!--    </div>-->
                                <div class="form-group col-xl-12">
                                    <button name="form_submit" id="form_submit" class="btn btn-primary pl-5 pr-5" type="submit">Update</button>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
