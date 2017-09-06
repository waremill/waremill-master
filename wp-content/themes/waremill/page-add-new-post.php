<?php /*Template Name: Add New Post */
defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );
get_header(); ?>

<?php if ( is_user_logged_in() ) { // user logged and with customer role ?>

<?php $homepage = get_option('homepage', 'options')->ID; ?>
	<div class="dashboard-page">
		<div class="main-content">
			<div class="container">
			
				<h1><?php _e('Create new post', THEME_TEXT_DOMAIN); ?></h1>
				<div class="sep"></div>
				<div class="new-project-form">
					<div class="wrappload">
						<div class="loader">
							<img src="img/loading.gif" alt="">
						</div>
						<div class="contentloader">
							<form action="" id="createpost_form" name="createpost_form"  method="POST" enctype="multipart/form-data" novalidate>
								<div class="input-block" data-tip="Please enter post name here.">
									<label><?php _e('Post Name *', THEME_TEXT_DOMAIN); ?></label>
									<input type="text" name="post_name"  required="required" >
								</div>
								<div class="row noovhidden">
								<?php  $terms = get_terms( 
											array(
											    'taxonomy' => 'category',
											    'hide_empty' => false,
											) 
										);
									if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
										<div class="cell x6">
											<div class="input-block-wrapper">
												<label><?php _e('Category *', THEME_TEXT_DOMAIN); ?></label>
												<div class="input-block" data-tip="Select a category.">
													<i class="fa fa-check-circle"></i>
													<div class="select-block">
														<select name="post_categories[]" multiple="multiple" placeholder="Please select a category." class="SlectBox" required="required">
															<?php foreach ( $terms as $term ) { ?>
															    <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="wrappselectitems"></div><?php // do not remove this div ?>
											</div>
										</div>
									<?php } ?>
									
								
									<div class="cell x6">
										<div class="input-block-wrapper">
											<div class="input-block" data-tip="Add Tags.">
												<label><?php _e('Tags (separate the tags by comma)', THEME_TEXT_DOMAIN); ?></label>
												<input type="text" name="post_tags">
											</div>
										</div>
									</div>
									
								</div>
								

								<div class="row visible">
									<div class="cell x12">
										<div class="input-block" data-tip="Add post content.">
											<label><?php _e('Post Content *', THEME_TEXT_DOMAIN); ?></label>
											<textarea class="wysform" name="post_content" required="required"></textarea>
										</div>
									</div>
								</div>

								<?php /*
								<div class="input-block file-upload-wrapper" data-tip="Select files.">
									<label><?php _e('Feature Image (max. 5 MB)', THEME_TEXT_DOMAIN); ?>
										<span class="smalltext"><?php _e('Accept file types .png/.jpg/.jpeg/.gif', THEME_TEXT_DOMAIN); ?></span>
									</label>
									<div class="file-btn-holder">
										<div class="fileUpload button">
										    <span><?php _e('Upload', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></span>
										    <input id="uploadBtn" type="file" name="post_feature_image"  accept="image/*" class="upload" />
										</div>
										
										<span id="flname" class="largetext"><?php _e('Choose File', THEME_TEXT_DOMAIN); ?></span>
									</div>								
								</div>
								<?php */ ?>

								

								<input type="hidden" value="<?php echo wp_create_nonce('submit-addpost'); ?>" name="submit-addpost">
								<input type="hidden" value="addpost" name="action">
								<div class="responsecheck_addpost"></div>
								<div class="submitarea smalltop smallbutton">
									<input type="submit" class="button" value="<?php _e('Create Post', THEME_TEXT_DOMAIN); ?>"> 
									<i class="fa fa-angle-right"></i>
								</div>
								
							</form>
						</div>
					</div>
				</div>				
			</div>	
		</div>
	</div>
<?php } else { ?>
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
<?php } ?>


<?php get_footer();