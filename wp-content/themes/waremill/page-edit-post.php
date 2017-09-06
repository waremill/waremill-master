<?php /*Template Name:  Edit Post */
defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );
get_header(); ?>

<?php if ( is_user_logged_in() ) { // user logged and with customer role ?>
<?php 
	$project_id = 0; 
  	$project_id = $_GET['id']; 
	$user 		= wp_get_current_user(); 
	$user_ID 	= $user->ID;
?>
<?php  
	$auth = get_post($project_id); // gets author from post 
	$authid = $auth->post_author; // gets author id for the post
	if(strcmp($user_ID, $authid) == 0 ){ ?>
		<div class="dashboard-page">
			<div class="main-content">
				<div class="container">
					<div class="margintitle">
						<h1><?php _e('Edit post: ', THEME_TEXT_DOMAIN); ?> <?php echo get_the_title($project_id); ?></h1>
						<div class="absview">
							<a href="<?php echo get_permalink($project_id); ?>" title="" class="button button-view"><?php _e('View Post', THEME_TEXT_DOMAIN); ?><i class="fa fa-eye" aria-hidden="true"></i></a>
						</div>
					</div>
					

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
										<input type="text" name="post_name"  required="required" value="<?php echo get_the_title($project_id); ?>" >
									</div>
									<div class="row noovhidden">
									<?php  
										$terms_list = get_the_terms( $project_id, 'category' );
										$draught_links = array(); 
										$draught_links2 = array();
										if ( $terms_list && ! is_wp_error( $terms_list ) ) {
											foreach ( $terms_list as $term ) {
											    $draught_links[] = $term->term_id;
											    $draught_links2[]["id"] = $term->term_id;
											}
										} 

										$terms = get_terms( 
											array(
											    'taxonomy' => 'category',
											    'hide_empty' => false,
											) 
										); 
										if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ $count_positions = -1; ?>
											<div class="cell x6">
												<div class="input-block-wrapper">
													<label><?php _e('Category *', THEME_TEXT_DOMAIN); ?></label>
													<div class="input-block" data-tip="Select a category.">
														<i class="fa fa-check-circle"></i>
														<div class="select-block">
															<select name="post_categories[]" multiple="multiple" placeholder="Please select a category." class="SlectBox" required="required">
																<?php foreach ( $terms as $term ) { $count_positions++; ?>
																    <option value="<?php echo $term->term_id; ?>" <?php if(in_array($term->term_id, $draught_links)){ 
																    	echo "selected='selected'"; 
																    	$key = array_search($term->term_id, $draught_links);
																    	$draught_links2[$key]['index'] = $count_positions;
																    	$draught_links2[$key]['name'] = $term->name;	
																    } ?>>
																    	<?php echo $term->name; ?>
																    </option>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="wrappselectitems"><?php //var_dump($draught_links2); ?>
														<?php foreach($draught_links2 as $item2){ ?>
															<div class="selected-option" data-item="<?php echo  $item2['index']; ?>">
																<span><?php echo $item2['name']; ?></span>
																<a href="#" title="" class="removesummo" data-item="<?php echo  $item2['index']; ?>"><i class="fa fa-times"></i></a>
															</div> 
														<?php } ?>

													</div><?php // do not remove this div ?>
												</div>
											</div>
										<?php } ?>

										
									
										<div class="cell x6">
											<div class="input-block-wrapper">
												<div class="input-block" data-tip="Add Tags.">
													<label><?php _e('Tags (separate the tags by comma)', THEME_TEXT_DOMAIN); ?></label>
													<input type="text" name="post_tags" value="<?php  
													    $posttags = wp_get_post_tags($project_id); 
													    $count=0; 
													    if ($posttags) { 
													    	$draught_links = array(); 
														    foreach($posttags as $tag) { $count++;  
															    $draught_links[] = $tag->name;
														    } 
														    $on_draught = join( ", ", $draught_links );
														    echo  $on_draught;
													}  ?>">
												</div>
											</div>
										</div>
										
									</div>
									

									<div class="row visible">
										<div class="cell x12">
											<div class="input-block" data-tip="Add post content.">
												<label><?php _e('Post Content *', THEME_TEXT_DOMAIN); ?></label>
												<textarea class="wysform" name="post_content" required="required" ><?php  $content_post = get_post($project_id);
													$content = $content_post->post_content;
													$content = apply_filters('the_content', $content);
													$content = str_replace(']]>', ']]&gt;', $content); 
													if(!empty($content)){  echo $content; } ?></textarea>
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
									<?php
										$img = wp_get_attachment_image_src(get_post_thumbnail_id($project_id), 'logo_company');
										if($img[0]){ ?>
											<div class="logocomp">
												<p><?php _e('Current Feature Image:', THEME_TEXT_DOMAIN); ?></p>
												<div class="logopost">
													<a href="#" title="" class="removefeatureimg"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
													<img src="<?php echo $img[0]; ?>" alt="<?php echo get_the_title(); ?>" />
												</div>
												<input type="hidden" name="old_feature_image"  value="<?php echo get_post_thumbnail_id($project_id); ?>">
											</div>
									<?php } */?>
									
									<input type="hidden" value="<?php echo $project_id; ?>" name="id_post">
									<input type="hidden" value="<?php echo wp_create_nonce('submit-editpost'); ?>" name="submit-editpost">
									<input type="hidden" value="addpost" name="action">
									<div class="responsecheck_addpost"></div>
									<div class="submitarea smalltop smallbutton">
										<input type="submit" class="button" value="<?php _e('Edit Post', THEME_TEXT_DOMAIN); ?>"> 
										<i class="fa fa-angle-right"></i>
									</div>
									
								</form>
							</div>
						</div>
					</div>				
				</div>	
			</div>
		</div>
	<?php }else { ?>
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