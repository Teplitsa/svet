<?php
/**
 * Films template
 **/

get_header();
?>
<header class="section-header">
	<?php get_template_part('partials/title', 'section');?>	
</header>
	
<div class="frame">
	<div id="primary" class="content-area bit md-8">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>
			
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part('partials/content', get_post_type());?>

			<?php endwhile; ?>

			<?php svet_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'partials/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
