<?php
$sqlRecomended = "SELECT * FROM book LIMIT 0,6";
$rowsRecomended = $database->DB_fetch_list($sqlRecomended);

?>
<div class="recommended_items">
	<h2 class="title text-center">recommended items</h2>
	
	<div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
		<div class="carousel-inner">
			<div class="item active">	
				<?php
				for($incRecomended = 0; $incRecomended < 3; $incRecomended++ ){
				?>
				<div class="col-sm-4">
					<div class="product-image-wrapper">
						<div class="single-products">
							<div class="productinfo text-center">
							<img src="files/images/<?php echo $rowsRecomended[$incRecomended]['image_src']; ?>" alt="<?php echo $database->stripDB($rowsRecomended[$incRecomended]['title']); ?>" alt="" height="229" />
							<h2>
							<?php 
							if( empty($rowsRecomended[$incRecomended]['sale_price']) ){
								echo '$'.$rowsRecomended[$incRecomended]['purchase_price'];
							}else{
								echo '$'.$database->stripDB($rowsRecomended[$incRecomended]['sale_price']).' <span class="old_price">'.'$'.$database->stripDB($rowsRecomended[$incRecomended]['purchase_price']).'</span>'; 
							}
							?>  
							</h2>
							<p><?php echo $database->stripDB($rowsRecomended[$incRecomended]['title']); ?></p>	
							<?php $bookId = $rowsRecomended[$incRecomended]['book_id']; include("modules/add-to-cart.php"); ?>
							</div>
						</div>
						<div class="choose">
							<ul class="nav nav-pills nav-justified">
								<li><a href="product.php?id=<?php echo $row['book_id']; ?>"><i class="fa fa-plus-square"></i>Details</a></li>
							</ul>
						</div>
					</div>
				</div>
				<?php 
				}
				?>
			</div>
			<div class="item">	
			<?php
				for($incRecomended = 3; $incRecomended < 6; $incRecomended++ ){
				?>
				<div class="col-sm-4">
					<div class="product-image-wrapper">
						<div class="single-products">
							<div class="productinfo text-center">
							<img src="files/images/<?php echo $rowsRecomended[$incRecomended]['image_src']; ?>" alt="<?php echo $database->stripDB($rowsRecomended[$incRecomended]['title']); ?>" alt="" height="229" />
							<h2>
							<?php 
							if( empty($rowsRecomended[$incRecomended]['sale_price']) ){
								echo '$'.$rowsRecomended[$incRecomended]['purchase_price'];
							}else{
								echo '$'.$database->stripDB($rowsRecomended[$incRecomended]['sale_price']).' <span class="old_price">'.'$'.$database->stripDB($rowsRecomended[$incRecomended]['purchase_price']).'</span>'; 
							}
							?>  
							</h2>
							<p><?php echo $database->stripDB($rowsRecomended[$incRecomended]['title']); ?></p>								
							<?php $bookId = $rowsRecomended[$incRecomended]['book_id']; include("modules/add-to-cart.php"); ?>
							</div>
						</div>
						
						<div class="choose">
							<ul class="nav nav-pills nav-justified">
								<li><a href="product.php?id=<?php echo $row['book_id']; ?>"><i class="fa fa-plus-square"></i>Details</a></li>
							</ul>
						</div>
					</div>
				</div>
				<?php 
				}
				?>
			</div>
		</div>
			<a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
			<i class="fa fa-angle-left"></i>
			</a>
			<a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
			<i class="fa fa-angle-right"></i>
			</a>			
	</div>
</div>