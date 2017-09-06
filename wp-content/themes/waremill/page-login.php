<?php /*Template Name: Login*/
if ( !is_user_logged_in()) { 
	defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );
	get_header(); ?>
	<div class="default-page">
		<div class="main-content">
			<div class="container">
				<div class="content-wrapper">
					<div class="full-content">
						<h1><?php echo get_the_title(); ?></h1>
						<div class="sep"></div>
						<div class="formbox formbox2">
							<div class="inner-content ">
								<div class="responselogin"><?php $login  = (isset($_GET['login']) ) ? $_GET['login'] : 0; 
									if ( $login === "failed" ) {
									    echo '<p class="ferror fcenter"><strong>Error:</strong> Invalid username or password.</p>';
									} elseif ( $login === "empty" ) {
									    echo '<p class="ferror fcenter"><strong>Error:</strong> Invalid username or password.</p>';
									} elseif ( $login === "false" ) {
									    echo '<p class="fsuccess fcenter">Successfully logout.</p>';
									} ?>
								</div>
								<div class="wrappload">
									<div class="loader">
										<img src="img/loading.gif" alt="">
									</div>
									<div class="contentloader">
										<form  action="" class="login_form" name="login_form2"  method="POST" enctype="multipart/form-data" >
											<div class="input-block-wrapper">
												<label><?php _e('Email address', THEME_TEXT_DOMAIN); ?></label>
												<div class="input-block">
													<input type="email" name="login_email" required value="">
												</div>
												<label><?php _e('Password', THEME_TEXT_DOMAIN); ?></label>
												<div class="input-block">
													<input type="password" name="login_password" required value="">
												</div>
											</div>
											<input type="hidden" value="<?php echo wp_create_nonce('submit-login-page'); ?>" name="submit-login-page">
											<input type="hidden" value="login_user_form" name="action">
											<div class="responsecheck_login"></div>
											<div class="submitarea">
												<input type="submit" class="button" value="<?php _e('Login', THEME_TEXT_DOMAIN); ?>"> 
												<i class="fa fa-angle-right"></i>
											</div>
										</form>
										<div class="sidebar">
											<a href="#forgot-password-popup" class="forgot fancybox_item"><i class="fa fa-question-circle"></i><?php _e('Forgot password', THEME_TEXT_DOMAIN); ?></a>
											<a href="#sign-up-popup" class="forgot fancybox_item"><i class="fa fa-pencil-square"></i><?php _e('No acccount? Register.', THEME_TEXT_DOMAIN); ?></a>
										</div>
										<div class="centered-content"><p><?php _e('Or', THEME_TEXT_DOMAIN); ?></p></div>
										<div class="sociallogin">
											<?php /*<a href="" title="" class="button facebook"><i class="fa fa-facebook"></i><?php _e('Sign Up with Facebook', THEME_TEXT_DOMAIN); ?></a>*/?>
											<?php do_action('facebook_login_button'); // login & register facebook ?>
											
											
											<div class="gpwrapp"> 
												<div id="gSignInWrapper">
												    <div id="customBtn2" class="customGPlusSignIn button google " data-type="login"><i class="fa fa-google-plus"></i><?php _e('Connect with Google+', THEME_TEXT_DOMAIN); ?></div>
												</div>
												<div class="errorgplus"></div>
											</div> 


											<?php /*	
											<a href="" title="" class="button google"><i class="fa fa-google-plus"></i><?php _e('Sign Up with Google+', THEME_TEXT_DOMAIN); ?></a>
											*/?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php get_footer();
} else {
	header("Location: ". site_url());
}

