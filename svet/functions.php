<?php
/**
 * svet functions and definitions
 *
 * @package svet
 */

define('SVET_VERSION', '1.0'); 
 
 
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'svet_setup' ) ) :
function svet_setup() {

	// Inits
	load_theme_textdomain( 'svet', get_template_directory() . '/lang' );	
//	add_theme_support( 'automatic-feed-links' );	
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );
	
	
	// Thumbnails
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size(350, 350, true ); // regular thumbnails
	//add_image_size('logo', 222, 140, true ); // logo thumbnail 
	add_image_size('poster', 252, 350, true ); // poster in widget	
	add_image_size('embed', 680, 450, true ); // fixed size for embending
	//add_image_size('long', 640, 280, true ); // long thumbnail for pages

	// Menus
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'svet' ),
		'journal'  => __('Journal Menu', 'svet' ),
	) );

	// Editor style
	add_editor_style(array('css/editor-style.css'));
}
endif; // svet_setup
add_action( 'after_setup_theme', 'svet_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function svet_widgets_init() {
	
	$config = array(
		'right' => array(
						'name' => 'Правая колонка',
						'description' => 'Общая боковая колонка справа'
					),		
//		'left' => array(
//						'name' => 'Левая колонка',
//						'description' => 'Общая боковая колонка справа'
//					),
		
		'footer_1' => array(
						'name' => 'Футер - 1 кол.',
						'description' => 'Динамическая нижняя область - 1 колонка'
					),
		'footer_2' => array(
						'name' => 'Футер - 2 кол.',
						'description' => 'Динамическая нижняя область - 2 колонка'
					),
		'footer_3' => array(
						'name' => 'Футер - 3 кол.',
						'description' => 'Динамическая нижняя область - 3 колонка'
					),
		'footer_4' => array(
						'name' => 'Футер - 4 кол.',
						'description' => 'Динамическая нижняя область - 4 колонка'
					)
	);
	
	
	foreach($config as $id => $sb) {
		
		$before = '<div id="%1$s" class="widget %2$s">';
		
		if(false !== strpos($id, 'footer')){
			$before = '<div id="%1$s" class="widget %2$s">';
		}		
		
		register_sidebar(array(
			'name' => $sb['name'],
			'id' => $id.'-sidebar',
			'description' => $sb['description'],
			'before_widget' => $before,
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));
	}
}
add_action( 'widgets_init', 'svet_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function svet_scripts() {
	
	$theme_dir_url = get_template_directory_uri();
	
	// Styles
	$style_dependencies = array();
	
	// Google fonts	
	$google_request = '//fonts.googleapis.com/css?family=Ubuntu+Mono:400,400italic,700,700italic&subset=latin,cyrillic';	
	if(isset($google_request) && !empty($google_request)) {
		wp_enqueue_style(
			'apl-google-fonts',
			$google_request,
			$style_dependencies,
			SVET_VERSION
		);
		$style_dependencies[] = 'apl-google-fonts';
	}	

	// Icons
	wp_enqueue_style('dashicons');
	$style_dependencies[] = 'dashicons';
	
	// Fresco
	//wp_enqueue_style(
	//	'svet-fresco',
	//	$theme_dir_url . '/css/fresco.css',
	//	array(),
	//	SVET_VERSION
	//);
	//$style_dependencies[] = 'svet-fresco';
		
	// Stylesheet
	wp_enqueue_style(
		'svet-style',
		$theme_dir_url . '/css/design.css',
		$style_dependencies,
		SVET_VERSION
	);
	
	
	// Scripts
	$script_dependencies = array();

	// jQuery
	$script_dependencies[] = 'jquery';
	
	//wp_enqueue_script(
	//	'svet-fresco',
	//	$theme_dir_url . '/js/fresco.js',
	//	$script_dependencies,
	//	SVET_VERSION,
	//	true
	//);
	//
	//$script_dependencies[] = 'svet-fresco';
	
	wp_enqueue_script(
		'svet-imageloaded',
		$theme_dir_url . '/js/imagesloaded.pkgd.min.js',
		$script_dependencies,
		SVET_VERSION,
		true
	);
	
	$script_dependencies[] = 'svet-imageloaded';
	
	//wp_enqueue_script('masonry');	
	//$script_dependencies[] = 'masonry';
	
	wp_enqueue_script(
		'svet-grids',
		$theme_dir_url . '/js/grids.min.js',
		$script_dependencies,
		SVET_VERSION,
		true
	);
	
	$script_dependencies[] = 'svet-grids';
	
	wp_enqueue_script(
		'svet-front',
		$theme_dir_url . '/js/front.js',
		$script_dependencies,
		SVET_VERSION,
		true
	);
	
}
add_action( 'wp_enqueue_scripts', 'svet_scripts' );

add_action( 'admin_enqueue_scripts', 'svet_admin_scripts' );
function svet_admin_scripts() {
			
	wp_enqueue_style('svet-admin', get_template_directory_uri().'/css/admin.css', array());
	
}

add_filter('show_admin_bar', 'svet_hide_admin_bar');
function svet_hide_admin_bar($show_admin_bar){
	
	if(!current_user_can('edit_posts')){
		$show_admin_bar = false;
	}
	
	return $show_admin_bar;
}



/**
 * Includes
 */

require get_template_directory().'/inc/aq_resizer.php';
require get_template_directory().'/inc/post-types.php';
require get_template_directory().'/inc/media.php';
require get_template_directory().'/inc/extras.php';
require get_template_directory().'/inc/template-tags.php';
require get_template_directory().'/inc/shortcodes.php';
require get_template_directory().'/inc/widgets.php'; 


if(is_admin()){
	require get_template_directory() . '/inc/admin.php';
}
