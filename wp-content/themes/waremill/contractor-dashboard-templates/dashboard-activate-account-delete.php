<?php /*Template Name: Contractor - Activate Account Delete */
get_header(); 

$user_id 		= $_GET['id'];
$token_id 		= $_GET['token'];

$token_id_field = get_field('hash_inactive_account', 'user_'. $user_id); 

$user = get_user_by('ID', $user_id );
$string_to_md5 = $user->user_email.''.$user->first_name.''.$user->last_name.''.$user_id ;
$output = md5($string_to_md5);


	if(  !is_user_logged_in() && isset($user_id) && isset($token_id) && strcmp($token_id, $token_id_field )==0){  ?>

		<?php if(strcmp($output, $token_id) == 0 ){ ?>
			<div class="full-screen-wrapper">
				<div class="inner-box">
					<?php $inactive_account = get_field('inactive_account', 'user_'.$user_id ); ?>
					<?php if($inactive_account == true){  ?>
						<div class="box-header"><h4><?php _e('Delete Account', THEME_TEXT_DOMAIN); ?></h4></div>
						<div class="box-content">
							<div class="formpassword">
								<div class="wrappload">
									<div class="loader largeloader">
										<img src="img/loading.gif" alt="">
									</div>
									<div class="contentloader">
										<div class="box-content-site">
											<div class="boxhcontent">
												<?php echo get_the_content(); ?>
												<?php $activate_page = get_field('activate_page','options'); ?>
												<?php if(!empty($activate_page)){ ?>
												<div class=" keepaccount">
													<a href="<?php echo get_permalink($activate_page); ?>?id=<?php echo $user_id;?>&token=<?php echo $token_id ;?>" title="" class="button active_button"><?php _e('Activate Account', THEME_TEXT_DOMAIN); ?><i class="fa fa-check-circle" aria-hidden="true"></i></a>
												</div>
												<?php } ?>

												<div class=" keepaccount">
													<a href="#" title="" id="remove_account" data-id="<?php echo $user_id; ?>" data-token="<?php echo $token_id; ?>" class="button remove_button"><?php _e('Delete Account', THEME_TEXT_DOMAIN); ?><i class="fa fa-minus-circle" aria-hidden="true"></i></a>
												</div>
											</div>	

											<div class="outputremove"></div>
										</div>
									</div>
								</div>
								
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