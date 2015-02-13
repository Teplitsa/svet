<?php
/**
 * Shortcodes
 **/

/**
 * Page blocks
 **/

add_shortcode('page_blocks', 'page_blocks_screen');
function page_blocks_screen($atts){
	global $post, $wp_query;
	
	if(!function_exists('get_field'))
		return '';
	
	$out = '';
	
	if(have_rows('page_blocks')) {
	ob_start();
	
		// loop through the rows of data
		while ( have_rows('page_blocks') ) {
			the_row();			
			$layout = get_row_layout();
			
			if($layout == 'pic_block'){
				$img_id = get_sub_field('block_pic');				
				$c_page_id = get_sub_field('connected_page');
				$title = get_sub_field('block_title');
				$url = get_sub_field('block_link');
				$color = get_sub_field('block_color');
				$format = 'bg'; //get_sub_field('block_format');
				
				
				$c_page = ($c_page_id) ? get_page($c_page_id) : false;
				$title = (empty($title) && $c_page) ? get_the_title($c_page) : $title;
				$url = (empty($url) && $c_page) ? get_permalink($c_page) : $url;
				$alt = sprintf(__('Thumbnail for page - %s', 'svet'), $title);
				
				$css = 'pictured-block '.esc_attr($color).' '.esc_attr($format);
			?>
				<div class="pb-item bit sm-6 md-4">
					<article class="inner <?php echo $css;?>">
						<?php
							if(!empty($url)){
								echo "<a href='{$url}'>";
							}
							if(!empty($img_id)){
								echo "<div class='img'>";
								echo wp_get_attachment_image($img_id, 'post-thumbnail', false, array('alt' => $alt));
								echo "</div>";
							}
							if(!empty($title)){
								echo "<h3>";
								echo $title;
								echo "</h3>";
							}
							if(!empty($url)){
								echo "</a>";
							}
						?>
						
					</article>
				</div>
			<?php
			}
			elseif($layout == 'img_block') {
				$img_id = get_sub_field('block_pic');
				$url = get_sub_field('block_link');
				$css = 'image-block';
			?>
				<div class="pb-item bit sm-6 md-4">
					<div class="inner <?php echo $css;?>">
					<?php
						if(!empty($url)){
							echo "<a href='{$url}'>";
						}
						if(!empty($img_id)){
							echo "<div class='img'>";
							echo wp_get_attachment_image($img_id, 'post-thumbnail');
							echo "</div>";
						}						
						if(!empty($url)){
							echo "</a>";
						}
					?>
					</div>
				</div>
			<?php
			}
			elseif($layout == 'text_block') {
							
				$c_page_id = get_sub_field('connected_page');
				$title = get_sub_field('block_title');
				$url = get_sub_field('block_link');
				$color = get_sub_field('block_color');
				$text = get_sub_field('block_desc');
								
				$c_page = ($c_page_id) ? get_page($c_page_id) : false;
				$title = (empty($title) && $c_page) ? get_the_title($c_page) : $title;
				$url = (empty($url) && $c_page) ? get_permalink($c_page) : $url;
				
				
				$css = 'text-block '.esc_attr($color);
			?>
				<div class="pb-item bit sm-6 md-4">
					<div class="inner <?php echo $css;?>">
					<?php
						if(!empty($url)){
							echo "<a href='{$url}'>";
						}
						
						if(!empty($title)){
							echo "<h3>";
							echo $title;
							echo "</h3>";
						}
						if(!empty($text)){
							echo "<div class='desc'>";
							echo $text;
							echo "</div>";
						}
						if(!empty($url)){
							echo "</a>";
						}
					?>
					</div>
				</div>
			<?php
			}
	
		} //enwhile
		$out = ob_get_contents();
		ob_end_clean();
		
		$sec_label = "<h2 class='screen-reader-text'>Подразделы</h2>";
		
		$out = "<section class='page-blocks'>{$sec_label}<div class='frame'>{$out}</div></section>";
	}
	
	return $out;
}

add_shortcode('embed_post', 'embed_post_screen');
function embed_post_screen($attr){
	extract( shortcode_atts( array(
	      'id' => 0
     ), $attr ) );
	
	$epost = get_post(intval($id));
	if(!empty($epost))
		return apply_filters('the_content', $epost->post_content);
	else	
		return '';
}


/**
 * Compact custom togle
 **/
add_shortcode('my_togle', 'frl_my_toggle_screen');
function frl_my_toggle_screen($atts, $content = ''){
	
	if(empty($content))
		return '';
	
	$open = __('More', 'apl');
	$close = __('Close', 'apl');
	
	
	$out = '<div class="tst-toggle content-details">';
	$out .= '<div class="tst-toggle-content">';
	$out .= apply_filters('the_content', $content).'</div>';
	$out .= "<div class='tst-toggle-trigger'><span class='open'>{$open}</span><span class='close'>{$close}</span></div>";
	$out .= "</div>";

	return $out;
}





/**
 * Markup shortcodes
 **/
add_shortcode('clear', 'frl_clear_screen');
function frl_clear_screen($atts){
		
	
	$out = '<div class="clear"></div>';		

	return $out;
}


/**
 * Partners gallery
 **/

add_shortcode('partners_gallery', 'apl_partners_gallery_screen');
function apl_partners_gallery_screen($atts){	
	
	extract( shortcode_atts( array(
		'type' => '',
		'num'  => -1,
		'css'  => ''
	), $atts ));
		
	$size = 'full'; //logo size
	
	$args = array(
		'post_type' => 'partner',
		'posts_per_page' => $num,
		'orderby' => array('menu_order' => 'DESC')
	);
	
	if(!empty($type)){
		
		$type = array_map('trim', explode('_', $type));
		
		
		$args['tax_query'][] = array(
			'taxonomy' => ($type[0] == 'category') ? 'partner_cat' : 'period',
			'field' => 'id',
			'terms' => intval($type[1])
		);
	}
	
	$query = new WP_Query($args);
		
	ob_start();
?>
	<ul class="cf partners logo-gallery frame <?php echo $css;?>">
    <?php
		foreach($query->posts as $item):
        
            $url = (!empty($item->post_excerpt)) ? esc_url($item->post_excerpt) : '';
            $txt = esc_attr($item->post_title);					
			$cat = frl_get_partner_type($item);
        ?>
		<li class="bit mf-4 md-3 lg-2">
			<div class="logo">
				<div class='logo-frame'>			
				<?php if(!empty($url)): ?>
					<a href="<?php echo $url;?>" target="_blank" title="<?php echo $txt;?>" class="logo-link">
				<?php else: ?>
					<span class="logo-link" title="<?php echo $txt;?>">
				<?php endif;?>
				
				<?php echo get_the_post_thumbnail($item->ID, $size);?>
			
				<?php if(!empty($url)): ?>
					</a>
				<?php else: ?>
					</span>
				<?php endif;?>
				</div>
				<?php if(!empty($cat)):?>
					<span class="partner-cat"><?php echo $cat;?></span>
				<?php endif;?>
			</div>
			
		</li>
        <?php endforeach; ?>        
    </ul>
<?php	
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}