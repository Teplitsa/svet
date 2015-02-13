<?php
/**
 * The template for displaying all single posts.
 *
 * @package svet
 */

get_header(); ?>
<header class="section-header">
	<?php get_template_part('partials/title', 'section');?>	
</header>

<div class="frame">
	<div id="primary" class="content-area bit md-8">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'partials/content_single', get_post_type() ); ?>

			<?php svet_post_nav(); ?>
			

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
