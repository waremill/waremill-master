<?php defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );

/**
 * Hide some of the widgets
 */
function unregister_default_widgets() {     
	unregister_widget('WP_Widget_Pages');     
	unregister_widget('WP_Widget_Calendar');     
	unregister_widget('WP_Widget_Archives');     
	unregister_widget('WP_Widget_Links');     
	unregister_widget('WP_Widget_Meta');     
	//unregister_widget('WP_Widget_Search');     
	//unregister_widget('WP_Widget_Text');     
	unregister_widget('WP_Widget_Categories');     
	unregister_widget('WP_Widget_Recent_Posts');    
	unregister_widget('WP_Widget_Recent_Comments');     
	unregister_widget('WP_Widget_RSS');     
	unregister_widget('WP_Widget_Tag_Cloud');  
	unregister_widget('WP_Nav_Menu_Widget');      
}
add_action('widgets_init', 'unregister_default_widgets', 11);


/**
 * Register our sidebars and widgetized areas.
 *
 */
add_action( 'widgets_init', 'theme_slug_widgets_init' );
function theme_slug_widgets_init() {
    register_sidebar( array(
        'name' 			=> __( 'Blog and Posts Sidebar', 'theme-slug' ),
        'id' 			=> 'sidebar-1',
        'description'	=> __( 'Widgets in this area will be shown on all posts and pages.', 'theme-slug' ),
        'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
    ) );

	register_sidebar( array(
        'name' 			=> __( 'Search', 'theme-slug' ),
        'id' 			=> 'sidebar-2',
        'description'	=> __( 'Widgets in this area will be shown on all posts and pages.', 'theme-slug' ),
        'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
    ) );
}

/**
 * Register and load the widgets
 */

function load_widgets() {
	register_widget( 'List_Posts' );
	register_widget( 'Search_Area' );	
	register_widget( 'Social' );
	register_widget( 'Categories' );

}

add_action( 'widgets_init', 'load_widgets' );


/**
 * WIDGET ----  List the last items from a post type
 * The default value are blog posts
 * You can use it to list other stuff too
 * 
 */

class List_Posts extends WP_Widget {
	public function __construct() {
		parent::__construct(
		'liststuff',
		'List Posts ',
		array( 'description' => 'List the last X posts', )
		);

	}

	public function widget( $args, $instance ) {
		$count = $instance['count'];
		$title = $instance['title'];
		$posttype=$instance['posttype'];
	
		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $count,
		);
		$loop = new WP_Query( $args );
		if($loop->have_posts()): ?>
			<div class="filter-box">
				<div class="filter-header"><h4><?php echo $title;?></h4></div>
				<div class="inner-content">
					<div class="latest-articles">
						<?php  while ( $loop->have_posts() ) : $loop->the_post(); ?>	
						<article>
							<?php if(check_user_login()==true ){ ?>
								<h6><a href="<?php echo get_permalink( $id ); ?>" title=""><?php echo get_the_title(); ?></a></h6>
							<?php } else { ?>
								<h6 class="fancybox"><a href="#sign-up-popup" title=""><?php echo get_the_title(); ?></a></h6>
							<?php } ?>
						</article>
						<?php endwhile; ?>	
					</div>				
				</div>
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>		
		<?php
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => '','posttype'=>'') );
		$title = strip_tags($instance['title']);
		$count = strip_tags($instance['count']);
		//$posttype = strip_tags($instance['posttype']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title: '); ?></label><br/>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"  style="width: 100%" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('How many: '); ?></label><br/>
			<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" value="<?php echo esc_attr($count); ?>" style="width: 100%" />
		</p>
		
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'count' => '','posttype'=>'') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		$instance['posttype'] = strip_tags($new_instance['posttype']);
		return $instance;

	}

}



/**
 * WIDGET ----  App Newsletter
 * 
 */

class Social extends WP_Widget {

	public function __construct() {
		parent::__construct(
		'socilalink',
		'Social Links',
		array( 'description' => 'Adds the social links', )
		);

	}

	public function widget( $args, $instance ) {
		$title = $instance['title'];
		$facebook = $instance['facebook'];
		$twitter = $instance['twitter'];
		$gogolep = $instance['gogolep'];
		?>
		<div class="filter-box">
			<div class="filter-header"><h4><?php echo $title; ?></h4></div>
			<div class="inner-content">
				<div class="social-networks">
					<?php if(!empty($facebook)){ ?>
						<a href="<?php echo $facebook; ?>" target="_blank" title=""><span class="facebook"><i class="fa fa-facebook"></i></span><?php _e('Facebook', THEME_TEXT_DOMAIN); ?></a>
					<?php } ?>
					<?php if(!empty($twitter)){ ?>
						<a href="<?php echo $twitter; ?>" target="_blank" title=""><span class="twitter"><i class="fa fa-twitter"></i></span><?php _e('Twitter', THEME_TEXT_DOMAIN); ?></a>
					<?php } ?>
					<?php if(!empty($gogolep)){ ?>
						<a href="<?php echo $gogolep; ?>" target="_blank" title=""><span class="google"><i class="fa fa-google-plus"></i></span><?php _e('Google+', THEME_TEXT_DOMAIN); ?></a>
					<?php } ?>
				</div>		
			</div>
		</div>
		<?php
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'facebook' => '', 'twitter' => '', 'gogolep'=> '') );
		$title = strip_tags($instance['title']); 
		$facebook = strip_tags($instance['facebook']);
		$twitter = strip_tags($instance['twitter']);
		$gogolep = strip_tags($instance['gogolep']);

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title: '); ?></label><br/>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" style="width: 100%" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook Link: '); ?></label><br/>
			<input id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="url" value="<?php echo esc_attr($facebook); ?>" style="width: 100%"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter Link: '); ?></label><br/>
			<input id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="url" value="<?php echo esc_attr($twitter); ?>" style="width: 100%"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('gogolep'); ?>"><?php _e('Googe Plus: '); ?></label><br/>
			<input id="<?php echo $this->get_field_id('gogolep'); ?>" name="<?php echo $this->get_field_name('gogolep'); ?>" type="url" value="<?php echo esc_attr($gogolep); ?>" style="width: 100%"/>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'facebook' => '', 'twitter' => '',   'gogolep'=> '') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['facebook'] = strip_tags($new_instance['facebook']);
		$instance['twitter'] = strip_tags($new_instance['twitter']);
		$instance['gogolep'] = strip_tags($new_instance['gogolep']);
		return $instance;

	}

}

/**
 * WIDGET ----  Categories
 * 
 */

class Categories extends WP_Widget {

	public function __construct() {
		parent::__construct(
		'categories',
		'Categories',
		array( 'description' => 'Adds the Categories', )
		);

	}

	public function widget( $args, $instance ) {
		$title = $instance['title'];
		$count = $instance['count'];
		?>
		<?php 
			$terms = get_terms( 'category' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
		    	<div class="filter-box">
					<div class="filter-header"><h4><?php echo $title; ?></h4></div>
				    <div class="inner-content">
						<ul><?php  
						$count_items = 0 ; 
					    foreach ( $terms as $term ) { 
					    	$count_items++;
					    	if($count_items <= $count || $count == -1 ){
					    		$term_link = get_term_link( $term );
					        	echo '<li><a href="'.$term_link.'" title="">' . $term->name . '</a></li>';
					    	}
					    	
					    } ?>
					   </ul>					
					</div>
				</div>
		<?php  } ?>
		
		<?php
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => '') );
		$title = strip_tags($instance['title']); 
		$count = strip_tags($instance['count']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title: '); ?></label><br/>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" style="width: 100%" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('How many (-1 for all) :'); ?></label><br/>
			<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" style="width: 100%" />
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'count' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		return $instance;

	}

}



/**
 * WIDGET ----  Search
 * 
 */

class Search_Area extends WP_Widget {

	public function __construct() {
		parent::__construct(
		'search',
		'Search Form',
		array( 'description' => 'Adds the Search Form', )
		);

	}

	public function widget( $args, $instance ) {
		$title = $instance['title'];
		?>
		<div class="search-widget">
			<form  method="get"  id="searchform" class="searchform" action="<?php echo home_url( '/' ); ?>">
                <input type="text" class="search-field" placeholder="<?php echo $title;  ?>" value="<?php echo get_search_query() ?>" name="s" id="s" title="<?php _e('Search for:', THEME_TEXT_DOMAIN); ?>" />
                <div class="subbutton">
                	<input type="submit" class="button search-submit"  value="<?php _e('Search', THEME_TEXT_DOMAIN); ?>" />
                </div>
            </form>
		</div>
		
		<?php
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = strip_tags($instance['title']); 
		//$count = strip_tags($instance['count']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title: '); ?></label><br/>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" style="width: 100%" />
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		//$instance['count'] = strip_tags($new_instance['count']);
		return $instance;

	}

}

