<?php include ("menubar.php"); ?>
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <!-- =====================================================
                        End Navigations
    ======================================================= -->
        <!-- ================================================
                    Start Container Wrapper
            ================================================== -->
        <div class="content-wrapper">
            <div class="container-fluid">
                <!-- Title & Breadcrumbs-->
                <div class="row page-breadcrumbs">
                    <div class="col-md-12 align-self-center">
                        <h4 class="theme-cl">Product Detail</h4>
                    </div>
                </div>
                <!-- Title & Breadcrumbs-->

                <?php

                $name=$title=$details=$amount="";

                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']))
                {

                    $id=mysqli_real_escape_string($con,$_POST['id']);

                    $query="SELECT * FROM  products where  id = '$id'";
                    $result = mysqli_query($con,$query);

                    while($row = mysqli_fetch_array($result))
                    {
                        $name="$row[name]";
                        $title="$row[title]";
                        $details="$row[details]";
                        $amount="$row[amount]";
                        $product_type="$row[product_type]";
                    }
                }

                ?>

                <script>
                    function showUser() {
                        var str= document.getElementById("tvphone").value;

                        if (str == "") {
                            document.getElementById("vtv").innerHTML = "IUC cannot be empty";
                            document.getElementById("btnd").removeAttribute("disabled");
                            return;
                        } else if (str.length<9) {
                            document.getElementById("vtv").innerHTML = "IUC is too short";
                            document.getElementById("btnd").removeAttribute("disabled");
                            return;
                        } else {
                            document.getElementById("btnv").innerText="Verify....";
                            var xmlhttp = new XMLHttpRequest();
                            xmlhttp.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {
                                    document.getElementById("btnv").innerText="Verify";
                                    if(this.responseText=="fail"){
                                        document.getElementById("vtv").innerHTML = "Error validating IUC Number";
                                        document.getElementById("btnd").setAttribute("disabled", "true");
                                    }else{
                                        document.getElementById("vtv").innerHTML = this.responseText;
                                        document.getElementById("btnd").removeAttribute("disabled");
                                    }
                                }
                            };
                            xmlhttp.open("GET","verifybill.php?number="+str+"&provider=<?php echo $name; ?>",true);
                            xmlhttp.send();
                        }
                    }
                </script>
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-4">
                        <div class="box">
                            <div class="box-body">

                                <h2 class="mb-0"><?php echo $name; ?></h2> <small><?php echo $title; ?></small>
                                <hr>

                                <div class="row">

                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="white-box text-center"> <img src="assets/dist/img/pro/visa.png" class="img-responsive" alt=""> </div>
                                        <div class="white-box text-center"> <img src="assets/dist/img/pro/mastercard.png" class="img-responsive" alt=""> </div>
                                        <div class="white-box text-center"> <img src="assets/dist/img/pro/paypal.png" class="img-responsive" alt=""> </div>
                                    </div>

                                    <div class="col-lg-8 col-md-8 col-sm-6">

                                        <h4 class="box-title m-t-40">Product description</h4>
                                        <p><?php echo $details; ?></p>
                                        <?php
                                        if (!($product_type=="airtime" || $product_type=="prepaid")) {
                                            ?>
                                            <h2><small class="text-success font-20"><?php echo "NGN"; ?> <?php echo $amount; ?></small></h2>
                                        <?php } ?>

                                        <form action="clearbill.php" method="post">
                                            <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                                            <input type="hidden" name="product" value="<?php echo $title; ?>">
                                            <input type="hidden" name="productid" value="<?php echo $id; ?>">

                                            <?php
                                            if ($product_type=="airtime" || $product_type=="prepaid") {
                                                ?>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" placeholder="Enter Amount" maxlength="4" minlength="1" id="amount" name="amount" value="" autocomplete="on" size="20" min="50" max="5000" required="">
                                                </div>
                                            <?php } else {
                                                ?>
                                                <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                                            <?php } ?>

                                            <?php if ($product_type=="paytv") { ?>
                                                <span class="text-danger" id="vtv"></span>
                                                <div class="form-group">
                                                    <input class="form-control" type="tel" placeholder="Enter recipient number" maxlength="10" minlength="9" id="tvphone" name="number" value="" autocomplete="on" size="20" required="">
                                                </div>

                                                <button id="btnv" type="button" onclick="showUser()" class="btn btn-rounded btn-info"> Verify </button>
                                                <button id="btnd" type="submit" disabled class="btn btn-rounded btn-outline-info"> Continue </button>

                                            <?php } else{ ?>

                                                <div class="form-group">
                                                    <input class="form-control" type="tel" placeholder="Enter recipient number" maxlength="11" minlength="11" id="phone" name="number" value="" autocomplete="on" size="20" required="">
                                                </div>

                                                <button type="submit" class="btn btn-rounded btn-outline-info"> Continue </button>
                                            <?php } ?>
                                        </form>
                                        <br>

                                        <h3 class="box-title mrg-top-30">Key Highlights</h3>

                                        <ul class="list-icons">
                                            <li><i class="fa fa-check text-success"></i> Secure Payment Gateways</li>
                                            <li><i class="fa fa-check text-success"></i> Instant Activation</li>
                                            <li><i class="fa fa-check text-success"></i> Efficient Performance</li>
                                        </ul>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>