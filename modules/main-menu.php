	
		<div class="header-bottom"><!--header-bottom-->
			<div class="container">
				<div class="row">
					<div class="col-sm-9">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="mainmenu pull-left">
							<ul class="nav navbar-nav collapse navbar-collapse">
								<li><a href="shop.php">Home</a></li>
								<li class="dropdown"><a href="#" class="active">Categories<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
										<?php
										$sqlGenres = 'SELECT `genre`.genre_id,`genre`.name, COUNT( * ) AS nbr
												FROM `book`
												INNER JOIN `genre` ON `book`.`genre_id` = `genre`.`genre_id`
												GROUP BY `book`.`genre_id`';

										$rowsGenres = $database->DB_fetch_list($sqlGenres);
										?>
										<?php
										foreach( $rowsGenres as $rowGenres ){
											
											if( $_GET['genre'] == $rowGenres['genre_id'] ) { 
												$isActiveGenre = 'class="active"'; 
											}else{
												$isActiveGenre = ""; 
											}
										?>
										
										<li <?php echo $isActiveGenre; ?>><a href="shop.php?genre=<?php echo $rowGenres['genre_id']; ?>"><?php echo $rowGenres['name']; ?></a></li>
										<?php
										}
										?>
                                    </ul>
                                </li> 
							</ul>
						</div>
					</div>
				</div>
				</div>
			</div>