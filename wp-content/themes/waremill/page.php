<?php 
defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );
get_header(); ?>
<div class="default-page">
	<div class="main-content">
		<div class="container">
			<div class="content-wrapper">
				<div class="full-content">
					<h1><?php echo get_the_title(); ?></h1>
					<div class="sep"></div>
					<?php
						$img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
						if($img[0]){
							echo '<img src="'.$img[0].'" alt="'.get_the_title().'" /><p>&nbsp;</p>';
						}
					?>
					<?php if (have_posts()) : while (have_posts()) : the_post();?>
					    <?php the_content(); ?>
					<?php endwhile; endif; ?> 
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer();