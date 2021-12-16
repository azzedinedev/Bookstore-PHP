<?php
include(dirname(__FILE__)."/../objects/db/class.db.php");
include(dirname(__FILE__)."/../objects/session/class.session.php");
include(dirname(__FILE__)."/../objects/mail/class.mail.php");
include(dirname(__FILE__)."/../objects/auth/class.auth.php");
include(dirname(__FILE__)."/configDB.php");


if( isset($_POST['action']) and $_POST['action'] == 'delete' ){
	if( isset($_POST['cbid']) and !empty($_POST['cbid']) ){
		//delete from cart
		$sqlDeleteCart = "DELETE FROM `cart_book` WHERE `cart_book_id` = '".$database->escapeDB($_POST['cbid'])."'";
		$database->DB_res($sqlDeleteCart,'');
	}
}else{

	if( isset($_POST['uid']) and !empty($_POST['uid']) ){
		//Select user_id from account_id
		$sqlUid = "SELECT `user_id` FROM `user` WHERE `account_id` = '".$database->escapeDB($_POST['uid'])."'";
		$rowUid = $database->DB_fetch($sqlUid);
		$uid = $database->stripDB($rowUid['user_id']);
		
		if( isset($_POST['id']) and !empty($_POST['id']) ){
			$id = $_POST['id'];

				//Add to cart
				//Quantity
				if(  $_POST['qty'] > 0 ){
					$qty = intval($_POST['qty']);
				}else{
					if( $_POST['qty'] == "" ){
						$qty = 1;			
					}else{
						$qty = 0;
					}
				}
				
				//Select Available Quantity from Book
				$sqlSelectQty = "SELECT quantity FROM `book` WHERE `book_id` = '".$database->escapeDB($id)."'";
				$rowSelectQty = $database->DB_fetch($sqlSelectQty);
				$qtyInStock = $database->stripDB($rowSelectQty['quantity']);

				//Select sum of all quantities from cart
				$sqlSelectQtyCart = "SELECT SUM(cart_qty) AS QtyCart FROM `cart_book` 
				INNER JOIN shopping_cart ON `shopping_cart`.`cart_id` = `cart_book`.`cart_id` 
				WHERE `cart_book`.`book_id` = '".$database->escapeDB($id)."' 
				AND `shopping_cart`.`user_id` = '".$database->escapeDB($uid)."'";
				$rowSelectQtyCart = $database->DB_fetch($sqlSelectQtyCart);
				$qtyInCart = $database->stripDB($rowSelectQtyCart['QtyCart']);
				
				if( $_POST['type'] == 'allqty' ){
					$qtyPlusCart = $qty;
					$qtyExact = true;
				}else{
					$qtyPlusCart = $qty + $qtyInCart;
				}

				if( $qtyInStock < $qtyPlusCart ){
					//availability / not exist
					if( $qtyInStock == 0 ){
						$availability = 'Out of stock';
					}else{
						$availability = 'Items in stock ('.$qtyInStock.')';
					}
					
					//return result as JSON
					$arrToJson = array(
						'id' => $id, 
						'availability' => $availability
					);
					echo json_encode($arrToJson);
					
				}else{
					//availability / exist
					
					//insert book to the cart
					$sqlSelectCart = "SELECT * FROM `shopping_cart` WHERE `user_id` = '".$database->escapeDB($uid)."'";
					$rowSelectCart = $database->DB_fetch($sqlSelectCart);	
					
					if( empty($rowSelectCart['cart_id']) ){
						$sqlInsertShopingCart = "INSERT INTO `shopping_cart` (`user_id`) VALUES ('".$database->escapeDB($uid)."');";
						$database->DB_res($sqlInsertShopingCart,'');
						$cartId = $database->insertid();
					}else{
						$cartId = $rowSelectCart['cart_id'];
					}

					//select if the book exist on the cart
					$sqlSelectBookCart = "SELECT * FROM `cart_book` WHERE `book_id` = '".$database->escapeDB($id)."' AND `cart_id` = '".$database->escapeDB($cartId)."'";
					$rowSelectBookCart  = $database->DB_fetch($sqlSelectBookCart);	
					
					if( empty($rowSelectBookCart['cart_id']) ){
						
						if( $qty > 0 ) {
							//the shoping cart existe for the selected user
							$sqlInsertCart = "INSERT INTO `cart_book` (`cart_qty`,`cart_id`,`book_id`) 
								VALUES ('".$database->escapeDB($qty)."',
								'".$database->escapeDB($cartId)."',
								'".$database->escapeDB($id)."'
							);";
							$database->DB_res($sqlInsertCart,'');
							
						}
					}else{
						if( $qtyExact ) {
							$newQtyCart = $qty;
						}else{
							$qtyBookCart = $rowSelectBookCart['cart_qty'];
							$newQtyCart = $qtyBookCart + $qty;
						}

						if( $newQtyCart > 0 ) {
							$sqlUpdateCart = "UPDATE `cart_book` SET `cart_qty` = '".$newQtyCart."' "
							."WHERE `cart_id` = '".$database->escapeDB($cartId)."' "
							."AND `book_id` = '".$database->escapeDB($id)."' ";
							$database->DB_res($sqlUpdateCart,'');
						}else{
							$sqlDeleteCart = "DELETE FROM `cart_book` WHERE `cart_id` = '".$database->escapeDB($cartId)."' "
							."AND `book_id` = '".$database->escapeDB($id)."' ";
							$database->DB_res($sqlDeleteCart,'');
						}
					}
					
					//return result as JSON
					$arrToJson = array(
						'id' => $id, 
						'availability' => 'In stock',
						'bookid' => $id,
						'cartid' => $cartId,
						'uid' => $uid,
						'sql' => $sqlUid
					);
					echo json_encode($arrToJson);
					
				}

		}
	}
}

?>