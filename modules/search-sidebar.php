<div class="price-range">

<h2>Advanced search</h2>

<form action="shop.php" methode='get'>
<?php
if( isset($_GET['title']) and !empty($_GET['title']) ) { 
	$search['title'] = $_GET['title']; 
}
if( isset($_GET['description']) and !empty($_GET['description']) ) { 
	$search['description'] = $_GET['description'];  
}
if( isset($_GET['isbn']) and !empty($_GET['isbn']) ) { 
	$search['isbn'] = $_GET['isbn']; 
}
if( isset($_GET['author']) and !empty($_GET['author']) ) { 
	$search = $_GET['author']; 
}
if( isset($_GET['publisher']) and !empty($_GET['publisher']) ) { 
	$search['publisher'] = $_GET['publisher']; 
}

if( isset($_GET['minprice']) and !empty($_GET['minprice']) ) { 
	$search['minprice'] = $_GET['minprice']; 
}
if( isset($_GET['maxprice']) and !empty($_GET['maxprice']) ) { 
	$search['maxprice'] = $_GET['maxprice']; 
}

?>
<div class="search_box pull-right">
	<span>Title</span>
		<input type="text" name="title" value="<?php echo $search['title']; ?>" placeholder="Title">
</div>	
<div class="search_box pull-right">	
	<span>Description</span>
		<input type="text" name="description" value="<?php echo $search['description']; ?>" placeholder="description">
</div>	
<div class="search_box pull-right">	
	<span>ISBN</span>
		<input type="text" name="isbn" value="<?php echo $search['isbn']; ?>" placeholder="ISBN">
</div>	
<div class="search_box pull-right">	
	<span>Author</span>
		<input type="text" name="author" value="<?php echo $search['author']; ?>" placeholder="author">
</div>	
<div class="search_box pull-right">	
	<span>Publisher</span>
		<input type="text" name="publisher" value="<?php echo $search['publisher']; ?>" placeholder="publisher">
</div>
<div class="search_box pull-right">	
	<span>Minimum price</span>
		<input type="text" name="minprice" value="<?php echo $search['minprice']; ?>" placeholder="Minimum price">
</div>
<div class="search_box pull-right">	
	<span>Maximum price</span>
		<input type="text" name="maxprice" value="<?php echo $search['maxprice']; ?>" placeholder="Maximum price">
</div>

<!--	
<div>Genres</div>
<select name="genres" id="genres" multiple>
<?php
$sqlSearchGenres = 'SELECT `genre`.genre_id,`genre`.name, COUNT( * ) AS nbr
		FROM `book`
		INNER JOIN `genre` ON `book`.`genre_id` = `genre`.`genre_id`
		GROUP BY `book`.`genre_id`';

$rowsSearchGenres = $database->DB_fetch_list($sqlSearchGenres);
foreach( $rowsSearchGenres as $rowSearchGenres ){
?>
  <option value="<?php echo $rowSearchGenres['genre_id']; ?>"><?php echo $rowSearchGenres['name']; ?></option>
<?php
}
?>
</select>

	<div class="well">
			<input type="text" class="span2" value="" data-slider-min="0" data-slider-max="600" data-slider-step="5" data-slider-value="[250,450]" id="sl2" ><br />
			<b>$ 0</b> <b class="pull-right">$ 600</b>
	</div>

-->
	<button type="submit" class="btn btn-default pull-right">
		Search
	</button>
	
</form>


</div>