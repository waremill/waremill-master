<div id="sign-up-popup" style="display: none; width: 510px;">
	<div class="popup-content">
		<div class="popup-header">
			<h4><?php _e('Register', THEME_TEXT_DOMAIN); ?></h4>
			<a href="" class="close-popup" title=""><i class="fa fa-times"></i></a>
		</div>
		<div class="popup-text-content">
			<?php if(check_user_login() == false ){ // if is nobody logged?>
				<div class="wrappload">
					<div class="loader">
						<img src="img/loading.gif" alt="">
					</div>
					<div class="contentloader">
						<form action="" id="register_form" name="register_form"  method="POST" enctype="multipart/form-data" >
							<div class="add-project-answer"></div>
							<div class="input-block center">
								<div class="icon"><i class="fa fa-user"></i></div>
								<input type="text" name="register_first_name" placeholder="First Name*" required value="">
							</div>
							<div class="input-block center">
								<div class="icon"><i class="fa fa-user"></i></div>
								<input type="text" name="register_family_name" placeholder="Family Name*" required >
							</div>
							<div class="input-block center">
								<div class="icon"><i class="fa fa-user"></i></div>
								<input type="text" name="register_company_name" placeholder="Company Name" >
							</div>
							<div class="input-block center">
								<div class="icon"><i class="fa fa-envelope-o"></i></div>
								<input type="email" name="register_email" placeholder="Email*" required value="">
							</div>
							<div class="input-block center">
								<div class="icon"><i class="fa fa-lock"></i></div>
								<input type="password" name="register_password" placeholder="Password*" required >
							</div>
							<div class="input-block center">
								<div class="icon"><i class="fa fa-lock"></i></div>
								<input type="password" name="register_password2" placeholder="Confirm Password*" required >
							</div>
							<div class="submitarea">
								<input type="submit" class="button" value="<?php _e('Register', THEME_TEXT_DOMAIN); ?>"> 
								<i class="fa fa-angle-right right"></i>
							</div>
							<input type="hidden" value="<?php echo wp_create_nonce('submit-register'); ?>" name="submit-register">
			                <input type="hidden" value="register_user_form" name="action">
			                <div class="responsecheck_register"></div>


							<div class="centered-content"><p><?php _e('Or', THEME_TEXT_DOMAIN); ?></p></div>
							<?php /*
							<a href="" title="" class="button facebook"><i class="fa fa-facebook"></i><?php _e('Sign Up with Facebook', THEME_TEXT_DOMAIN); ?></a>
							<?php */ ?>
							<div class="sociallogin">
								<?php do_action('facebook_login_button'); // login & register facebook ?>
							</div>

							<div class="gpwrapp"> 
								<div id="gSignInWrapper">
								    <div id="customBtn" class="customGPlusSignIn button google" data-type="register"><i class="fa fa-google-plus"></i><?php _e('Register with Google+', THEME_TEXT_DOMAIN); ?></div>
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
							<?php // $login_page = '';?>
							<?php echo wp_loginout($login_page, 'Logout' ); // redirect on home page ?>
						</div>
					</div>	
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>