<?php
	include(dirname(__FILE__)."/objects/db/class.db.php");
	include(dirname(__FILE__)."/objects/pagination/class.pagination.php");
	include(dirname(__FILE__)."/objects/session/class.session.php");
	include(dirname(__FILE__)."/objects/mail/class.mail.php");
	include(dirname(__FILE__)."/objects/auth/class.auth.php");
	include(dirname(__FILE__)."/modules/configDB.php");


	$sqlCart = "SELECT `B`.*, `CB`.`cart_book_id`, `CB`.`cart_qty` FROM `book` AS `B` 
				INNER JOIN `cart_book` AS `CB` ON `B`.`book_id` = `CB`.`book_id` 
				INNER JOIN `shopping_cart` AS `SC` ON `SC`.`cart_id` = `CB`.`cart_id` 
				INNER JOIN `user` AS `US` ON `US`.`user_id` = `SC`.`user_id` 
				INNER JOIN `account` AS `AC` ON `AC`.`account_id` = `US`.`account_id` 
				WHERE `AC`.`account_id` = '".$database->escapeDB($auth->getId())."'";

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
				  <li class="active">Shopping Cart</li>
				</ol>
			</div>
			
			<div class="step-one">
				<h2 class="heading">Step 1/2</h2>
			</div>

			<?php 
				if( $nbCart > 0 ){
			?>			
			<div class="table-responsive cart_info">
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="description">Item</td>					
							<td class="price">Price</td>
							<td class="quantity">Quantity</td>
							<td class="total">Total</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<?php
						
						$rowsCart = $database->DB_fetch_list($sqlCart);
						$totalAmountCart = 0;
						foreach( $rowsCart as $rowCart){
							$bookAmountCart = $rowCart['cart_qty'] * $rowCart['sale_price'];
							$totalAmountCart = $totalAmountCart + $bookAmountCart;
						?>
						<tr id="cart-line-<?php echo $rowCart['cart_book_id']; ?>">
							<td class="cart_description" data-id="<?php echo $rowCart['book_id']; ?>">
								<a href="product.php?id=<?php echo $rowCart['book_id']; ?>"><img class="image-list-cart" src="files/images/<?php echo $rowCart['image_src']; ?>" height="100" ></a>
								<h4>
									<a href="product.php?id=<?php echo $rowCart['book_id']; ?>">
										<?php echo cut_sentence($database->stripDB($rowCart['title']),0); ?>
									</a>
								</h4>
								<p>Book ID: <?php echo $rowCart['book_id']; ?></p>
							</td>
					
							<td class="cart_price" data-price="<?php echo $rowCart['sale_price']; ?>">
								<p>$<?php echo $database->stripDB($rowCart['sale_price']); ?></p>
							</td>
							<td class="cart_quantity" data-qty="<?php echo $rowCart['cart_qty']; ?>">
								<div class="cart_quantity_button">
									<a class="cart_quantity_up" href="javascript:void(0)"> + </a>
									<input class="cart_quantity_input" type="text" name="quantity" value="<?php echo $rowCart['cart_qty']; ?>" autocomplete="off" size="2">
									<a class="cart_quantity_down" href="javascript:void(0)"> - </a>
								</div>
							</td>
							<td class="cart_total" data-amount="<?php echo $bookAmountCart; ?>">
								<p class="cart_total_price">$<?php echo $bookAmountCart; ?></p>
							</td>
							<td class="cart_delete">
								<a class="cart_quantity_delete" data-cart="<?php echo $rowCart['cart_book_id']; ?>" href="javascript:void(0)"><i class="fa fa-times"></i></a>
							</td>
						</tr>
						<?php } ?>
						
					</tbody>
				</table>
			</div>
			<?php 
				}else{
			?>
			<div id="cart_empty" class="register-req" >
				<p>The cart is empty</p>
			</div>
			<?php 
				}
			?>
		</div>
	</section> <!--/#cart_items-->
<?php 
	if( $nbCart > 0 ){
		
		$sqlCartId = "SELECT `SC`.`cart_id` FROM `shopping_cart` AS `SC` 
		INNER JOIN `user` AS `US` ON `US`.`user_id` = `SC`.`user_id` 
		INNER JOIN `account` AS `AC` ON `AC`.`account_id` = `US`.`account_id` 
		WHERE `AC`.`account_id` = '".$database->escapeDB($auth->getId())."'";

		$rowsCartId = $database->DB_fetch($sqlCartId);
		$cartId = $rowsCartId['cart_id'];
?>
	<section id="do_action">
		<div class="container">
			<div class="row">
				
				<div class="col-sm-6">
					<div class="total_area" data-total="<?php echo $totalAmountCart; ?>">
							<ul>
								<li>Total <span class="total_area_amount">$<?php echo $totalAmountCart; ?></span></li>
							</ul>
								<a class="btn btn-default update" href="shop.php">Continue shopping</a>
								<a class="btn btn-default check_out" href="checkout.php">Check Out</a>
								<input name="cart" type="hidden" value="<?php echo $cartId; ?>">
					</div>

				</div>
			</div>
		</div>
	</section><!--/#do_action-->
<?php 
	}
?>
	<!--Footer-->
	<?php include('modules/footer.php'); ?>
	


    <script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</body>
</html>