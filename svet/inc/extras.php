<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package svet
 */

 
/* Workin ones */ 

/** Default filters **/
add_filter( 'frl_the_content', 'wptexturize'        );
add_filter( 'frl_the_content', 'convert_smilies'    );
add_filter( 'frl_the_content', 'convert_chars'      );
add_filter( 'frl_the_content', 'wpautop'            );
add_filter( 'frl_the_content', 'shortcode_unautop'  );
add_filter( 'frl_the_content', 'do_shortcode' );

add_filter( 'frl_the_title', 'wptexturize'   );
add_filter( 'frl_the_title', 'convert_chars' );
add_filter( 'frl_the_title', 'trim'          );

/* jpeg compression */
add_filter( 'jpeg_quality', create_function( '', 'return 90;' ) );

 
/**
 * Custom excerpts
 **/

function svet_continue_reading_link() {
	
	$more = '&rarr;';
	$url = get_permalink();
		
	$sr_label = sprintf(__('Continue reading - %s', 'svet'), get_the_title());
	
	return '&nbsp;<a href="'. esc_url($url) . '" class="more-link"><span class="screen-reader-text">'.$sr_label.'</span><span class="meta-nav" aria-hidden="true">['.$more.']</span></a>';
}

/** excerpt filters  */
add_filter( 'excerpt_more', 'svet_auto_excerpt_more' );
function svet_auto_excerpt_more( $more ) {
	return '&hellip;';
}

add_filter( 'excerpt_length', 'svet_custom_excerpt_length' );
function svet_custom_excerpt_length( $l ) {
	return 50;
}

/** inject */
add_filter( 'get_the_excerpt', 'svet_custom_excerpt_more' );
function svet_custom_excerpt_more( $output ) {
	global $post;
		
	if(is_singular() || is_search())
		return $output;
	
	$output .= svet_continue_reading_link();	
	return $output;
}



/**
 * Current URL
 **/
if(!function_exists('frl_current_url')){
function frl_current_url() {
   
    $pageURL = 'http';
   
    if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")) {$pageURL .= "s";}
    $pageURL .= "://";
   
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
   
    return $pageURL;
}
}

/** extract posts IDs from query **/
function frl_get_posts_ids_from_query($query){
	
	$ids = array();
	if(!$query->have_posts())
		return $ids;
	
	foreach($query->posts as $qp){
		$ids[] = $qp->ID;
	}
	
	return $ids;
}





/**
 * Inject  top link  HTML
 * require <body> tag to have id ='top'
 **/
function frl_print_top_link(){

	if(!is_admin()):
 ?>	
	<div id="top-link">
		<a href="#top"><?php _e('On top', 'svet');?></a>
	</div>
	
<?php endif; 
}
add_action('wp_footer', 'frl_print_top_link');


/**
 * Favicon
 **/
function frl_favicon(){
	
	$favicon_test = WP_CONTENT_DIR. '/favicon.ico'; //in the root not working don't know why
    if(!file_exists($favicon_test))
        return;
        
    $favicon = content_url('favicon.ico');
	echo "<link href='{$favicon}' rel='shortcut icon' type='image/x-icon' >";
}
add_action('wp_enqueue_scripts', 'frl_favicon', 1);
add_action('admin_enqueue_scripts', 'frl_favicon', 1);
add_action('login_enqueue_scripts', 'frl_favicon', 1);


/* deregister taxonomy for object */
function deregister_taxonomy_for_object_type( $taxonomy, $object_type) {
	global $wp_taxonomies;

	if ( !isset($wp_taxonomies[$taxonomy]) )
		return false;

	if ( ! get_post_type_object($object_type) )
		return false;
	
	foreach($wp_taxonomies[$taxonomy]->object_type as $index => $object){
		
		if($object == $object_type)
			unset($wp_taxonomies[$taxonomy]->object_type[$index]);
	}
	
	return true;
}


/**
 * Adds custom classes to the array of body classes.
 */
function svet_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
//add_filter( 'body_class', 'svet_body_classes' );


/**
 *  Login/logout link in main menu
 **/
add_filter('wp_nav_menu_objects', 'svet_menu_items', 2, 2);
function svet_menu_items($items, $args){
	
	if(empty($items))
		return;	
	
	//var_dump($args);
	if($args->theme_location == 'primary'){
		
		foreach($items as $index => $menu_item){
			if(false !== strpos($menu_item->url, '#loginout#')){				
				if(is_user_logged_in()) {
					$items[$index]->url = wp_logout_url($_SERVER['REQUEST_URI']);
					$items[$index]->title = __('Log Out', 'svet');
				}
				else {
					$items[$index]->url = home_url('cine/view');
					$items[$index]->title = __('Log In', 'svet');
				}
			}
			
		}		
	}
	
	return $items;
}

