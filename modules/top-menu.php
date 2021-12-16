                        <div class="shop-menu pull-right">
							<ul class="nav navbar-nav">
								<li><a href="backend-owner/"><i class="fa fa-user"></i> Backend Account</a></li>
								<?php 
									if( $auth->getUser() != "" ){ 
								?>
								<li><a href="orders.php"><i class="fa fa-crosshairs"></i> Orders</a></li>
								<li><a href="cart.php"><i class="fa fa-shopping-cart"></i> Cart</a></li>
								<li><a class = "uid" id="uid<?php echo $auth->getId(); ?>" data-id="<?php echo $auth->getId(); ?>" href="login.php?disconnect=1"><i class="fa fa-unlock"></i> Disconnect</a></li>
								<?php 
									}else{ 
								?>
								<li><a href="login.php"><i class="fa fa-lock"></i> Login</a></li>
								<?php 
									} 
								?>
							</ul>
						</div>