<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class('pyfal');?>>
<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" role="banner">
	  <div class="bect-group">
		<div class="site-branding">
			<div id="open"></div>
			<div id="close"></div>
			<?php if ( get_theme_mod( 'bect_logo' ) ) : ?>
    			<div class='site-logo'>
        			<a href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>' rel='home'><img src='<?php echo esc_url( get_theme_mod( 'bect_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a>
    			</div>
			<?php else : ?>
			<?php
				if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php endif;

				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description hide"><?php echo $description; ?></p>
				<?php endif;
			?>
			<?php endif;
			?>
			</div><!-- .site-branding -->
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<nav id="site-navigation" class="main-navigation nav" role="navigation">
					<?php
						// Primary navigation menu.
						wp_nav_menu( array(
						"menu_class"     	=> "nav-menu",
						"theme_location" 	=> "primary",
						"container"			=> "true",
						) );
					?>
				</nav><!-- .main-navigation -->
			<?php endif; ?>
			<form role="search" method="get" class="bect search-form" action="<?php echo home_url( '/' ); ?>">
				<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
				<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
			</form>
		  </div>
	</header><!-- .site-header -->
	<main id="main" class="site-main bect-group" role="main">