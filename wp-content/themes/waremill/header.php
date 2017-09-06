<?php global $vc; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
		<?php $favicon = get_field('favicon_desktop', 'options');
			if($favicon){ ?>
				<link rel="icon" href="<?php echo $favicon['url']; ?>" type="<?php echo $favicon['mime_type']; ?>">
		<?php } ?>
		<?php $favicon_iphone = get_field('favicon_iphone', 'options');
			if($favicon_iphone){ ?>
			<link rel="apple-touch-icon" href="<?php echo $favicon_iphone['url']; ?>">
		<?php } ?>
		<?php $favicon_ipad = get_field('favicon_ipad', 'options');
			if($favicon_ipad){ ?>
			<link rel="apple-touch-icon" sizes="76x76" href="<?php echo $favicon_ipad['url']; ?>">
		<?php } ?>
		<?php $favicon_iphone_retina = get_field('favicon_iphone_retina', 'options');
			if($favicon_iphone_retina){ ?>
			<link rel="apple-touch-icon" sizes="120x120" href="<?php echo $favicon_iphone_retina['url']; ?>">
		<?php } ?>
		<?php $favicon_ipad_retina = get_field('favicon_ipad_retina', 'options');
			if($favicon_ipad_retina){ ?>
				<link rel="apple-touch-icon" sizes="152x152" href="<?php echo $favicon_ipad_retina['url']; ?>">
		<?php } ?>
		<?php wp_head(); ?>
		<base href="<?php echo get_template_directory_uri() ?>/"><!--[if IE]></base><![endif]-->
	</head>
	<body>
<?php /* daca e logat ca contractor, eu am pus de test is_user_logged_in() */?>
<div id="page" <?php if(is_user_logged_in() && check_role_current_user('contractor' ) == true){ echo ' class="contractor-theme"'; } else if( is_user_logged_in() && check_role_current_user('customer' ) == true) { echo ' class="customer-theme"'; } ?>>
	<div class="switchcontent">
		<div class="loadercontent">
			<img src="img/loading.gif" alt="">
		</div>
	</div>
	<div class="blockall">
		<header <?php if(is_front_page()){ echo " class='home-header'";}?>>
			<div class="container">
				<div class="floating-objects visible">
					<?php $image_png = get_field('logo_png', 'options');
						$image_svg = get_field('logo_svg', 'options');
						if( !empty($image_png) || !empty($image_svg)){ ?>
						<div class="logo left">
						<?php $logo_link = site_url(); ?>
							<?php if(check_user_login() == true ){ 
								if(check_role_current_user('customer' ) == true){ 
									$logo_link_id   = get_field('client_dashboard', 'options')->ID;
									$logo_link 		= get_permalink($logo_link_id);
 								} else if(check_role_current_user('contractor' ) == true){ 
 									$logo_link_id   = get_field('contractor_dashboard', 'options')->ID;
									$logo_link 		= get_permalink($logo_link_id);
								} 
							} ?>
							<a href="<?php echo $logo_link ; ?>" title="<?php  echo get_bloginfo('name') . ' - ' . get_bloginfo('description'); ?>">
								<img src="<?php echo $image_svg['url']; ?>" onerror="this.src='<?php echo $image_png['sizes']['logo'];?>'">
							</a>
						</div>
					<?php } ?>
					
					<?php include_once("parts/menu-user.php"); ?>
					
				</div>
			</div>
		</header>

		<nav id="mobile-navigation-menu" class="hide">
			<?php if(check_user_login() == false ){ ?>
				<?php wp_nav_menu( array( 'theme_location' => 'mobile-header-menu','container'=>false,'menu_class' => 'mobile-home-header-menu' ) ); ?>
			<?php }else{ ?>
				<?php type_of_menu(); //wp_nav_menu( array( 'theme_location' => 'header-menu-users','container'=>false,'menu_class' => 'home-header-menu' ) ); ?>
			<?php } ?>
		</nav>

		<?php include_once("parts/register-form.php"); ?>

		<?php include_once("parts/forgot-password-form.php"); ?>

		<?php include_once("parts/login-form.php"); ?>