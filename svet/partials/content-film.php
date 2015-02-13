<?php
/**
 * @package svet
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('tpl-film'); ?>>
<h2 class="screen-reader-text"><?php _e('Film', 'svet');?></h2>
<div class="frame">
	
	<div class="bit sm-3 md-4">
		<a href="<?php the_permalink();?>" class="thumbnail-link">
		<?php
			$attr = array(
				'alt' => sprintf(__('Poster for film - %s', 'svet'), get_the_title()),				
			);
			the_post_thumbnail('poster', $attr);
		?>
		</a>		
	</div>
	
	<div class="bit sm-9 md-8">
		<header class="entry-header">
			<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
			
			<div class="entry-meta">
			<?php // some film meta
				$obj = get_field_object('film_director', get_the_ID());
				//var_dump($obj);
				if(!empty($obj['value'])){
					echo "<span class='meta-label'>".apply_filters('frl_the_title', $obj['label']).":</span> ";
					echo apply_filters('frl_the_title', $obj['value']);
				}
			?>
			</div>
			
		</header>
	
		<div class="entry-summary">
			<?php the_excerpt(); ?>		
		</div>
	</div>

</div>	
</article><!-- #post-## -->