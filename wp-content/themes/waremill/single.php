<?php /*Template Name: Article Page*/
defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );
get_header(); ?>
<?php if ( is_user_logged_in() ) { // user logged and with customer role ?>
	<?php 
		$project_id = 0; 
		$project_id = get_the_ID(); 
		$user 		= wp_get_current_user(); 
		$user_ID 	= $user->ID;


	?>
	<div class="article-page">
		<div class="main-content">
			<div class="container">
				<div class="content-wrapper">
					<div class="content left">
						
						<div class="margintitle">
							<h1><?php echo get_the_title(); ?></h1>
							<?php  
								$auth = get_post($project_id); // gets author from post 
								$authid = $auth->post_author; // gets author id for the post
								if(strcmp($user_ID, $authid) == 0 ){ ?>
									<div class="absview">
										<a href="<?php echo get_permalink(get_field('edit_post_forum', 'options')->ID); ?>?id=<?php echo $id;  ?>" title="" class="button button-edit"><?php _e('Edit', THEME_TEXT_DOMAIN); ?><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									</div>
								<?php } ?>
						</div>
						<div class="sep"></div>
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
						
						<?php if (have_posts()) : while (have_posts()) : the_post();?>
							<?php the_content(); ?>
						<?php endwhile; endif; ?>
						
						<div class="comments comment-box">
							<div class="inner-content">
							<?php 
								// If comments are open or we have at least one comment, load up the comment template.
								if ( comments_open() || get_comments_number() ) {
									comments_template();
								}
							?>
							</div>
						</div>

						<?php $forum_page = get_field('forum_page', 'options'); ?>
						<?php if(!empty($forum_page)){ ?>
							<div class="pagination">
								<a href="<?php echo get_permalink($forum_page->ID); ?>" title=""><i class="fa fa-caret-left"></i></a>
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
						<?php $articles_no_access = get_field('articles_no_access', 'options'); ?>
						<?php if(!empty($articles_no_access)){ ?>
							<?php echo $articles_no_access; ?>
						<?php } ?>
						<?php /*$txt = get_field('no_access_pages_customer_and_contractor', 'options'); ?>
						<?php if(!empty($txt)){ ?>
							<?php echo $txt; ?>
						<?php } */?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php get_footer();