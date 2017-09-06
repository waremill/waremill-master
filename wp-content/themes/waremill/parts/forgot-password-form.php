<div id="forgot-password-popup" style="display: none; width: 510px;">
	<div class="popup-content">
		<div class="popup-header">
			<h4><?php _e('Forgot Password', THEME_TEXT_DOMAIN); ?></h4>
			<a href="" class="close-popup" title=""><i class="fa fa-times"></i></a>
		</div>
		<div class="popup-text-content">
			<?php /*<p><?php _e('Please check your email!', THEME_TEXT_DOMAIN); ?></p>*/?>
			<?php if(check_user_login() == false ){ // if is nobody logged?>
			<div class="wrappload">
				<div class="loader">
					<img src="img/loading.gif" alt="">
				</div>
				<div class="contentloader">
					<form  action="" id="forgot_email" name="forgot__form"  method="POST" enctype="multipart/form-data" >
						<div class="input-block">
							<div class="icon"><i class="fa fa-envelope"></i></div>
							<input type="email" name="forgot_email" placeholder="Email" required="required">
						</div>
						<input type="hidden" value="<?php echo wp_create_nonce('submit-forgot'); ?>" name="submit-forgot">
						<input type="hidden" value="forgot_user_form" name="action">

						<div class="submitarea">
							<input type="submit" class="button" value="<?php _e('SEND', THEME_TEXT_DOMAIN); ?>"> 
							<i class="fa fa-angle-right"></i>
						</div>
						<div class="responsecheck_forgot"></div>
					</form> 
				</div>
			</div>
			<?php }else { ?>
				<?php $login_already_logged = get_field('login_already_logged', 'options'); ?>
				<?php if(!empty($login_already_logged)){ ?>
					<div class="infocontent">
						<h2><?php _e('Hi', THEME_TEXT_DOMAIN); ?> <?php echo do_shortcode('[username]'); ?>,</h2>
						<?php echo $login_already_logged; ?>
						<div class="button button_wrapp" >
							<?php echo wp_loginout($login_page, 'Logout' ); // redirect on home page ?>
						</div>
					</div>	
				<?php } ?>
			<?php } ?>
			<?php /*
			<a href="#login-popup" class="fancybox_item" title=""><i class="fa fa-angle-left"></i> <?php _e('Back', THEME_TEXT_DOMAIN); ?></a>
			<?php */ ?>
		</div>
	</div>
</div>