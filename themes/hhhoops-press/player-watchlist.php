<?php
/**
 * Template Name: Player Watchlist
 */
get_header();
global $wp_query;
$watchlist_remote_data = g365_fn(['fn_name'=>'g365_remote_api', 'arguments'=>['hhh-watchlist', ['post_id'=>$post->ID, 'wt_id'=>$wp_query->query_vars['wt_id'], 'wt_tp'=>$wp_query->query_vars['wt_tp']]]]);
// $g365_ad_info = g365_start_ads( $post->ID );
$default_profile_img = 'https://hypeherhoopscircuit.com/wp-content/uploads/2022/11/H-2c.png';
$watchlistbg = 'http://grassroots365.com/wp-content/uploads/2021/08/watchlistbg.jpg';
?>

<section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
  <div class="cell small-12 watchlist small-padding">
  <?php
  if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
  if ( have_posts() ) : while ( have_posts() ) : the_post();

    get_template_part( 'page-parts/content', 'rankings' );

  endwhile;
  // If no content, include the "No posts found" template.
  else :

    get_template_part( 'page-parts/content', 'none' );

  endif;
//   echo '<div class="watchlist__wrap large-margin-bottom">
//           <img src="https://grassroots365.com/wp-content/uploads/2021/08/watchlistbg.jpg" class="watchlist__img" alt="watchlist image">
//           <div class="watchlist__info">
//               <h1 class="watchlist__heading">Player Watchlist</h1>
//               <p class="watchlist__text large-margin-top">Keep an eye out for these outstanding performers</p>
//           </div>
//       </div>';
  $watchlist_data = $watchlist_remote_data->watchlist_data;
// echo '<pre class="">';
// print_r($watchlist_data);
// echo '</pre>';
//if we have data, process it.
if( !empty($watchlist_data) && is_object($watchlist_data) ) :
  //if we have a group of groups, create the heading for that
  if( $watchlist_data->groups == 1 ) : ?>
    <h3 class="text-center"><?php echo date("F Y", strtotime($watchlist_data->records[0]->records[0]->start_datetime)); ?></h3>
    <div class="tabs separate grid-x small-up-2 medium-up-3 large-up-7 align-center text-center collapse" id="event-tabs" data-tabs data-deep-link="true" data-deep-link-smudge="true" data-deep-link-smudge-delay="600">
      <?php foreach( $watchlist_data->records as $dex => $group_data ) : if( empty($group_data->item_ids) ) continue; ?>
        <div style="width:17%" class="tabs-title cell<?php echo ( $dex == 0 ) ? ' is-active' : ''; ?> watchlist-title">
          <a href="#<?php echo strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name)); ?>"><?php echo (empty($group_data->abbr)) ? $group_data->name : $group_data->abbr; ?></a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else : ?>
    <h3><?php echo date("F Y", strtotime($watchlist_data->records[0]->start_datetime)) . ' - ' . $watchlist_data->name; ?></h3>
  <?php endif; ?>
  <div id="tables-container" class="tabs-content table-data table-reveal gset-wrap-tabs player-watchlist" data-tabs-content="event-tabs">
    <?php foreach( ( $watchlist_data->groups == 1 ? $watchlist_data->records : array($watchlist_data) ) as $dex => $group_data ) : if( empty($group_data->item_ids) ) continue;
      $group_data->handle = strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name));
    ?>
    <div class="grid-x tabs-panel small-padding<?php echo ( $dex == 0 ) ? ' is-active" role="tabpanel" aria-hidden="false' : ''; ?>" id="<?php echo $group_data->handle; ?>">
      <div class="cell">
<!--         <h2 class="watchlist__tab-heading emphasis border-radius">ELITE BASKETBALL CIRCUIT PLAYER WATCHLIST</h2> -->
        <div class="relative">
          <div class="watchlist__tabs-wrapper mobileScroll">
              <nav class="tabs separate grid-x small-up-2 medium-up-3 large-up-7 align-center text-center collapse medium-padding-bottom" id="<?php echo $group_data->handle; ?>_players" data-tabs>
                  <?php foreach( $group_data->records as $dex => $subgroup_data ) : if( empty($subgroup_data->rankings) ) continue; ?>
                  <div class="watchlist__nav tabs-title<?php echo ( $dex == 0 ) ? ' is-active' : ''; ?>"><a  href="#<?php echo $group_data->handle; ?>_players_<?php echo $subgroup_data->id; ?>"><?php echo $subgroup_data->ranking_type; ?></a></div>
                  <?php endforeach; ?>
              </nav>
          </div>
          <div class="tabs-content relative" data-tabs-content="<?php echo $group_data->handle; ?>_players">
            <?php foreach( $group_data->records as $dex => $subgroup_data ) : if( empty($subgroup_data->rankings) ) continue; ?>
              <div class="watchlist__panel gray-bg tabs-panel<?php echo ( $dex == 0 ) ? ' is-active' : ''; ?>" id="<?php echo $group_data->handle; ?>_players_<?php echo $subgroup_data->id; ?>">
                <div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-5 align-center text-center img-grid">
                  <?php
                    foreach( $subgroup_data->rankings as $subdex => $player_id ) :
                      $validate_player_img = $watchlist_remote_data->ebc_watch_list_player_imgs->$player_id;
                      $player_img = ( empty($watchlist_data->player_records->$player_id->player_img) ) ? $default_profile_img : $watchlist_data->player_records->$player_id->player_img;
                      if(!empty($watchlist_data->player_records->$player_id->player_url)){ $player_url = $watchlist_data->player_records->$player_id->player_url; }
                      else{ $player_url = ''; }
                      if(!empty($watchlist_data->player_records->$player_id->name)){ $player_name = $watchlist_data->player_records->$player_id->name; }
                      else{ $player_name = ''; }
                  ?>
                    <div class="cell">
                      <a class="emphasis ebc_watchlist__player" href="http://grassroots365.com/player/<?php echo $player_url; ?>" target="_blank">
                        <img class="watchlist__player-img small-margin-bottom lazy-img" loading="lazy" data-src="<?php echo $player_img; ?>" src="<?php echo $player_img; ?>" alt="Player headshot for <?php echo $player_name; ?>" /><br>
                        <p><?php echo $player_name; ?></p>
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
  <div class="archive large-margin-top">
    <hr>
    <h2>Archive</h2>
    <?php
    foreach( $watchlist_data->ranking_brackets as $dex => $dates ) {
      if(!empty($watchlist_remote_data->watchlist_date)){ $watchlist_date = $watchlist_remote_data->watchlist_date; }
      else{ $watchlist_date = ''; }
      if(!empty($watchlist_remote_data->watchlist_date_min_limit)){ $watchlist_date_min_limit = $watchlist_remote_data->watchlist_date_min_limit; }
      else{ $watchlist_date_min_limit = ''; }
      $watchlist_set = $watchlist_remote_data->watchlist_set;
      $date_start = date("Y-m-d", strtotime($dates->start_datetime));
      $date_end = date("Y-m-d", strtotime($dates->end_datetime));
      $date_name = ( ( $dex === 0 ) ? '' : '' ) . date("M Y", strtotime($dates->start_datetime));
      if( ($watchlist_date === null && $dex === 0) || $date_start == date("Y-m-d", strtotime($watchlist_date_min_limit)) ) {
        echo '<a class="button primary archive__btn" style="color:#000;" href="#">' . $date_name . '</a> ';
      } else {
        $date_url_helper = ( is_numeric($watchlist_set) ) ? $watchlist_data->nickname : $watchlist_set;
        $date_url = get_permalink() . $date_url_helper . '/' . $date_start . '_' . $date_end;
        echo '<a class="button archive__btn" href="' . $date_url . '">' . $date_name . '</a> ';
      }
    }
    ?>
  </div>
<?php	else :
  $g365_error = '<h3>Sorry, no data found.</h3><br><a href="/players-to-watch/">back to main watchlist</a>';
  if( !empty($player_data) ) $g365_error .= '<p>' . $watchlist_data . '</p>';
  echo $g365_error;
endif; ?>
</div>
</section>

<?php get_footer(); ?>