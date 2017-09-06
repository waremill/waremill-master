<?php /*Template Name: Homepage*/
defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );
get_header(); ?>
<div class="homepage">
	<div class="homepage-banner" style="background-image:url(img/home-banner.jpg);">
		<div class="container">
			<div class="inner-content">
				<?php $title_1 = get_field('title_1'); ?>
				<?php if(!empty($title_1)){ ?>
				 	<?php echo $title_1; ?>
				<?php } ?>

				<form method="POST" action="<?php $contractors_list = get_field('contractors_search_list', 'options'); if(!empty($contractors_list)){ echo get_permalink($contractors_list->ID); } ?>" enctype="multipart/form-data" >
					<div class="search-bar">
						<div class="input-block searchbox">
							<i class="fa fa-keyboard-o"></i>
							<input type="text" name="search" value="<?php $search = $_POST['search']; if(isset($search)) { echo $search; }?>" placeholder="Keywords">
						</div> 
						<div class="input-block"><?php 
							$draught_links = array(); 
							$draught_links2 = array();
							if(isset($_POST['services']) && sizeof($_POST['services']) > 0){
								$list_service = $_POST['services'];
								foreach ( $list_service as $term ) {
								    $draught_links[] = $term;
								    $draught_links2[]["id"] = $term;
								}
							}
							
							$terms = get_terms( 
								array(
								    'taxonomy' => 'service',
								    'hide_empty' => false,
								) 
							);
							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ $count_positions = -1; ?>
								<i class="fa fa-filter"></i>
								<div class="select-block">
									<select name="services[]" multiple="multiple" placeholder="Service" class="SlectBox" >
									<?php foreach ( $terms as $term ) { $count_positions++; ?>
										<option value="<?php echo $term->term_id; ?>" <?php 
											if(isset($list_service) && sizeof($list_service) > 0){
												if(in_array($term->term_id, $list_service)){
													echo "selected='selected'";
													$key = array_search($term->term_id, $draught_links);
											    	$draught_links2[$key]['index'] = $count_positions;
											    	$draught_links2[$key]['name'] = $term->name;	
												}
											}
										?>><?php echo $term->name; ?></option>
									<?php } ?>
									</select>
								</div>
							<?php } ?>
						</div>

						<div class="input-block">
							<i class="fa fa-map-marker"></i>
							<div class="select-block">
								<select name="country" class="styled select">
									<option value="0"><?php _e('Location', THEME_TEXT_DOMAIN); ?></option>
								  	<?php $cnt = $_POST['country']; ?>
								  	<?php if(!empty($cnt)){ ?>
								  		<?php echo get_selected_country($cnt); ?>
								  	<?php } else{ ?>
								  		<?php get_template_part( 'parts/list', 'countries'); ?> 
								  	<?php } ?>
								</select>
							</div>
						</div>
						<button type="submit" class="button"><i class="fa fa-search"></i></button>
					</div>
				</form>


				<?php $subtitle_1 = get_field('subtitle_1'); ?>
				<?php if(!empty($subtitle_1)){ ?>
					<h2><?php echo $subtitle_1; ?></h2>
				<?php } ?>

				<?php if(check_user_login() == true ){  ?>
					<?php if(check_user_customer() == true){  ?>
							
						<?php $customer_create_project = get_field('customer_create_project', 'options'); ?>
						<?php if(!empty($customer_create_project)){ ?>
							<div class="button-wrapper fancybox ">
								<a href="<?php echo get_permalink( $customer_create_project->ID ); ?>" title="" class="button"><?php _e('Start New Project', THEME_TEXT_DOMAIN); ?><i class="fa fa-angle-right"></i></a>
							</div>
						<?php } ?>

					<?php } else if(check_user_contractor() == true ) {  ?>

						<?php $projects_page = get_field('projects_page', 'options'); ?>
						<?php if(!empty($projects_page)){ ?>
							<div class="button-wrapper fancybox ">
								<a href="<?php echo get_permalink( $projects_page->ID ); ?>" title="" class="button"><?php _e('Search Projects', THEME_TEXT_DOMAIN); ?><i class="fa fa-angle-right"></i></a>
							</div>
						<?php } ?>

					<?php } ?>
				<?php } else { ?>
					<?php $customer_create_project = get_field('customer_create_project', 'options'); ?>
					<?php if(!empty($customer_create_project)){ ?>
						<div class="button-wrapper fancybox ">
							<?php $_SESSION['create-project-hp'] = true; ?>
							<a href="<?php echo get_permalink( $customer_create_project->ID ); ?>" title="" class="button"><?php _e('Start New Project', THEME_TEXT_DOMAIN); ?><i class="fa fa-angle-right"></i></a>
						</div>
					<?php } ?>
				<?php } ?>

			</div>
		</div>
	</div>

	<div class="grey-section">
		<div class="container">
			<?php $title_section_2 = get_field('title_section_2'); ?>
			<?php if(!empty($title_section_2)){ ?>
				<div class="centered-content"><h3><?php echo $title_section_2; ?></h3></div>
			<?php } ?>
			<?php $box = get_field('items_2'); ?>
			<?php	if ($box){ ?>
			<div class="row products-types">
				<?php foreach ($box as $box1) {  ?>					
					<div class="cell x2-4">
						<div class="product-type">
						
							<?php $image = $box1['image_items2'];
								if( !empty($image) ): ?>
								<div class="icon">
									<img src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
								</div>
							<?php endif; ?>
							<div class="sep"></div>
							<?php $title_items2 = $box1['title_items2']; ?>
							<?php if(!empty($title_items2)){ ?>
								<h4><?php echo $title_items2; ?></h4>
							<?php } ?>
						</div>
					</div>
						
					
				<?php } ?>
			</div>
			<?php } ?>
			
		</div>
	</div>

	<div class="white-section right-bg" style="background-image: url(<?php $image = get_field('background_image_3'); if(!empty($image)){ echo $image['sizes']['large']; } ?>);">
		<div class="overlay"></div>
		<div class="container">
			<div class="floating-objects">
				<div class="limited-content left">
					<?php $content_3 = get_field('content_3'); ?>
					<?php if(!empty($content_3)){ ?>
						<?php echo $content_3; ?>
					<?php } ?>

					<?php if(check_user_login() == false ){  ?>
						<?php $button_text_3 = get_field('button_text_3'); ?>
						<?php $button_link_3 = get_field('button_link_3'); ?>
						<?php if(!empty($button_text_3) && !empty($button_link_3)){ ?>
						<div class="fancybox">
							<a href="<?php echo $button_link_3; ?>" title="" class="button"><?php echo $button_text_3; ?> <i class="fa fa-angle-right"></i></a>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<div class="grey-section" id="how-it-works">
		<div class="container">
			<?php $title_section_4 = get_field('title_section_4'); ?>
			<?php if(!empty($title_section_4)){?>
				<div class="centered-content"><h3><?php echo $title_section_4; ?></h3></div>
			<?php } ?>

			<?php $box = get_field('items_4'); ?>
			<?php	if ($box)	{?>
				<div class="row products-types">
				<?php foreach ($box as $box1) {  ?>					
					<div class="cell x3">
						<div class="product-type">
							<?php $image = $box1['image_items4'];
								if( !empty($image) ): ?>
								<div class="icon">
									<img src="<?php echo $image['sizes']['large-icon']; ?>" alt="<?php echo $image['alt']; ?>" />
								</div>
							<?php endif; ?>
							<div class="sep"></div>
							<?php $title_items4 = $box1['title_items4']; ?>
							<?php if(!empty($title_items4)){ ?>
								<h4><?php echo $title_items4; ?></h4>
							<?php } ?>
							<?php $subtitle_items4 = $box1['subtitle_items4']; ?>
							<?php if(!empty($subtitle_items4)){ ?>
								<p><?php echo $subtitle_items4; ?></p>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				</div>
			<?php } ?>

			<?php if(check_user_login() == false ){  ?>
				<?php $button_text_4 = get_field('button_text_4'); ?>
				<?php $button_link_4 = get_field('button_link_4'); ?>
				<?php if(!empty($button_text_4) && !empty($button_link_4)){ ?>
					<div class="centered-content fancybox">
						<?php $_SESSION['create-project-hp'] = true; ?>
						<a href="<?php echo $button_link_4; ?>" title="" class="button"><?php echo $button_text_4; ?> <i class="fa fa-angle-right"></i></a>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>

	<div class="white-section left-bg" style="background-image: url(<?php $image = get_field('background_image_5'); if(!empty($image)){ echo $image['sizes']['large']; } ?>);">
		<div class="overlay"></div>
		<div class="container">
			<div class="floating-objects">
				<div class="limited-content right">
					<?php $content_5 = get_field('content_5'); ?>
					<?php if(!empty($content_5)){ ?>
						<?php echo $content_5; ?>
					<?php } ?>
					<?php $button_text_5 = get_field('button_text_5'); ?>
					<?php $button_link_5 = get_field('button_link_5'); ?>
					<?php if(!empty($button_text_5) && !empty($button_link_5 )){ ?>
						<a href="<?php echo $button_link_5 ; ?>" title="" class="button"><?php echo $button_text_5; ?> <i class="fa fa-angle-right"></i></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

</div>
<?php get_footer();