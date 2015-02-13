<?php
/**
 * Template Name: Homepage
 **/

global $post;

$per_page = (function_exists('get_field')) ? (int)get_field('news_per_page', $post->ID) : 3;
$bottom =  (function_exists('get_field')) ? get_field('bottom_content', $post->ID) : '';
$banner = get_template_directory_uri().'/img/home-banner';

get_header(); ?>
<div id="primary" class="content-area">	
	<main id="main" class="site-main homepage" role="main">
		
		<section class="home-intro">
			<div class="home-banner">
				<div class="banner-img">
					<img src="<?php echo $banner;?>.svg" onerror="this.onerror=null;this.src=<?php echo $banner;?>.png" alt="Постер кинотеатра">
				</div>
				<div class="banner-link"><a href='<?php echo home_url('cine');?>'>Приглашаем в наш кинотеатр для слабовидящих&nbsp;></a></div>
			</div>
			
			<div class="intro-text">
			<?php
				while(have_posts()){
					the_post();
					the_content(); 
				}  
			?>
			</div>
		</section>
		
		<section class="home-news">
		<?php			
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => $per_page
			);
			$query = new WP_Query($args);
			if($query->have_posts()){
			echo '<h2><span>Наши мероприятия</span></h2>';
			echo '<div class="home-news-posts">';	
				while($query->have_posts()){
					$query->the_post();	
					get_template_part('partials/content');				
				}
				wp_reset_postdata();
			echo '</div>';
			}
		?>
			<div class="all-news"><a href="<?php echo home_url('events');?>">Все события&nbsp;>></a></div>
		</section>
		
		<section class="home-cta">
		<?php echo apply_filters('the_content', $bottom); ?>	
		</section>	
		
	</main><!-- #main -->
</div><!-- #primary -->
	
<?php get_footer(); ?>