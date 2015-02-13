<?php
/**
 * Common media functions
 **/

// Custom image size for medialib
//add_filter('image_size_names_choose', 'grt_medialib_custom_image_sizes');
function grt_medialib_custom_image_sizes($sizes) {
	
	$addsizes = apply_filters('grt_medialib_custom_image_sizes', array(
		"post-thumb" => __("Column-width", "grt")
	));	
	
	return array_merge($sizes, $addsizes);
}



/**
 * Lightbox for linked images
 **/
add_filter('media_send_to_editor', 'frl_media_send_to_editor_filter', 2, 3);
function frl_media_send_to_editor_filter($html, $id, $attachment) {
		
	$post = get_post($id);		
	//var_dump($attachment);
	
	if (false !== strpos($post->post_mime_type, 'image')) { //image shortcode
		
		if(false !== strpos($html, '<a') && (strpos($attachment['url'], '.jpg') || strpos($attachment['url'], '.png')))
			$html = str_replace('<a ', '<a class="fresco" ', $html);

	}
		
	return $html;
}


/**
 * Gallery templates
 **/

add_action('init', 'lam_gallery_shortcodes', 1);
function lam_gallery_shortcodes(){
    
	remove_shortcode('gallery');
    add_shortcode('gallery', 'lam_gallery_screen');
	
}

if(!function_exists('lam_gallery_screen')):
function lam_gallery_screen($atts){	
	
	extract(shortcode_atts(array('ids' => '', 'columns' => 3, 'format' => 'lightbox'), $atts));
  

    $out = '';
    if(empty($ids))
        return $out; // no items

    $args = array(
        'post_type'   => 'attachment',
        'post_status' => 'inherit',
        'orderby'     => 'post__in',
        'order'       => 'ASC',
        'post_mime_type' => 'image',
        'post__in'     => explode(',', $ids),
        'posts_per_page' => -1
    );

    $query = new WP_Query($args);
    if(empty($query->posts))
        return $out; //no attachments
	
	if($format == 'lightbox'){ //default fresco-style gallery
		return lam_lightbox_gallery_output($query->posts, $columns);
	
	}	
}
endif;

if(!function_exists('lam_lightbox_gallery_output')):
function lam_lightbox_gallery_output($items, $columns) {
	
	$columns = intval($columns);

    if($columns == 0 || $columns > 8)
        $columns = 5;    

    $out = "<div class='lam-gallery'><ul class='lam-clearfix cols-{$columns}'>";
    $gallery_ref = uniqid('gallery-');

    foreach($items as $picture) {
		$size = apply_filters('lpg_thumbnail_size', 'post-thumbnail'); //backward compat		
		$title = esc_attr(stripslashes($picture->post_excerpt));
		
		$attr = array(
			'title' => '',
			'alt' => (!empty($title)) ? $title : ''
		);
		
        $img = wp_get_attachment_image($picture->ID, $size, false, $attr);
        $url = wp_get_attachment_url($picture->ID);
        

        // HTML for lightbox
        $out .= '<li>';
        $out .= "<a data-fresco-group='{$gallery_ref}' href='{$url}' data-fresco-caption='{$title}' rel='image-overlay' class='img-padder fresco'>{$img}</a>";
        $out .= '</li>';
    }

    $out .= '</ul></div>';

    return $out;	
}
endif;



/* how to add gallery settings 
add_action('print_media_templates', function(){

  // define your backbone template;
  // the "tmpl-" prefix is required,
  // and your input field should have a data-setting attribute
  // matching the shortcode name
  ?>
  <script type="text/html" id="tmpl-my-custom-gallery-setting">
    <label class="setting">
      <span><?php _e('My setting'); ?></span>
      <select data-setting="my_custom_attr">
        <option value="foo"> Foo </option>
        <option value="bar"> Bar </option>
        <option value="default_val"> Default Value </option>
      </select>
    </label>
  </script>

  <script>

    jQuery(document).ready(function(){

      // add your shortcode attribute and its default value to the
      // gallery settings list; $.extend should work as well...
      _.extend(wp.media.gallery.defaults, {
        my_custom_attr: 'default_val'
      });

      // merge default gallery settings template with yours
      wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
        template: function(view){
          return wp.media.template('gallery-settings')(view)
               + wp.media.template('my-custom-gallery-setting')(view);
        }
      });

    });

  </script>
  <?php

});
*/