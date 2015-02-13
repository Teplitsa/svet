<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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
			<div class="entry-content">
				<?php the_content(); ?>		
			</div>
			<?php endwhile; // end of the loop. ?>
		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_sidebar(); ?>
	
</div>
<?php get_footer(); ?>
