<?php /*Template Name: Contractors Search Page*/
get_header(); ?>
<?php //var_dump($_POST) ; ?>
<?php $search_service = $_POST['services']; ?>
<?php $search_industries = $_POST['industries']; ?>
<?php $search = $_POST['search']; ?>
<?php $search_country = $_POST['country']; ?>
<?php if ( !is_user_logged_in() ) { // user logged and with customer role ?>
	<div class="categories-page">
		<div class="main-content">
			<div class="container">
				<div class="content-wrapper">
					<div class="full-content bigtitle">
						<div class="margintitle">
							<h1><?php echo get_the_title(); ?></h1>
							<div class="sep"></div>
						</div>
					</div>
					
					<div class="full-content sidebar-wrapper-full ">
						<?php include_once("parts/search-sidebar.php"); ?>
					</div>
					<div class="full-content full-content-colums">
				   <?php 
				   		$post_per_page = 20; 

						$args =  array( 
			                'ignore_sticky_posts' 	=> true, 
			                'post_type'           	=> 'contractor',
			                'order'     			=> 'ASC',
           					'meta_key' 				=> 'company_name',
            				'orderby'   			=> 'meta_value',
			                'posts_per_page'		=> $post_per_page,
			               	'meta_query'	 		=> array(
	                         	'relation' => 'AND',
	                         	array(
							        'key'       => 'associated_user',
							        'value' 	=> '', 
					        		'compare' 	=> '!=',
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

						if(isset( $search) && strlen( $search) > 0){ 
							$args['meta_query'][] = array(
	                         	'relation' => 'OR',
							    array(
							        'key'       => 'company_name',
							        'value'     => $search,
							        'compare'   => 'LIKE',
							    ),
							    array(
							        'key'       => 'additional_text',
							        'value'     => $search,
							        'compare'   => 'LIKE',
							    )
	                        );
						}

						if(isset($search_country) && strlen($search_country) > 0 && strcmp($search_country, "0") != 0){
							$args['meta_query'][] = array(
							    array(
							        'key'       => 'country',
							        'value'     => $search_country,
							        'compare'   => 'IN',
							    )
							   
	                        );	
						}

						//var_dump(($args));
			        	$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1; 
					 	$loop = new WP_Query( $args );
					 	$current_page = $args['paged'];
					 	
					 	$nr_post_page = sizeof($loop->posts);  
					 	 
			 			$count = 0 ;
			 			if ($loop->have_posts()) {  $total = $loop->post_count;  ?>
			 				<div class="full-content-colums">
				 				<div class="row products-listing products-listing2">
				 				<?php  while ($loop->have_posts())	{ $count++;  $loop->the_post(); ?>
				 					<?php $contractor_id = get_the_ID(); ?>
					 				<div class="cell x6 each-prod-listing">
					 					<div class="product-line">
											<a href="<?php echo get_permalink($contractor_id); ?>" title="" class="image">
												<?php $logo = get_field('logo', $contractor_id); ?>
												<?php if(!empty($logo)){ ?>
													<img src="<?php echo $logo['sizes']['logo_company']; ?>" alt="">
												<?php } else { ?>
													<img src="img/no-logo-company.jpg" alt="">
												<?php } ?>
											</a>
											<div class="text-content">
												<h3>
													<a href="<?php echo get_permalink(); ?>" title="">
														<?php $company_name = get_field('company_name', $contractor_id); ?>
														<?php if(!empty($company_name )){ ?>
															<?php echo $company_name; ?>
														<?php } else { ?>
															<?php echo get_the_title(); ?>
														<?php } ?>
													</a>
												</h3>
												<?php $country = get_field('country', $contractor_id); ?>
												<?php 
													$terms = get_the_terms( get_the_ID(), 'service' );
													if ( $terms && ! is_wp_error( $terms ) ) : 
													    $draught_links = array();
													    foreach ( $terms as $term ) {
													        $draught_links[] = $term->name;
													    }
													    $service = join( ", ", $draught_links );  ?>
													<?php endif; ?>
												<?php 
													$terms = get_the_terms( get_the_ID(), 'industry' );
													if ( $terms && ! is_wp_error( $terms ) ) : 
													    $draught_links = array();
													    foreach ( $terms as $term ) {
													        $draught_links[] = $term->name;
													    }
													    $industry = join( ", ", $draught_links );  ?>
													<?php endif; ?>	

												<?php if( !empty($country) || !empty($service) || !empty($industry) ) { ?>	
												<div class="services-list">
													<ul>
														<?php if(strlen($industry) > 0 ){ ?>
															<li><b><?php _e('Material:', THEME_TEXT_DOMAIN); ?></b> <?php echo esc_html($industry); ?></li>
														<?php } ?>
														<?php if(strlen($service) > 0 ){  ?>
															<li><b><?php _e('Services:', THEME_TEXT_DOMAIN); ?></b> <?php  echo esc_html($service); ?></li>
														<?php } ?>
														<?php if(strlen( $country )) { ?>
															<li><b><?php _e('Country:', THEME_TEXT_DOMAIN); ?></b> <?php echo  $country; ?></li>
														<?php } ?>
													</ul>
												</div>
												<?php } ?>
											

												<?php $additional_text = get_field('additional_text', $contractor_id); ?>
												<?php if(!empty($additional_text)){ ?>
													<p><?php echo wp_trim_words($additional_text, 19, '...');  ?></p>
												<?php } ?>
												<a href="<?php echo get_permalink($contractor_id); ?>" class="read-more" title="">
													<?php _e('View more', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i>
												</a>
											</div>
										</div>
									</div>
										
									<?php if($count%2 == 0 && $total != $count){ echo '</div><div class="row products-listing products-listing2">'; } ?>
				 				<?php }	?>
				 				</div>
			 				</div>
			 				<?php wp_reset_query(); ?>	
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