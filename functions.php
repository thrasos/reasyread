<?php
/**
 * easyread functions and definitions
 *
 * @package easyread
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 697; /* pixels */
}

/**
 * Set the content width for full width pages with no sidebar.
 */
function easyread_content_width() {
  if ( is_page_template( 'page-fullwidth.php' ) ) {
    global $content_width;
    $content_width = 1008; /* pixels */
  }
}
add_action( 'template_redirect', 'easyread_content_width' );

if ( ! function_exists( 'easyread_main_content_bootstrap_classes' ) ) :
/**
 * Add Bootstrap classes to the main-content-area wrapper.
 */
function easyread_main_content_bootstrap_classes() {
	if ( is_page_template( 'page-fullwidth.php' ) ) {
		return 'col-sm-12 col-md-12';
	}
	return 'col-sm-12 col-md-8';
}
endif; // easyread_main_content_bootstrap_classes

if ( ! function_exists( 'easyread_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function easyread_setup() {

  /*
   * Make theme available for translation.
   * Translations can be filed in the /languages/ directory.
   */
  load_theme_textdomain( 'easyread', get_template_directory() . '/languages' );

  // Add default posts and comments RSS feed links to head.
  add_theme_support( 'automatic-feed-links' );

  /**
   * Enable support for Post Thumbnails on posts and pages.
   *
   * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
   */
  add_theme_support( 'post-thumbnails' );

  add_image_size( 'easyread-featured', 1170, 550, true );
  add_image_size( 'easyread-slider', 1920, 550, true );
  add_image_size( 'easyread-thumbnail', 330, 220, true );
  add_image_size( 'easyread-medium', 640, 480, true );

  // This theme uses wp_nav_menu() in one location.
  register_nav_menus( array(
    'primary'      => esc_html__( 'Primary Menu', 'easyread' )
  ) );

  // Enable support for Post Formats.
//  add_theme_support( 'post-formats', array(
//		'video',
//		'audio',
//	) );
  add_theme_support( 'post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'quote', 'status', 'link', 'video'
	) );

  // Setup the WordPress core custom background feature.
  add_theme_support( 'custom-background', apply_filters( 'easyread_custom_background_args', array(
    'default-color' => 'FFFFFF',
    'default-image' => '',
  ) ) );

  // Enable support for HTML5 markup.
  add_theme_support( 'html5', array(
    'comment-list',
    'search-form',
    'comment-form',
    'gallery',
    'caption',
  ) );

  /*
   * Let WordPress manage the document title.
   * By adding theme support, we declare that this theme does not use a
   * hard-coded <title> tag in the document head, and expect WordPress to
   * provide it for us.
   */
  add_theme_support( 'title-tag' );

}
endif; // easyread_setup
add_action( 'after_setup_theme', 'easyread_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function easyread_widgets_init() {
  register_sidebar( array(
    'name'          => esc_html__( 'Sidebar', 'easyread' ),
    'id'            => 'sidebar-1',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ));
  register_sidebar(array(
'name' => 'Footer',
'before_widget' => '<div class="col-md-4">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));


  register_widget( 'easyread_recent_posts' );
  register_widget( 'easyread_categories' );
}
add_action( 'widgets_init', 'easyread_widgets_init' );


/* --------------------------------------------------------------
       Theme Widgets
-------------------------------------------------------------- */
require_once(get_template_directory() . '/inc/widgets/widget-categories.php');

require_once(get_template_directory() . '/inc/widgets/widget-recent-posts.php');

/**
 * This function removes inline styles set by WordPress gallery.
 */
function easyread_remove_gallery_css( $css ) {
  return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}

add_filter( 'gallery_style', 'easyread_remove_gallery_css' );

/**
 * Enqueue scripts and styles.
 */
function easyread_scripts() {

  // Add Bootstrap default CSS
  wp_enqueue_style( 'easyread-bootstrap', get_template_directory_uri() . '/inc/css/bootstrap.min.css' );


  // Add slider CSS only if is front page ans slider is enabled
  if( ( is_home() || is_front_page() ) && get_theme_mod('easyread_featured_hide') == 1 ) {
    wp_enqueue_style( 'flexslider-css', get_template_directory_uri().'/inc/css/flexslider.css' );
  }

  // Add main theme stylesheet
  wp_enqueue_style( 'easyread-style', get_stylesheet_uri() );

  // Add Modernizr for better HTML5 and CSS3 support
  wp_enqueue_script('easyread-modernizr', get_template_directory_uri().'/inc/js/modernizr.min.js', array('jquery') );

  // Add Bootstrap default JS
  wp_enqueue_script('easyread-bootstrapjs', get_template_directory_uri().'/inc/js/bootstrap.min.js', array('jquery') );

  // Add slider JS only if is front page ans slider is enabled
  if( ( is_home() || is_front_page() ) && get_theme_mod('easyread_featured_hide') == 1 ) {
    wp_register_script( 'flexslider-js', get_template_directory_uri() . '/inc/js/flexslider.min.js', array('jquery'), '20140222', true );
  }

  // Main theme related functions
  wp_enqueue_script( 'easyread-functions', get_template_directory_uri() . '/inc/js/functions.min.js', array('jquery') );

  // This one is for accessibility
  wp_enqueue_script( 'easyread-skip-link-focus-fix', get_template_directory_uri() . '/inc/js/skip-link-focus-fix.js', array(), '002', true );

  // Threaded comments
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action( 'wp_enqueue_scripts', 'easyread_scripts' );

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

/**
 * Load custom nav walker
 */
require get_template_directory() . '/inc/navwalker.php';

/**
 * Load custom metabox
 */
require get_template_directory() . '/inc/metaboxes.php';



/* Globals */
global $site_layout, $header_show;
$site_layout = array('pull-right' =>  esc_html__('Left Sidebar','easyread'), 'side-right' => esc_html__('Right Sidebar','easyread'), 'no-sidebar' => esc_html__('No Sidebar','easyread'),'full-width' => esc_html__('Full Width', 'easyread'));
$header_show = array(
                        'logo-only' => __('Logo Only', 'easyread'),
                        'logo-text' => __('Logo + Tagline', 'easyread'),
                        'title-only' => __('Title Only', 'easyread'),
                        'title-text' => __('Title + Tagline', 'easyread')
                      );

/* Get Single Post Category */
function easyread_get_single_category($post_id){

    if( !$post_id )
        return '';

    $post_categories = wp_get_post_categories( $post_id );

    if( !empty( $post_categories ) ){
        return wp_list_categories('echo=0&title_li=&show_count=0&include='.$post_categories[0]);
    }
    return '';
}

if ( ! function_exists( 'easyread_woo_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function easyread_woo_setup() {
	/*
	 * Enable support for WooCemmerce.
	*/
	add_theme_support( 'woocommerce' );

}
endif; // easyread_woo_setup
add_action( 'after_setup_theme', 'easyread_woo_setup' );

/*
 * Function to modify search template for header
 */
function easyread_header_search_filter($form){
    $form = '<form action="'.esc_url( home_url( "/" ) ).'" method="get"><input type="text" name="s" value="'.get_search_query().'" placeholder="'. esc_attr_x( __('Search', 'easyread'), 'search placeholder', 'easyread' ).'"><button type="submit" style="color:#E8E8E8; background:white;" name="submit" id="searchsubmit" value="'. esc_attr_x( 'Search', 'submit button', 'easyread' ).'"><span style="color:#696969;background:white;" class="glyphicon glyphicon-search"></span></button></form>';
    return $form;    
}
