<?php
include_once ("include/database.php");
include_once ("menubar.php");

ini_set( 'display_errors', 0 );
error_reporting( E_ALL );

// Inialize session
// Check, if username session is NOT set then this page will jump to login page
?>

<?php
$query = "select * from users where username = '".$loggedin_session."' and allowpurchase=0";
$result = mysqli_query($con,$query);
$count = mysqli_num_rows($result);

if($count == 1) { ?>
    <script>window.location.replace("404.php");</script>
<?php } ?>
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-fluid">

                <!-- Title & Breadcrumbs-->
                <div class="row page-breadcrumbs">
                    <div class="col-md-5 align-self-center">
                        <h4 class="theme-cl">Available Products</h4>
                    </div>

                    <div class="col-md-7 text-right">

                        <div class="btn-group mr-lg-2">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Short By
                            </button>
                            <div class="dropdown-menu pull-right animated flipInX">
                                <a href="#">Prices</a>
                                <a href="#">Ascending</a>
                                <a href="#">Descending</a>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Title & Breadcrumbs-->

                <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST' && ($status!=""))
                {
                    print $errormsg;
                }
                ?>

                <!-- All Product List -->
                <div class="row">

                    <?php

                    $query="SELECT * FROM products";
                    $result = mysqli_query($con,$query);
                    while($row = mysqli_fetch_array($result))
                    {
                        $id="$row[id]";
                        ?>

                        <!-- Single Product -->
                        <div class="col-md-2 col-sm-3 mb-2 col-xs-2">
                            <div class="product-wrap">
                                <div class="product-box">
                                    <div class="product-thumb">

                                        <div class="product-pic">
                                            <div class="uc_pic_box">
                                                <img src="assets/dist/img/<?php echo "$row[logo]"; ?>" alt="Product logo"></div>
                                        </div>

                                        <div class="product-detailed">
                                            <span class="product-uc-price"><?php echo "$row[name]"; $id=$row[id]; ?></span>

                                            <?php if($row["product_type"]=="data" || $row["product_type"]=="tv"){ ?>
                                                <span class="product-uc-price"><?php echo "NGN". "$row[amount]"; ?></span>
                                            <?php } else if($row["product_type"]=="prepaid"){ ?>
                                                <span class="product-uc-price">Prepaid</span>

                                            <?php } else{ ?>
                                                <span class="" style="font-style: italic;"><?php echo "$row[details]"; ?></span>

                                            <?php } ?>

                                            <form action="previewproduct.php" method="post">
                                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                <?php if($row["status"]==1){ ?>
                                                    <button type="submit" class="btn btn-outline-primary btn-rounded"><i class="fa fa-check"></i> Continue</button>
                                                <?php }else{ ?>
                                                    <button type="button" class="btn btn-danger btn-rounded"><i class="fa fa-times"></i> Not Available</button>
                                                <?php } ?>
                                            </form>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!-- End All Product List -->
            </div>