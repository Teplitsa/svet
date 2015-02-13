<?php
/**
 * The template for displaying search results pages.
 *
 * @package svet
 */

get_header(); ?>

<header class="section-header">	
	<?php get_template_part('partials/title', 'section');?>	
</header>
	

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>
			
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'partials/content', 'search' );?>

			<?php endwhile; ?>

			<?php svet_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'partials/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php //get_sidebar(); ?>

<?php get_footer(); ?>