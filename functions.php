<?php
/**
 * Theme functions and definitions
 */


if ( ! function_exists( 'bect_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Bect 1.0
 */
function bect_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on bect, use a find and replace
	 * to change 'bect' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'bect', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu',      'bect' ),
		'footer'  => __( 'Footer Links Menu', 'bect' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * @link https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', 'genericons/genericons.css', bect_fonts_url() ) );
}
endif; // bect_setup
add_action( 'after_setup_theme', 'bect_setup' );

/**
 * Register widget area.
 *
 * @since Bect 1.0
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function bect_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Widget Area', 'bect' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'bect' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'bect_widgets_init' );

if ( ! function_exists( 'bect_fonts_url' ) ) :
/**
 * Register Google fonts for Bect.
 *
 * @since Bect 0.4
 *
 * @return string Google fonts URL for the theme.
 */
function bect_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Sans font: on or off', 'bect' ) ) {
		$fonts[] = 'Noto Sans:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Serif, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Serif font: on or off', 'bect' ) ) {
		$fonts[] = 'Noto Serif:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Inconsolata, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'bect' ) ) {
		$fonts[] = 'Inconsolata:400,700';
	}

	/*
	 * Translators: To add an additional character subset specific to your language,
	 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
	 */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'bect' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Enqueue scripts and styles.
 *
 * @since Bect0.4
 */
function bect_scripts() {
	
	// Load our main stylesheet.
	wp_enqueue_style( 'bect-style', get_stylesheet_uri() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'bect-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150330', true );
}
add_action( 'wp_enqueue_scripts', 'bect_scripts' );


/**
 * Display descriptions in main navigation.
 *
 * @since Bect 0.4
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function bect_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'bect_nav_description', 10, 4 );

/**
 * Custom header Logo
 */
function bect_customizer( $wp_customize ) {
    $wp_customize->add_section( 'bect_logo_section' , array(
    'title'       => __( 'Header Logo', 'bect' ),
    'priority'    => 30,
    'description' => __( 'Upload logo to replace site title on header ', 'bect' ),
) );
$wp_customize->add_setting( 'bect_logo' );
$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bect_logo', array(
    'label'    => __( 'Logo', 'bect' ),
    'section'  => 'bect_logo_section',
    'settings' => 'bect_logo',
) ) );
}
add_action( 'customize_register', 'bect_customizer' );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Bect 0.4
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function bect_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'bect_search_form_modify' );


/**
 * Custom template tags for this theme.
 *
 * @since Bect 0.4
 */
require get_template_directory() . '/inc/template-tags.php';

