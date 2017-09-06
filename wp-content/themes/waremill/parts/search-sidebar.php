<?php //var_dump($_POST); ?>	

<div class="sidebar">
	<form method="POST"  enctype="multipart/form-data" >
		<div class="widget">
			<div class="filter-box">				
				<div class="filter-header"><h4><?php _e('Filter', THEME_TEXT_DOMAIN); ?></h4></div>
				<div class="inner-content">
					<div class="input-block-wrapper">
						<div class="widget">
							<label><?php _e('Search', THEME_TEXT_DOMAIN); ?></label>
							<div class="search-widget ">
								
								<input type="text" name="search" value="<?php $search = $_POST['search']; if(isset($search)) { echo $search; }?>" placeholder="Keywords">
								<div class="submitsearch">
									<i class="fa fa-search"></i>
								</div>
						
							</div>
						</div>
					</div>

					<div class="input-block-wrapper">
						<?php 
						$draught_links = array(); 
						$draught_links2 = array();
						if(isset($_POST['industries']) && sizeof($_POST['industries']) > 0){
							$list_industries = $_POST['industries'];

							foreach ( $list_industries as $term ) {
							    $draught_links[] = $term;
							    $draught_links2[]["id"] = $term;
							}
						}
						
						$terms = get_terms( 
							array(
							    'taxonomy' => 'industry',
							    'hide_empty' => false,
							) 
						);
						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ $count_positions = -1; ?>

							<label><?php _e('Material', THEME_TEXT_DOMAIN); ?></label>
							<div class="input-block">
								<i class="fa fa-cog" aria-hidden="true"></i>
								<div class="select-block">
									<select name="industries[]" multiple="multiple" placeholder="Please select a material." class="SlectBox" >
										<?php foreach ( $terms as $term ) { $count_positions++; ?>
									 	<option value="<?php echo $term->term_id; ?>" <?php 
											if(isset($list_industries) && sizeof($list_industries) > 0){
												if(in_array($term->term_id, $list_industries)){
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
							</div>
							<div class="wrappselectitems">
								<?php foreach($draught_links2 as $item2){ ?>
								<div class="selected-option" data-item="<?php echo  $item2['index']; ?>">
									<span><?php echo $item2['name']; ?></span>
									<a href="#" title="" class="removesummo" data-item="<?php echo  $item2['index']; ?>"><i class="fa fa-times"></i></a>
								</div> 
								<?php } ?>
							</div>
						<?php } ?>
					</div>

					<div class="input-block-wrapper">
						<?php 
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
						<label><?php _e('Service', THEME_TEXT_DOMAIN); ?></label>
						<div class="input-block">
							<i class="fa fa-filter"></i>
							<div class="select-block">
								<select name="services[]" multiple="multiple" placeholder="Please select a service." class="SlectBox" >
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
						</div>
						<?php //var_dump($draught_links2); ?>
						<div class="wrappselectitems">
							<?php foreach($draught_links2 as $item2){ ?>
								<div class="selected-option" data-item="<?php echo  $item2['index']; ?>">
									<span><?php echo $item2['name']; ?></span>
									<a href="#" title="" class="removesummo" data-item="<?php echo  $item2['index']; ?>"><i class="fa fa-times"></i></a>
								</div> 
							<?php } ?>
						</div>
						<?php } ?>
					</div>

					<div class="input-block-wrapper">
						<label><?php _e('Country', THEME_TEXT_DOMAIN); ?></label>
						<div class="input-block">
							<i class="fa fa-map-marker"></i>
							<div class="select-block">
								<select name="country" class="styled">
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
					</div>

					<button type="submit"><?php _e('Search', THEME_TEXT_DOMAIN); ?><i class="fa fa-angle-right"></i></button>
					<?php if(!empty($_POST)){ ?>
						<a href="<?php echo get_permalink(); ?>" title="" class="button fullbutton"><?php _e('RESET FILTERS', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</form>
</div>