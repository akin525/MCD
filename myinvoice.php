<?php
include_once ("include/database.php");
include_once ("menubar.php");
// Inialize session
// Check, if username session is NOT set then this page will jump to login page
?>
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">

        <div class="content-wrapper">
            <div class="container-fluid">

                <!-- Title & Breadcrumbs-->
                <div class="row page-breadcrumbs">
                    <div class="col-md-12 align-self-center">
                        <h4 class="theme-cl">Active Payment Invoice</h4>
                    </div>
                </div>
                <!-- Title & Breadcrumbs-->



                <!-- row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="flex-box padd-10 bb-1">
                                <h4 class="mb-0">Payment Lists</h4>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-lg table-hover">
                                        <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Payment ID</th>
                                            <th>Price</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <?php
                                        $query="SELECT * FROM bill_payment where username = '".$loggedin_session."'";
                                        $result = mysqli_query($con,$query);
                                        while($row = mysqli_fetch_array($result))
                                        {
                                            $status="$row[status]";
                                            if($status==1)
                                                $sta="Paid";
                                            if($status==1)
                                                $color="cl-success bg-success-light";
                                            if ($status==2)
                                                $sta="Declined";
                                            if($status==2)
                                                $color="danger";
                                            if ($status==0)
                                                $sta="Pending";
                                            if($status==0)
                                                $color="cl-danger bg-danger-light";
                                            ?>
                                            <tr>
                                                <td><a href="#"><?php echo "$row[product]"; ?></a></td>
                                                <td><i class="fa fa-lg"></i><?php echo "$row[transactionid]"; ?></td>
                                                <td><div class="label <?php echo $color; ?> ">NGN.<?php echo "$row[amount]"; ?></div></td>
                                                <td><?php echo "$row[timestamp]"; ?></td>
                                                <form action="invoice.php" method="post">
                                                    <input type="hidden" name="id" value="<?php echo "$row[id]"; ?>">
                                                    <td><button type="submit" class="badge btn-outline-primary btn-rounded"><i class="fa fa-print"></i> Print Invoice</button>
                                                </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
