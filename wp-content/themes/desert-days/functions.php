<?php
/**
 * Desert Days functions and definitions
 *
 * @package Desert Days
 */

/**
 * multi language
 */

function languages()
{
    return(array(
        "he" => "עברית",
        "en" => "English",
        "fr" => "Francaise",
        "es" => "Espanyol"
    ));
}

function default_language_code()
{
    return(supported_language_codes()[0]);
}

function client_language_code()
{
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/../php/HTTP2.php' );
    $http2 = new HTTP2();
    $supported_array = array();
    foreach (supported_language_codes() as $value)
        $supported_array[$value] = 'locales/'.$value;
    return($http2->negotiateLanguage($supported_array, supported_language_codes()[0]));
}

function language_code()
{
    $code = filter_input(INPUT_GET, 'lang');
    $lang_array = languages();
    if (!$code || IsNullOrEmptyString($code) || !array_key_exists(strtolower($code), $lang_array))
        return(client_language_code());
    return($code);
}

function language_display()
{
    return(languages()[language_code()]);
}

function add_language_to_url( $url, $code = null) {
    if ($code === null)
        $code = language_code();
    $parsed_url = parse_url( $url );
    if(array_key_exists('query', $parsed_url))
        parse_str( $parsed_url['query'], $query );
    return $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'] . '?' . http_build_query( isset($query) ? array_merge( $query, array('lang' => $code ) ) : array( 'lang' => $code ) );
}

function supported_language_codes()
{
    return(array_keys(languages()));
}

function current_url()
{
    return('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

/**
 * Check if string is null or empty
 */
function IsNullOrEmptyString($s){
    return (!isset($s) || trim($s)==='');
}

/**
 * Resolve custom field name (possibly using language code
 */

function resolve_custom_field_name($name)
{
    $full_name = $name . '_' . language_code();
    $v = get_field($full_name);
    if ($v === NULL)
    {
        $full_name = $name . '_' . default_language_code();
         $v = get_field($full_name);
    }
    return($v ? $full_name : NULL);
}

/**
 *
 *  Return custom field text according to language.
 */
function get_custom_sub_field_text($sub_field_name)
{
    $text = get_sub_field($sub_field_name . '_' . language_code());
    if (IsNullOrEmptyString($text))
        $text = get_sub_field($sub_field_name . '_' . default_language_code());
    return($text);
}

function get_custom_field_text($field_name, $pageID = NULL)
{
    $text = get_field($field_name . '_' . language_code(), $pageID);
    if (IsNullOrEmptyString($text))
        $text = get_field($field_name . '_' . default_language_code(), $pageID);
    return($text);
}

require_once(get_template_directory() . '/inc/Mobile_Detect.php');

function isMobile()
{
    $mobile_detect = new Mobile_Detect;
    return($mobile_detect->isMobile());
}
function isTablet()
{
    $mobile_detect = new Mobile_Detect;
    return($mobile_detect->isTablet());
}

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}

if ( ! function_exists( 'desert_days_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function desert_days_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Desert Days, use a find and replace
	 * to change 'desert-days' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'desert-days', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'desert-days' ),
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
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array('aside'));
        
        

	// Setup the WordPress core custom background feature.
//	add_theme_support( 'custom-background', apply_filters( 'desert_days_custom_background_args', array(
//		'default-color' => 'ffffff',
//		'default-image' => '',
//	) ) );
}
endif; // desert_days_setup
add_action( 'after_setup_theme', 'desert_days_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function desert_days_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'desert-days' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'desert_days_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function desert_days_scripts() {
        wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css');
        wp_enqueue_style( 'custom-scroll-bar', get_template_directory_uri() . '/jquery.mCustomScrollbar.css');
//        wp_enqueue_style( 'dropdown', get_template_directory_uri() . '/jquery.dropdown.css');
        wp_enqueue_style( 'desert-days-style', get_stylesheet_uri() );
        if (is_page_template('page-templates/main-content.php'))
        {
            wp_enqueue_style( 'desert-days-main-content-style', get_template_directory_uri() . '/layouts/main-content.css' );
        }
        if (is_page_template('page-templates/availability.php'))
        {
            wp_enqueue_style( 'desert-days-availability-style', get_template_directory_uri() . '/layouts/availability.css' );
        }
        wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js', array('jquery'), '', false );
	wp_enqueue_script( 'custom-scrollbar-js', get_template_directory_uri() . '/js/jquery.mCustomScrollbar.concat.min.js', array('jquery'), '', false );
//        wp_enqueue_script( 'dropdown-js', get_template_directory_uri() . '/js/jquery.dropdown.js', array('jquery'), '', false );
        
        wp_enqueue_script( 'touchSwipe-js', get_template_directory_uri() . '/js/jquery.touchSwipe.min.js', array('jquery'), '', false );
        wp_enqueue_script( 'lazyLoad-js', get_template_directory_uri() . '/js/jquery.lazyload.min.js', array('jquery'), '', false );
        wp_enqueue_script( 'desert-days-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', false );
	wp_enqueue_script( 'desert-days-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', false );
        wp_enqueue_script( 'desert-days-js', get_template_directory_uri() . '/js/DesertDays.js', array('jquery'), '', false );
       
}
add_action( 'wp_enqueue_scripts', 'desert_days_scripts' );

if (!function_exists('write_log')) :
function write_log ( $log )  {
    if ( true === WP_DEBUG ) {
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }
    }
}
endif;

add_action( 'admin_head', 'hide_editor' );
function hide_editor() {
        if (strpos(get_page_template_slug(), 'main-content') || strpos(get_page_template_slug(), 'availability'))
            remove_post_type_support('page', 'editor');
}

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
