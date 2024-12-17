<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * @package OGP Press
 * @since OGP 1.0.0
 */
get_header();
if(empty($post->ID )){ $hhhoops_ad_info = hhhoops_start_ads( $post->ID ); }else{ $hhhoops_ad_info = ''; }
if(!empty($hhhoops_ad_info['go'])){ $hhhoops_ad_info_go = $hhhoops_ad_info['go']; }else{ $hhhoops_ad_info_go = ''; }

?>

<section id="content" class="grid-x site-main <?php echo ( (has_post_thumbnail( $post->ID ) || get_post_meta($post->ID, 'hero-video', true)) && !is_product() ) ? 'featured-image small-padding-top ' : ''; ?>xlarge-padding-bottom<?php if ( $hhhoops_ad_info_go ) echo $hhhoops_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell small-12">
		<?php
		if ( $hhhoops_ad_info_go ) echo $hhhoops_ad_info['ad_before'] . $hhhoops_ad_info['ad_content'] . $hhhoops_ad_info['ad_after'];
		if ( have_posts() ) : 
		if ( is_home() && ! is_front_page() ) : ?>
		<div class="tiny-margin-bottom tiny-padding gset no-border">
			<h1 class="entry-title screen-reader-text"><?php single_post_title(); ?></h1>
		</div>
		<?php endif;

    while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', get_post_type() );

		endwhile;
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

		endif;
		?>
	</div>
</section>

<?php get_footer(); ?>