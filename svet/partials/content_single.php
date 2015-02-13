<?php
/**
 * @package svet
 */

$show_thumb = (function_exists('get_field')) ? (bool)get_field('show_thumbnail') : true;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('tpl-post-full'); ?>>
	
	
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
	
	<?php if($show_thumb) { ?>
		<div class="entry-media"><?php echo svet_get_post_thumbnail(null, 'embed');?></div>
	<?php  } ?>
	
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
	
	<footer class="entry-footer">
		<?php svet_posted_on_single(); ?>
	</footer>
</article><!-- #post-## -->
