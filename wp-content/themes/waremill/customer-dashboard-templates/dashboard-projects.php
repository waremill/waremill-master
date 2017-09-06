<?php /*Template Name: Customer - Dashboard - My Projects*/

if(isset($_SESSION['undone_message'] ) && $_SESSION['undone_message'] == true ){
	unset($_SESSION['undone_message'] );
	
	// redirect page -> create message
	$customer_create_new_messages = get_field('customer_create_new_messages', 'options')->ID; 
	$customer_create_new_messages_link =  get_permalink($customer_create_new_messages); 
	header('Location: '.  $customer_create_new_messages_link);  exit();
		
	// var_dump($_SESSION['undone_message'] );
} else { 
	get_header(); ?>

	<?php if ( is_user_logged_in() && check_role_current_user('customer' ) == true) { // user logged and with customer role ?>
		<?php $user = wp_get_current_user(); 
		 	$user_ID = $user->ID;
	 	?>
		<div class="dashboard-page">
			<div class="main-content">
				<div class="container">
					<?php // Draft projects ?>
					<?php 
						$args =  array( 
			                'post_type'           	=> 'project',
			                'post_status' 			=> 'draft',
			                'order'              	=> 'ASC',
			                'posts_per_page'		=> -1, 
			                'author' 				=> $user_ID,
			                'meta_key'				=> 'project_expire_date_project',
			                'orderby'				=> 'meta_value',
			                // check if date expire is > current date
						);  
						$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					 	$loop = new WP_Query( $args ); 
			 			$count = 1 ;
			 			if ($loop->have_posts()) {  ?>
			 			<div class="inactiveprojects">
			 				<h1><?php _e('Inactive Projects', THEME_TEXT_DOMAIN); ?></h1>
							<div class="sep"></div> 
			 				<div class="project-listing" >
			 				<?php  while ($loop->have_posts())	{  $loop->the_post(); ?>
			 					<?php $project_id = get_the_ID(); ?>
			 					<div class="project-item white-box simple" data-project="<?php echo $project_id; ?>">
			 						<div class="wrappload">
										<div class="loader">
											<img src="img/loading.gif" alt="">
										</div>
										<div class="contentloader">
											<div class="left-details">
												<?php echo get_bidders_no($project_id); ?>
												<?php // get nr of new bids ?>

												<?php $bid_new_nr = get_bidders_unread($project_id); ?>
												<?php if($bid_new_nr >= 0){ ?>
													<div <?php if($bid_new_nr > 0){ echo "class='bulletnotif'"; } ?>><p ><b><?php _e('New bids:', THEME_TEXT_DOMAIN); ?></b><br/><?php echo $bid_new_nr; ?></p></div>
												<?php } ?>


												<?php echo get_creation_date($project_id); ?>
												<?php echo get_expiration_date($project_id);?>

												<?php // check if someone is hider ?>
												
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

														<?php // see more details project  - in dashboard template ?>										
														<?php /*$proj_pg = get_field('customer_project_page_individual', 'options')->ID; ?>
														<?php if(!empty($proj_pg)){ ?>
															<a href="<?php echo get_permalink($proj_pg); ?>?id=<?php echo $project_id; ?>" title="View in Dashoard" class="edit-project  left">
																<i class="fa fa-external-link" aria-hidden="true"></i>
																<p class="smalllabel"><?php _e('View in Dashoard', THEME_TEXT_DOMAIN); ?></p>
															</a>
														<?php } */ ?>

														<a href="#" data-id="<?php echo $project_id; ?>" title="Activate" class="activate-project left">
															<i class="fa fa-check-circle" aria-hidden="true"></i>
															<p class="smalllabel"><?php _e('Activate', THEME_TEXT_DOMAIN); ?></p>
														</a>	

														<?php echo generate_project_links($project_id); ?>
														
													</div>
												</div>
												<div class="floating-objects">
													<div class="description">
														<h6><?php _e('Description', THEME_TEXT_DOMAIN); ?></h6>
														<?php //echo get_the_content($project_id);
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
												</div>

												<div class="hidden-content" >
													<?php echo get_project_inf($project_id); ?>
												</div>
												<p><a href="#" class="expand-details" title=""><?php _e('More Details', THEME_TEXT_DOMAIN); ?></a></p>
											</div>
											<div class="outputvalidate"></div>
										</div>
									</div>
								</div>
			 				<?php }	?>
						 	</div> 
						 </div>
			 			<?php } ?>
			 			<?php wp_reset_postdata(); ?>
					

					<h1><?php _e('Active Projects', THEME_TEXT_DOMAIN); ?></h1>
					<div class="sep"></div>

					<?php 
						$today = date('Ymd');
						$args =  array( 
			                'ignore_sticky_posts' 	=> true, 
			                'post_type'           	=> 'project',
			                'order'              	=> 'ASC',
			                'meta_key'				=> 'project_expire_date_project',
							'orderby'				=> 'meta_value',
			                'posts_per_page'		=> 10, 
			                'author' 				=> $user_ID,
			                'meta_query' => array(
								array(
							        'key'		=> 'project_expire_date_project',
							        'compare'	=> '>',
							        'value'		=> $today,
							    )
						    ),
			                // check if date expire is > current date
						);   
		        	$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				 	$loop = new WP_Query( $args ); 
		 			$count = 1 ;
		 			if ($loop->have_posts()) {  ?>
		 				<div class="project-listing" >
		 				<?php  while ($loop->have_posts())	{  $loop->the_post(); ?>
		 					<?php $project_id = get_the_ID(); ?>
		 					<div class="project-item white-box simple" data-project="<?php echo $project_id; ?>">
								<div class="left-details">
									<?php echo get_bidders_no($project_id); ?>
									<?php // get nr of new bids ?>

									<?php $bid_new_nr = get_bidders_unread($project_id); ?>
									<?php if($bid_new_nr >= 0){ ?>
										<div <?php if($bid_new_nr > 0){ echo "class='bulletnotif'"; } ?>><p ><b><?php _e('New bids:', THEME_TEXT_DOMAIN); ?></b><br/><?php echo $bid_new_nr; ?></p></div>
									<?php } ?>


									<?php echo get_creation_date($project_id); ?>
									<?php echo get_expiration_date($project_id);?>

									<?php // check if someone is hider ?>
									
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

											<?php // see more details project  - in dashboard template ?>										
											<?php /*$proj_pg = get_field('customer_project_page_individual', 'options')->ID; ?>
											<?php if(!empty($proj_pg)){ ?>
												<a href="<?php echo get_permalink($proj_pg); ?>?id=<?php echo $project_id; ?>" title="View in Dashoard" class="edit-project  left">
													<i class="fa fa-external-link" aria-hidden="true"></i>
													<p class="smalllabel"><?php _e('View in Dashoard', THEME_TEXT_DOMAIN); ?></p>
												</a>
											<?php } */ ?>	

											<?php echo generate_project_links($project_id); ?>
											
										</div>
									</div>
									<div class="floating-objects">
										<div class="description">
											<h6><?php _e('Description', THEME_TEXT_DOMAIN); ?></h6>
											<?php //echo get_the_content($project_id);
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

												<?php $user_hider = user_bid($project_id);
												if($user_hider != null){ ?>
													<div class="project-done">
														<h6><i class="fa fa-check" aria-hidden="true"></i> <?php _e('Hired ', THEME_TEXT_DOMAIN); ?></h6>
														<p><?php echo  ucwords($user_hider['user_firstname'].' '.$user_hider['user_lastname'] ); //$user_hider['user_email'] ; ?></p>	
													</div>
												<?php }else { ?>
													<div class="project-notdone">
														<h6><i class="fa fa-times" aria-hidden="true"></i> <?php _e('Hired ', THEME_TEXT_DOMAIN); ?></h6>
														<p class="ferror"><?php _e('Nobody', THEME_TEXT_DOMAIN); ?></p>	
													</div>
												<?php } ?>

											</div>
										<?php } ?>
									</div>

									<div class="hidden-content" >
										<?php echo get_project_inf($project_id); ?>
									</div>
									<p><a href="#" class="expand-details" title=""><?php _e('More Details', THEME_TEXT_DOMAIN); ?></a></p>
								</div>
							</div>
		 				<?php }	?>
					 	</div> 
					 	<div class="pagination">
							<?php wp_pagenavi(array( 'query' => $loop )); ?>
						</div>
						<?php wp_reset_postdata(); ?>
					<?php }	else { ?>



						<div class="content-wrapper">
							<div class="full-content">
								<div class="bids-listing ">
									<div class="no-bids noopacity">
										<i class="fa fa-frown-o"></i>
										<p><?php _e('You don\'t have active projects.', THEME_TEXT_DOMAIN); ?></p>

										<?php $customer_create_project = get_field('customer_create_project', 'options'); ?>
										<?php if(!empty($customer_create_project)){ ?>
											<a href="<?php echo get_permalink($customer_create_project->ID);?>"  class="button" title=""><?php _e('Create New Project', THEME_TEXT_DOMAIN); ?></a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>

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

}