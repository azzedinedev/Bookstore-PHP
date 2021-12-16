<?php
	include(dirname(__FILE__)."/objects/db/class.db.php");
	include(dirname(__FILE__)."/objects/pagination/class.pagination.php");
	include(dirname(__FILE__)."/objects/session/class.session.php");
	include(dirname(__FILE__)."/objects/mail/class.mail.php");
	include(dirname(__FILE__)."/objects/auth/class.auth.php");
	include(dirname(__FILE__)."/modules/configDB.php");

	$sql = "SELECT book.*, publisher.name AS publisherName, publisher.email AS publisherEmail, publisher.phone_number AS publisherPhone, publisher.address AS publisherAdress, genre.name AS genreName FROM book 
	LEFT JOIN publisher on publisher.publisher_id = book.publisher_id 
	LEFT JOIN genre on genre.genre_id = book.genre_id 
	WHERE book_id='".$database->escapeDB($_GET['id'])."'";
	$rowBook = $database->DB_fetch($sql);

	$sqlAuthor = "SELECT author.author_id, author.name FROM author INNER JOIN book_author ON author.author_id = book_author.author_id 
	WHERE book_id = '".$database->escapeDB($_GET['id'])."'";
	$rowAuthor = $database->DB_fetch_list($sqlAuthor);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Product Details | BOOKSTORE</title>
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
	
	<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="left-sidebar">
						<!--category-productsr-->
						<?php include('modules/category.php'); ?>
					
						<!--genre links-->
						<?php include('modules/genre.php'); ?>
						
						<!--price-range-->
						<?php include('modules/search-sidebar.php'); ?>
						
					</div>
				</div>
				
				<div class="col-sm-9 padding-right">
					<div class="product-details"><!--product-details-->
						<div class="col-sm-5">
							<div class="view-product">
							<img class="list-img" src="files/images/<?php echo $rowBook['image_src']; ?>" alt="<?php echo $database->stripDB($rowBook['title']); ?>" />
							</div>


						</div>
						<div class="col-sm-7">
							<div class="product-information"><!--/product-information-->
								<h2><?php echo $database->stripDB($rowBook['title']); ?></h2>
								<p>BOOK ID: <?php echo $database->stripDB($rowBook['book_id']); ?></p>
								<span>
									<span>
										<?php 
										if( empty($rowBook['sale_price']) ){
											echo '$'.$rowBook['purchase_price'];
										}else{
											echo '$'.$database->stripDB($rowBook['sale_price']).' <span class="old_price">'.'$'.$database->stripDB($rowBook['purchase_price']).'</span>'; 
										}
										?> 										
									</span>
									
									<?php
									if($rowBook['quantity'] > 0 ){
										if( $auth->getUser() != "" ){ 
									?>
									<label>Quantity:</label>
									<input class="add-to-cart-qty" type="text" value="1" />
									<button type="button" data-id="<?php echo $rowBook['book_id']; ?>" class="btn btn-default cart add-to-cart">
										<i class="fa fa-shopping-cart"></i>
										Add to cart
									</button>
									<?php	
										}
									}
									?>
								</span>
								<p><b>Availability:</b> 
								<span id="availability-<?php echo $rowBook['book_id']; ?>">
								<?php
									if($rowBook['quantity'] > 0 ){
										echo 'In stock';
									}else{
										echo 'Out of stock';
									}
								?>
								</span>
								</p>
								<p><b>Author:</b> 
								<?php
								$incAuthor = 1;
								foreach ($rowAuthor as $author){
									echo '<a href="shop.php?authorid='.$database->stripDB($author['author_id']).'">'.$database->stripDB($author['name']).'</a>';
									if( count($rowAuthor) >= $incAuthor+1 )	{ echo ', '; }
									$incAuthor++;
								}
								?>
								</p>
								<p><b>Publisher:</b> <?php echo '<a href="shop.php?publisherid='.$database->stripDB($rowBook['publisher_id']).'">'.$database->stripDB($rowBook['publisherName']).'</a>'; ?></p>
								<p><b>ISBN:</b> <?php echo $database->stripDB($rowBook['isbn']); ?></p>
								<p><b>Genre:</b> <?php echo '<a href="shop.php?genre='.$database->stripDB($rowBook['genre_id']).'">'.$database->stripDB($rowBook['genreName']).'</a>'; ?></p>
								
							</div><!--/product-information-->
						</div>
					</div><!--/product-details-->
					
					<div class="category-tab shop-details-tab"><!--category-tab-->
						<div class="col-sm-12">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#details" data-toggle="tab">Details</a></li>
								<li><a href="#publisher" data-toggle="tab">From the publisher</a></li>
								<li><a href="#author" data-toggle="tab">From the author</a></li>
							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane fade active in" id="details" >
							<?php echo $database->stripDB($rowBook['description']); ?>
							</div>
							
							<div class="tab-pane fade" id="publisher" >
								<p><b>Publisher name:</b> <?php echo $database->stripDB($rowBook['publisherName']); ?></p>
								<?php
								if( !empty($rowBook['publisherPhone']) ){
								?>
								<p><b>Publisher phone:</b> <?php echo $database->stripDB($rowBook['publisherPhone']); ?></p>
								<?php
								}
								if( !empty($rowBook['publisherAddress']) ){
								?>
								<p><b>Publisher address:</b> <?php echo $database->stripDB($rowBook['publisherAddress']); ?></p>
								<?php
								}
								?>
								<!--Publisher books-->
								<?php
									$sqlPublisherBooks = "SELECT * FROM book WHERE publisher_id = '".$database->escapeDB($rowBook['publisher_id'])."' LIMIT 0, 4";
						
									$rowsPublisherBooks = $database->DB_fetch_list($sqlPublisherBooks);
									foreach($rowsPublisherBooks  as $rowPublisherBooks ){

								?>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="files/images/<?php echo $rowPublisherBooks['image_src']; ?>" alt="<?php echo $database->stripDB($rowPublisherBooks['title']); ?>" alt="" height="229" />
												<h2>
												<?php 
												if( empty($rowPublisherBooks['sale_price']) ){
													echo '$'.$rowPublisherBooks['purchase_price'];
												}else{
													echo '$'.$database->stripDB($rowPublisherBooks['sale_price']).' <span class="old_price">'.'$'.$database->stripDB($rowPublisherBooks['purchase_price']).'</span>'; 
												}
												?>  
												</h2>
												<p><?php echo $database->stripDB($rowPublisherBooks['title']); ?></p>
												<?php $bookId = $rowPublisherBooks['book_id']; include("modules/add-to-cart.php"); ?>
											</div>
										</div>
										<div class="choose">
											<ul class="nav nav-pills nav-justified">
												<li><a href="product.php?id=<?php echo $rowPublisherBooks['book_id']; ?>"><i class="fa fa-plus-square"></i>Details</a></li>
											</ul>
										</div>
									</div>
								</div>
								<?php
									}
								?>
							</div>
							
							<div class="tab-pane fade" id="author" >
								
								<!--Auhtor books-->
								<?php
									$sqlAuthorBooks = "
									SELECT DISTINCT B.* FROM `book_author` AS BA1 
									INNER JOIN `book` AS B ON B.book_id = BA1.book_id   
									WHERE BA1.author_id IN (SELECT BA2.author_id FROM `book_author` AS BA2 WHERE BA2.book_id = '".$database->escapeDB($rowBook['book_id'])."') 
									LIMIT 0, 4";

									$rowsAuthorBooks = $database->DB_fetch_list($sqlAuthorBooks);
									foreach($rowsAuthorBooks  as $rowAuthorBooks ){

								?>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="files/images/<?php echo $rowAuthorBooks['image_src']; ?>" alt="<?php echo $database->stripDB($rowAuthorBooks['title']); ?>" alt="" height="229" />
												<h2>
												<?php 
												if( empty($rowAuthorBooks['sale_price']) ){
													echo '$'.$rowAuthorBooks['purchase_price'];
												}else{
													echo '$'.$database->stripDB($rowAuthorBooks['sale_price']).' <span class="old_price">'.'$'.$database->stripDB($rowAuthorBooks['purchase_price']).'</span>'; 
												}
												?>  
												</h2>
												<p><?php echo $database->stripDB($rowAuthorBooks['title']); ?></p>
												<?php $bookId = $rowAuthorBooks['book_id']; include("modules/add-to-cart.php"); ?>												
											</div>
										</div>
										
										<div class="choose">
											<ul class="nav nav-pills nav-justified">
												<li><a href="product.php?id=<?php echo $rowAuthorBooks['book_id']; ?>"><i class="fa fa-plus-square"></i>Details</a></li>
											</ul>
										</div>
									</div>
								</div>
								<?php
									}
								?>

							</div>

							
						</div>
					</div><!--/category-tab-->

					<!--recommended_items-->
					<?php include('modules/recomended-product.php'); ?>
					<!--/recommended_items-->
					
				</div>
			</div>
		</div>
	</section>
	
		<!--Footer-->
		<?php include('modules/footer.php'); ?>
	
	

  
    <script src="js/jquery.js"></script>
	<script src="js/price-range.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</body>
</html>