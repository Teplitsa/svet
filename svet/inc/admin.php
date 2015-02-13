<?php
/**
 * Admin customization
 **/

//add_filter('manage_posts_columns', 'frl_common_columns_names', 50, 2);
function frl_common_columns_names($columns, $post_type) {
		
	if(!in_array($post_type, array('post', 'attachment', 'project')))
		return $columns;
	
	$columns['id'] = 'ID';
	
	if($post_type != 'attachment')
		$columns['thumbnail'] = 'Миниат.';
		
	if($post_type == 'page')
		$columns['menu_order'] = 'Порядок';
		
	if(isset($columns['tags']))
		unset($columns['tags']);
		
	
	
	return $columns;
}


//add_action('manage_project_posts_custom_column', 'frl_common_columns_content', 2, 2);
//add_action('manage_posts_custom_column', 'frl_common_columns_content', 2, 2);
function frl_common_columns_content($column_name, $post_id) {
	
	$cpost = get_post($post_id);
	if($column_name == 'id'){
		echo intval($cpost->ID);
		
	}
	elseif($column_name == 'thumbnail') {
		$img = get_the_post_thumbnail($cpost->ID, 'thumbnail');
		if(empty($img))
			echo "&ndash;";
		else
			echo "<div class='admin-tmb'>{$img}</div>";
			
		//format
		if(in_array($cpost->post_type, array('material', 'post'))){
			$format = apl_get_media_format($post_id); 
			$format_label = $format; //@to_do get label here
			echo "<div class='format-label'><small>{$format_label}</small></div>";
		}	
	}
	if($column_name == 'menu_order'){
		echo intval($cpost->menu_order);
	}
}

//manage_edit-topics_columns
add_filter( "manage_edit-category_columns", 'frl_common_tax_columns_names', 10);
add_filter( "manage_edit-post_tag_columns", 'frl_common_tax_columns_names', 10);
function frl_common_tax_columns_names($columns){
	
	$columns['id'] = 'ID';
	
	return $columns;	
}

add_filter( "manage_category_custom_column", 'frl_common_tax_columns_content', 10, 3);
add_filter( "manage_post_tag_custom_column", 'frl_common_tax_columns_content', 10, 3);
function frl_common_tax_columns_content($content, $column_name, $term_id){
	
	if($column_name == 'id')
		return intval($term_id);
}


/* admin tax columns */
/*add_filter('manage_taxonomies_for_material_columns', function($taxonomies){
	$taxonomies[] = 'pr_type';
	$taxonomies[] = 'audience';
	
    return $taxonomies;
});*/



/**
* SEO UI cleaning
**/
add_action('admin_init', function(){
	foreach(get_post_types(array('public' => true), 'names') as $pt) {
		add_filter('manage_' . $pt . '_posts_columns', 'frl_clear_seo_columns', 100);
	}	
}, 100);

function frl_clear_seo_columns($columns){

	if(isset($columns['wpseo-score']))
		unset($columns['wpseo-score']);
	
	if(isset($columns['wpseo-title']))
		unset($columns['wpseo-title']);
	
	if(isset($columns['wpseo-metadesc']))
		unset($columns['wpseo-metadesc']);
	
	if(isset($columns['wpseo-focuskw']))
		unset($columns['wpseo-focuskw']);
	
	return $columns;
}

add_filter('wpseo_use_page_analysis', '__return_false');


/* Excerpt metabox */
add_action('add_meta_boxes', 'frl_correct_metaboxes', 2, 2);
function frl_correct_metaboxes($post_type, $post ){
	
	if(post_type_supports($post_type, 'excerpt')){
		remove_meta_box('postexcerpt', null, 'normal');
		
		$label = 'Аннотация';
		add_meta_box('frl_postexcerpt', $label, 'frl_excerpt_meta_box', null, 'normal', 'core');
	}
	//add_meta_box('postexcerpt', __('Excerpt'), 'post_excerpt_meta_box', null, 'normal', 'core');
}

function frl_excerpt_meta_box($post){

$label =  'Аннотация';
$help =  'Аннотация для списков, на странице основного текста добавляется в начало.';
?>
<label class="screen-reader-text" for="excerpt"><?php echo $label;?></label>
<textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
<p><?php echo $help;?></p>
<?php	
}


/* Menu Labeles */
add_action('admin_menu', 'svet_admin_menu_labels');
function svet_admin_menu_labels(){ /* change adming menu labels */
    global $menu, $submenu;
	
    /* find proper top menu item */    
    foreach($menu as $order => $item){
         
        if($item[2] == 'edit.php?post_type=acf-field-group'){
            $menu[$order][0] = __('Custom fields', 'svet');            
            break;
        }
    }   
    
}



/** Visual editor **/
add_filter( 'tiny_mce_before_init', 'svet_format_TinyMCE' );
function svet_format_TinyMCE( $in ) {

    $in['block_formats'] = "Абзац=p; Выделенный=pre; Заголовок 3=h3; Заголовок 4=h4; Заголовок 5=h5; Заголовок 6=h6";
	$in['toolbar1'] = 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_fullscreen,wp_adv ';
	$in['toolbar2'] = 'formatselect,underline,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help ';
	$in['toolbar3'] = '';
	$in['toolbar4'] = '';
	return $in;
}


add_action('admin_init', 'svet_no_subscriber_in_admin');
function svet_no_subscriber_in_admin(){
	if(!current_user_can('edit_posts')){
		wp_redirect(home_url());
	}
}
