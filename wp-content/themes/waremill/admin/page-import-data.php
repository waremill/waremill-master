<?php /*Template Name: Import Data */
get_header(); ?>
<?php if ( is_user_logged_in() && (check_role_current_user('administrator' ) == true ) ){ // || check_role_current_user('contractor' ) == true)  ) { // user logged and with customer role ?>
	
<div class="default-page">
	<div class="main-content">
		<div class="container">
			<div class="content-wrapper">
				<div class="full-content">
					<h1><?php echo get_the_title(); ?></h1>
					<div class="sep"></div>
					
					<div class="wrappload">
						<div class="statussendinv"></div>
						<div class="loader largeloader">
							<img src="img/loading.gif" alt="">
						</div>
						<div class="contentloader"> 
							<form action="" class="import_data_form" name="import_data_form"  method="POST" enctype="multipart/form-data" >
								<div class="row">
								    <div class="cell x5">
								    	<p><strong><?php _e('Import data (only CSV file)', THEME_TEXT_DOMAIN); ?></strong></p>
								        <input type="file" name="csv_file" value="" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
								    </div>
							    </div>

							    <div class="row  centered-content">
									<div class="cell x3 no-float">
										<input type="hidden" value="<?php echo wp_create_nonce('submit-form-import'); ?>" name="submit-form-import">
										<input type="hidden" value="import_data" name="action">
										<div class="submitarea smalltop">
											<input type="submit" class="button" value="<?php _e('Import', THEME_TEXT_DOMAIN); ?>"> 
											<i class="fa fa-angle-right"></i>
										</div>
									</div>
								</div>
								<?php /*<div class="responsecheck_import_data"></div> */?>

						    </form>
						    <div class="logarea">
							    <p><?php _e('Logs: ', THEME_TEXT_DOMAIN); ?><span id="statusupdate"></span></p>
							    <div class="responsecheck_send_invitations responsecheck_import_data"></div>
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