<?php /*Template Name: Contractor - Activate Account Change Password */
get_header(); 

$user_id 		= $_GET['id'];
$token_id 		= $_GET['token'];

$token_id_field = get_field('hash_inactive_account', 'user_'. $user_id); 

$user = get_user_by('ID', $user_id );
$string_to_md5 = $user->user_email.''.$user->first_name.''.$user->last_name.''.$user_id ;


$output = md5($string_to_md5);
//var_dump($output);


if( !is_user_logged_in() && isset($user_id) && isset($token_id) && strcmp($token_id, $token_id_field )==0){  ?>
	<?php if(strcmp($output, $token_id) == 0 ){ ?>	
		<div class="full-screen-wrapper">
			<div class="inner-box">
				<?php $inactive_account = get_field('inactive_account', 'user_'.$user_id ); ?>
				<?php if($inactive_account == true){  ?>
					<div class="box-header"><h4><?php _e('Change Password', THEME_TEXT_DOMAIN); ?></h4></div>
					<div class="box-content">
						<div class="formpassword">
							
							<div class="box-content-site"><?php echo get_the_content(); ?></div>
							<form  action="" class="form_get_new_password" name="form_get_new_password"  method="POST" enctype="multipart/form-data" >
								
								<div class="input-block-wrapper">
									<label><?php _e('New Password', THEME_TEXT_DOMAIN); ?></label>
									<input type="password" name="contractor_new_password">
								</div>
								 
								<div class="input-block-wrapper">
									<label><?php _e('Confirm Password', THEME_TEXT_DOMAIN); ?></label>
									<input type="password" name="contractor_new_password2">
								</div>

								<input type="hidden" value="<?php echo wp_create_nonce('submit-get-new-password'); ?>" name="submit-get-new-password">
								<input type="hidden" name="user_id_send" value="<?php echo $user_id ; ?>">
								<input type="hidden" name="user_token_send" value="<?php echo $token_id; ?>">
								<input type="hidden" value="user_get_new_password" name="action">
								<div class="responsecheck_password"></div>
								<div class="submitarea">
									<input type="submit" class="button" value="<?php _e('Change Password', THEME_TEXT_DOMAIN); ?>"> 
								</div>
							</form>
							
						</div>	
					</div>
				<?php } else { ?>
					<?php $account_already_activated = get_field('account_already_activated', 'options'); ?>
					<?php if(!empty($account_already_activated)){ ?>
						<div class="box-content-site box-content-site2"><?php echo $account_already_activated; ?></div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	<?php } else { ?>
		<?php $invalid_token = get_field('invalid_token', 'options'); ?>
		<?php if(!empty($invalid_token)){ ?>
			<div class="default-page">
				<div class="main-content">
					<div class="container">
						<div class="content-wrapper">
							<div class="full-content">
								<?php echo $invalid_token; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	<?php } ?>

<?php } else { ?>
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
<?php } ?>
<?php get_footer();