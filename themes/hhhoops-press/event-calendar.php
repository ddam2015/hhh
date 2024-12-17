<?php
/**
 * Template Name: Event Calendar
 * Author: Daradona Dam
 * Version: 1.0
 */
get_header();
$calendar_data = g365_fn(['fn_name'=>'g365_remote_api', 'arguments'=>['calendar-api', ['target_url'=>get_site_url()]]]);
// echo "<pre>"; print_r($calendar_data); echo "</pre>";
$map = array();
foreach($calendar_data->event_calendar as $event_calendar):
  $event_time_sep = explode("-", $event_calendar->eventtime);
  $event_month = (int)$event_time_sep[1];
  $dateObj = DateTime::createFromFormat('!m', $event_month);
  $monthName = $dateObj->format('F'); // March
  if (!isset($map[$monthName])) {
  $map[$monthName] = array();
  };
  $map[$monthName][] = $event_calendar; 
?>
<?php endforeach;
// echo "<pre>"; p rint_r($map); echo "</pre>";
?>
<div class="grid-container">
  <section id="content" class="grid-x site-main xlarge-padding-bottom" role="main">
    <?php if(!empty($calendar_data->event_calendar)): ?>
    <div class="cell small-12 grid-x calendar__container">
      <header class="entry-header"><h1 class="entry-title">Calendar</h1>	</header><!-- .entry-header -->
      <div class="entry-content cell">
<!--         <div class="grid-x grid-margin-x">
          <div class="cell small-12">
            <table class="calendar">
              <thead>
                <tr>
                  <th class="text-center">EVENT</th>
                  <th class="text-center">DATE</th>
                  <th class="text-center">NAME</th>
                  <th class="text-center">LOCATION</th>
                </tr>
              </thead>
              <tbody class="table-stripe">
                <?php foreach($calendar_data->event_calendar as $event_calandar): ?>
                <tr class="event-line" data-event_link="<?php echo $event_calandar->link; ?>">
                  <td class="text-center">
                    <img class="event-logo" src="<?php echo $event_calandar->logo_img; ?>" alt="<?php echo $event_calandar->name; ?>"/>
                  </td>
                  <td class="text-center"><?php echo $event_calandar->dates; ?></td>
                  <td class="text-center">
                    <?php  
                    $event_name = $event_calandar->short_name;
                    $substringToRemove = "HHH";

                    $result = str_replace($substringToRemove, "", $event_name);
                    
                    ?>
                    <a href="<?php echo $event_calandar->link; ?>" target="_blank" title="<?php echo $event_calandar->name; ?>"><?php echo $result; ?></a>
                  </td>
                  <td class="text-center"><?php echo $event_calandar->locations; ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div> -->
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
                          $eventDate = hhh_build_dates($event->dates, 2);
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
    <?php else: echo ('<h3>'. $calendar_data->nv_message . '</h3>'); endif; ?>
  </section>
</div>
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