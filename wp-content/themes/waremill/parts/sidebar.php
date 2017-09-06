<div class="sidebar">
	<div class="widget">
		<div class="filter-box">
			<div class="filter-header"><h4><?php _e('Login', THEME_TEXT_DOMAIN); ?></h4></div>
			<div class="inner-content">
				<div class="responselogin"><?php $login  = (isset($_GET['login']) ) ? $_GET['login'] : 0; 
					if ( $login === "failed" ) {
					    echo '<p class="ferror fcenter"><strong>Error:</strong> Invalid username or password.</p>';
					} elseif ( $login === "empty" ) {
					    echo '<p class="ferror fcenter"><strong>Error:</strong> Invalid username or password.</p>';
					} elseif ( $login === "false" ) {
					    echo '<p class="fsuccess fcenter">Successfully logout.</p>';
					} ?>
				</div>
				<div class="formbox2">
					<div class="wrappload">
						<div class="loader">
							<img src="img/loading.gif" alt="">
						</div>
						<div class="contentloader">
							<form  action="" class="login_form" name="login_form2"  method="POST" enctype="multipart/form-data" >
								<div class="input-block-wrapper">
									<label><?php _e('Username', THEME_TEXT_DOMAIN); ?></label>
									<div class="input-block">
										<input type="email" name="login_email"  required value="">
									</div>
									<label><?php _e('Password', THEME_TEXT_DOMAIN); ?></label>
									<div class="input-block">
										<input type="password" name="login_password"  required value="">
									</div>
								</div>
								<div class="responsecheck_login"></div>
								<input type="hidden" value="<?php echo wp_create_nonce('submit-login'); ?>" name="submit-login">
								<input type="hidden" value="login_user_form" name="action">

								<div class="submitarea">
									<input type="submit" class="button" value="<?php _e('Login', THEME_TEXT_DOMAIN); ?>"> 
									<i class="fa fa-angle-right"></i>
								</div>
							</form>
						</div>
					</div>
				</div>
				<a href="#forgot-password-popup" class="forgot fancybox_item"><i class="fa fa-question-circle"></i><?php _e('Forgot password', THEME_TEXT_DOMAIN); ?></a>
				<a href="#sign-up-popup" class="forgot fancybox_item"><i class="fa fa-pencil-square"></i><?php _e('No acccount? Register.', THEME_TEXT_DOMAIN); ?></a>
			</div>
		</div>
	</div>
</div>