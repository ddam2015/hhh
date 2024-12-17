<?php
/**
 * Template Name: Alumni
 */

get_header();
// $hhhoops_ad_info = hhhoops_start_ads( $post->ID );


$default_profile_img = get_site_url() . '/wp-content/themes/hhhoops-press/assets/hhhoops_profile_placeholder.jpg';
$award_data = g365_conn( 'g365_build_awards', [24] );
// echo '<pre class="hide">';
// print_r($award_data);
// echo '</pre>';	

?>

<section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php //if ( $hhhoops_ad_info['go'] ) echo $hhhoops_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell small-12">
		<?php
// 		if ( $hhhoops_ad_info['go'] ) echo $hhhoops_ad_info['ad_before'] . $hhhoops_ad_info['ad_content'] . $hhhoops_ad_info['ad_after'];
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', get_post_type() );

		endwhile;
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

		endif;
		?>
		<div class="tabs separate grid-x small-up-2 medium-up-3 large-up-4 align-center text-center collapse" id="event-tabs" data-tabs>
			<?php foreach( $award_data->records as $dex => $group_data ) : if( empty(array_intersect($group_data->item_ids, $award_data->data_present)) ) continue; ?>
				<div class="tabs-title cell<?php echo ( $dex == 0 ) ? ' is-active' : ''; ?>">
					<a href="#<?php echo strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name)); ?>"><?php echo $group_data->name; ?></a></div>
			<?php endforeach; ?>
		</div>
		<div id="tables-container" class="tabs-content table-data table-reveal header-padding gset-wrap-tabs" data-tabs-content="event-tabs">
			<?php foreach( $award_data->records as $dex => $group_data ) : if( empty(array_intersect($group_data->item_ids, $award_data->data_present)) ) continue;
				$group_data->handle = strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name));
			?>
			<div class="grid-x tabs-panel small-padding<?php echo ( $dex == 0 ) ? ' is-active' : ''; ?>" id="<?php echo $group_data->handle; ?>">
				<div class="cell gset medium-padding">
					<h2 class="hide"><?php echo $group_data->name; ?></h2>
					<div class="text-center">
						<img class="width-50-200" src="<?php echo $group_data->records[0]->logo_img; ?>" alt="Official Logo of <?php echo $group_data->name; ?>">
					</div>
					<div>
						<nav class="tabs separate grid-x small-up-2 medium-up-3 large-up-7 align-center text-center collapse medium-padding-bottom small-padding-top" id="<?php echo $group_data->handle; ?>_awards" data-tabs>
							<?php foreach( $group_data->records as $ev_dex => $event_data ) : if( empty(in_array($event_data->id, $award_data->data_present)) ) continue;
								$increment_title = date('Y',strtotime($event_data->eventtime));
							?>
							<div class="tabs-title<?php echo ( $ev_dex == 0 ) ? ' is-active' : ''; ?>"><a href="#<?php echo $group_data->handle; ?>_awards_<?php echo $increment_title; ?>"><?php echo $increment_title; ?></a></div>
							<?php endforeach; ?>
						</nav>
						<div class="award-data tabs-content table-data" data-tabs-content="<?php echo $group_data->handle; ?>_awards">
						<?php foreach( $group_data->records as $ev_dex => $event_data ) : if( empty(in_array($event_data->id, $award_data->data_present)) ) continue; ?>
							<div class="tabs-panel<?php echo ( $ev_dex == 0 ) ? ' is-active' : ''; ?>" id="<?php echo $group_data->handle; ?>_awards_<?php echo date('Y',strtotime($event_data->eventtime)); ?>">
								 <table class="pTable text-center no-margin-bottom scroll-for-small">
									<tbody>
										<tr class="sub-head">
										<?php
										$col_classes = array_keys( (array) current($award_data->awards->{$event_data->id}) );
										$col_count = count( $col_classes );
										foreach( $col_classes as $award_title_dex => $award_class) : ?>
											<th>Class of <?php echo $award_class; ?></th>
										<?php endforeach; ?>
										</tr>
										<?php foreach( $award_data->awards->{$event_data->id} as $award_dex => $award_vals) : $award_vals = (array) $award_vals; ?>
										<tr class="division-title">
											<th colspan="<?php echo $col_count; ?>">
												<h3><?php echo $award_vals[$col_classes[0]][0]->award_label; ?></h3>
											</th>
										</tr>
										<?php
										$line_count = 0;
										foreach( $award_vals as $award_item_dex => $award_val ) if( count( $award_val ) > $line_count ) $line_count = count( $award_val );
										for( $i=0; $i < $line_count; $i++ ){
											echo '<tr>';
											foreach( $col_classes as $award_title_dex => $award_class ) {
												echo ( !empty($award_vals[$award_class][$i]->award_type) && $award_vals[$award_class][$i]->award_type == 1) ? '<td class="award-profile-img">' : '<td>';
												if( empty($award_vals[$award_class][$i]->player_url) ) {
													echo '&nbsp;';
												} else {
													echo '<a href="https://grassroots365.com/player/' . $award_vals[$award_class][$i]->player_url . '" target="_blank" title="Official Grassroots365 Player Profile for ' . $award_vals[$award_class][$i]->player_name . '">';
													if( $award_vals[$award_class][$i]->award_type == 1){
														$player_img = ( empty($award_vals[$award_class][$i]->profile_img) ) ? $default_profile_img : $award_vals[$award_class][$i]->profile_img;
														echo '<img src="' . $player_img . '" alt="' . $award_vals[$award_class][$i]->player_name . ' at ' . $group_data->name . '"><br>';
													}
													echo $award_vals[$award_class][$i]->player_name . '</a>';
												}
												echo '</td>';
											}
											echo '</tr>';
										}
										endforeach;
										?>
									</tbody>
								</table>
							</div>
						<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>