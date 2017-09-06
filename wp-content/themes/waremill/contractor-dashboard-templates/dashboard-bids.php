<?php /*Template Name: Contractor - Dashboard - Bids  */



if(isset($_SESSION['undone_message'] )  && $_SESSION['undone_message'] == true ){
	unset($_SESSION['undone_message']);
	
	// redirect page -> create message
	$contractor_create_new_messages = get_field('contractor_create_new_messages', 'options')->ID; 
	$contractor_create_new_messages_link =  get_permalink($contractor_create_new_messages); 
	header('Location: '.  $contractor_create_new_messages_link);  exit();

	// var_dump($_SESSION['undone_message'] );
} else { 


get_header(); ?>
<?php if ( is_user_logged_in() && check_role_current_user('contractor' ) == true) { // user logged and with customer role ?>
	<?php $user = wp_get_current_user(); 
	 	$user_ID = $user->ID;
	 	$page_id = get_the_ID();
	 	$no_bids = get_field('no_bids_available', $page_id);
 	?>

	<div class="dashboard-page">
		<div class="main-content">
			<div class="container">
				<h1><?php _e('My Active Bids', THEME_TEXT_DOMAIN); ?></h1>
				<div class="sep"></div>
				<?php 
					$args =  array( 
				        'ignore_sticky_posts' 	=> true, 
				        'post_type'           	=> 'bid',
				        'order'              	=> 'DESC',
				        'posts_per_page'		=> -1,
				        'meta_query' => array(
				        	'relation' => 'AND',   
						    array(
						    	'key'		=> 'bidder',
						        'compare'	=> '=', 
						        'value'		=> $user_ID,
						    ),
						    array (
						        'key' 		=> 'project', 
						        'value' 	=> '', 
						        'compare' 	=> '!=',
						    ),  
					    ),
					);   

				 	$loop = new WP_Query( $args ); 
				 	$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				 	$loop = new WP_Query( $args ); 

		 			$array_bids = array();
		 			if ($loop->have_posts()) {
		 				while ($loop->have_posts())	{  $loop->the_post(); 
		 					$bid_id 		= get_the_ID(); 
		 					$project 		= get_field('project',  $bid_id );
		 					$project_id 	= $project->ID;
		 					$array_bids[] 	= $project_id;
		 				} 
		 				wp_reset_postdata();
		 				$result = array_unique($array_bids);


		 				if(sizeof($result) > 0 ){
		 					
		 					$today 	= date('Ymd');
							$args_2 =  array( 
				                'ignore_sticky_posts' 	=> true, 
				                'post_type'           	=> 'project',
				                'order'              	=> 'DESC',
				                'posts_per_page'		=> 10,
				                'post__in'				=> $result,
				                'meta_query' 			=> array(
									array(
								        'key'		=> 'project_expire_date_project',
								        'compare'	=> '>',
								        'value'		=> $today,
								    )
							    )
							);   

							
				        	$args_2['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
						 	$loop_2 = new WP_Query( $args_2 ); 
				 			$count = 1 ;
				 			if ($loop_2->have_posts()) {  ?>
				 				<div class="project-listing">
					 				<?php while ($loop_2->have_posts())	{  $loop_2->the_post(); ?>
					 					<?php $project_id = get_the_ID(); ?>
					 					<div class="project-item white-box simple">
											<div class="left-details">
												<?php echo get_bidders_no($project_id); ?>
												<?php echo get_creation_date($project_id); ?>
												<?php echo get_expiration_date($project_id);?>
											</div>
											<div class="text-content">
												<div class="project-header">
													<h2 class="left">
														<div class="icon"><i class="fa fa-file-text-o"></i></div>
														<a href="<?php echo get_permalink($project_id); ?>" title=""><?php echo get_the_title($project_id); ?></a>
													</h2>
													<div class="project-options right">
														<?php $box = get_field('files_project', $project_id); ?>
														<?php	if ($box){ ?>
															<div class="has-at left">
																<i class="fa fa-paperclip"></i>
																<?php _e('The project containers attachments', THEME_TEXT_DOMAIN); ?>
															</div>
														<?php } ?>
														<?php /*
														<a href="<?php echo get_permalink($project_id); ?>" title="View" class="left">
															<i class="fa fa-eye" aria-hidden="true"></i>
															<p class="smalllabel"><?php _e('View', THEME_TEXT_DOMAIN); ?></p>
														</a>
														<?php */?>
													</div>
												</div>
												<div class="floating-objects">
													<div class="description">
														<h6><?php _e('Description', THEME_TEXT_DOMAIN); ?></h6>
														<?php 

															$content_post = get_post($project_id);
															$content = $content_post->post_content;
															$content = apply_filters('the_content', $content);
															$content = str_replace(']]>', ']]&gt;', $content);
															echo $content;
														?>
													</div>
													<?php $country_project = get_field('country_project', $project_id); ?>
													<?php if(!empty($country_project)){?>
														<div class="countries">
															<h6><?php _e('Country', THEME_TEXT_DOMAIN); ?></h6>
															<p><?php echo $country_project; ?></p>
														</div>
													<?php } ?>

													<?php $customer_hired = get_field('customer_hired', $project_id); //var_dump($customer_hired); ?>
													<div class="countries">
														<?php if(!empty($customer_hired)){ ?>
															<?php if($customer_hired['ID'] == $user_ID){ ?>
																<div class="project-done">
																	<h6><i class="fa fa-check" aria-hidden="true"></i> <?php _e('Hired', THEME_TEXT_DOMAIN); ?></h6>
																	<p class="fsuccess"><?php _e('YES!', THEME_TEXT_DOMAIN); ?></p>
																</div>
															<?php }else{ ?>
																<div class="project-notdone">
																	<h6><i class="fa fa-times" aria-hidden="true"></i> <?php _e('Hired', THEME_TEXT_DOMAIN); ?></h6>
																	<p class="ferror"><?php _e('Not yet', THEME_TEXT_DOMAIN); ?></p>
																</div>
															<?php } ?>
														<?php } else { ?>
																<div class="project-notdone">
																	<h6><i class="fa fa-times" aria-hidden="true"></i> <?php _e('Hired', THEME_TEXT_DOMAIN); ?></h6>
																	<p class="ferror"><?php _e('Not yet', THEME_TEXT_DOMAIN); ?></p>
																</div>
														<?php } ?>
													</div>

												</div>

												<div class="hidden-content">
													<?php echo get_project_inf($project_id); ?>
												</div>
												<p><a href="#" class="expand-details" title=""><?php _e('More Details', THEME_TEXT_DOMAIN); ?></a></p>
											</div>
										</div>
					 				<?php } ?>
							 	 	<div class="pagination">
										<?php wp_pagenavi(array( 'query' => $loop_2 )); ?>
									</div>
								</div>
							<?php }else{ ?>
								<?php if(!empty($no_bids)){ ?>
			 						<?php echo $no_bids; ?>
			 					<?php } ?>
							<?php } ?>
						 	<?php wp_reset_postdata();
		 				}else{ ?>
		 					<?php // you do not bid to projects => buttons search projects ?>
		 					<?php if(!empty($no_bids)){ ?>
		 						<?php echo $no_bids; ?>
		 					<?php } ?>
		 				<?php } ?>
					<?php } else { ?>
						<?php // you do not bid to projects => buttons search projects ?>
						<?php if(!empty($no_bids)){ ?>
		 					<?php echo $no_bids; ?>
		 				<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
 	
<?php } else { ?>
	<?php if( !is_user_logged_in()){ ?>
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
	<?php } else { // login_but_without_access_on_this_page  ?>
		<div class="default-page">
			<div class="main-content">
				<div class="container">
					<div class="content-wrapper">
						<div class="full-content">
							<?php $txt = get_field('login_but_without_access_on_this_page', 'options'); ?>
							<?php if(!empty($txt)){ ?>
								<?php echo $txt; ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>
<?php get_footer(); 

}