<?php
/**
 * Prophets & Apostles functions and definitions
 *
 * @package Prophets & Apostles
 */

if ( ! function_exists( 'prophets_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function prophets_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Prophets & Apostles, use a find and replace
	 * to change 'prophets' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'prophets', get_template_directory() . '/languages' );

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
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'prophets' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'prophets_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // prophets_setup
add_action( 'after_setup_theme', 'prophets_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function prophets_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'prophets_content_width', 640 );
}
add_action( 'after_setup_theme', 'prophets_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function prophets_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'prophets' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'prophets_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function prophets_scripts() {
	wp_enqueue_style( 'prophets-style', get_stylesheet_uri(), '', '20150612' );

	wp_enqueue_script( 'prophets-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'prophets-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'prophets_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

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


function calculate_age($start, $end){
	$date = new DateTime( $start );
	$now = new DateTime( $end );
	$interval = $now->diff($date);
	return $interval->y;
}


function show_all_leaders_sorted( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;

    if ( is_home() ) {
        // Display only 1 post for the original blog archive
        $query->set( 'posts_per_page', -1 );
        $query->set( 'post_type', 'leader' );
    	$query->set( 'orderby', 'meta_value' );
    	$query->set( 'meta_key', 'ordained_date' );
        
        return;
    }

    if ( is_post_type_archive( 'leader' ) ) {
        // Display 50 posts for a custom post type called 'movie'
    	$query->set( 'posts_per_page', -1 );
    	$query->set( 'orderby', 'meta_value' );
    	$query->set( 'meta_key', 'ordained_date' );
        return;
    }
}
add_action( 'pre_get_posts', 'show_all_leaders_sorted', 1 );


//clear transient date on every leader save to ensure most recent json data is sent to apps
function publish_leader_update_cache( $ID, $post ) {
	delete_transient( 'leader_json' );
	delete_transient( 'leader_table' );
	delete_transient( 'leader_table_polygamy' );
	delete_transient( 'cclds_all_leaders' );
}

add_action( 'save_post', 'publish_leader_update_cache', 10, 3 );
