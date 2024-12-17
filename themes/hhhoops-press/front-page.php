<?php
/**
 * The front page template
 * @package OGP  Press
 * @since OGP 1.0.0
 */

// News query for the slider
$news_feat = new WP_Query( array( 'category_name' => 'Featured', 'posts_per_page' => 6 ) );

//https://dev.grassroots365.com/wp-content/uploads/display-assets/event-promo-hhhoops.jpg
//https://dev.grassroots365.com/wp-content/uploads/2017/11/hhhoops-posts-banner.jpg
get_header();
// $product_event = g365_conn( 'g365_get_event_data', [$product_event_link, true] ); print_r($product_event);
//see if we need a splash display
$hhhoops_ad_info = g365_start_ads( $post->ID );

$default_img = get_site_url() . '/wp-content/themes/hhhoops-press/hhhoops_default_placeholder.gif';
$bigBG = get_site_url() . '/wp-content/uploads/2024/03/hhh-hero-img.jpg';
$smallBG = get_site_url() . '/wp-content/uploads/2024/03/hhh-hero-img.jpg';

$hhhoops_layout_type = get_option( 'hhhoops_layout' );
if( $hhhoops_layout_type['front_layout']['type'] === 'tiles' && count($news_feat->posts) === 6 ){
  //trigger for tile video support
  $tile_vid = false;
  $tile_video_settings = [];

  //get tile banner
	$hhhoops_tile_banner = get_option( 'hhhoops_display' );
	//reassign to focus on tile banner
	$hhhoops_tile_banner = $hhhoops_tile_banner['site_4'];
  $hhhoops_tile_banner_build = '';
  //build tile banner from global settings if we have data
  if ( !empty($hhhoops_tile_banner['title']) ) {
    if ( !empty($hhhoops_tile_banner['link']) ) {
      $hhhoops_tile_banner_build .= '<h2 class="no-margin"><a href="' . $hhhoops_tile_banner['link'] . '">' . $hhhoops_tile_banner['title'] . '</a></h2>';
    } else {
      $hhhoops_tile_banner_build .= '<h2 class="no-margin">' . $hhhoops_tile_banner['title'] . '</h2>';
    }
  }
  if ( !empty($hhhoops_tile_banner['sub_title']) ) $hhhoops_tile_banner_build .= '<p class="no-margin">' . $hhhoops_tile_banner['sub_title'] . '</p>';
  function hhhoops_tile_template( $target_num, $news_feat, $classes ) {
    $tile_type = get_post_meta($news_feat->posts[$target_num]->ID, 'video_head', true);
    if( empty($tile_type) ) {
      $tile_type = '<img src="' . (( has_post_thumbnail($news_feat->posts[$target_num]->ID) ) ? get_the_post_thumbnail_url( $news_feat->posts[$target_num]->ID, "featured-tile" ) : get_site_url() . "/wp-content/themes/hhhoops-press/assets/hhhoops_profile_placeholder_640x640.jpg") . '" alt="' . $news_feat->posts[$target_num]->post_title . '" />';
    } else {
      $video_settings = explode(":", $tile_type);
      if( $video_settings[0] === 'youtube' ) {
        global $tile_vid;
        global $tile_video_settings;
        $tile_type = '<div id="tile_player_' . $news_feat->posts[$target_num]->ID . '"></div>';
        $tile_vid = true;
        $tile_video_settings[] = (object) [
          'id' => 'tile_player_' . $news_feat->posts[$target_num]->ID,
          'data'=> (object)[
            'height' => '640.125',
            'width' => '1138',
            'videoId' => $video_settings[1],
            'playerVars' => (object)[
              'controls' => 0,
              'fs'  => 0,
              'modestbranding'  => 1,
              'enablejsapi' => 1,
              'loop' => 1,
              'playlist' => $video_settings[1]
            ]
          ]
        ];
        $classes .= ' responsive-embed';
        //og code before embed method of youtube auto play
//         $tile_type = '<iframe type="text/html" width="1138" height="640.125"
// src="https://www.youtube.com/embed/' . $video_settings[1] . '?autoplay=1&controls=0&enablejsapi=1&loop=1&modestbranding=1&fs=0" frameborder="0"></iframe>';
//         $classes .= ' responsive-embed';
      }
    }
    return '        <div id="news-' . $news_feat->posts[$target_num]->ID . '" class="black-border thick-border tile relative maximum-height">
          <a href="' . get_permalink($news_feat->posts[$target_num]->ID) . '" class="' . $classes . '">' . $tile_type . '</a>
          <h1 class="article-info">
            <a href="' . get_permalink($news_feat->posts[$target_num]->ID) . '">' . $news_feat->posts[$target_num]->post_title . '</a>' . 
            (( !empty($news_feat->posts[$target_num]->post_excerpt) ) ? "<p class=\"no-margin cute orange text-lowercase\">" . $news_feat->posts[$target_num]->post_excerpt . "</p>" : "") . 
          '</h1>
        </div>';
  } ?>

<!-- video should go here -->
<div class="hide-for-small-only" >
<!--   <video style="height:700px; width:100%;"  controls autoplay="true" loop="true" muted>
  <source src='/wp-content/themes/hhhoops-press/assets/videos/.recap.mov.swpc' type='video/mp4'>
  Your browser does not support the video tag.
  </video> -->
  
<!--       <iframe width="100%" height="1000" src="https://www.youtube.com/embed/EdcnKNZlVBE?&autoplay=1&loop=1&rel=0&showinfo=0&color=white&mute=1&playlist=EdcnKNZlVBE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; loop" allowfullscreen></iframe> -->

  
  <figure class=" size-full is-resized">
        <img srcset="<?php echo $smallBG ?> 1920w, <?php echo $bigBG ?> 2400w" src="<?php $smallBG ?>"  alt="" class="wp-image-51" style="filter: brightness(0.3);"/>
</figure>  
  
  <div class="parent-2 ">
        
       <div class="grid-x align-center">
         <img class="img-2" src="<?php echo get_site_url(); ?>/wp-content/themes/hhhoops-press/assets/tiny-logos/Hype-Her-Hoops-logo.png">
       </div>
        
      <div class="wp-block-columns is-layout-flex wp-container-9 header-icons" style="text-align:center">
        
       <div class="wp-block-column is-layout-flow">
       <div class="wp-block-columns is-layout-flex wp-container-3">




       <div class="wp-block-column is-layout-flow">
          <a href="https://hypeherhoopscircuit.com/stat-leaderboard/" target="_blank" rel="noopener"><figure class="wp-block-image size-full"><img src="https://hypeherhoopscircuit.com/wp-content/uploads/2023/02/HHH-Stats.png" alt="" class="wp-image-1213"><figcaption>STATS</figcaption></figure></a>       
       </div>
       </div>
       </div>
    
      <div class="wp-block-column is-layout-flow">
      <div class="wp-block-columns is-layout-flex wp-container-7">




      <div class="wp-block-column is-layout-flow">
      <a href="https://hypeherhoopscircuit.com/team-ranking/" target="_blank" rel="noopener"><figure class="wp-block-image size-full"><img src="https://hypeherhoopscircuit.com/wp-content/uploads/2023/02/HHH-Standings.png" alt="" class="wp-image-1213"><figcaption>STANDINGS</figcaption></figure></a>
      </div>
      </div>
      </div>

      <div class="wp-block-column is-layout-flow">
      <div class="wp-block-columns is-layout-flex wp-container-7">




      <div class="wp-block-column is-layout-flow">          
      <a href="https://hypeherhoopscircuit.com/category/news/" target="_blank" rel="noopener"><figure class="wp-block-image size-full"><img src="https://hypeherhoopscircuit.com/wp-content/uploads/2023/02/HHH-Blog.png" alt="" class="wp-image-1213"><figcaption>LIVE BLOG</figcaption></figure></a>
      </div>
      </div>
      </div>
        
        
    </div>
  </div>
  
</div>

<div class="show-for-small-only">
<!--   <video style="height:100%; width:100%;"  autoplay="true" loop="true" muted  playsinline autoplay>
  <source src='/wp-content/themes/hhhoops-press/assets/videos/Horizontal-Hype-Her-Hoops .mov.swpc.swpc' type='video/mp4'>
  Your browser does not support the video tag.
  </video> -->
  
    <iframe width="560" height="315" src="https://www.youtube.com/embed/EdcnKNZlVBE?&autoplay=1&loop=1&rel=0&showinfo=0&color=white&mute=1&playlist=EdcnKNZlVBE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; loop" allowfullscreen></iframe>

    <div class="parent-2 ">
        
       <div class="grid-x align-center">
         <img class="img-2" src="<?php echo get_site_url(); ?>/wp-content/themes/hhhoops-press/assets/tiny-logos/Hype-Her-Hoops-logo.png">
        </div>
        
        
        
    </div>
  
</div>



<!-- <figure class=" size-full is-resized">
        <img srcset="<?php echo $smallBG ?> 1920w, <?php echo $bigBG ?> 2400w" src="<?php $smallBG ?>"  alt="" class="wp-image-51" />
</figure>   -->




  <section class="site-main width-hd hero-tiles<?php if ( $hhhoops_ad_info['go'] ) echo $hhhoops_ad_info['ad_section_class']; ?>">
    <?php if ( $hhhoops_ad_info['go'] ) echo $hhhoops_ad_info['ad_before'] . $hhhoops_ad_info['ad_content'] . $hhhoops_ad_info['ad_after']; ?>
    <div class="grid-x white-border thick-border" style="overflow-x:scroll; flex-wrap: nowrap;">
  
            <div class="cell small-4 maximum-height">
              <?php echo hhhoops_tile_template( 0, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-4 maximum-height">
              <?php echo hhhoops_tile_template( 1, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-4 maximum-height">
              <?php echo hhhoops_tile_template( 2, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-4 maximum-height">
              <?php echo hhhoops_tile_template( 3, $news_feat, 'tile-image' ); ?>
            </div> 
            <div class="cell small-4 maximum-height">
              <?php echo hhhoops_tile_template( 4, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-4 maximum-height">
              <?php echo hhhoops_tile_template( 5, $news_feat, 'tile-image' ); ?>
            </div> 
        <?php if( $hhhoops_tile_banner_build !== '' ) : ?>
        <div class="cell shrink">
          <div class="grid-x maximum-height">
            <div class="cell small-12 text-center small-small-padding large-padding callout secondary no-margin white-border thick-border">
              <?php echo $hhhoops_tile_banner_build; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>
  </div>
    
<div style="text-align: right; margin-right: 2rem;">

<button class="button slider-btn" id="newsLeft" disabled><</button>
<button class="button slider-btn" id="newsRight">></button>
</div>
  </section>
  <?php
  //$featured_events_arr = g365_conn( 'g365_display_events', [65, 6] );
  $hhhoops_potm = get_post_meta($post->ID, 'hhhoops_potm', true);
  $hhhoops_ctotm = get_post_meta($post->ID, 'hhhoops_ctotm', true);
  if( !empty( $hhhoops_potm ) || !empty( $hhhoops_ctotm ) || !empty( $featured_events_arr ) ) :
?>
    <section class="site-main small-padding-top xlarge-padding-bottom grid-container">
      <div class="grid-x">
        <div id="main" class="small-12 cell">
          <?php if( !empty($featured_events_arr) ) : ?>
          <div class="tiny-padding gset no-border">
            <h2 class="entry-title text-center screen-reader-text"><a href="/calendar">Featured Events</a></h2>
          </div>
          <div class="widget-wrapper medium-margin-bottom">
            <div class="grid-x small-up-2 medium-up-3 large-up-6 text-center profile-feature profile-widget">
              <?php foreach( $featured_events_arr as $dex => $obj ) : ?>
              <div class="cell">
                <div class="small-margin-bottom">
                  <a href="<?php echo $obj->link; ?>" target="_blank">
                <img src="<?php echo (!empty($obj->logo_img)) ? $obj->logo_img : $default_event_img ?>" alt="<?php echo $obj->name; ?> official logo" />
                <p>
                  <?php echo ( empty($obj->short_name) ) ? $obj->name : $obj->short_name; ?><br>	
                  <small class="tiny-margin-top block"><?php echo hhhoops_build_dates($obj->dates); ?></small>
                </p>
              </a>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <a class="button expanded no-margin-bottom" href="/calendar">Full Calendar</a>
          </div>
          <?php endif;
      if( !empty($hhhoops_potm) ) : ?>
          <div class="widget-wrapper medium-margin-bottom">
            <div class="grid-x">
              <div class="cell">
                <img src="<?php echo $hhhoops_potm; ?>" alt="Players of the month by region. <?php the_modified_date(); ?>" />
              </div>
            </div>
          </div>
          <?php endif; ?>
          <?php if( !empty($hhhoops_ctotm) ) : ?>
          <div class="widget-wrapper medium-margin-bottom">
            <div class="grid-x">
              <div class="cell">
                <img src="<?php echo $hhhoops_ctotm; ?>" alt="Club Team of the month. <?php the_modified_date(); ?>" />
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
    <?php endif; //end ptom section ?>

    <?php } else { //end tile layout hero section, begin standard featured post rotator ?>

    <!--  add the main image here to display-->


    <!--        ------------------------------------------------------------------------------------------------------>



    <?php if ( $hhhoops_ad_info['go'] ) echo $hhhoops_ad_info['ad_before'] . $hhhoops_ad_info['ad_content'] . $hhhoops_ad_info['ad_after']; ?>

    <?php if ( $news_feat -> have_posts() ) : while ( $news_feat -> have_posts() ) : $news_feat -> the_post(); ?>

    <div class="parent">

<!--       <img class="" src="<?php echo ( has_post_thumbnail() ) ? the_post_thumbnail_url( 'featured-home' ) : 'http://image.mlive.com/home/mlive-media/width960/img/kalamazoogazette/photo/2016/12/22/-c8733c1e608c238b.JPG'; ?>" alt="<?php echo get_the_title(); ?>"
      /> -->
<!--       hello-->
      <figure class=" size-full is-resized">
<!--         <img srcset="<?php echo $smallBG ?> 1920w, <?php echo $bigBG ?> 2400w" src="<?php $smallBG ?>"  alt="" class="wp-image-51" /> -->
        
        
        <div class="hide-for-small-only" >
  <video style="height:100%; width:100%;"  controls autoplay="true" loop="true" muted>
  <source src='/wp-content/themes/hhhoops-press/assets/videos/Horizontal-Hype-Her-Hoops .mov.swpc' type='video/mp4'>
  Your browser does not support the video tag.
  </video>
</div>

<div class="show-for-small-only">
  <video style="height:100%; width:100%;"  autoplay="true" loop="true" muted  playsinline autoplay>
  <source src='/wp-content/themes/hhhoops-press/assets/videos/Horizontal-Hype-Her-Hoops .mov.swpc' type='video/mp4'>
  Your browser does not support the video tag.
  </video>
</div>
        
        
      </figure>

      <div class="parent-2 ">
        
<!--        <div class="grid-x align-center">
         <img class="img-2" src="<?php echo get_site_url(); ?>/wp-content/themes/hhhoops-press/assets/tiny-logos/Hype-Her-Hoops-logo.png">
        </div> -->
        
        
        
      </div>
      </div>

      <?php endwhile; wp_reset_postdata(); endif; ?>



      <!--        ------------------------------------------------------------------------------------------------------>





      <?php } //end default hero featured image section ?>

      <section id="content" class="site-main small-padding-top xlarge-padding-bottom grid-container">

        <?php //if we have page content
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <?php the_content(); ?>

        <?php endwhile; endif; ?>

      </section>

      <?php
//if we have a splash graphic, add  the elements to support, part 1
if( !empty($hhhoops_ad_info['splash']) ) echo $hhhoops_ad_info['splash'];

get_footer();

//if we have a splash graphic, initialize it now that foundation() has started, part 2
if( !empty($hhhoops_ad_info['splash']) ) echo '<script type="text/javascript">
    var hhhoops_closed = localStorage.getItem("hhhoops_close_today");
    var hhhoops_closed_date = localStorage.getItem("hhhoops_close_today_date");
    var hhhoops_now_date = new Date();
    if( hhhoops_closed_date !== null && new Date(hhhoops_closed_date).getDate() !== hhhoops_now_date.getDate() ) {
      localStorage.removeItem("hhhoops_close_today");
      localStorage.removeItem("hhhoops_close_today_date");
      hhhoops_closed = null;
    }
    if( hhhoops_closed === null ) {
      (function($){$("#hhhoops_home_reveal").foundation("open");})(jQuery);
    }
  </script>';

if( $tile_vid ) {
  print_r(
    '<script>
      var tag = document.createElement("script");
      tag.src = "https://youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName("script")[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
      var tile_players = ' . json_encode( $tile_video_settings) . ';
      function onYouTubeIframeAPIReady() {
        tile_players.forEach( function( vid_settings, dex ) {
          vid_settings.data.events = {
            "onReady": onPlayerReady,
            "onStateChange": onPlayerStateChange
          };
          tile_players[dex]["video_ref"] = new YT.Player( vid_settings.id, vid_settings.data);
        });
      }
       function onPlayerReady(event) {
         event.target.playVideo();
         event.target.mute();
       }
       function onPlayerStateChange(event) {
        if( event.data === 0 ){
         event.target.playVideo();
        }
       }
    </script>'
  );
}

    
    
    
?>