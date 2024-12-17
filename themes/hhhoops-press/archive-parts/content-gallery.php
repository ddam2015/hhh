<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class('cell'); ?>>

  <!-- Accordion tab title -->
  <?php the_title( '<h3 class="no-margin-botttom"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
	<a class="gallery-tile" href="<?php echo get_permalink(); ?>">
		<?php the_post_thumbnail( 'featured-home' ); ?>
	</a><!-- .entry-header -->

</div>
