<?php
/**
 * Film
 */

global $post;

$fields = get_field_objects($post->ID); 
//var_dump($fields);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('tpl-film-full'); ?>>
	
	<!-- films data -->
	<div class="frame film-intro">
		<div class="bit sm-4">
		<?php
			$attr = array(
				'alt' => sprintf(__('Poster for film - %s', 'svet'), get_the_title()),				
			);
			the_post_thumbnail('poster', $attr);
		?>
		</div>
		
		<div class="bit sm-8">
			<dl class="film-data">
			<?php
				if(!empty($fields)){ foreach($fields as $key => $obj) {
					if($key == 'film_video')
						continue;
					
					$label = apply_filters('frl_the_title', $obj['label']);
					$value = apply_filters('frl_the_content', $obj['value']);
			?>
				<dt><?php echo $label;?>: </dt>
				<dd><?php echo $value;?></dd>
				
			<?php }} ?>		
			</dl>
		</div>
	</div>
	
	
	<div class="entry-content">
		<?php the_content(); ?>
		
		<?php if(is_user_logged_in() && !empty($fields['film_video']['value'])) { ?>
			<div class="film-video">
				<h3>Просмотр фильма</h3>
				<div class="embed-content">
				<?php echo apply_filters('frl_the_content', $fields['film_video']['value']); ?>
				</div>
			</div>
			
		<?php }?>
	</div>	
	
</article><!-- #post-## -->
