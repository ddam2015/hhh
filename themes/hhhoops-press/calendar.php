<?php
/**
 * Template Name: Calendar
 */
get_header();
$g365_ad_info = g365_start_ads( $post->ID );

$default_profile_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_blank-placeholder_100x100.png';

?>

<section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell small-12">
		<?php
		if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', get_post_type() );

		endwhile;
		// If no content, include the "No posts found" template.
		else : 

			get_template_part( 'page-parts/content', 'none' );

		endif;

    global $wp_query;
    //if we have a calendar, show it otherwise show the master
    $group_data_package = ( empty($wp_query->query_vars['cl_id']) ? 44 : $wp_query->query_vars['cl_id'] );
    //get the package
    $group_data_package = g365_get_groups_data( $group_data_package, 2 );
//     print_r($group_data_package);
    //if we strike out with the package, then load the default
    if( empty($group_data_package) || !is_object($group_data_package) || $group_data_package === null ) $group_data_package = g365_get_groups_data( 44, 2 );
    $current_time = date("Y-m-d");
// echo '<pre class="hide">';
// print_r($group_data_package);
// echo '</pre>';

    //if this is just a single calendar, then don't add the top navigation
    if( $group_data_package->groups == 1 ) : ?>
		<ul class="tabs separate grid-x small-up-2 align-center text-center collapse" data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-tabs id="event-tabs" role="tablist">
			<?php foreach( $group_data_package->records as $dex => $group_data ) : if( empty($group_data->item_ids) ) continue; ?>
				<li class="tabs-title cell<?php echo ( $dex == 0 ) ? ' is-active large-expand' : ''; ?>">
					<a href="#<?php echo strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name)); ?>"><?php echo $group_data->name; ?></a></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
		<div id="tables-container" class="tabs-content table-data table-reveal medium-padding-top gset-wrap-tabs" data-tabs-content="event-tabs">
			<?php foreach( ( $group_data_package->groups == 1 ? $group_data_package->records : array($group_data_package) ) as $dex => $group_data ) : if( empty($group_data->item_ids) ) continue;
				$group_data->handle = strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name));
			?>
			<div class="tabs-panel no-padding<?php echo ( $dex == 0 ) ? ' is-active" role="tabpanel" aria-hidden="false' : ''; ?>" id="<?php echo $group_data->handle; ?>">
				<div class="grid-x calendar__container">
					<h2 class="cell small-margin-bottom"><?php echo $group_data->name; ?> Calendar</h2>
					<div class="cell">
<!-- 						<table>
							<thead>
								<tr>
									<th class="text-center">EVENT</th>
									<th class="text-center">DATE</th>
									<th class="text-center">NAME</th>
									<th class="text-center">LOCATION</th>
								</tr>
							</thead>
							<tbody class="table-stripe"> -->
							<?php
                $map = array();
                foreach( $group_data->records as $sub_dex => $sub_group_data ) :  //var_dump($sub_group_data);
                $event_time = date("Y-m-d", strtotime($sub_group_data->eventtime));
                if( intval($sub_group_data->enabled) !== 1 || $current_time >= $event_time || date("Y-m-d", strtotime($current_time . ' +1 year')) <= $event_time ) continue; 
                
                $event_time_sep = explode("-", $event_time);
                $event_month = (int)$event_time_sep[1];
                $dateObj   = DateTime::createFromFormat('!m', $event_month);
                $monthName = $dateObj->format('F'); // March
                if (!isset($map[$monthName])) {
                    $map[$monthName] = array();
                };
                $map[$monthName][] = $sub_group_data;
                ?>
								
<!--               <tr<?php echo ( empty($sub_group_data->link) ) ? '' : ' class="event-line" data-event_link="' . $sub_group_data->link . '"'; ?>>
									<td class="text-center"><img class="event-logo" src="<?php echo (empty($sub_group_data->logo_img)) ? $default_profile_img : $sub_group_data->logo_img; ?>" alt="<?php echo $sub_group_data->name; ?> Logo" /></td>
									<td class="text-center"><?php echo g365_build_dates($sub_group_data->dates, 2); ?></td>
									<td class="text-center"><?php echo ( empty($sub_group_data->link) ) ? $sub_group_data->short_name : '<a href="' . $sub_group_data->link . '" target="_blank" title="Official site of ' . $sub_group_data->name . '">' . $sub_group_data->short_name . '</a>'; ?></td>
									<td class="text-center"><?php echo implode('<br>', explode('|', $sub_group_data->locations)); ?></td>
								</tr> -->
							<?php endforeach; ?>
<!-- 							</tbody>
						</table> -->
                <?php
                  $monthKeys = array_keys($map);
                  for ($i = 0; $i < count($map); $i++) {
                    $month = $monthKeys[$i];
                    echo "<div class='calendarMonthContainer'>";
                      echo "<div class='calendarHeaderContainer'><h3 class='calendarAccordian active'>{$month}</h3><p class='minusPlus active'>-</p><p class='plusMinus hide'>+</p></div>";
                      echo "<table class='calendarInfo'>";
                        echo "<thead>";
                          echo "<tr>";
                            echo "<th class='text-center'>EVENT</th>";
                            echo "<th class='text-center'>DATE</th>";
                            echo "<th class='text-center'>NAME</th>";
                            echo "<th class='text-center'>LOCATION</th>";
                          echo "</tr>";
                        echo "</thead>";
                        foreach($map[$month] as $event) {
                          $eventDate = g365_build_dates($event->dates, 2);
                          $eventLocation = implode('<br>', explode('|', $event->locations));
                          echo "<tr>";
                          echo "<td class='text-center'><a href='{$event -> link}'><img class='event-logo' src='{$event -> logo_img}' alt='{$event -> name} logo' /></a></td>";
                          echo "<td class='text-center'><a href='{$event -> link}'>{$eventDate}</a></td>";
                          echo "<td class='text-center'><a href='{$event -> link}'>{$event->short_name}</a></td>";
                          echo "<td class='text-center'><a href='{$event -> link}'>{$eventLocation}</a></td>";
                          echo "</tr>";
                         };
                      echo "</table>";
                    echo "</div>";
                   };
                ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
    //the endosed opporators will always be the same
//     $endorsed_event_operators = g365_get_groups_data( 46, 1 );
    if( !empty($endorsed_event_operators) ) : ?>
		<div id="endorsed-operators" class="xlarge-padding-bottom"></div>
		<h2 class="xlarge-margin-top medium-margin-bottom"><?php echo $endorsed_event_operators->name; ?></h2>
		<div class="grid-x small-up-2 medium-up-4 large-up-6 align-center text-center">
			<?php foreach( $endorsed_event_operators->records as $dex => $org ) : ?>
			<div class="cell medium-margin-bottom">
				<a href="<?php echo get_site_url(); ?>/club/<?php echo $org->nickname; ?>">
					<img src="<?php echo get_site_url(); ?>/wp-content/uploads/org-logos/<?php echo $org->profile_img; ?>" alt="<?php echo $org->name; ?> official logo" /><br>
					<?php echo $org->name; ?><br>
					<small>(<?php echo $org->city; ?>, <?php echo $org->state; ?>)</small>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>

<script>
  let accHead = document.getElementsByClassName("calendarAccordian");
  let accInfo = document.getElementsByClassName("calendarInfo");
  let accPlus = document.getElementsByClassName("plusMinus");
  let accMinus = document.getElementsByClassName("minusPlus");

  
  for (let i = 0; i < accHead.length; i++) {
    accHead[i].addEventListener("click", function() {
    this.classList.toggle("active");
    accPlus[i].classList.toggle("active");
    accMinus[i].classList.toggle("active");      
    accInfo[i].classList.toggle("hide");
    accPlus[i].classList.toggle("hide");
    accMinus[i].classList.toggle("hide"); 
    });
    
    
  }
  
</script>