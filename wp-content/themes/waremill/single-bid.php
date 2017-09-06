<?php 
defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );
get_header(); ?>
<div class="full-screen-wrapper">
	<div class="inner-box">
		<?php $title_page_404 = get_field('title_page_404', 'options'); ?>
		<?php if(!empty($title_page_404)){ ?>
			<div class="box-header"><h4><?php echo $title_page_404; ?></h4></div>
		<?php } ?>
		<div class="box-content">
			<?php $content_page_404 = get_field('content_page_404', 'options'); ?>
			<?php if(!empty($content_page_404)){ ?>
				<?php echo $content_page_404; ?>
			<?php } ?>
			<?php $return_page_404 = get_field('return_page_404', 'options'); ?>
			<?php $button_text_404 = get_field('button_text_404', 'options'); ?>
			<?php if(!empty($return_page_404) && !empty($button_text_404)){ ?>
				<a href="<?php echo $return_page_404; ?>" title="" class="button"><?php echo $button_text_404; ?><i class="fa fa-angle-right"></i></a>
			<?php } ?>
			<div class="clear"></div>
			
			<?php $image = get_field('icon_404', 'options');
				if( !empty($image) ): ?>
					<img src="<?php echo $image['sizes']['logo-small']; ?>" alt="<?php echo $image['alt']; ?>" />
			<?php endif; ?>
		</div>
	</div>
</div>
<?php get_footer();