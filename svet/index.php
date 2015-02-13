<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package svet
 */

get_header();
?>
<header class="section-header">
	<?php get_template_part('partials/title', 'section');?>	
</header>
	

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<h2 class='screen-reader-text'>Список материалов</h2>
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

	<?php //get_sidebar(); ?>

<?php get_footer(); ?>
