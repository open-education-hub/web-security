<?php
session_start();
include 'php-random-name-generator/randomNameGenerator.php';
$fibo = [1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144, 233, 377, 610, 987, 1597, 2584, 4181, 6765, 10946, 17711, 28657, 46368, 75025, 121393, 196418, 317811];

$r = new randomNameGenerator('array');

$invoice = $_GET['invoice'];
$error = '';
if (!in_array($invoice, $fibo)) {
	$error = "Oops! Invoice not found!";
}


if ($_SESSION['invoice'] == $invoice) {
	$name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
	$address = $_SESSION['address'] . '<br>Bucharest, RO';
} else {
	$name = $r->generateNames(1)[0];
	$address = '505 N 9th Ave<br />Hopewell, Virginia(VA), <br>23860';
}

$magicInvoice = 10946;

switch ($_SESSION['subscription']) {
	case 'months-1':
		$subscription['months'] = 1;
		$subscription['price'] = 45;
		break;
	case 'months-6':
		$subscription['months'] = 6;
		$subscription['price'] = 125;
		break;
	case 'months-12':
		$subscription['months'] = 12;
		$subscription['price'] = 225;
		break;
	case 'months-24':
		$subscription['months'] = 24;
		$subscription['price'] = 400;
		break;
}

$flag = '__TEMPLATE__';
?>
<?php if ($error != ''): ?>
<h1 style="text-align: center; margin-top: 300px;"><?php echo $error; ?></h1>
<?php else: ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Invoice System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" type="text/css" href="invoice/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="invoice/font-awesome/css/font-awesome.min.css" />

    <script type="text/javascript" src="invoice/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="invoice/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">

<div class="page-header">
    <h1>Your invoice <small> <?php echo $name; ?></small></h1>
</div>

<!-- Simple Invoice - START -->
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="text-center">
                <i class="fa fa-search-plus pull-left icon"></i>
                <h2>Invoice for purchase #<?php echo $invoice; ?></h2>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-12 col-md-3 col-lg-3 pull-left">
                    <div class="panel panel-default height">
                        <div class="panel-heading">Billing Details</div>
                        <div class="panel-body">
                            <strong><?php echo $name; ?>:</strong><br>
                            <?php echo $address; ?><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-3">
                    <div class="panel panel-default height">
                        <div class="panel-heading">Payment Information</div>
                        <div class="panel-body">
                            <strong>Card Name:</strong> Visa<br>
                            <strong>Card Number:</strong> ***** 332<br>
                            <strong>Exp Date:</strong> 09/2020<br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-3">
                    <div class="panel panel-default height">
                        <div class="panel-heading">Order Preferences</div>
                        <div class="panel-body">
                            <strong>Gift:</strong> No<br>
                            <strong>Express Delivery:</strong> Yes<br>
                            <strong>Insurance:</strong> No<br>
                            <strong>Coupon:</strong> No<br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-3 pull-right">
                    <div class="panel panel-default height">
                        <div class="panel-heading">Shipping Address</div>
                        <div class="panel-body">
                            <strong><?php echo $name; ?>:</strong><br>
                            <?php echo $address; ?><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="text-center"><strong>Order summary</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td><strong>Item Name</strong></td>
                                    <td class="text-center"><strong>Item Price</strong></td>
                                    <td class="text-center"><strong>Item Quantity</strong></td>
                                    <td class="text-right"><strong>Total</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Subscription for <?php echo $subscription['months']; ?> month(s)</td>
                                    <td class="text-center">$<?php echo $subscription['price']; ?>.00</td>
                                    <td class="text-center"><strong>x1</strong></td>
                                    <td class="text-right">$<?php echo $subscription['price']; ?>.00</td>
                                </tr>
								<?php if ($invoice == $magicInvoice): ?>
								<tr>
                                    <td><?php echo $flag; ?></td>
                                    <td class="text-center">Priceless</td>
                                    <td class="text-center"><strong>x1</strong></td>
                                    <td class="text-right">Priceless</td>
                                </tr>
								<?php endif; ?>
                                <tr>
                                    <td class="highrow"></td>
                                    <td class="highrow"></td>
                                    <td class="highrow text-center"><strong>Subtotal</strong></td>
                                    <td class="highrow text-right">$<?php echo $subscription['price']; ?>.00</td>
                                </tr>
                                <tr>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow text-center"><strong>Shipping</strong></td>
                                    <td class="emptyrow text-right">$15.00</td>
                                </tr>
                                <tr>
                                    <td class="emptyrow"><img src="img/0_OoYCUcwTcfOIntWP.jpg" style="width: 100px;" /></td>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow text-center"><strong>Total</strong></td>
                                    <td class="emptyrow text-right">$<?php echo $subscription['price'] + 15; ?>.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.height {
    min-height: 200px;
}

.icon {
    font-size: 47px;
    color: #5CB85C;
}

.iconbig {
    font-size: 77px;
    color: #5CB85C;
}

.table > tbody > tr > .emptyrow {
    border-top: none;
}

.table > thead > tr > .emptyrow {
    border-bottom: none;
}

.table > tbody > tr > .highrow {
    border-top: 3px solid;
}
</style>
<?php endif; ?>

<!-- Simple Invoice - END -->

</div>

</body>
</html>