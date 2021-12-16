<?php
	include(dirname(__FILE__)."/objects/db/class.db.php");
	include(dirname(__FILE__)."/objects/pagination/class.pagination.php");
	include(dirname(__FILE__)."/objects/session/class.session.php");
	include(dirname(__FILE__)."/objects/mail/class.mail.php");
	include(dirname(__FILE__)."/objects/auth/class.auth.php");
	include(dirname(__FILE__)."/modules/configDB.php");


	$sqlCart = "SELECT B.*, CB.cart_book_id, CB.cart_qty FROM book AS B
				INNER JOIN cart_book AS CB ON B.book_id = CB.book_id 
				INNER JOIN shopping_cart AS SC ON SC.cart_id = CB.cart_id 
				INNER JOIN user AS US ON US.user_id = SC.user_id 
				INNER JOIN account AS AC ON AC.account_id = US.account_id 
				WHERE AC.account_id = '".$database->escapeDB($auth->getId())."'";

	$nbCart = $database->DB_num_rows($sqlCart);
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Cart | BOOKSTORE</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
</head><!--/head-->

<body>
<header id="header"><!--header-->

		
<div class="header-middle"><!--header-middle-->
	<div class="container">
		<div class="row">
			<div class="col-sm-4">
				<div class="logo pull-left">
					<a href="index.php"><img src="images/home/logo.png" alt="" /></a>
				</div>						
			</div>
			
			<div class="col-sm-8">
				<?php include('modules/top-menu.php'); ?>
			</div>
		</div>
	</div>
</div><!--/header-middle-->

<?php include('modules/main-menu.php'); ?>

</header>

	<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="index.php">Home</a></li>
				  <li class="active">List of orders</li>
				</ol>
			</div>
			
			<div class="step-one">
				<h2 class="heading">List of orders</h2>
			</div>

<!--order-list-->
		<div class="panel-group category-products" id="accordian">
<?php
//Select all orders of the curreent user
$sqlOrders = "SELECT `O`.* FROM `order` AS `O` 
	INNER JOIN `user` AS `US` ON `US`.`user_id` = `O`.`user_id` 
	INNER JOIN `account` AS `AC` ON `AC`.`account_id` = `US`.`account_id` 
	WHERE `AC`.`account_id` = '".$database->escapeDB($auth->getId())."'";

$rowsOrders = $database->DB_fetch_list($sqlOrders);	

foreach( $rowsOrders as $order ){
	
	//Select all books of the current order
	$sqlOrderBooks = "SELECT `OB`.*, `B`.`title`, `B`.`image_src`, `B`.`sale_price`, `B`.`purchase_price` FROM `book` AS `B` 
	INNER JOIN `order_book` AS `OB` ON `OB`.`book_id` = `B`.`book_id` 
	INNER JOIN `order` AS `O` ON `O`.`order_id` = `OB`.`order_id` 
	WHERE `O`.`order_id` = '".$database->escapeDB($order['order_id'])."'";

	$rowsOrderBooks = $database->DB_fetch_list($sqlOrderBooks);	

	//Select all books of the current order
	$sqlOrderHistory = "SELECT `OH`.* FROM `order_history` AS `OH` 
	INNER JOIN `order` AS `O` ON `O`.`order_id` = `OH`.`order_id` 
	WHERE `O`.`order_id` = '".$database->escapeDB($order['order_id'])."'";

	$rowsOrderHistory = $database->DB_fetch_list($sqlOrderHistory);	
	$current_status = array(
		'0' => 'On process',
		'1' => 'On delivery',
		'2' => 'completed',
	);
?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordian" href="#<?php echo 'order-items-'.$database->stripDB($order['order_id']); ?>">
							<span class="badge pull-right"><i class="fa fa-plus"></i></span>
							<div class="total_area">
								<div>Order <?php echo '<b>#'.$database->stripDB($order['order_id']).'</b>'; ?> <div><b>Current status :</b><?php echo $current_status[$order['current_status']]; ?></div>  <span class="total_area_amount"><b>Total amount :</b> $<?php echo $order['Order_TotalAmount']; ?></span></div>
							</div>
						</a>

					</h4>
				</div>

				<!--#order_items-->
				<div id="<?php echo 'order-items-'.$database->stripDB($order['order_id']); ?>" class="panel-collapse collapse">
					<div class="panel-body">
					<table class="table table-condensed">
						<thead>
							<tr class="cart_menu">
								<td class="description">Item</td>					
								<td class="price">Price</td>
								<td class="quantity">Quantity</td>
								<td class="total">Total</td>
							</tr>
						</thead>
						<tbody>
						<?php foreach( $rowsOrderBooks as $rowOrderBooks ){ ?>
							<tr>
								<td class="cart_description" data-id="<?php echo $rowOrderBooks['book_id']; ?>">
									<a href="product.php?id=<?php echo $rowOrderBooks['book_id']; ?>"><img class="image-list-cart" src="../files/<?php echo $rowOrderBooks['image_src']; ?>" height="100" ></a>
									<h4>
										<a href="product.php?id=<?php echo $rowOrderBooks['book_id']; ?>">
											<?php echo cut_sentence($database->stripDB($rowOrderBooks['title']),0); ?>
										</a>
									</h4>
									<p>Book ID: <?php echo $rowOrderBooks['book_id']; ?></p>
								</td>
						
								<td class="cart_price">
									<p>$<?php echo $database->stripDB($rowOrderBooks['sale_price']); ?></p>
								</td>
								<td class="cart_quantity">
									<div class="cart_quantity_button">
										<?php echo $rowOrderBooks['qty']; ?>
									</div>
								</td>
								<td class="cart_total">
									<p class="cart_total_price">$<?php echo $rowOrderBooks['amounts']; ?></p>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>						
					</div>
				</div><!--/#order_items-->
				
				<div class="col-sm-6" id="<?php echo 'order-history-'.$database->stripDB($order['order_id']); ?>">
					<div class="total_area">
						<ul>
							<?php foreach( $rowsOrderHistory as $orderHistory ){ ?>
							<li><?php echo $orderHistory['date']; ?><span><?php echo $current_status[$orderHistory['status']]; ?></span></li>
							<?php } ?>
						</ul>
					</div>
				</div>

			</div>
			<?php 
				}
			?>
		</div>	
<!--order-list-->

	</section> <!--/#cart_items-->

	<!--Footer-->
	<?php include('modules/footer.php'); ?>
	


    <script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</body>
</html>