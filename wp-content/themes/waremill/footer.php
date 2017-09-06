<?php global $vc; ?>
<?php //echo get_total_nr_unread_messages(); ?>
	<footer>
		<div class="container">
			<div class="row">
				<?php $contact_address = get_field('contact_address', 'options'); ?>
				<?php if(!empty($contact_address)){ ?>
					<div class="cell x3">
						<div class="footer-column">
							<h5><?php _e('Contact', THEME_TEXT_DOMAIN); ?></h5>
							<div class="dec"></div>
							<?php echo $contact_address; ?>
						</div>
					</div>
				<?php } ?>

				<div class="cell x3">
					<div class="footer-column">
						<?php
							$locations = get_nav_menu_locations();
							$menu_id = $locations[ 'footer-menu-1' ] ;
						 	$menu_object = wp_get_nav_menu_object($menu_id  );  ?> 
						<?php $name_menu = $menu_object->name;  ?>
						<h5><?php echo $name_menu; ?></h5>
						<div class="dec"></div>
						<?php $menu = wp_nav_menu( array( 'theme_location' => 'footer-menu-1', 'container' => false, 'depth'  => 1, 'echo'  => false) ); echo $menu; ?>
					</div>
				</div>

				<div class="cell x3">
					<div class="footer-column">
						<?php 
							$locations = get_nav_menu_locations();
							$menu_id = $locations[ 'footer-menu-2' ] ;
						 	$menu_object = wp_get_nav_menu_object($menu_id  ); ?> 
						<?php $name_menu = $menu_object->name;  ?>
						<h5><?php echo $name_menu; ?></h5>
						<div class="dec"></div>
						<?php $menu = wp_nav_menu( array( 'theme_location' => 'footer-menu-2', 'container' => false, 'depth'  => 1, 'echo'  => false) ); echo $menu; ?>
					</div>
				</div>

				<div class="cell x3">
					<div class="footer-column">
						<h5><?php _e('Social media', THEME_TEXT_DOMAIN); ?></h5>
						<div class="dec"></div>
						<div class="social-networks">
							<?php $facebook_link = get_field('facebook_link', 'options'); ?>
							<?php if(!empty($facebook_link)){ ?>
								<a href="<?php echo $facebook_link; ?>" target="_blank" title="" class="facebook"><i class="fa fa-facebook"></i></a>
							<?php } ?>
							<?php $twitter_link = get_field('twitter_link', 'options'); ?>
							<?php if(!empty($twitter_link)){ ?>
								<a href="<?php echo $twitter_link; ?>" target="_blank" title="" class="twitter"><i class="fa fa-twitter"></i></a>
							<?php } ?>
							<?php $google_plus_link = get_field('google_plus_link', 'options'); ?>
							<?php if(!empty($google_plus_link)){ ?>
								<a href="<?php echo $google_plus_link; ?>" target="_blank" title="" class="google"><i class="fa fa-google-plus"></i></a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php $copyright = get_field('copyright', 'options'); ?>
			<?php if(!empty($copyright)){ ?>
				<div class="bottom-footer centered-content">
					<p><?php echo $copyright; ?></p>
				</div>
			<?php } ?>
		</div>
	</footer>

	<script src="https://maps.googleapis.com/maps/api/js?key=<?php $api_google_maps = get_field('api_google_maps', 'options'); echo $api_google_maps; ?>"></script>
	<script src="https://apis.google.com/js/platform.js"></script>
	<?php wp_footer(); ?>
	</div>
</div>
</body>
</html>