<?php
add_action('init', 'frl_custom_content', 20);
if(!function_exists('frl_custom_content')) {
function frl_custom_content(){
        
   
//	register_taxonomy('film_cat', array('film'), array(
//        'labels' => array(
//            'name'                       => __('Films categories', 'svet'),
//            'singular_name'              => __('Films category', 'svet'),
//            'menu_name'                  => __('Categories', 'svet'),
//            'all_items'                  => __('All Categories', 'svet'),
//            'edit_item'                  => __('Edit Category', 'svet'),
//            'view_item'                  => __('View Category', 'svet'),
//            'update_item'                => __('Update Category', 'svet'),
//            'add_new_item'               => __('Add new Category', 'svet'),
//            'new_item_name'              => __('New Category Name', 'svet'),
//            'parent_item'                => __('Parent Category', 'svet'),
//            'parent_item_colon'          => __('Parent Category:', 'svet'),         
//            'search_items'               => __('Search Categories', 'svet'),
//            'popular_items'              => __('Popular categories', 'svet'),
//            'separate_items_with_commas' => __('Separate with commas', 'svet'),
//            'add_or_remove_items'        => __('Add or Remove Categories', 'svet'),
//            'choose_from_most_used'      => __('Select from popular', 'svet'),
//            'not_found'                  => __('Not found', 'svet'),
//        ),
//        'hierarchical'      => true,
//        'show_ui'           => true,
//        'show_in_nav_menus' => false,
//        'show_tagcloud'     => false,
//        'show_admin_column' => true,
//        'query_var'         => true,
//        'rewrite'           => array('slug' => 'attack-type', 'with_front' => false),
//    ));
	

    register_post_type('film', array(
        'labels' => array(
            'name'               => __('Films', 'svet'),
            'singular_name'      => __('Film', 'svet'),
            'menu_name'          => __('Films', 'svet'),
            'name_admin_bar'     => __('Add film', 'svet'),
            'add_new'            => __('Add new film', 'svet'),
            'add_new_item'       => __('Add film', 'svet'),
            'new_item'           => __('New film', 'svet'),
            'edit_item'          => __('Edit film', 'svet'),
            'view_item'          => __('View film', 'svet'),
            'all_items'          => __('All films', 'svet'),
            'search_items'       => __('Search films', 'svet'),
            'parent_item_colon'  => __('Parent film', 'svet'),
            'not_found'          => __('No films found', 'svet'),
            'not_found_in_trash' => __('No films found in Trash', 'svet'),
       ),
        'public'             => true,
        'exclude_from_search'=> false,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_nav_menus'  => false,
        'show_in_menu'       => true,
        'show_in_admin_bar'  => true,
        //'query_var'          => true,        
        'capability_type'    => 'post',
        'has_archive'        => 'films',
        'rewrite'            => array('slug' => 'film', 'with_front' => false),
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies'         => array('film_cat'),
	));
    
	register_post_type('block', array(
        'labels' => array(
            'name'               => __('Blocks', 'svet'),
            'singular_name'      => __('Block', 'svet'),
            'menu_name'          => __('Blocks', 'svet'),
            'name_admin_bar'     => __('Add block', 'svet'),
            'add_new'            => __('Add new block', 'svet'),
            'add_new_item'       => __('Add block', 'svet'),
            'new_item'           => __('New block', 'svet'),
            'edit_item'          => __('Edit block', 'svet'),
            'view_item'          => __('View block', 'svet'),
            'all_items'          => __('All blocks', 'svet'),
            'search_items'       => __('Search blocks', 'svet'),
            'parent_item_colon'  => __('Parent block', 'svet'),
            'not_found'          => __('No blocks found', 'svet'),
            'not_found_in_trash' => __('No blocks found in Trash', 'svet'),
       ),
        'public'             => true,
        'exclude_from_search'=> false,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_nav_menus'  => false,
        'show_in_menu'       => true,
        'show_in_admin_bar'  => true,
        //'query_var'          => true,        
        'capability_type'    => 'post',
        //'has_archive'        => 'projects',
        'rewrite'            => false, //array('slug' => 'film', 'with_front' => false),
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array('title', 'editor'),
        'taxonomies'         => array(),
	));
    
   deregister_taxonomy_for_object_type('post_tag', 'post');
}

}//if frl_custom_content


/* Alter post labels */
function frl_change_post_labels($post_type, $args){ /* change assigned labels */
    global $wp_post_types;
     
    if($post_type != 'post')
        return;
     
    $labels = new stdClass();
     
    $labels->name               = "Статьи"; 
    $labels->singular_name      = "Статья";
    $labels->add_new            = "Добавить новую";
    $labels->add_new_item       = "Добавить новую";
    $labels->edit_item          = "Редактировать статью";
    $labels->new_item           = "Новая";
    $labels->view_item          = "Просмотреть";
    $labels->search_items       = "Поиск";
    $labels->not_found          = "Не найдено";
    $labels->not_found_in_trash = "В Корзине статьи не найдены";
    $labels->parent_item_colon  = NULL;
    $labels->all_items          = "Все статьи";
    $labels->menu_name          = "Статьи";
    $labels->name_admin_bar     = "Статья";
     
    $wp_post_types[$post_type]->labels = $labels;
}
//add_action('registered_post_type', 'frl_change_post_labels', 2, 2);
 
function frl_change_post_menu_labels(){ /* change adming menu labels */
    global $menu, $submenu;
     
    $post_type_object = get_post_type_object('post');
    $sub_label = $post_type_object->labels->all_items;
    $top_label = $post_type_object->labels->name;
     
    /* find proper top menu item */
    $post_menu_order = 0;
    foreach($menu as $order => $item){
         
        if($item[2] == 'edit.php'){
            $menu[$order][0] = $top_label;
            $post_menu_order = $order;
            break;
        }
    }
     
    /* find proper submenu */
    $submenu['edit.php'][$post_menu_order][0] = $sub_label;
}
//add_action('admin_menu', 'frl_change_post_menu_labels');
 
function frl_change_post_updated_labels($messages){     /* change updated post labels */
    global $post;
         
    $permalink = get_permalink($post->ID);
         
    $messages['post'] = array(
         
    0 => '',
    1 => sprintf( 'Статья обновлена. <a href="%s">Просмотреть</a>', esc_url($permalink)),
    2 => "Пользовательское поле обновлено",
    3 => "Пользовательское поле удалено",
    4 => "Статья обновлена",   
    5 => isset($_GET['revision']) ? sprintf('Редакция статьи восстановлена из %s', wp_post_revision_title((int)$_GET['revision'], false)) : false,
    6 => sprintf('Статья опубликована. <a href="%s">Просмотреть</a>', esc_url($permalink)),
    7 => "Статья сохранена",
    8 => sprintf('Статья отправлена на рассмотрение. <a target="_blank" href="%s">Просмотреть</a>', esc_url(add_query_arg('preview','true', $permalink))),
    9 => sprintf('Статья запланирована. <a target="_blank" href="%s">Просмотреть</a>', esc_url(add_query_arg('preview','true', $permalink))),
    10 => sprintf('Черновик статьи обновлен. <a target="_blank" href="%s">Просмотреть</a>', esc_url(add_query_arg('preview', 'true', $permalink))) 
    );
 
    return $messages;
}
//add_filter('post_updated_messages', 'frl_change_post_updated_labels');


//add_action( 'p2p_init', 'apl_connection_types' );
function apl_connection_types() {
	p2p_register_connection_type( array(
		'name' => 'project_post',
		'from' => 'project',
		'to'   => 'post',
		'sortable'   => true,
		'reciprocal' => false,
		'prevent_duplicates' => true,
		'admin_box' => array(
			'show' => 'any',
			'context' => 'normal',
			'can_create_post' => true
		),
		'admin_column' => 'to'
	) );
		
}



