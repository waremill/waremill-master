<?php /*Template Name: Forum Page*/
defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );
get_header(); ?>
<div class="blog-page">
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
			                'posts_per_page'		=> 10
						);   

			        	$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					 	$loop = new WP_Query( $args ); 
			 			$count = 0 ;
			 			if ($loop->have_posts()) {  ?>
			 			<div class="articles-listing">
			 				<?php  while ($loop->have_posts())	{  $loop->the_post(); $count++; ?>
			 					<?php $id = get_the_ID(); ?>
								<article>
								
									<div class="text-content text-content-full ">
										<?php if(check_user_login()==true){ ?>
											<h3><a href="<?php echo get_permalink( $id ); ?>" title=""><?php echo get_the_title(); ?></a></h3>
											<div class="authorpg">
												<p class="authorpost"><i class="fa fa-pencil" aria-hidden="true"></i><?php 
													$auth = get_post($project_id); // gets author from post 
													$authid = $auth->post_author;
													$user_author = get_user_by('ID', $authid);
													//var_dump($user_author);
													echo ucwords($user_author->user_firstname).' '.ucwords($user_author->user_lastname); //$user_author->user_email;
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
										<?php } else { ?>
											<h3 class="fancybox"><a href="#login-popup" title=""><?php echo get_the_title(); ?></a></h3>
										<?php } ?>
										<p><?php echo get_the_excerpt(); ?></p>
										<?php if(check_user_login()==true){ ?>
											<a href="<?php echo get_permalink( $id ); ?>" class="read-more" title=""><?php _e('View more', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
										<?php } else { ?>
										<div  class="fancybox">
											<a href="#login-popup" class="read-more" title=""><?php _e('View more', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
										</div>
										<?php } ?>
									</div>
									
									
								</article>
				 			<?php }	?>
				 		</div>	
				 		<div class="pagination">
							<?php wp_pagenavi(array( 'query' => $loop )); ?>
						</div>
					<?php }	?>
				</div>
				<div class="sidebar-wrapper right">
					<?php include_once("parts/blog-sidebar.php"); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer();