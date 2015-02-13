<?php
/**
 * Template Name: FullWidth
 **/

get_header(); ?>
<header class="section-header">
	<?php get_template_part('partials/title', 'section');?>	
</header>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
			<div class="entry-content">
				<?php the_content(); ?>		
			</div>
			<?php endwhile; // end of the loop. ?>
		</main><!-- #main -->
	</div><!-- #primary -->
	
<?php get_footer(); ?>
