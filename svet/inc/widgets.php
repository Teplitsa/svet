<?php
/**
 * Widgets
 **/

 
add_action('widgets_init', 'apl_custom_widgets', 11);
function apl_custom_widgets(){

	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Widget_RSS');
	//unregister_widget('WP_Widget_Search');
	unregister_widget('FrmListEntries');
	
	
	//register_widget('FRL_RSS_Widget');
	//register_widget('FRL_Recent_Posts_Widget');
	//register_widget('FRL_Featured_Post_Widget');
	//register_widget('FRL_Social_Links');
	//register_widget('TST_Related_Widget');
	
}


/** RSS feed ***/

class FRL_RSS_Widget extends WP_Widget {
		
    function __construct() {
        $this->WP_Widget('widget_rss_feed', 'RSS канал', array(
            'classname' => 'widget_rss_feed',
            'description' => 'Трансляция произвольного RSS канала',
        ));
    }

    
    function widget($args, $instance) {
        extract($args); 
						
		
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$link_txt = apply_filters('frl_the_title', $instance['link_txt']);
		$link_url = esc_url($instance['link_url']);
		
        echo $before_widget;
        if($title){
			$title = "<a href='{$link_url}'>{$title}</a>";
            echo $before_title.$title.$after_title;
        }
	?>
		<div class="rss-news">
			<div class="cf">
				<?php $this->_items_markup($instance['rss_url'], $instance['number'], $instance['show_date']);?>
			</div>
			<div class="readmore"><a href='<?php echo $link_url;?>'><?php echo $link_txt;?></a></div>
		</div>
	<?php	
		echo $after_widget;
    }
	
	
	function _items_markup($rss_url, $number, $show_date){
		
		$rss = fetch_feed($rss_url);
		
		if (is_wp_error($rss) ) {
			echo "<div class='item'><span class='rss-error'>Канал временно недоступен</span></div>";
			return;
		}
		
		foreach($rss->get_items(0, $number) as $item) {
			
			$link = $item->get_link();
			while ( stristr($link, 'http') != $link )
				$link = substr($link, 1);
			$link = esc_url(strip_tags($link));
			
			$title = esc_attr(strip_tags($item->get_title()));
			
	
			$date = '';
			if($show_date ) {
				$date = $item->get_date( 'U' );
	
				if($date) {
					$date = '<span class="date">'.date_i18n('d.m', $date ).'</span>';
				}
			}
			
			if(!empty($link) && !empty($title)){
				echo "<div class='item'>";
				echo $date;
				echo "<a href='{$link}'>{$title}</a>";
				echo "</div>";
			}			
		}
		
		$rss->__destruct();
		unset($rss);
    		
	}
    function form($instance) {
		
        /* Set up some default widget settings */
		$defaults = array(
			'title'     => '',
			'rss_url'   => '',
			'link_url'  => '',
			'link_txt'  => '',
			'number'    => 4,
			'show_date' => 1,			
		);

		$instance = wp_parse_args((array)$instance, $defaults);
				
		$number = intval($instance['number']);
		$show_date = intval($instance['show_date']);
		
		
	?>
        <p>
            <label for="<?php echo $this->get_field_id('title');?>">
            Заголовок:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($instance['title']);?>">
        </p>
        
		<p>			
            <label for="<?php echo $this->get_field_id('rss_url');?>">
            URL канала:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('rss_url'); ?>" name="<?php echo $this->get_field_name('rss_url');?>" type="text" value="<?php echo esc_url($instance['rss_url']);?>">
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('link_txt');?>">
            Текст ссылки на сайт - источник:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('link_txt'); ?>" name="<?php echo $this->get_field_name('link_txt');?>" type="text" value="<?php echo esc_attr($instance['link_txt']);?>">
        </p>
		
		<p>			
            <label for="<?php echo $this->get_field_id('link_url');?>">
            URL сайта-источника:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('link_url'); ?>" name="<?php echo $this->get_field_name('link_url');?>" type="text" value="<?php echo esc_url($instance['link_url']);?>">
        </p>
				
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>">Кол.-во:</label>
			<select class="widefat" name="<?php echo $this->get_field_name('number'); ?>" id="<?php echo $this->get_field_id('number'); ?>">
				<?php for ($i = 1; $i < 11; $i++): ?>
					<option <?php selected($i, $number) ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php endfor; ?>
			</select>
		</p>
		
		<p>
            <label for="<?php echo $this->get_field_id('show_date');?>">            
            <input id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date');?>" type="checkbox" value="1" <?php checked(1, $show_date);?>>
			&nbsp;Отображать дату:</label>
        </p>	
    <?php
    }

    
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        
		$instance['title']     = sanitize_text_field($new_instance['title'], 'save');		
		$instance['link_txt']  = sanitize_text_field($new_instance['link_txt'], 'save');
		$instance['link_url']  = esc_url_raw(strip_tags($new_instance['link_url']));
		$instance['number']    = intval($new_instance['number']);
		$instance['show_date'] = intval($new_instance['show_date']);	
		$instance['rss_url'] = esc_url_raw(strip_tags($new_instance['rss_url']));
		
        return $instance;
    }
	
} //class end



/** Recent Posts Widget **/
class FRL_Recent_Posts_Widget extends WP_Widget {

	/** Widget setup */
	function __construct() {
        
		$widget_ops = array(
			'classname'   => 'widget_posts_recent',
			'description' => __('List of recent posts with some styling', 'apl')
		);
		$this->WP_Widget('widget_posts_recent', __('Recent Posts', 'apl'), $widget_ops);	
	}

	/** Display widget */
	function widget($args, $instance) {
		global $post;
		
		extract($args, EXTR_SKIP);

		$title = apply_filters('widget_title', $instance['title']);
		
		$limit = (int)$instance['limit']; 
		$excerpt = (bool)$instance['excerpt'];
		$length = (int)($instance['length']);	
		$date = (bool)$instance['date'];
		$thumb_size = $instance['thumb_size'];
		
		$taxonomy = $instance['taxonomy'];
		$term = $instance['slug'];
		$post_type = $instance['post_type'];
		
		$has_thumb = ($thumb_size == 'none') ? false : true;
		
		echo $before_widget;
	

		if($title)
			echo $before_title.$title.$after_title;
		
		$args = array(
			'post_type' => (!empty($post_type)) ? $post_type : 'any',
			'posts_per_page' => $limit
		);
		
		if(!empty($taxonomy) && !empty($term)){
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $term
				)
			);
		}		
		
		$query = new WP_Query($args);
		if($query->have_posts()):
		?>

		<div class="rpw-block<?php if($has_thumb) echo ' has-thumb';?>">

			<ul class="rpw-ul">

			<?php while($query->have_posts()): $query->the_post(); $pt = esc_attr(get_post_type()); ?>

				<li class="rpw-item <? echo $pt;?>">

			<?php
				if(has_post_thumbnail() && $has_thumb == true){ 					
					
					self::_recent_item_preview($thumb_size);
				}
					
				self::_recent_item_title();
				
				$meta = array();
				if($date)
					$meta[] = "<time>".get_the_date()."</time>";
				
				$meta = apply_filters('rpw_post_meta', $meta, $post, $instance);
				$sep = apply_filters('rpw_post_meta_separator', frl_get_sep(), $post);
				
				if(!empty($meta)){
					self::_recent_item_meta($meta, $sep);
				}
					
				if($excerpt){
					self::_recent_item_excerpt($length);
				}
			?>
				</li>

				<?php endwhile; wp_reset_postdata(); ?>

			</ul>

		</div><!-- .la-rpw-block -->

		<?php endif; echo $after_widget;
	}
	
	/**
	 * Markup methods
	 * work only inside the loop
	 **/
	static function _recent_item_preview($thumb_size){
	?>
		<div class="rpw-preview">
			<a href="<?php the_permalink(); ?>" rel="bookmark" ><?php the_post_thumbnail($thumb_size);?></a>
		</div>
	<?php
	}
	
	static function _recent_item_title(){
	?>
		<div class="rpw-title">
			<a href="<?php the_permalink();?>" title="<?php printf(esc_attr__('Permalink to %s', 'apl'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title();?></a>
		</div>	
	<?php
	}
	
	static function _recent_item_meta($meta, $sep){
	?>
		<div class="rpw-metadata"><?php echo implode($sep, $meta); ?></div>
	<?php
	}
	
	static function _recent_item_excerpt($length){
	?>
		<div class="rpw-excerpt"><?php echo self::create_excerpt($length);?></div>
	<?php
	}
	
	static function create_excerpt($excerpt_length = 55) {
		
		$text = strip_tags(get_the_excerpt());
		$text = str_replace(']]>', ']]&gt;', $text);
		
		//filter shortcodes
		$text = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $text );
		
		if(empty($text))
			return '';
		
		//check do we need trimming
		$all_words = preg_split("%\s*((?:<[^>]+>)+\S*)\s*|\s+%s", $text,-1,PREG_SPLIT_NO_EMPTY);
		
		if(count($all_words) <= $excerpt_length)
			return $text;
		
		// trim finally
		$words = preg_split("%\s*((?:<[^>]+>)+\S*)\s*|\s+%s", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		
		if(count($words) > $excerpt_length) {
			array_pop($words);
			$text = implode(' ', $words);
		}
		
		//clear last symbol
		$last = substr($text, -1);
		preg_match("/^(,|\.|!|\?|\-|:)$/", $last, $matches);
		if(!empty($matches))
			$text = substr($text, 0, -1);
		
		
		$text .= '&hellip;';
		
		
		return $text;
	}

	
	/** Update widget */
	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
			
		$instance['limit'] = intval($new_instance['limit']);
		$instance['excerpt'] = intval($new_instance['excerpt']);
		$instance['length'] = intval(($new_instance['length']));				
		$instance['thumb_size'] = sanitize_key($new_instance['thumb_size']);
		$instance['date'] = intval($new_instance['date']);
		
		$instance['taxonomy'] = sanitize_key($new_instance['taxonomy']);
		$instance['slug'] = sanitize_text_field($new_instance['slug']); 
		$instance['post_type'] = sanitize_key($new_instance['post_type']);		
		
		return $instance;
	}
	
	

	/** Widget setting */
	function form($instance) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => '',				
			'limit' => 5,
			'excerpt' => 0,
			'length' => 10,			
			'thumb_size' => '',
			'taxonomy' => '',
			'slug' => '',
			'post_type' => '',
			'date' => true,			
		);

		$instance = wp_parse_args((array)$instance, $defaults);
		
		$title = esc_attr($instance['title']);
				
		$limit = (int)$instance['limit'];
		$excerpt = (int)$instance['excerpt'];
		$length = (int)($instance['length']);
		$date = (int)$instance['date'];
		$thumb_size = $instance['thumb_size'];		
				
		$taxonomy = $instance['taxonomy'];
		$term = $instance['slug']; 
		$post_type = $instance['post_type'];		
				
		$post_types = get_post_types(array('public' => true), 'objects');
		$taxes = get_taxonomies(array('public' => true), 'objects');
		$thumb_sizes = array(
			'none'           => __('Don\'t show', 'apl'),
			'thumbnail'      => __('Square thumbnail', 'apl'),
			'post-thumbnail' => __('Landscape thumnbail', 'apl')
		);
	?>
				
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'apl');?>:</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo $title; ?>"/>
		</p>
			
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('limit')); ?>"><?php _e('Limit', 'apl'); ?>:</label>
			<select class="widefat" name="<?php echo $this->get_field_name('limit'); ?>" id="<?php echo $this->get_field_id('limit'); ?>">
				<?php for ($i = 1; $i <= 20; $i++) { ?>
					<option <?php selected($limit, $i) ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('date')); ?>"><?php _e('Display Date?', 'apl'); ?></label>
			<input id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" type="checkbox" value="1" <?php checked('1', $date); ?> />&nbsp;
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('excerpt')); ?>"><?php _e('Display Excerpt?', 'apl'); ?></label>
			<input id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>" type="checkbox" value="1" <?php checked('1', $excerpt); ?> />&nbsp;
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('length')); ?>"><?php _e('Excerpt Length', 'apl'); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('length')); ?>" name="<?php echo esc_attr($this->get_field_name('length')); ?>" type="text" value="<?php echo $length; ?>"/>
		</p>

		<?php if(current_theme_supports('post-thumbnails')) :?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('thumb_size')); ?>"><?php _e('Thumbnail', 'apl'); ?>:</label>
			<select class="widefat" name="<?php echo $this->get_field_name('thumb_size'); ?>" id="<?php echo $this->get_field_id('thumb_size'); ?>">
				<?php foreach($thumb_sizes as $key => $label) { ?>
					<option <?php selected($key, $thumb_size) ?> value="<?php echo $key; ?>"><?php echo $label; ?></option>
				<?php } ?>
			</select>
		</p>
		
		<?php endif; ?>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('post_type'));?>"><?php _e('Choose the Post Type', 'apl');?>:</label>

			<select class="widefat" id="<?php echo $this->get_field_id('post_type');?>" name="<?php echo $this->get_field_name('post_type');?>">
				<option value="0"><?php _e('Select post type', 'apl');?></option>
				<?php foreach ($post_types as $post_type) {?>
					<option value="<?php echo esc_attr($post_type->name);?>" <?php selected($instance['post_type'], $post_type->name);?>><?php echo esc_html($post_type->labels->singular_name);?></option>
				<?php } ?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('taxonomy'));?>"><?php _e('Choose the Taxonomy', 'apl');?>:</label>

			<select class="widefat" id="<?php echo $this->get_field_id('taxonomy');?>" name="<?php echo $this->get_field_name('taxonomy');?>">
			<option value="0"><?php _e('Select taxonomy', 'apl');?></option>
				<?php foreach ($taxes as $id => $tax):  ?>
					<option value="<?php echo esc_attr($id);?>" <?php selected($instance['taxonomy'], $id);?>><?php echo esc_html($tax->labels->name);?></option>
				<?php endforeach; ?>
			</select>
		</p>
		
		
		<p>
		<label for="<?php echo $this->get_field_id( 'slug' ); ?>"><?php _e('Term slug', 'apl'); ?>:</label>
		<input id="<?php echo $this->get_field_id( 'slug' ); ?>" name="<?php echo $this->get_field_name( 'slug' ); ?>" value="<?php echo $term; ?>" type="text" class="widefat"><br/>
		<small class="help"><?php _e('Copy paste a slug (or comma-separated list of several slugs) of the term', 'apl'); ?> </small>
		</p>
		
		
	<?php
	}
	
	
	
} //class end


// meta to RPW
add_filter('rpw_post_meta', 'apl_rpw_post_meta', 2, 3); 
function apl_rpw_post_meta($meta, $post, $instance) {
	
	if(!(bool)$instance['date']) //don't show at all
		return $meta;
	
	//@to_do
	
	return $meta;
}


/** Featured Post **/
class FRL_Featured_Post_Widget extends WP_Widget {
	
	/** Widget setup */
	function __construct() {
        
		$widget_ops = array(
			'classname'   => 'widget_featured_post',
			'description' => 'Рекомендуемый отдельный материал'
		);
		$this->WP_Widget('widget_featured_post',  'Рекомендация', $widget_ops);	
	}

	
	/** Display widget */
	function widget($args, $instance) {
		global $post;
		
		extract($args, EXTR_SKIP);

		$title = apply_filters('widget_title', $instance['title']);
		$cpost = get_post($instance['post_id']);
		if(empty($cpost))
			return;
		
		
		//markup
		echo $before_widget;
		
		if($title)
			echo $before_title.$title.$after_title;
			
	?>
		<div class="fw-item">
			<div class="entry-thumbnail">
				<a href="<?php echo get_the_permalink($cpost);?>">
					<?php echo get_the_post_thumbnail($cpost->ID, 'post-thumbnail');?>
				</a>
				<?php echo get_the_term_list($cpost->ID, 'topic', '<div class="entry-topics">', ', ', '</div>');?>
			</div>
			
			<h1 class="entry-title">
				<a href="<?php echo get_the_permalink($cpost);?>"><?php echo get_the_title($cpost); ?></a>
			</h1>
			
			<div class="entry-summary"><?php echo apply_filters('frl_the_content', apl_get_excerpt_with_link($cpost));?></div>
		</div>
		
	<?php	
		echo $after_widget;
	}
	
	

	/** Update widget */
	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		
		$instance['title']  = sanitize_text_field($new_instance['title']);			
		$instance['post_id']  = intval($new_instance['post_id']);
				
		return $instance;
	}
	
	

	/** Widget setting */
	function form($instance) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title'   => '',				
			'post_id' => 0
		);

		$instance = wp_parse_args((array)$instance, $defaults);
		
		$title = esc_attr($instance['title']);				
		$post_id = intval($instance['post_id']);				
	?>	
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Заголовок:</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo $title; ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('post_id')); ?>">ID записи:</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('post_id')); ?>" name="<?php echo esc_attr($this->get_field_name('post_id')); ?>" type="text" value="<?php echo $post_id; ?>">
		</p>	
		
	<?php
	}
	
	
} // class end


/** Social Links Widget **/
class FRL_Social_Links extends WP_Widget {
		
    function __construct() {
        $this->WP_Widget('widget_socila_links', __('Social Buttons', 'apl'), array(
            'classname' => 'widget_socila_links',
            'description' => __('Social links menu with optional text', 'apl'),
        ));
    }

    
    function widget($args, $instance) {
        extract($args); 
						
		
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$desc = apply_filters('the_content', $instance['desc']);
		
        echo $before_widget;
        if($title){			
            echo $before_title.$title.$after_title;
        }
		
		echo "<div class='social-menu-position'>";
		wp_nav_menu(array(
			'theme_location'  => 'social',
			//'menu'          => ,
			'menu_class'      => 'social-menu',
			'menu_id'         => 'social',
			'echo'            => true,                
			'depth'           => 0,
			'fallback_cb'     => ''
		));
		echo "</div>";
		
		if(!empty($desc))
			echo "<div class='social-desc'>{$desc}</div>";
		
		echo $after_widget;
    }
	
	
	
	
    function form($instance) {
		
        /* Set up some default widget settings */
		$defaults = array(
			'title' => '',			
			'desc'  => '',			
		);

		$instance = wp_parse_args((array)$instance, $defaults);
		
	?>
        <p>
            <label for="<?php echo $this->get_field_id('title');?>">
            Заголовок:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($instance['title']);?>">
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('desc');?>">Описание:</label>         
            <textarea id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc');?>" class="widefat"><?php echo esc_textarea($instance['desc']); ?></textarea>
			
        </p>	
    <?php
    }

    
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        
		$instance['title'] = sanitize_text_field($new_instance['title'], 'save');				
		$instance['desc'] = wp_kses_post(trim($new_instance['desc']));	
		
        return $instance;
    }
	
} //class end