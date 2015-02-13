<?php
/**
 * @package svet
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('tpl-post'); ?>>
<div class="screen-reader-text"><?php _e('News article', 'svet');?></div>

<div class="frame">
	
	<div class="bit sm-3 md-4">
		<a href="<?php the_permalink();?>" class="thumbnail-link">
		<?php
			$attr = array(
				'alt' => sprintf(__('Thumbnail for - %s', 'svet'), get_the_title()),				
			);
			the_post_thumbnail('post-thumbnail', $attr);
		?>
		</a>		
	</div>
	
	<div class="bit sm-9 md-8">
		<header class="entry-header">
			<div class="entry-meta"><?php svet_posted_on(); ?></div>
			<h3 class="entry-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>			
		</header>
	
		<div class="entry-summary">
			<?php the_excerpt(); ?>		
		</div>
	</div>

</div>	
</article><!-- #post-## -->