<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package svet
 */

$logo = get_template_directory_uri().'/img/logo';
?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<?php wp_head(); ?>
</head>

<body id="top" <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'svet' ); ?></a>
	<header id="masthead" class="site-header" role="banner"><div class="container">
		
		<div class="site-branding">
			<div class="site-title">
			<?php if(!is_front_page()) { ?>	
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="title-wide">
			<?php } else { ?>
				<span class="title-wide">
			<?php }?>
				<!-- title -->
				<span class="name"><?php bloginfo( 'name' ); ?></span><span id="logo"><img src="<?php echo $logo;?>.svg" onerror="this.onerror=null;this.src=<?php echo $logo;?>.png;" alt="Логотип"></span>
			
			<?php if(!is_front_page()) { ?>	
				</a>
			<?php } else { ?>
				</span>
			<?php }?>
			</div>
			
			<p class="site-description">
				<span class="screen-reader-text">Местонахождение, телефон, email: </span>
				<?php bloginfo( 'description' ); ?>
			</p>
		</div>	
	
	</div></header><!-- #masthead -->
	
	
	<nav class="main-navigation" role="navigation"><div class="container">
		<span class="screen-reader-text"><?php _e( 'Main menu', 'svet' ); ?></span>
		<div id="site-navigation" >
			<button id="menu-trigger" class="menu-toggle"><span class="dashicons dashicons-menu" aria-hidden="true"></span> <?php echo esc_html(__('Menu', 'svet')); ?></button>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false ) ); ?>
		</div>
	</div></nav>
	
	<div id="content" class="site-content"><div class="container">
