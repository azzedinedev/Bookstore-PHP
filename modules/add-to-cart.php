<?php
if( $auth->getUser() != "" ){ 
?>
<button type="button" data-id="<?php echo $bookId; ?>" id="book<?php echo $bookId; ?>" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
<?php
}
?>