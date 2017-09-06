<div id="login-popup" style="display: none; width: 510px;">
	<div class="popup-content">
		<div class="popup-header">
			<h4><?php _e('Login', THEME_TEXT_DOMAIN); ?></h4>
			<a href="" class="close-popup" title=""><i class="fa fa-times"></i></a>
		</div>
		<div class="popup-text-content">
			<?php if(check_user_login() == false ){ // if is nobody logged?>
				<?php /*<p><?php _e('Welcome back!', THEME_TEXT_DOMAIN); ?></p>*/?>
				<div class="wrappload">
					<div class="loader">
						<img src="img/loading.gif" alt="">
					</div>
					<div class="contentloader">
						<?php $my_account = get_field('client_dashboard', 'options')->ID; ?>
						<form  action="" class="login_form" name="login_form1"  method="POST" enctype="multipart/form-data" data-redirect="<?php echo get_permalink($my_account); ?>">
							<div class="input-block">
								<div class="icon"><i class="fa fa-envelope-o"></i></div>
								<input type="email" name="login_email" placeholder="<?php _e('Email', THEME_TEXT_DOMAIN); ?>" required >
							</div>
							<div class="input-block"> 
								<div class="icon"><i class="fa fa-lock"></i></div>
								<input type="password" name="login_password" placeholder="<?php _e('Password', THEME_TEXT_DOMAIN); ?>" required >
							</div>
							<div class="floating-objects">
								<a href="#forgot-password-popup" title="" class="fancybox_item left"><i class="fa fa-question-circle"></i><?php _e('Forgot Password', THEME_TEXT_DOMAIN); ?></a>
								<a href="#sign-up-popup" title="" class="fancybox_item right"><i class="fa fa-pencil"></i><?php _e('Not registered?', THEME_TEXT_DOMAIN); ?></a>
							</div>
							<input type="hidden" value="<?php echo wp_create_nonce('submit-login'); ?>" name="submit-login">
							<input type="hidden" value="login_user_form" name="action">

							<div class="submitarea">
								<input type="submit" class="button" value="<?php _e('Enter', THEME_TEXT_DOMAIN); ?>"> 
								<i class="fa fa-angle-right"></i>
							</div>
							<div class="responsecheck_login"></div>

							<div class="centered-content"><p><?php _e('Or', THEME_TEXT_DOMAIN); ?></p></div>
							<?php /*<a href="" title="" class="button facebook"><i class="fa fa-facebook"></i><?php _e('Sign Up with Facebook', THEME_TEXT_DOMAIN); ?></a>*/?>
							<div class="sociallogin">
								<?php do_action('facebook_login_button'); // login & register facebook ?>
							</div>
							<div class="gpwrapp"> 
								<div id="gSignInWrapper">
								    <div id="customBtn3" class="customGPlusSignIn button google " data-type="login"><i class="fa fa-google-plus"></i><?php _e('Connect with Google+', THEME_TEXT_DOMAIN); ?></div>
								</div>
								<div class="errorgplus"></div> 
							</div> 
							<?php /*
							<a href="" title="" class="button google"><i class="fa fa-google-plus"></i><?php _e('Sign Up with Google+', THEME_TEXT_DOMAIN); ?></a>*/?>
						</form>
					</div>
				</div>
			<?php } else { ?>
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
		</div>
	</div>
</div>