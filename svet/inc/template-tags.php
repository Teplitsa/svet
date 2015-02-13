<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package svet
 */

/* CPT Filters */
add_action('parse_query', 'frl_request_corrected');
function frl_request_corrected($query) {
	
	if(is_admin())
		return;
	
	if(is_post_type_archive('film')){
		$query->query_vars['orderby'] = array('title' => 'ASC');
	}
	
	//var_dump($query->query_vars);
	
	/*if(is_tag() && $query->is_main_query()){
		//var_dump($query->query_vars);
		
		$query->query_vars['post_type'] = array('post', 'event', 'material');
	}
	elseif((is_post_type_archive('element') ) && $query->is_main_query()){
		$query->query_vars['orderby'] = 'menu_order';
		$query->query_vars['order'] = 'ASC';
		
	}
	elseif((is_post_type_archive('member') || is_tax('membercat')) && $query->is_main_query()){
		$query->query_vars['orderby'] = 'meta_value';
		$query->query_vars['meta_key'] = 'brand_name';
		$query->query_vars['order'] = 'ASC';
		$query->query_vars['posts_per_page'] = 24;
	}*/
	
	
} 



/* Custom conditions */
function is_about(){
	global $post;
		
	if(is_page_branch('about'))
		return true;
	
	return false;
}

function is_page_branch($slug){
	global $post;
	
	if(empty($slug))
		return false;
	
		
	if(!is_page())
		return false;
	
	if(is_page($slug))
		return true;
	
	$parents = get_post_ancestors($post);
	$test = get_page_by_path($slug);
	if(in_array($test->ID, $parents))
		return true;
	
	return false;
}


function is_tax_branch($slug, $tax) {
	global $post;
	
	$test = get_term_by('slug', $slug, $tax);
	if(empty($test))
		return false;
	
	if(is_tax($tax)){
		$qobj = get_queried_object();
		if($qobj->term_id == $test->term_id || $qobj->parent == $test->term_id)
			return true;
	}
	
	if(is_singular() && is_object_in_term($post->ID, $tax, $test->term_id))
		return true;
	
	return false;
}

if(!function_exists('is_posts')):
function is_posts() {
	
	if(is_home() || is_category() || is_tag())
		return true;
		
	if(is_singular('post'))
		return true;
	
	return false;
}
endif;

function is_cine(){
	global $post;
		
	if(is_page_branch('cine'))
		return true;
	
	if(is_post_type_archive('film'))
		return true;
	
	if(is_singular('film'))
		return true;
	
	return false;
}

function is_help() {
	
	if(is_page_branch('help-us'))
		return true;
	
	
	if(is_singular('leyka_campaign'))
		return true;
	
	return false;
}


function svet_posts_nav() {
	global $wp_query;
	
	//$query_pt = (isset($wp_query->query_vars['post_type'])) ? $wp_query->query_vars['post_type'] : 'post';
	$label_next = __('Older', 'svet');
	$label_prev = __('Newer', 'svet');	
?>

	<div class="nav-links cf">						
		<div class="nav-previous"><?php echo get_previous_posts_link( '&larr;&nbsp;' . "<span>$label_prev</span>"); ?></div>
		<div class="nav-next"><?php echo get_next_posts_link( "<span>$label_next</span>" . '&nbsp;&rarr;'); ?></div>
	</div>
<?php }
 

if ( ! function_exists( 'svet_paging_nav' ) ) :
/**
 * Display paging nav
 */
function svet_paging_nav($query = null) {
	global $wp_query;
	
	if(!$query)
		$query = $wp_query;
		
	// Don't print empty markup if there's only one page.
	if ($query->max_num_pages < 2 ) {
		return;
	}
?>
	<nav class="navigation paging-navigation" role="navigation">
		<span class="screen-reader-text"><?php _e( 'Paging navigation', 'svet' ); ?>: </span>
		<?php
			$p = frl_paginate_links($query, false);
			if(!empty($p)):
		?>
			<div class="pagination"><?php frl_paginate_links($query); ?></div>
		<?php endif; ?>
		
	</nav><!-- .navigation -->
	<?php
}
endif;


function frl_paginate_links($query = null, $echo = true) {
    global $wp_rewrite, $wp_query, $post;
    
	if(null == $query)
		$query = $wp_query;
	
    //var_dump($wp_query);
	$remove = array(
		's'	
	);
	
	$current = ($query->query_vars['paged'] > 1) ? $query->query_vars['paged'] : 1; 
	
		$parts = parse_url(get_pagenum_link(1));	
		$base = trailingslashit(esc_url($parts['host'].$parts['path']));
		$format = 'page/%#%/';
	
    
	$pagination = array(
        'base' => $base.'%_%',
        'format' => $format,
        'total' => $query->max_num_pages,
        'current' => $current,
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
        'end_size' => 4,
        'mid_size' => 4,
        'show_all' => false,
        'type' => 'plain', //list
		'add_args' => array()
    );
    
	
    if(!empty($query->query_vars['s']))
        $pagination['add_args'] = array('s' => str_replace(' ', '+', get_search_query()));
	
	foreach($remove as $param){
		if($param == 's')
			continue;
		
		if(isset($_GET[$param]) && !empty($_GET[$param]))
			$pagination['add_args'] = array_merge($pagination['add_args'], array($param => esc_attr(trim($_GET[$param]))));
	}
		
		    
    if($echo)
		echo paginate_links($pagination);
	return
		paginate_links($pagination);
}


if ( ! function_exists( 'svet_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function svet_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<span class="screen-reader-text"><?php _e( 'Post navigation', 'svet' ); ?></span>
		<div class="nav-links">
			<?php
				previous_post_link( '%link', '<span class="screen-reader-text">пред.</span><span class="meta-nav" title="пред.">&larr;</span>' );
				next_post_link('%link', '<span class="screen-reader-text">след.</span><span class="meta-nav" title="след.">&rarr;</span>');
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;



function svet_posted_on() {
	
	$cat = '';	
	
	if('post' == get_post_type()){
		$sr_label = '<span class="screen-reader-text">'.__('Category', 'svet').': </span>';
		$cat = get_the_term_list(get_the_ID(), 'category', '<span class="category">'.$sr_label, ', ', '</span>');
	}
?>
	<time class="date"><span class="screen-reader-text"><?php _e( 'Publishing date', 'svet' ); ?>: </span><?php echo esc_html(get_the_date());?></time>
<?php
	echo $cat;	
}



function svet_posted_on_single(){
	
	$pt = get_post_type();
	$cat = '';	
	$date = "<time class='date'>".esc_html(get_the_date())."</time>";
	
	if('post' == $pt){		
		$cat = get_the_term_list(get_the_ID(), 'category', '<span class="category">', ', ', '</span>');
	}
		
	printf(__('Published %s in category: %s', 'svet'), $date, $cat);
}


function frl_get_sep() {
	
	return "<span class='sep'>//</span>";
}


/**
 * Breadcrumbs
 **/
function frl_breadcrumbs(){	
	global $post;
	
	$links = array();
	
	if(is_singular('post')) {
		$p = get_post(get_option('page_for_posts'));
		if(!$p)
			return '';
		
		$links[] = "<a href='".get_permalink($p)."' aria-hidden='true'>".get_the_title($p)."</a>";
		$cat = wp_get_object_terms(get_the_ID(), 'category');
		if(!empty($cat)){
			$links[] = "<a href='".get_term_link($cat[0])."'>".apply_filters('frl_the_title', $cat[0]->name)."</a>";
		}
	}
	elseif(is_singular('film')){
		$p = get_page_by_path('cine');
		if(!$p)
			return '';
		
		$links[] = "<a href='".get_permalink($p)."' aria-hidden='true'>".get_the_title($p)."</a>";
		
		$films_url = get_post_type_archive_link('film');
		$links[] = "<a href='{$films_url}' aria-hidden='true'>".__('Films Catalogue', 'svet')."</a>";
	}
	elseif(is_singular('leyka_campaign')){
		$p = get_page_by_path('help-us');
		if(!$p)
			return '';
		
		$links[] = "<a href='".get_permalink($p)."'>".get_the_title($p)."</a>";
	}
	elseif(is_page() && $post->post_parent > 0 ) {
		$p = get_post($post->post_parent);
		if(!$p)
			return '';
		
		$links[] = "<a href='".get_permalink($p)."'>".get_the_title($p)."</a>";
		
	}
	elseif(is_post_type_archive('film')){
		$p = get_page_by_path('cine');
		if(!$p)
			return '';
		
		$links[] = "<a href='".get_permalink($p)."'>".get_the_title($p)."</a>";
	}
	
	if(count($links) == 1)
		$links[] = '';
	
	return (!empty($links)) ? "<div class='crumbs'>".implode(' / ', $links)."</div>" : '';	
}


/**
 * Accessable thumbnail
 **/
function svet_get_post_thumbnail($cpost = null, $size = 'post-thumbnail'){
	global $post;
	
	if(!$cpost)
		$cpost = $post;
		
	$thumb_id = get_post_thumbnail_id($cpost->ID);
	if(!$thumb_id)
		return '';
	
	$att = get_post($thumb_id);
	$att_label = sprintf(__('Thumbnail for - %s', 'svet'), get_the_title($cpost->ID));
	
	$attr = array(
		'alt' => (!empty($att->post_excerpt)) ? $att->post_excerpt : $att_label
	);
	
	return wp_get_attachment_image($thumb_id, $size, false, $attr);
}





