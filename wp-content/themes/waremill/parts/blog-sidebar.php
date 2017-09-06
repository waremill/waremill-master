<div class="sidebar">
	<?php if(check_user_login()==true ){ ?>
		<?php $add_new_post_forum = get_field('add_new_post_forum', 'options'); ?>
		<?php if($add_new_post_forum){ ?>
			<a href="<?php echo get_permalink($add_new_post_forum->ID); ?>" title="" class="button postbutton"><?php _e('Add New Post', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right" aria-hidden="true"></i></a>
		<?php } ?>
	<?php } ?>

	<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
		<div class="widget">
			<?php dynamic_sidebar( 'sidebar-2' ); ?>
		</div>
	<?php endif; ?>
	
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<div class="widget">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div>
	<?php endif; ?>
</div>

	
