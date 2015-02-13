<?php
/**
 * Title */
global $post;


// breadcrumbs
echo frl_breadcrumbs();	
?>


<h1 class="section-title"><?php
	if(is_singular()){
		the_title(); 
	}
	elseif(is_home()){
		$p = get_post(get_option('page_for_posts'));
		if($p)
			echo get_the_title($p);
	}
	elseif(is_category()){
		$p = get_post(get_option('page_for_posts'));
		if($p){
			echo get_the_title($p);
			single_cat_title(' / ');
		}
	}
	elseif(is_post_type_archive('film')){
		
		_e('Films Catalogue', 'svet');
	}
	elseif(is_search()){
		_e('Search results', 'svet');
	}
?></h1>
<?php if(is_singular()) { ?>
<h2 class="screen-reader-text wac-go-to-hell">Полный текст публикации</h2>
<?php } ?>

<?php
	if(is_home() || is_category()) {
		echo '<nav class="subtitle-menu">';
		echo '<div class="screen-reader-text">Меню раздела</div>';
		wp_nav_menu( array( 'theme_location' => 'journal', 'menu_class' => 'journal-menu', 'container' => 'false' ) );
		echo '</nav>';
	}
?>


