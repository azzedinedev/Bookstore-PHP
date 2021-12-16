<?php
$sql = 'SELECT `genre`.genre_id,`genre`.name, COUNT( * ) AS nbr
		FROM `book`
		INNER JOIN `genre` ON `book`.`genre_id` = `genre`.`genre_id`
		GROUP BY `book`.`genre_id`';

$rows = $database->DB_fetch_list($sql);
?>
<div class="brands_products">
	<h2>Genres</h2>
	<div class="brands-name">
		<ul class="nav nav-pills nav-stacked">
			<?php
			foreach( $rows as $row ){
				
				if( $_GET['genre'] == $row['genre_id'] ) { 
					$isActiveGenre = 'class="active"'; 
				}else{
					$isActiveGenre = ""; 
				}
			?>
			
			<li <?php echo $isActiveGenre; ?>><a href="shop.php?genre=<?php echo $row['genre_id']; ?>"> <span class="pull-right">(<?php echo $row['nbr']; ?>)</span><?php echo $row['name']; ?></a></li>
			<?php
			}
			?>
		</ul>
	</div>
</div>