/*price range*/

//$('#sl2').slider();

	var RGBChange = function() {
	  $('#RGB').css('background', 'rgb('+r.getValue()+','+g.getValue()+','+b.getValue()+')')
	};	
		
/*scroll to top*/

$(document).ready(function(){
	$(function () {
		$.scrollUp({
	        scrollName: 'scrollUp', // Element ID
	        scrollDistance: 300, // Distance from top/bottom before showing element (px)
	        scrollFrom: 'top', // 'top' or 'bottom'
	        scrollSpeed: 300, // Speed back to top (ms)
	        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
	        animation: 'fade', // Fade, slide, none
	        animationSpeed: 200, // Animation in speed (ms)
	        scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
					//scrollTarget: false, // Set a custom target element for scrolling to the top
	        scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
	        scrollTitle: false, // Set a custom <a> title if required.
	        scrollImg: false, // Set true to use image
	        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
	        zIndex: 2147483647 // Z-Index for the overlay
		});

		$('.add-to-cart').click(function(){
			console.log('CLICK -> add-to-cart');
			

			var book_id = $(this).data('id');
			var uid =  $(".uid").data('id');
			var qty = $(this).parent().find(".add-to-cart-qty").val();

			$.ajax
			({ 
				url: 'modules/ajax-add-to-cart.php',
				data: {"id": book_id, "uid": uid, "qty": qty},
				dataType: 'json',
				type: 'post',
				success: function(result)
				{
					$("#availability-"+book_id).html(result.availability);
					console.log('Book ID : '+book_id+' -> availability : '+result.availability+'\n bookid : '+result.bookid + ' cartid : '+result.cartid+' uid : '+result.uid+'\n SQLuid : '+result.sql);
				}
			});

		});

		$(".cart_info .cart_quantity_input").change(function(){
			
			var qty = parseInt($(this).val());

			var book_id = $(this).parent().parent().parent().find(".cart_description").data('id');
			var uid =  $(".uid").data('id');
			
			var price =  Number($(this).parent().parent().parent().find(".cart_price").data('price'));

			var amount = qty * price;

			//update amount
			$(this).parent().parent().parent().find(".cart_total").data('amount',amount);
			$(this).parent().parent().parent().find(".cart_total .cart_total_price").html('$'+amount);
			
			//Update qty
			$(this).parent().parent().find(".cart_quantity").data('id',qty);

			var total = 0;
			//update total
			$(this).parent().parent().parent().parent().find(".cart_total").each(function() {
				total += Number($(this).data('amount'));
			});
			$(".total_area").data('total',total)
			$(".total_area .total_area_amount").html('$'+total);

			console.log('total : '+total+' -> price : '+price+' * qty : '+qty+' = amount : '+amount);

			$.ajax
			({ 
				url: 'modules/ajax-add-to-cart.php',
				data: {"id": book_id, "uid": uid, "qty": qty, "type": 'allqty'},
				dataType: 'json',
				type: 'post',
				success: function(result)
				{
					console.log('Book ID : '+book_id+' -> availability : '+result.availability);
				}
			});

		});

		$(".cart_info .cart_quantity_up").click(function(){
			
			
			var qty_input = $(this).parent().find(".cart_quantity_input");
			var qty = parseInt(qty_input.val()) + 1;
			qty_input.val(qty);
			
			var price =  Number($(this).parent().parent().parent().find(".cart_price").data('price'));

			var amount = qty * price;

			$(this).parent().parent().parent().find(".cart_total").data('amount',amount);
			$(this).parent().parent().parent().find(".cart_total .cart_total_price").html('$'+amount);
			
			//Update qty
			$(this).parent().parent().find(".cart_quantity").data('id',qty);

			var total = 0;
			//update total
			$(this).parent().parent().parent().parent().find(".cart_total").each(function() {
				total += Number($(this).data('amount'));
			});
			$(".total_area").data('total',total)
			$(".total_area .total_area_amount").html('$'+total);

			console.log('total : '+total+' -> price : '+price+' * qty : '+qty+' = amount : '+amount);

			var book_id = $(this).parent().parent().parent().find(".cart_description").data('id');
			var uid =  $(".uid").data('id');

			$.ajax
			({ 
				url: 'modules/ajax-add-to-cart.php',
				data: {"id": book_id, "uid": uid, "qty": qty, "type": 'allqty'},
				dataType: 'json',
				type: 'post',
				success: function(result)
				{
					console.log('Book ID : '+book_id+' -> availability : '+result.availability);
				}
			});

		});

		$(".cart_info .cart_quantity_down").click(function(){
			var qty_input = $(this).parent().find(".cart_quantity_input");
			var qty = parseInt(qty_input.val()) - 1;
			qty_input.val(qty);
			if(qty >= 0 ){
				var price =  Number($(this).parent().parent().parent().find(".cart_price").data('price'));

				var amount = qty * price;

				$(this).parent().parent().parent().find(".cart_total").data('amount',amount);
				$(this).parent().parent().parent().find(".cart_total .cart_total_price").html('$'+amount);
				
				//Update qty
				$(this).parent().parent().find(".cart_quantity").data('id',qty);

				var total = 0;
				//update total
				$(this).parent().parent().parent().parent().find(".cart_total").each(function() {
					total += Number($(this).data('amount'));
				});
				$(".total_area").data('total',total)
				$(".total_area .total_area_amount").html('$'+total);
				
				console.log('total : '+total+' -> price : '+price+' * qty : '+qty+' = amount : '+amount);

				var book_id = $(this).parent().parent().parent().find(".cart_description").data('id');
				var uid =  $(".uid").data('id');
	
				$.ajax
				({ 
					url: 'modules/ajax-add-to-cart.php',
					data: {"id": book_id, "uid": uid, "qty": qty, "type": 'allqty'},
					dataType: 'json',
					type: 'post',
					success: function(result)
					{
						console.log('Book ID : '+book_id+' -> availability : '+result.availability);
					}
				});
			}
		});

		$(".cart_delete .cart_quantity_delete").click(function(){
		
			var cbid =  $(this).data('cart');

			$.ajax
			({ 
				url: 'modules/ajax-add-to-cart.php',
				data: {"action": 'delete',"cbid":cbid},
				dataType: 'json',
				type: 'post',
				success: function(result)
				{
					console.log('Book ID : '+book_id+' -> availability : '+result.availability);
				},
				complete: function (data) {
					var total = 0;
					$(".cart_total").each(function() {
						total += Number($(this).data('amount'));
					});
					$(".total_area").data('total',total)
					$(".total_area .total_area_amount").html('$'+total);

					$("#cart-line-"+cbid).fadeOut(300, function() { $(this).remove(); });
					var nbline = $('.cart_info tbody tr').length;
					console.log('nbline : '+nbline);

					if( nbline < 2 ){
						//remove the table of cart
						$(".cart_info").fadeOut(300, function() { $(this).remove(); });
						$("#do_action").fadeOut(300, function() { $(this).remove(); });
						$("#cart_items .container").append( '<div id="cart_empty" class="register-req"><p>The cart is empty</p></div>');
					}
				}
			});	
		});		

		//checkout
		$('.cart_submit').click(function(){
			console.log('CLICK -> checkout');			
			$("#cart-check-form").submit();

		});

	});

});
