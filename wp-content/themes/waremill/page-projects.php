<?php /*Template Name: All Projects*/
get_header(); ?>
<?php //var_dump($_POST); ?>
<?php $search_service = $_POST['services']; ?>
<?php $search_industries = $_POST['industries']; ?>
<?php $search = $_POST['search']; ?>
<?php $search_country = $_POST['country']; ?>
<?php if ( is_user_logged_in() && ( check_role_current_user('contractor' ) == true ) ) { // user logged and with customer role ?>
	<div class="categories-page	dashboard-page nmtop">
		<div class="main-content">
			<div class="container"> 
				<div class="content-wrapper">
					<div class="full-content bigtitle">
						<div class="margintitle">
							<h1><?php echo get_the_title(); ?></h1>
							<?php 
								if ( is_user_logged_in() && check_role_current_user('customer' ) == true ) { // user logged and with customer role ?>
								<div class="absview ">
									<a href="<?php echo get_permalink(get_field('customer_create_project', 'options')->ID) ; ?>" title="" class="button button-view"><?php _e('Create project', THEME_TEXT_DOMAIN); ?> <i class="fa fa-pencil" aria-hidden="true"></i></a>
								</div>
							<?php } ?>
						</div>
						<div class="sep"></div>
					</div>
					
					<div class="full-content sidebar-wrapper-full "> 
						<?php include_once("parts/search-sidebar.php"); ?>
					</div>
					<div class="full-content ">
						
						<?php 
							$posts_per_page = 10; 
							$today = date('Ymd');
							$args =  array( 
				                'ignore_sticky_posts' 	=> true, 
				                'post_type'           	=> 'project',
				                'order'              	=> 'ASC',
				                'orderby' 				=> 'title',
				                'posts_per_page'		=> $posts_per_page ,
				                'meta_query' => array(
				                	'relation'		=> "AND",
									array(
								        'key'		=> 'project_expire_date_project',
								        'compare'	=> '>',
								        'value'		=> $today,
								    ),
								    array(
								    	'key'		=> 'project_author',
								    	'compare'	=> '!=',
								        'value'		=> '',
								    )
							    ),
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

						if(isset( $search) && strlen( $search) > 0){ 
							$args['s'] = $search;
						}	

						if(isset($search_country) && strlen($search_country) > 0 && strcmp($search_country, "0") != 0){
							$args['meta_query'][] = array(
							    array(
							        'key'       => 'country_project',
							        'value'     => $search_country,
							        'compare'   => 'IN',
							    )
							   
	                        );	
						}
						//echo "<pre>";
						//var_dump($args);

			        	$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					 	$loop = new WP_Query( $args ); 
			 			$count = 1 ;
			 			if ($loop->have_posts()) {  ?>
			 				<div class="project-listing">
			 				<?php  while ($loop->have_posts())	{  $loop->the_post(); ?>
			 					<?php $project_id = get_the_ID(); ?>
			 					<div class="project-item white-box simple">
									<div class="left-details">
										<?php echo get_bidders_no($project_id); ?>
										<p><b><?php _e('Created:', THEME_TEXT_DOMAIN); ?></b>
										<?php echo get_the_date('d.m.Y'); ?></p>

										<?php 
											$date = get_field('project_expire_date_project', $project_id);
											$date = new DateTime($date);
										 ?>
										<p><b><?php _e('Expires:', THEME_TEXT_DOMAIN); ?></b>
										<?php echo $date->format('d.m.Y'); ?></p>
									</div>
									<div class="text-content">
										<div class="project-header">
											<h2 class="left">
												<div class="icon"><i class="fa fa-file-text-o"></i></div>
												<a href="<?php echo get_permalink($project_id); ?>" title=""><?php echo get_the_title( $project_id); ?></a>
											</h2>
											<div class="project-options right">
												<?php $box = get_field('files_project', $project_id); ?>
												<?php	if ($box){ ?>
													<div class="has-at left">
														<i class="fa fa-paperclip"></i>
														<?php _e('The project containers attachments', THEME_TEXT_DOMAIN); ?>
													</div>
												<?php } ?>
												<?php // check if is active & if current user is the author for this project ?>
												<?php $id_author = get_the_author_meta('ID');?>
												<?php if($user_ID  == $id_author && check_role_current_user('customer' ) && $archive==false){ ?>
													<a href="<?php echo get_permalink(get_field('customer_project_edit_page_individual', 'options')->ID).'?id='.$project_id; ?>" class="edit-project left">
														<i class="fa fa-pencil-square-o"></i>
														<p class="smalllabel"><?php _e('Edit', THEME_TEXT_DOMAIN); ?></p>
													</a>
												<?php } ?>
											</div>
										</div>
										<div class="floating-objects projectdesc">
											<div class="description">
												<h6><?php _e('Description:', THEME_TEXT_DOMAIN); ?></h6>
												<?php //the_content(); 
												
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
													<h6><?php _e('Country:', THEME_TEXT_DOMAIN); ?></h6>
													<p><?php echo $country_project; ?></p>
												</div>
											<?php } ?>

											<?php echo get_project_industry($project_id); ?>
											<?php echo get_project_service($project_id); ?>
										</div>

										 
										<p><a href="<?php echo get_permalink($project_id); ?>"  title=""><?php _e('More Details', THEME_TEXT_DOMAIN); ?></a></p>
									</div>
								</div>
			 				<?php }	?>
						 	</div> 
						 	<div class="pagination">
								<?php wp_pagenavi(array( 'query' => $loop )); ?>
							</div>
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
						<?php wp_reset_postdata(); ?>

					</div>
					
				</div>
			</div>
		</div>
	</div>
<?php } else if(check_role_current_user('customer' ) == true ){ ?>
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

<?php	} else { ?>
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