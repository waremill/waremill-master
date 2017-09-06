<?php /*Template Name: About*/
defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );
get_header(); ?>
<div class="default-page">
	<div class="main-content">
		<div class="container">
			<div class="content-wrapper">
				<?php if(check_user_login() == true){ ?>
					<div class="full-content">
						<h1><?php echo get_the_title(); ?></h1>
						<div class="sep"></div>
						<?php if (have_posts()) : while (have_posts()) : the_post();?>
						    <?php the_content(); ?>
						<?php endwhile; endif; ?> 
					</div>
				<?php } else { ?>
					<div class="content left">
						<h1><?php echo get_the_title(); ?></h1>
						<div class="sep"></div>
						<?php if (have_posts()) : while (have_posts()) : the_post();?>
						    <?php the_content(); ?>
						<?php endwhile; endif; ?> 
					</div>
					<div class="sidebar-wrapper right">
						<?php include_once("parts/sidebar.php"); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer();