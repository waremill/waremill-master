<?php /*Template Name: Customer - Dashboard - Archive*/
get_header(); ?>

<?php  
	$search_service = $_POST['services'];
	$search_industries = $_POST['industries'];
	$search = $_POST['search']; 	
	$search_country = $_POST['country']; 

?>

<?php if ( is_user_logged_in() && check_role_current_user('customer' ) == true) { // user logged and with customer role ?>
	<?php $user = wp_get_current_user(); 
	 	$user_ID = $user->ID;
 	?>
	<div class="dashboard-page">
		<div class="main-content">
			<div class="container">
				<h1><?php _e('Archived Projects', THEME_TEXT_DOMAIN); ?></h1>
				<div class="sep"></div>
				<div class="full-content sidebar-wrapper-full ">
					<?php get_template_part( 'parts/search', 'sidebar'); //include_once(get_bloginfo('template_directory')."parts/search-sidebar.php"); ?>
				</div>
				
				<div class="filtercontent">
				<?php 
					$today 	= date('Ymd');
					$args 	=  array( 
		                'ignore_sticky_posts' 	=> true, 
		                'post_type'           	=> 'project',
		                'order'              	=> 'ASC',
		                'meta_key'				=> 'project_expire_date_project',
						'orderby'				=> 'meta_value',
		                'posts_per_page'		=> 10, 
		                'author' 				=> $user_ID,
		                'meta_query' => array(   // check if date expire is > current date
		                	'relation' 		=> 'AND',
							array(
						        'key'		=> 'project_expire_date_project',
						        'compare'	=> '<=',
						        'value'		=> $today,
						    )
					    )
					);   

				
					if(isset($search_service) && sizeof($search_service)>0){
					    $args['tax_query'][] = array(
                            array(
                                'taxonomy' => 'service',
                                'field'    => 'term_id',
                                'terms'    =>  $search_service,
                            )
                        );
					}

					if(isset($search_industries) && sizeof($search_industries)>0){
						$args['tax_query'][] = array(
                            array(
                                'taxonomy' => 'industry',
                                'field'    => 'term_id',
                                'terms'    =>  $search_industries,
                            )
                        );
					}

					if(isset($search_country) && strlen($search_country) > 0 && strcmp($search_country, "0") != 0){
						$args['meta_query'][] = array(
						    array(
						        'key'       => 'country_project',
						        'value'     => $search_country,
						        'compare'   => '=',
						    )
                        );	
					}

					if(isset( $search) && strlen( $search) > 0){ 
						$args['s'] = $search;
					}

		        	$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				 	$loop = new WP_Query( $args ); 
		 			$count = 1 ;
		 			if ($loop->have_posts()) {  ?>
						<div class="project-listing">
							<?php  while ($loop->have_posts())	{  $loop->the_post(); ?>
								<?php $project_id = get_the_ID(); ?>
								<div class="project-item white-box simple grey" data-project="<?php echo $project_id; ?>">
									<div class="left-details">
										<?php echo get_bidders_no($project_id); ?>
										<?php echo get_creation_date($project_id); ?>
										<?php echo get_expiration_date_archive($project_id);?>
									</div>
									<div class="text-content">
										<div class="project-header">
											<h2 class="left">
												<div class="icon"><i class="fa fa-file-text-o"></i></div>
												<a href="<?php echo get_permalink($project_id); ?>" title=""><?php echo get_the_title($project_id); ?></a>
											</h2>
											<?php /*
											<a href="<?php echo get_permalink($project_id); ?>" class="right">
												<i class="fa fa-eye" aria-hidden="true"></i>
												<p class="smalllabel"><?php _e('View', THEME_TEXT_DOMAIN); ?></p>
											</a>
											<?php */ ?>
											<div class="project-options right">
												<?php echo generate_project_links_delete($project_id); ?>
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

													<?php $user_hider = user_bid($project_id);
														if($user_hider != null){ ?>
														<div class="project-done">
															<h6><i class="fa fa-check" aria-hidden="true"></i> <?php _e('Hired ', THEME_TEXT_DOMAIN); ?></h6>
															<p><?php echo  ucwords($user_hider['user_firstname'].' '.$user_hider['user_lastname'] ); //echo $user_hider['user_email'] ; ?></p>	
														</div>
													<?php }else{ ?>
														<div class="project-notdone">
															<h6><i class="fa fa-times" aria-hidden="true"></i> <?php _e('Hired ', THEME_TEXT_DOMAIN); ?></h6>
															<p class="ferror"><?php _e('Nobody', THEME_TEXT_DOMAIN); ?></p>	
														</div>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<div class="hidden-content">
											<?php echo get_project_inf($project_id); ?>
										</div>
										<p><a href="#" class="expand-details" title=""><?php _e('More Details', THEME_TEXT_DOMAIN); ?></a></p>
									</div>
								</div>
							<?php }	?>
						</div>
						<div class="pagination">
							<?php wp_pagenavi(array('query' => $loop )); ?>
						</div>
						<?php wp_reset_postdata(); ?>
					<?php }else { ?>
						<div class="content-wrapper">
							<div class="full-content">
								<?php $txt = get_field('no_results_found', 'options'); ?>
								<?php if(!empty($txt)){ ?>
									<?php echo $txt; ?>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					
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