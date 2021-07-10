<?php //include ("include/session.php"); ?>
<?php include ("menubar.php"); ?>

<?php

$query="SELECT * FROM  wallet WHERE username='".$loggedin_session."'";
$result = mysqli_query($con,$query);
$count = mysqli_num_rows($result);
$row=mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

//if($count == 1) { ?>
<!--    <script>window.location.replace("404.php");</script>-->
<?php //} ?>

<?php
$query="SELECT * FROM  wallet WHERE username='".$loggedin_session."'";
$result = mysqli_query($con,$query);

$row=mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
while($row = mysqli_fetch_array($result))
{
    $balance=$row["balance"];
}
?>
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">

<div class="view_profile_wrapper_top float_left">

    <div class="row">

        <div class="col-md-12 col-lg-12 col-sm-12 col-12">
            <div class="sv_heading_wraper">

                <h3>Fund wallet</h3>

            </div>
            <div class="userdet uderid">
<!--                <h3>Id: --><?php //echo $loggedin_id; ?><!--</h3>-->
                <h3>Wallet Balance: Naira <?php echo number_format(intval($balance *1),2);?></h3>
            </div>
        </div>
        <center>
            <div class="content-wrapper">
                <div class="container-fluid">

                    <!-- Title & Breadcrumbs-->
                    <div class="row page-breadcrumbs">
                        <div class="col-md-12 align-self-center">
                            <h4 class="theme-cl"></h4>
                        </div>
                    </div>
                    <!-- Title & Breadcrumbs-->


                    <!-- row -->
                    <div class="row">
                        <!-- col-md-12 -->
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Enter Amount</h4>
                                </div>
                                <div class="card-body">


                                    <label for="basic-url">Please Enter Amount To Deposit</label>

                                    <form id="paymentForm">
                                        <div class="form-group">
                                            <!--                                        <input type="hidden" name="user_email" value="--><?php //echo $email; ?><!--">-->
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="email"></label>
                                                    <input type="hidden"  id="email-address" value="<?php echo $user_check; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="amount">Amount</label>
                                                    <input type="tel" id="amount" required />
                                                </div>
                                                <!--                                        <input type="hidden" name="cartid" value="--><?php //echo $cartid; ?><!--">-->
                                                <div class="form-submit">
                                                    <button type="submit" onclick="payWithPaystack()"> Pay Now </button>
                                                </div>
                                                <!--                                            <div class="form-submit>"-->
                                                <!--                                            <script src="https://checkout.flutterwave.com/v3.js"></script>-->
                                                <!--                                            <button type="button" onClick="makePayment()">Pay Now</button>-->
                                                <!--                                        </div>-->
                                    </form>
                                    <script src="https://js.paystack.co/v1/inline.js"></script>


                                    <hr>
                                    <label>Methods Accepted For Online Deposits</label>
                                    <div class="row">

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">

                                                <div class="payment-box">

                                                    <div class="padd-10">
                                                        <img src="assets/dist/img/paypal.png" class="fl-left width-30" alt="" />
                                                        <h5 class="mb-0">Paypal</h5>
                                                        <small><a href="#" class="__cf_email__" data-cfemail="badedbd4d3dfd69494faddd7dbd3">Accepted</a></small>
                                                    </div>

                                                    <div class="pay-box-footer bt-1">
                                                        <a href="#" data-toggle="tooltip" data-original-title="Remove" class="theme-cl font-13 fl-right">Available</a>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">

                                                <div class="payment-box">

                                                    <div class="padd-10">
                                                        <img src="assets/dist/img/visa.png" class="fl-left width-30" alt="" />
                                                        <h5 class="mb-0">Visa Card</h5>
                                                        <small>Accepted</small>
                                                    </div>

                                                    <div class="pay-box-footer bt-1">
                                                        <a href="#" data-toggle="tooltip" data-original-title="Remove" class="theme-cl font-13 fl-right">Available</a>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">

                                                <div class="payment-box">

                                                    <div class="padd-10">
                                                        <img src="assets/dist/img/mastercard.png" class="fl-left width-30" alt="" />
                                                        <h5 class="mb-0">Master Card</h5>
                                                        <small>Accepted</small>
                                                    </div>

                                                    <div class="pay-box-footer bt-1">
                                                        <a href="#" data-toggle="tooltip" data-original-title="Remove" class="theme-cl font-13 fl-right">Available</a>
                                                    </div>
        </center>

    </div>

</div>
</div>

</div>

</div>
<!-- col-md-12 -->

</div>
</div>
</div>
<!-- /.col-md-12 -->

</div>
<!-- /.row -->

</div>
<script>
    const paymentForm = document.getElementById('paymentForm');
    paymentForm.addEventListener("submit", payWithPaystack, false);
    function payWithPaystack(e) {
        e.preventDefault();
        let handler = PaystackPop.setup({
            key: 'pk_test_17fd09d2f1b858a21859595153d9770573a7c996', // Replace with your public key
            email: document.getElementById("email-address").value,
            amount: document.getElementById("amount").value * 100,
            ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
// label: "Optional string that replaces customer email"
            onClose: function(){
                alert('Window closed.');
            },
            callback: function(response){
                let message = 'Payment complete! Reference: ' + response.reference;
                alert(message);

                window.location = 'transaction.php?reference='+response.reference;

            }
        });
        handler.openIframe();
    }
</script>
<script>
    const API_publicKey = "<?php echo $rave; ?>";

    function payWithRave() {
        var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: "<?php echo $email; ?>",
            amount: "<?php echo $amount; ?>",
            customer_phone: "<?php echo $phone; ?>",
            currency: "<?php echo $cur; ?>",
            txref: "rave-123456",
            meta: [{
                metaname: "flightID",
                metavalue: "AP1234"
            }],
            onclose: function() {},
            callback: function(response) {
                var txref = response.tx.txRef; // collect txRef returned and pass to a 					server page to complete status check.
                console.log("This is the response returned after a charge", response);
                if (
                    response.tx.chargeResponseCode == "00" ||
                    response.tx.chargeResponseCode == "0"
                ) {
                    window.location='paymentdeposit.php?amount=' + <?php echo $amount; ?> + '&refid='+<?php echo $scode; ?>+'&method=Rave';

                } else {
                    alert("Hello! Payment Not Successfull!");
                }

                x.close(); // use this to close the modal immediately after payment.
            }
        });
    }
</script>
