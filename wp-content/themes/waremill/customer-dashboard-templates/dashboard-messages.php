<?php /*Template Name: Customer - Dashboard - All Messages*/
get_header(); ?>

<?php if ( is_user_logged_in() && check_role_current_user('customer' ) == true ) { // user logged and with customer role ?>
	<?php $user = wp_get_current_user(); 
	 	$user_ID = $user->ID;
 	?>
	<div class="dashboard-page">
		<div class="main-content">
			<div class="container">
				<div class="floating-objects">
					<h1 class="left"><?php _e('Your Inbox', THEME_TEXT_DOMAIN); ?></h1>
					
					<div class="select-block right display-options search_messages">
						<form method="POST"  enctype="multipart/form-data" >
							<div class="input-block-wrapper">
								<input type="text" name="search" value="<?php $search = $_POST['search']; if(isset($search)) { echo $search; }?>" placeholder="Keywords">
								<div class="submitsearch">
									<i class="fa fa-search"></i>
								</div>
							</div>

						</form>
					</div>
					
				</div>
				<div class="sep"></div>
				<?php 
					$args =  array( 
				        'ignore_sticky_posts' 	=> true, 
				        'post_type'           	=> 'message',
				        'order'              	=> 'DESC',
				        'posts_per_page'		=> 20,
				        'meta_key'				=> 'date_last_message',
						'orderby'				=> 'meta_value',
				        'meta_query' => array(
				        	//'relation'		=> 'OR',
							array(
						        'key'		=> 'customer',
						        'compare'	=> '=',
						        'value'		=> $user_ID,
						    ),
						    /* array(
						        'key'		=> 'contractor',
						        'compare'	=> '=',
						        'value'		=> $user_ID,
						    ) */
					    ),
					);  

				if(isset($_POST['search']) && strlen($_POST['search'])){
					$args['s'] = $_POST['search'];
				}	 

				$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			 	$loop = new WP_Query( $args ); 
	 			$count = 1 ;
	 			if ($loop->have_posts()) {  ?>
		 			<div id="contentmsg" class="wrappcontent">
			 			<div class="messages-listing">
			 				<?php  while ($loop->have_posts())	{  $loop->the_post(); ?>
			 					<?php $page_id = get_the_ID(); ?>
			 					<?php $customer = get_field('customer', $page_id); ?>
			 					<?php $contractor = get_field('contractor', $page_id); ?>
			 					<a href="<?php echo get_permalink($page_id); ?>" class="message-item white-box <?php $check = check_new_messages($page_id); if($check == true){ echo "new"; } ?>  no-img"> 
									<div class="text-content">
										
										<?php $last_row =  get_last_message($page_id); //var_dump($last_row); ?>
										<div class="row">
											<div class="cell x4">
												<h3 class="projectname"><b><?php _e('Project Name: ', THEME_TEXT_DOMAIN); ?></b><?php echo get_project_name_from_message($page_id); ?></h3>
												<h3 class="projectname"><b><?php _e('Contractor: ', THEME_TEXT_DOMAIN); ?></b>
												<?php // other email address 
													if($customer['ID'] == $user_ID ){
														echo ucwords($contractor['user_firstname']).' '.ucwords($contractor['user_lastname']); //echo $contractor['user_email'];
													}else{
														echo ucwords($customer['user_firstname']).' '.ucwords($customer['user_lastname']); //echo $customer['user_email'];
													}
												?></h3>

											</div>
											<div class="cell x6 markasunread">
												<h3><strong><?php _e('Last Message', THEME_TEXT_DOMAIN); ?></strong></h3>
												<p><strong><?php _e('From: ', THEME_TEXT_DOMAIN); ?></strong><?php echo ucwords($last_row['user']['user_firstname']).' '.ucwords($last_row['user']['user_lastname']); //echo ' ('.$last_row['user']['user_email'].')';  ?></p>
												<p><?php echo substr(strip_tags($last_row['message']), 0, 80).'...';  ?></p>
											</div>
											<div class="cell x2 datearea">
												<h3><strong><?php _e('Date:', THEME_TEXT_DOMAIN); ?></strong></h3>
												<span><?php 
													$date 	= $last_row['date']; 
													$date 	= new DateTime($date);
												?>
												<?php echo $date->format('d/m/Y'); ?>
												<br/><?php  echo $date->format('H:i:s'); ?></span>
												
											</div>
										</div>
									</div>
								</a>
			 				<?php }	?>
					 	</div>	
					 	<div class="pagination">
					 		<?php wp_pagenavi(array( 'query' => $loop )); ?>
					 	</div>
					 </div>
					<?php wp_reset_postdata(); ?>	
				<?php }else{	?>
					<?php if(isset($_POST['search']) && strlen($_POST['search'])){ ?>
					<div class="content-wrapper">
						<div class="full-content">
							<?php $txt = get_field('no_results_found', 'options'); ?>
							<?php if(!empty($txt)){ ?>
								<?php echo $txt; ?>
							<?php } ?>
						</div>
					</div>
					<?php } else { ?>
						
						<div class="content-wrapper">
							<div class="full-content">
								<div class="bids-listing ">
									<div class="no-bids noopacity">
										<i class="fa fa-frown-o"></i>
										<p><?php _e('You don\'t have any messages.' , THEME_TEXT_DOMAIN); ?></p>
										<?php $customer_create_project = get_field('customer_create_new_messages', 'options'); ?>
										<?php if(!empty($customer_create_project)){ ?>
											<a href="<?php echo get_permalink($customer_create_project->ID);?>"  class="button" title=""><?php _e('Create New Message', THEME_TEXT_DOMAIN); ?></a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>

					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } else { ?>
	<div class="default-page">
		<div class="main-content">
			<div class="container">
				<div class="content-wrapper">
					<div class="full-content">
						<?php $txt = get_field('no_access_pages_customer_and_contractor', 'options'); ?>
						<?php if(!empty($txt)){ ?>
							<?php echo $txt; ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php get_footer();