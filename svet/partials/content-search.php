<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package svet
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('tpl-search'); ?>>

	<header class="entry-header">		
		<h4 class="entry-title">
			<a href="<?php the_permalink();?>"><?php the_title();?></a>
		</h4>
		<cite><?php echo esc_attr(the_permalink()); ?></cite>
	</header>

	<div class="entry-summary">
		<?php the_excerpt(); ?>		
	</div>
</article><!-- #post-## -->
