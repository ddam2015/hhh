<?php
/**
 * Template Name: Team Ranking
 */
get_header();
global $wp_query;
$team_ranking_remote_data = g365_fn(['fn_name'=>'g365_remote_api', 'arguments'=>['hhh-team-ranking', ['post_id'=>$post->ID, 'rk_id'=>$wp_query->query_vars['rk_id'], 'rk_tp'=>$wp_query->query_vars['rk_tp']]]]);
$g365_ad_info = $team_ranking_remote_data->start_ads;
$default_profile_img = $team_ranking_remote_data->default_profile_img;
// echo '<pre>';
//   print_r($team_ranking_remote_data->ranking_data->records);
//   print_r($team_ranking_remote_data->team_info);
// echo '</pre>';
?>
<section class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom">
	<div class="cell small-12">
    <?php
//           $ranking_data = g365_build_ranking(184, $rank_date);
      $ranking_data = $team_ranking_remote_data->ranking_data;
//       			echo '<pre>';
// //       			print_r($rank_set);
// //         print_r($team_ranking_remote_data->ranking_data->records);
//       			print_r($ranking_data->records);
//       			echo '</pre>';
          //if we have a group of groups, create the heading for that
          echo '<h1 class="uppercase text-center" id="teamRankingHeader">Team Rankings</h1>
              <div id="teamRankingDetails">
                  <ul class="accordion" data-accordion data-allow-all-closed="true">
                      <li class="accordion-item" data-accordion-item>
                          <!-- Accordion tab title -->
                          <a href="#" class="accordion-title">Rankings Criteria</a>
                            <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                          <div class="accordion-content" data-tab-content>
                             <ul><li>Grassroots 365 (G365) tournament results are exclusively weighted in the team rankings. We do not factor in non-G365 events.&nbsp;</li><li>The higher levels of competitive divisions are the primary focus of the rankings.&nbsp; Gold division teams are more likely to be ranked than teams playing in Silver or below.&nbsp;</li><li>Final tournament standings as well as head to head results are used to determine placement within the rankings.</li><li>We observe as many games in person as possible to evaluate teams and players in live action.</li><li>Rankings are updated and re-evaluated once per month.</li></ul>
                          </div>
                      </li>
                  </ul>
              </div>';
      if( $ranking_data->groups == 1 ) : ?>
        <h3 class="centering"><?php echo date("F Y", strtotime($ranking_data->records[0]->records[0]->start_datetime)); ?></h3>
  <!-- 			<div class="tabs separate grid-x small-up-2 medium-up-3 large-up-6 align-center text-center collapse" id="event-tabs" data-tabs>
          <?php foreach( $ranking_data->records as $dex => $group_data ) : if( empty($group_data->item_ids) ) continue; ?>
            <div class="tabs-title cell<?php echo ( $dex == 0 ) ? ' is-active' : ''; ?>">
              <a href="#<?php echo strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name)); ?>"><?php echo (empty($group_data->abbr)) ? $group_data->name : $group_data->abbr; ?></a>
            </div>
          <?php endforeach; ?>
        </div> -->
      <?php else : ?>
        <h3><?php echo date("F Y", strtotime($ranking_data->records[0]->start_datetime)) . ' - ' . $ranking_data->name; ?></h3>
      <?php endif; ?>
      <?php foreach( ( $ranking_data->groups == 1 ? $ranking_data->records : array($ranking_data) ) as $dex => $group_data ) : if( empty($group_data->item_ids) ) continue;
          $group_data->handle = strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name));
        ?>
        <div class="grid-x tabs-panel small-padding<?php echo ( $dex == 0 ) ? ' is-active" role="tabpanel" aria-hidden="false' : ''; ?>" id="<?php echo $group_data->handle; ?>">
          <div class="cell white-bg medium-padding">
            <h2 class="border-radius">G365 Team Rankings</h2>
  <!--           <?php echo $group_data->name; ?>  -->
            <div>
                          <div class="mobile-tabs__wrapper">
                              <nav class="tabs separate grid-x small-up-2 medium-up-3 large-up-7 align-center text-center collapse medium-padding-bottom mobile-tabs__nav" id="<?php echo $group_data->handle; ?>_teams" data-tabs>
                                  <?php foreach( $group_data->records as $dex => $subgroup_data ) : if( empty($subgroup_data->rankings) ) continue; 
  //                                   echo $subgroup_data->ranking_type;
                                   if( $subgroup_data->ranking_type){?>
                                  <div class="tabs-title<?php echo ( $dex == 0 ) ? ' is-active' : ''; ?>"><a href="#<?php echo $group_data->handle; ?>_teams_<?php echo $subgroup_data->id; ?>"><?php echo $subgroup_data->ranking_type; ?></a></div>
                                  <?php }endforeach; ?>
                              </nav>
                          </div>
              <div class="tabs-content" data-tabs-content="<?php echo $group_data->handle; ?>_teams">
              <?php foreach( $group_data->records as $dex => $subgroup_data ) : if( empty($subgroup_data->rankings) ) continue; 
                $is_team = json_decode($subgroup_data->team_rankings); 
//                     echo '<pre>'; print_r($subgroup_data); echo '</pre>';
              ?>
                <div class="gray-border gray-bg tabs-panel<?php echo ( $dex == 0 ) ? ' is-active' : ''; ?>" id="<?php echo $group_data->handle; ?>_teams_<?php echo $subgroup_data->id; ?>">
                  <div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-5 align-center text-center img-grid ranking-grid">
                    <?php 
                    foreach( $subgroup_data->rankings as $subdex => $org_id ) :
                    $org_img = ( empty($ranking_data->org_records->$org_id->org_logo) ) ? $default_profile_img : 'http://sportspassports.com/wp-content/uploads/org-logos/' . $ranking_data->org_records->$org_id->org_logo;
                    $is_team_id = $is_team[$subdex]->org->team_id;
                    $club_url = "http://sportspassports.com/club/".$ranking_data->org_records->$org_id->org_url;
                    $team_url = "http://sportspassports.com/club/".$ranking_data->org_records->$org_id->org_url."/teams/".$team_ranking_remote_data->team_info->$is_team_id->url;
                    $team_name = $team_ranking_remote_data->team_info->$is_team_id->name;
                    ?>
                    <div class="cell relative small-margin-bottom">
                      <a class="white-bg gray-border emphasis" href="<?php echo (is_numeric($is_team_id) && !empty($is_team_id) ? $team_url : $club_url); ?>">
                        <img class="small-margin-bottom" loading="lazy" src="<?php echo $org_img; ?>" alt="Organization logo for <?php echo $ranking_data->org_records->$org_id->name; ?>" /><br>
                        <?php echo (!empty($is_team_id) ? $ranking_data->org_records->$org_id->name."<br/>".$team_name : $ranking_data->org_records->$org_id->name);?>
                      </a>
                    </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="large-margin-top">
        <hr>
        <h2>Archive</h2>
        <?php
        foreach( $ranking_data->ranking_brackets as $dex => $dates ) {
          $date_start = date("Y-m-d", strtotime($dates->start_datetime));
          $date_end = date("Y-m-d", strtotime($dates->end_datetime));
          $date_name = ( ( $dex === 0 ) ? 'Current Ranking - ' : '' ) . date("M Y", strtotime($dates->start_datetime));
          if( ($team_ranking_remote_data->rank_date === null && $dex === 0) || $date_start == date("Y-m-d", strtotime($team_ranking_remote_data->rank_date_min_limit)) ) {
            echo '<a class="button primary" href="#">' . $date_name . '</a> ';
          } else {
            $date_url_helper = ( is_numeric($team_ranking_remote_data->rank_set) ) ? $ranking_data->nickname : $team_ranking_remote_data->rank_set;
            $date_url = get_permalink() . $date_url_helper . '/' . $date_start . '_' . $date_end;
            echo '<a class="button" href="' . $date_url . '">' . $date_name . '</a> ';
          }
        }
        ?>
  </div>
</section>

<?php get_footer(); ?>