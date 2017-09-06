<?php /*Template Name: My Posts*/
get_header(); ?>

<?php if ( is_user_logged_in() ) { // user logged and with customer role ?>
	<?php $user = wp_get_current_user(); 
	 	$user_ID = $user->ID;
 	?>

	<div class="dashboard-page blog-page">
		<div class="main-content">
			<div class="container">
				<div class="content-wrapper">
					<div class="content left">
						<h1><?php echo get_the_title(); ?></h1>
						<div class="sep"></div>
						<?php 
							$args =  array( 
				                'ignore_sticky_posts' 	=> true, 
				                'post_type'           	=> 'post',
				                'order'              	=> 'DESC',
				                'author' 				=> $user_ID,
				                'posts_per_page'		=> 10
							);   

				        	$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
						 	$loop = new WP_Query( $args ); 
				 			$count = 0 ;
				 			if ($loop->have_posts()) {  ?>
				 			<div class="articles-listing">
				 				<?php  while ($loop->have_posts())	{  $loop->the_post(); $count++; ?>
				 					<?php $id = get_the_ID(); ?>
									<article class="eachmypost">
										
										<div class="text-content text-content-full" >
											<h3><a href="<?php echo get_permalink( $id ); ?>" title=""><?php echo get_the_title(); ?></a></h3>
											<?php  
									 		$auth = get_post($project_id); // gets author from post 
											$authid = $auth->post_author; // gets author id for the post
											if(strcmp($user_ID, $authid) == 0 ){ ?>
												<div class="headposts">
													<div class="project-options right">
														<a href="<?php echo get_permalink(get_field('edit_post_forum', 'options')->ID); ?>?id=<?php echo $id; ?>" title="Edit" class="edit-project left">
															<i class="fa fa-pencil-square-o"></i>
															<p class="smalllabel"><?php _e('Edit', THEME_TEXT_DOMAIN); ?></p>
														</a>
														
														<a href="#" class="edit-project  left removepost">
															<i class="fa fa-times-circle" aria-hidden="true"></i>		
															<p class="smalllabel"><?php _e('Remove', THEME_TEXT_DOMAIN); ?></p>								
														</a>

														<div class="removemessage" data-id="<?php echo $id; ?>">
															<div class="wrappremovemsg">
																<div class="wrappload">
																	<div class="loader">
																		<img src="img/loading.gif" alt="">
																	</div>
																	<div class="contentloader">
																		<div class="wrappbasictext" data-id="<?php echo $id; ?>"> 
																			<p class="large"><?php _e('Are you sure that you want to remove this post?', THEME_TEXT_DOMAIN); ?></p>
																			<div class="wrappbuttons">
																				<a href="#" title="" class="button green nobutton" data-id="<?php echo $id; ?>"><?php _e('NO', THEME_TEXT_DOMAIN); ?> <i class="fa fa-check-circle-o" aria-hidden="true"></i></a>
																				<a href="#" title="" class="button red yesbutton" data-id="<?php echo $id; ?>"><?php _e('YES', THEME_TEXT_DOMAIN); ?> <i class="fa fa-times-circle-o" aria-hidden="true"></i></a> 
																			</div>
																		</div>
																		<div class="answerremoveproject" data-id="<?php echo $id; ?>"></div>
																	</div>
																</div>
															</div>
														</div>

													</div>
												</div> 
										<?php }  ?>	
											<div class="authorpg">
												<p class="authorpost"><i class="fa fa-pencil" aria-hidden="true"></i><?php 
													$auth = get_post($project_id); // gets author from post 
													$authid = $auth->post_author;
													$user_author = get_user_by('ID', $authid);
													//var_dump($user_author);
													echo $user_author->user_email;
												?> </p>
												<p class="authorpost"><i class="fa fa-calendar" aria-hidden="true"></i><?php echo get_the_date('d/m/Y'); ?></p>
												<p class="authorpost"><i class="fa fa-commenting-o" aria-hidden="true"></i><?php $comments_count = wp_count_comments( get_the_ID() ); ?>
													<?php if($comments_count->approved == 1) { 
																echo '1 Comment';
															} else {
																echo $comments_count->approved .' Comments';
															}
												?></p>
											</div>
											<p><?php echo get_the_excerpt(); ?></p>
											<a href="<?php echo get_permalink( $id ); ?>" class="read-more" title=""><?php _e('View more', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
										</div>
									

									</article>
					 			<?php }	?>
					 		</div>	
					 		<div class="pagination">
								<?php wp_pagenavi(array( 'query' => $loop )); ?>
							</div>
						<?php } else{ ?>
							<div class="content-wrapper">
								<div class="full-content">
									<div class="bids-listing ">
										<div class="no-bids noopacity">
											<i class="fa fa-frown-o"></i>
											<p><?php _e('You don\'t have posts.', THEME_TEXT_DOMAIN); ?></p>

											<?php $customer_create_project = get_field('add_new_post_forum', 'options'); ?>
											<?php if(!empty($customer_create_project)){ ?>
												<a href="<?php echo get_permalink($customer_create_project->ID);?>"  class="button" title=""><?php _e('Create New Post', THEME_TEXT_DOMAIN); ?></a>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
					<div class="sidebar-wrapper right">
						<?php include_once("parts/blog-sidebar.php"); ?>
					</div>
				</div>
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