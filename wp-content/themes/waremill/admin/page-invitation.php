<?php /*Template Name: Invitation */
get_header(); ?>
<?php if ( is_user_logged_in() && (check_role_current_user('administrator' ) == true ) ){ // || check_role_current_user('contractor' ) == true)  ) { // user logged and with customer role ?>
	
<div class="default-page">
	<div class="main-content">
		<div class="container">
			<div class="content-wrapper">
				<div class="full-content">
					<h1><?php echo get_the_title(); ?></h1>
					<div class="sep"></div>
					
					<div class="row ">
						<div class="cell x12">
							<h4><?php _e('List of inactive users', THEME_TEXT_DOMAIN); ?></h4>
						</div>
					</div>
						
					<div class="wrappload">
						<div class="statussendinv"></div>
						<div class="loader largeloader">
							<img src="img/loading.gif" alt="">
						</div>
						<div class="contentloader"> 
							<form action="" class="send_invitation_form" name="send_invitation_form"  method="POST" enctype="multipart/form-data" >
								<div class=" selectionusers">
									<div class="cell x5">
								        <select name="from[]" id="search" class="form-control" size="10" multiple="multiple">
								           <?php 

											$args = array(
												'role__in' => array('Contractor'),
												'meta_query' => array(
													'relation'		=> 'AND',
													array(
														'key'     => 'inactive_account',
														'value'   => '0',
											 			'compare' => '!='
													),
													array(
												        'key'       => 'associated_company',
												        'value' 	=> '', 
										        		'compare' 	=> '!=',
												    )
												), 
											);

											$user_query = new WP_User_Query( $args );
											if ( ! empty( $user_query->results ) ) {
												foreach ( $user_query->results as $user ) { ?>
													<option value="<?php echo $user->ID; ?>"><?php echo ucwords($user->user_firstname).' '.ucwords($user->user_lastname). ' ('.$user->user_email.')'; ?></option>
												<?php 
												}
											}  ?>
								        </select>
								    </div>
							    
								    <div class="cell x2">
								    	<button type="button" id="search_rightSelected" class="btn btn-block"><i class="fa fa-angle-right" aria-hidden="true"></i></button>
								        <button type="button" id="search_rightAll" class="btn btn-block"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
								        <button type="button" id="search_leftSelected" class="btn btn-block"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
								        <button type="button" id="search_leftAll" class="btn btn-block"><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
								    </div>
							    
								    <div class="cell x5">
								        <select name="to[]" id="search_to" class="form-control" size="10" multiple="multiple"></select>
								    </div>
							    </div>

							    <div class="row  centered-content">
									<div class="cell x3 no-float">
										<input type="hidden" value="<?php echo wp_create_nonce('submit-form-invitations'); ?>" name="submit-form-invitations">
										<input type="hidden" value="send_invitation" name="action">
										<div class="submitarea smalltop">
											<input type="submit" class="button" value="<?php _e('Send Invitations', THEME_TEXT_DOMAIN); ?>"> 
											<i class="fa fa-angle-right"></i>
										</div>
									</div>
								</div>
								<div class="responsecheck_send_invitations2"></div>

						    </form>
						    <div class="logarea">
							    <p><?php _e('Logs: ', THEME_TEXT_DOMAIN); ?><span id="statusupdate"></span></p>
							    <div class="responsecheck_send_invitations"></div>
						    </div>
				    	</div>
				    </div>
					

				</div>
			</div>
		</div>
	</div>
</div>

<?php } else { ?>
	<?php if( !is_user_logged_in()){ ?>
	<div class="default-page">
		<div class="main-content">
			<div class="container">
				<div class="content-wrapper">
					<div class="full-content">
						<?php $txt = get_field('no_access_pages_customer_and_contractor', 'options'); ?>
						<?php if(!empty($txt)){ ?>
							<?php echo $txt; ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } else { // login_but_without_access_on_this_page  ?>
		<div class="default-page">
			<div class="main-content">
				<div class="container">
					<div class="content-wrapper">
						<div class="full-content">
							<?php $txt = get_field('login_but_without_access_on_this_page', 'options'); ?>
							<?php if(!empty($txt)){ ?>
								<?php echo $txt; ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>

<?php get_footer();	