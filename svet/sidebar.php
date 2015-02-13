<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package svet
 */

if ( ! is_active_sidebar( 'right-sidebar' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area bit md-3 md-offset-1" role="complementary">
	<?php dynamic_sidebar( 'right-sidebar' ); ?>
</div><!-- #secondary -->
