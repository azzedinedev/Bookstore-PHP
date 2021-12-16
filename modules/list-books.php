<?php

	$pagination = new Paging();
	$pagination->setDb($database);
	
	$addGet = '';
	$arrAddWhere = array();


	if( isset($_GET['authorid']) and !empty($_GET['authorid']) ) { 
		//Go to to list for the selected genre
		$addGet = '&authorid='.$_GET['authorid'];
		$addWhereSQL = "WHERE book_id IN ( SELECT book_id FROM book_author WHERE author_id = '".$database->escapeDB($_GET['authorid'])."') ";
	}else{
		if( isset($_GET['publisherid']) and !empty($_GET['publisherid']) ) { 
			//Go to to list for the selected genre
			$addGet = '&publisherid='.$_GET['publisherid'];
			$addWhereSQL = "WHERE publisher_id='".$database->escapeDB($_GET['publisherid'])."'";
		}else{
			if( isset($_GET['genre']) and !empty($_GET['genre']) ) { 
				//Go to to list for the selected genre
				$addGet = '&genre='.$_GET['genre'];
				$addWhereSQL = "WHERE genre_id='".$database->escapeDB($_GET['genre'])."'";
			}else{
		
				if( isset($_GET['title']) and !empty($_GET['title']) ) { 
					$search['title'] = $_GET['title']; 
					$arrAddWhere[] = "( title LIKE '%".$database->escapeDB($_GET['title'])."%') "; 
				
				}
				if( isset($_GET['description']) and !empty($_GET['description']) ) { 
					$search['description'] = $_GET['description'];  
					$arrAddWhere[] = "( description LIKE '%".$database->escapeDB($_GET['description'])."%') "; 
				}
				if( isset($_GET['isbn']) and !empty($_GET['isbn']) ) { 
					$search['isbn'] = $_GET['isbn']; 
					$arrAddWhere[] = "( isbn LIKE '%".$database->escapeDB($_GET['isbn'])."%') "; 
				}
				if( isset($_GET['author']) and !empty($_GET['author']) ) { 
					$search = $_GET['author']; 
					$arrAddWhere[] = "( author LIKE '%".$database->escapeDB($_GET['author'])."%') "; 
				}
				if( isset($_GET['publisher']) and !empty($_GET['publisher']) ) { 
					$search['publisher'] = $_GET['publisher']; 
					$arrAddWhere[] = "( publisher LIKE '%".$database->escapeDB($_GET['publisher'])."%') "; 
				}
		
				if( isset($_GET['minprice']) and !empty($_GET['minprice']) ) { 
					$search['minprice'] = $_GET['minprice']; 
					$arrAddWhere[] = "( sale_price > '".$database->escapeDB($_GET['minprice'])."') OR ( (purchase_price > '".$database->escapeDB($_GET['minprice'])."') AND sale_price='' )"; 
				}
				if( isset($_GET['maxprice']) and !empty($_GET['maxprice']) ) { 
					$search['maxprice'] = $_GET['maxprice']; 
					$arrAddWhere[] = "( sale_price > '".$database->escapeDB($_GET['minprice'])."') OR ( (purchase_price > '".$database->escapeDB($_GET['minprice'])."') AND sale_price='' )"; 
				}
				if( isset($_GET['genres']) and !empty($_GET['genres']) ) { 
					$search['genres'] = $_GET['genres']; 
					$genreArr = explode(',', str_replace(' ','',$_GET['genres']) );
					//$arrAddWhere[] = "( genre_id IN ('".implode("','",$genreArr)."') )";
				}
				if( count($arrAddWhere) > 0 ) {
					$addWhereSQL = 'WHERE '.implode(' AND ', $arrAddWhere);
				}
				
			}

		}
	}

	$sql = "SELECT * FROM book ".$addWhereSQL;

	$pagination->go($sql);
	$pagination->setLimit(9);
	$pagination->setParameter($addGet);
	if(  isset($_GET['page']) and !empty($_GET['page']) ) { $page = $_GET['page'] ;} else{ $page = 1; };
	$pagination->getOffset($page);
	$database->res = $pagination->resLimit();
	
	$sql = $sql." LIMIT $pagination->offset,$pagination->limit";

	$rows = $database->DB_fetch_list($sql);

	$sqlGenre = "SELECT name FROM genre WHERE genre_id = '".$database->escapeDB($_GET['genre'])."' ";
	$rowGenre = $database->DB_fetch($sqlGenre);
	$titleGenre = $rowGenre['name'];
?>

<div class="features_items"><!--features_items-->
<h2 class="title text-center"> List of availabale books <?php if( !empty($titleGenre) ){ echo '('.$titleGenre.')'; } ?></h2>
<h5>
<?php 
	$startItem = $pagination->offset + 1;

	$pagination->getNoOfPages();

	if( $page == $pagination->getNoOfPages() ){
		$endItem = $pagination->numrows;
	}else{
		$endItem = ( ($page) * $pagination->limit );
	}

	echo $pagination->numrows.' books found | showing results from '.$startItem.' to '.$endItem; 
?>
</h5>
<?php
foreach( $rows as $row ){
?>
<div class="col-sm-4">
	<div class="product-image-wrapper">
		<div class="single-products">
			<div class="productinfo text-center">
				<img class="list-img" src="files/images/<?php echo $row['image_src']; ?>" alt="<?php echo $database->stripDB($row['title']); ?>" height="336" />
				<h2>
					<?php 
					if( empty($row['sale_price']) ){
						echo '$'.$row['purchase_price'];
					}else{
						echo '$'.$database->stripDB($row['sale_price']).' <span class="old_price">'.'$'.$database->stripDB($row['purchase_price']).'</span>'; 
					}
					?>  
				</h2>
				<p class="list-title"><?php echo $database->stripDB($row['title']); ?></p>
				<?php $bookId = $row['book_id']; include("modules/add-to-cart.php"); ?>				
			</div>
			<div class="product-overlay">
				<div class="overlay-content">
					<h2>
					<?php 
					if( empty($row['sale_price']) ){
						echo '$'.$row['purchase_price'];
					}else{
						echo '$'.$database->stripDB($row['sale_price']).' <span class="old_price">'.'$'.$database->stripDB($row['purchase_price']).'</span>'; 
					}
					?> 
					</h2>
					<p><a href="product.php?id=<?php echo $row['book_id']; ?>"><?php echo $database->stripDB($row['title']); ?></a></p>
					<?php $bookId = $row['book_id']; include("modules/add-to-cart.php"); ?>
					<a href="product.php?id=<?php echo $row['book_id']; ?>" class="btn btn-default add-to-cart"><i class="fa fa-plus-square"></i>Details</a>
			
				</div>
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

<?php
$pagination->getPageList();
?>