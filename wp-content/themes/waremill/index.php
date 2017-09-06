<?php 
defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );
get_header(); ?>
<div class="blog-page">
	<div class="main-content">
		<div class="container">
			<div class="content-wrapper">
				<div class="content left listingarea">
					<h1><?php if ( is_404() || is_category() || is_tag() || is_day() || is_month() || is_year() || is_search() ) { ?>
						<?php /* If this is a category archive */ if (is_category()) { ?>
							<?php single_cat_title(); ?>
						<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
							<?php single_tag_title(); ?>
						<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
							<?php the_time('F jS, Y'); ?> Archives
						<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
							<?php the_time('F, Y'); ?> Archives
						<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
							<?php the_time('Y'); ?> Archives
						<?php /* If this is an author archive */ } elseif (is_author()) { ?>
							<?php _e('Author Archive', THEME_TEXT_DOMAIN); ?>
						<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
							<?php _e('Blog Archives', THEME_TEXT_DOMAIN); ?>
						<?php } elseif( is_search() )	{ ?>
							<?php _e('Results for :', THEME_TEXT_DOMAIN); ?> <?php the_search_query() ?>
						<?php } ?>
					<?php } else { ?>
						<?php _e('Forum :', THEME_TEXT_DOMAIN); ?> <?php if ( $paged ) {echo  _e(' - Page ', THEME_TEXT_DOMAIN). $paged; } ?>
					<?php } ?></h1>
					<div class="sep"></div>
					<?php
						global $wp_query;
						$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
						query_posts( array_merge( $wp_query->query_vars, array( 'ignore_sticky_posts' => true, 'paged' => $paged ) ) );?>
						<?php if (have_posts())	: $x = 0; ?>
							<div class="articles-listing">
							<?php while (have_posts())	: the_post(); $x++; ?>
								<?php $id = get_the_ID(); ?>
								<?php $post = get_post( $id ); ?>
								<?php $type = $post->post_type; ?>
								<article>
									<div class="text-content text-content-full ">
										<?php if(strcmp($type, 'post') == 0 ){ ?>
											<?php if(check_user_login()==true ){ ?>
												<h3><a href="<?php echo get_permalink( $id ); ?>" title=""><?php echo get_the_title(); ?></a></h3>
											<?php } else { ?>
												<h3 class="fancybox"><a href="#sign-up-popup" title=""><?php echo get_the_title(); ?></a></h3>
											<?php } ?>
										<?php } else { ?>
											<h3><a href="<?php echo get_permalink( $id ); ?>" title=""><?php echo get_the_title(); ?></a></h3>
										<?php } ?>

										<div class="authorpg">
											<p class="authorpost"><i class="fa fa-pencil" aria-hidden="true"></i><?php 
												$auth = get_post($project_id); // gets author from post 
												$authid = $auth->post_author;
												$user_author = get_user_by('ID', $authid);
												//var_dump($user_author);
												// echo $user_author->user_email;
												echo ucwords($user_author->user_firstname).' '.ucwords($user_author->user_lastname);
											?> </p>
											<p class="authorpost"><i class="fa fa-calendar" aria-hidden="true"></i><?php echo get_the_date('d/m/Y'); ?></p>
											<p class="authorpost"><i class="fa fa-commenting-o" aria-hidden="true"></i><?php $comments_count = wp_count_comments( get_the_ID() ); ?>
												<?php if($comments_count->approved == 1) { 
															echo '1 Comment';
														} else {
															echo $comments_count->approved .' Comments';
														}
											?></p>

											<?php
												$terms = get_the_terms( $post->ID, 'category' );
												if ( $terms && ! is_wp_error( $terms ) ) : 
													$draught_links = array();
													foreach ( $terms as $term ) { $term_link = get_term_link( $term );  
														$draught_links[] = '<a href="'.$term_link.'" title="">'. $term->name .'</a>'; //$term->name;
													}
													$on_draught = join( ", ", $draught_links );
												?>
												<p class="authorpost"><i class="fa fa-database" aria-hidden="true"></i><?php echo $on_draught; ?></p>
											<?php endif; ?>

											<?php
											    $posttags = get_the_tags();
											    $count=0; 
											    if ($posttags) { 
											    	$draught_links = array();
											        foreach($posttags as $tag) { 
											        	$draught_links[] = '<a href="'.get_tag_link($tag->term_id).'" title="">'. $tag->name .'</a>';
											        }
											        $on_draught = join( ", ", $draught_links ); ?>
											        <p class="authorpost"><i class="fa fa-tag" aria-hidden="true"></i><?php echo $on_draught; ?></p>
											<?php } ?> 
										</div> 	
										
										<p><?php echo get_the_excerpt(); ?></p>
										<?php if(strcmp($type, 'post') == 0 ){ ?>
											<?php if(check_user_login()==true){ ?>
												<a href="<?php echo get_permalink( $id ); ?>" class="read-more" title=""><?php _e('View more', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
											<?php } else { ?>
											<div  class="fancybox">
												<a href="#sign-up-popup" class="read-more" title=""><?php _e('View more', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
											</div>
											<?php } ?>
										<?php } else { ?>
											<a href="<?php echo get_permalink( $id ); ?>" class="read-more" title=""><?php _e('View more', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
										<?php } ?>
									</div>
									
								</article>	
							<?php endwhile; ?>
							</div>
							<div class="pagination"><?php
								global $wp_query;
								$big = 999999999; 
								echo paginate_links( array(
									'base' 			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
									'format' 		=> '?paged=%#%',
									'current' 		=> max( 1, get_query_var('paged') ),
									'total' 		=> $wp_query->max_num_pages,
									'type' 			=> 'list',
									'prev_next'    	=> True,
									'prev_text'    	=> '<i class="fa fa-caret-left"></i>' ,
									'next_text'    	=> '<i class="fa fa-caret-right"></i>' 
										
								) );
							?></div>
						<?php endif; ?>
				</div>
				<div class="sidebar-wrapper right">
					<?php include_once("parts/blog-sidebar.php"); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>