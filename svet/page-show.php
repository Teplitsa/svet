<?php
/**
 *	Template Name: Films View
 **/

global $wp_query;

get_header(); ?>
<header class="section-header">
	<?php get_template_part('partials/title', 'section');?>	
</header>

<div class="frame">
	<div id="primary" class="content-area bit md-8">
		<main id="main" class="site-main" role="main">
		<?php
			if(!is_user_logged_in()){
				
				//login form here
				echo "<p>".__('You have to be authorized to view films.', 'svet')."</p>";
				echo "<p>Для получения логина и пароля свяжитесь с нами.</p>";
				echo "<div class='svet-login-form'>";
				wp_login_form();
				echo "</div>";
			}
			else {
				
				//post loop here
				$args = array(
					'post_type' => 'film',
					'orderby' => 'title',
					'order' => 'ASC'
				);
				
				if(isset($wp_query->query_vars['paged'])){
					$args['paged'] = $wp_query->query_vars['paged'];
				}
				
				$query = new WP_Query($args);
				
				if($query->have_posts()){
					while($query->have_posts()) {
						$query->the_post();
						
					?>
					<div class='preview-item'>
						<div class="frame">
							<div class="bit mf-8 lg-9">
								<?php the_title(sprintf('<h4><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>');?>
							</div>
							<div class="bit mf-4 lg-3"><a href="<?php esc_url(the_permalink());?>"><?php _e('View', 'svet');?>&nbsp;&raquo;</a></div>
						</div>					
					</div>
					<?php
					}
					
					svet_paging_nav($query);
					
					wp_reset_postdata();
				}
				else {
					get_template_part( 'partials/content', 'none' );
				}
			}
		?>			
		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_sidebar(); ?>
	
</div>
<?php get_footer(); ?>
