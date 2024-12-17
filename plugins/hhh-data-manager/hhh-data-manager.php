<?php
/*
 *	Plugin Name: Hype Her Hoops Data Management Framework
 *	Description: Hype Her Hoops Data Management contains functionality to support Hype Her Hoops website.
 *	Version: 1.0
 *	Author: Daradona Dam
 */
function hhh_dir_render($sub_dir = null, $file_name, $player_id = null, $arg = null){
  $dir = 'inc/'.$sub_dir.'/'.$file_name.'.php';
  include($dir);
  $output_dir = ob_get_contents();
  return $output_dir;
}
function g365_fn($arg = null, $type = null){
  $fn_data = g365_conn( $arg['fn_name'], $arg['arguments'] );
  if(!empty($arg['decode'])){ $is_decoded = $arg['decode']; }else{ $is_decoded = ''; }
  if($is_decoded == true){
    $fn_data = json_decode(json_encode($fn_data), true);  
  }else{
    $fn_data = $fn_data;
  }
  return $fn_data;
}


//retrieve groups and groups of groups
function g365_get_groups_data( $group = null, $type = null, $args = null ) {
	if( $group === null ) return 'Need group to pull data.';
	global $wpdb;
// echo '<pre class="">';
// print_r($wpdb);
// echo '</pre>';
	$wpdb_groups = $wpdb->g365_groups;
  $enabled = 'AND `enabled` = 1';
  $enabled_ref = 'AND gr.enabled = 1';
  $enabled_part = 'enabled = 1';
  if( !is_null($args) && isset($args['enabled']) ) {
    if( is_numeric($args['enabled']) ) {
      $enabled = 'AND `enabled` = ' . intval($args['enabled']);
      $enabled_ref = 'AND gr.enabled = ' . intval($args['enabled']);
      $enabled_part = 'enabled = ' . intval($args['enabled']);
    } else {
      $enabled = '';
      $enabled_ref = '';
      $enabled_part = '';
    }
  }
	//if you have an id, go straigth there, otherwise see what we find with a name
	if( is_numeric($group) ) {
		$group = $wpdb->get_results(
			"SELECT * FROM $wpdb_groups WHERE `id` = $group $enabled;"
		);
	} else {
		//narrow the search by group type org, event, series
		$sql_type = ( $type === null || $type < 1 ) ? '' : "AND `type` = $type"; 
		$group = $wpdb->get_results(
			"SELECT * FROM $wpdb_groups WHERE `nickname` LIKE '$group' $sql_type $enabled;"
		);
	}
	if( empty($group) ) return 'No groups found.';
	//it the result is ambiguous then return the data for refinement
	if( count($group) > 1 || $type < 0 ) return $group;
	//simplify the data reference
	$group = $group[0];
	if( $group->groups != 1 && $type === 0 ) return $group;
	//the second phase is pulling the group references
	$wpdb_group_refs = $wpdb->g365_group_refs;
  $wpdb_events = $wpdb->g365_events;
	//extract the group id for use in the query
	$group_id = $group->id;
	//if the group is a group of groups do the diligence
	if( $group->groups == 1 ) {
		//get the list of subgroups
    switch($group_id) {
      case 44:
    		//for the master calendar...put it in this order...
        $group->records = $wpdb->get_results(
          "SELECT gr.*
          FROM $wpdb_group_refs AS refs
          LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
          WHERE refs.group_id = $group_id $enabled_ref
          ORDER BY FIELD(gr.id,43,8,13,12,168,169,170);"
        );
        break;
      case 24:
        //for EBC Camps...put it in this order...
        $group->records = $wpdb->get_results(
          "SELECT gr.*
          FROM $wpdb_group_refs AS refs
          LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
          WHERE refs.group_id = $group_id $enabled_ref
          ORDER BY FIELD(gr.id,23,22,14,15,63,16,17,18,19,20,21,54,64);"
        );
        break;
      case 89:
        if(!empty($args['tsc_only']) && $args['tsc_only'] === true){ $tsc_only = " AND gr.name LIKE ('%The Stage ACT%') "; }else{ $tsc_only = ''; }
        //for all-tournament awards...put it in this order...
        $group->records = $wpdb->get_results(
          "SELECT gr.*, (
          SELECT ev_ref.eventtime 
          FROM $wpdb_group_refs AS sub_group_ref 
          LEFT JOIN $wpdb_events AS ev_ref ON sub_group_ref.item_id=ev_ref.id
          WHERE gr.id=sub_group_ref.group_id ORDER BY ev_ref.eventtime LIMIT 1
          ) AS eventtime, (
          SELECT ev_ref.org 
          FROM $wpdb_group_refs AS sub_group_ref 
          LEFT JOIN $wpdb_events AS ev_ref ON sub_group_ref.item_id=ev_ref.id
          WHERE gr.id=sub_group_ref.group_id ORDER BY ev_ref.eventtime LIMIT 1
          ) AS ev_org
          FROM $wpdb_group_refs AS refs
          LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
          WHERE refs.group_id = $group_id $enabled_ref $tsc_only
          ORDER BY eventtime DESC;"
        );
        break;
      default:
        //defaults to no order
        $group->records = $wpdb->get_results(
          "SELECT gr.*
          FROM $wpdb_group_refs AS refs
          LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
          WHERE refs.group_id = $group_id $enabled_ref;"
        );
    }
		//if we only want the list of sub groups output now
		if( $type === 0 ) return $group;
		//make the search list for the subgroup pull
		$group_id_list = array();
		foreach( $group->records as $dex => $sub_group ) {
			$group_id_list[] = $sub_group->id;
		}
		//format for sql query
		$group_id_list = 'IN (' . implode(',',$group_id_list) . ')';
	} else {
		$group_id_list = "= $group_id";
	}
  //return only the groups
  if( $args['truncate'] === true ) return $group;
  //attach the data that the groups are pointing at
	switch( $type ) {
		case 0: //sub groups
      $enabled_part = 'AND gr.' . $enabled_part;
			$group_ref_data = $wpdb->get_results(
				"SELECT gr.*
				FROM $wpdb_group_refs AS refs
				LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
				WHERE refs.group_id $group_id_list $enabled_part;"
			);
			break;
		case 1: //orgs
			$wpdb_orgs = $wpdb->g365_orgs;
      $enabled_part = 'AND org.' . $enabled_part;
			$group_ref_data = $wpdb->get_results(
				"SELECT org.*, refs.group_id
				FROM $wpdb_group_refs AS refs
				LEFT JOIN $wpdb_orgs AS org ON refs.item_id=org.id
				WHERE refs.group_id $group_id_list $enabled_part;"
			);
			break;
		case 2: //events asc
			$wpdb_events = $wpdb->g365_events;
      $enabled_part = 'AND ev.' . $enabled_part;
			$group_ref_data = $wpdb->get_results(
				"SELECT ev.*, refs.group_id
				FROM $wpdb_group_refs AS refs
				LEFT JOIN $wpdb_events AS ev ON refs.item_id=ev.id
				WHERE refs.group_id $group_id_list $enabled_part
				ORDER BY ev.eventtime;"
			);
			break;
		case 3: //events desc
			$wpdb_events = $wpdb->g365_events;
      $enabled_part = 'AND ev.' . $enabled_part;
			$group_ref_data = $wpdb->get_results(
				"SELECT ev.*, refs.group_id
				FROM $wpdb_group_refs AS refs
				LEFT JOIN $wpdb_events AS ev ON refs.item_id=ev.id
				WHERE refs.group_id $group_id_list $enabled_part
				ORDER BY ev.eventtime DESC;"
			);
			break;
		case 4: //rankings
			$wpdb_rankings = $wpdb->g365_rankings;
			//create date based array for subnavigation
			$group->ranking_brackets = $wpdb->get_results(
				"SELECT DISTINCT start_datetime, end_datetime
				FROM $wpdb_rankings
				WHERE group_id $group_id_list $enabled
				ORDER BY start_datetime DESC;"
			);
			//if there is limit data use it otherwise assume we are pulling the lastest data
			$min_limit = ( empty($args['min-limit']) ? date("Y-m-d", strtotime($group->ranking_brackets[0]->start_datetime)) : date("Y-m-d", strtotime($args['min-limit'])) );
			$max_limit = ( empty($args['max-limit']) ? date("Y-m-d", strtotime($group->ranking_brackets[0]->end_datetime)) : date("Y-m-d", strtotime($args['max-limit'])) );
			//different stipulations if we are looking for the ranking at a specific time
// 			$date_limit = 'AND `start_datetime` >= "' . $min_limit . '" AND `end_datetime` <= "' . $max_limit . '"';
			$date_limit = "AND `start_datetime` >= '" . $min_limit . "' AND `end_datetime` <= '" . $max_limit . "' ";
			$group_ref_data = $wpdb->get_results(
				"SELECT *
				FROM $wpdb_rankings
				WHERE group_id $group_id_list $date_limit $enabled
				ORDER BY group_id ASC, ranking_type DESC;"
			);
			break;
		default:
			return "Can't find group type to finish data processing.";
			break;
	}
	//if this is a group of groups, organize the data into sections then make lists of the contained group ids
	//otherwise make the id list and append the records
	$group->item_ids = array();
	if( $group->groups == 1 ) {
		foreach( $group->records as $dex => &$record ) {
			$record->item_ids = array();
			$record->records = array();
			foreach( $group_ref_data as $subdex => $subrecord ) {
				if( $record->id == $subrecord->group_id ) {
					$record->records[] = $subrecord;
					$group->item_ids[] = $subrecord->id;
					$record->item_ids[] = $subrecord->id;
				}
			}
		}
	} else {
		foreach( $group_ref_data as $dex => $record ) {
			$group->item_ids[] = $record->id;
		}
		$group->records = $group_ref_data;
	}
	return $group;
}

//format start and end date based on a 'pipe' separated string
function hhh_build_dates($dates, $type = 1, $abbv = false, $add_reg = false) {
	//date is undetermined, don't process
	if( strpos($dates, 'TBD') !== false ) return $dates;
  //set default timezone
  date_default_timezone_set('America/Los_Angeles');
	//if the event is only one day, cut most of the processing
  $start_date = $dates;
  //if the date does have the "|" jump to bottom
	if( strpos($dates, '|') !== false ) {
		$dates = explode('|', $dates);
    //if we want just the dates, only first and last
    if( $type === 5 ) {
      return array( date("m-d-y", strtotime($dates[0])), date("m-d-y", strtotime(end($dates))) );
    }
		$start_date = $dates[0];
    if( $type === 4 ) return $start_date;
		$end_date = end($dates);
		$start_month = explode(' ', $start_date);
		$end_month = explode(' ', $end_date);
		if( $start_month[0] != $end_month[0] ) {
			if( end($start_month) != end($end_month) ) {
        $dates = $start_date . ' - ' . $end_date . $type;
			} else {
        if( $type === 3 ){
          $dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1) . ' - ' . substr($end_month[1], 0, -1);
        } else {
          $dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1) . ' - ' . $end_month[0] . ' ' . substr($end_month[1], 0, -1);
        }
			}
		} else {
			$start_day = substr($start_month[1], 0, -1);
			$end_day = substr($end_month[1], 0, -1);
			if( $start_day == $end_day ) {
				$dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1);
			} else {
				$dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1) . ' - ' . substr($end_month[1], 0, -1);
			}
		}
		switch( $type ){
			case 1:
				break;
			case 2:
				$dates .= ', ' . end($end_month);
        $dates = preg_replace('/ \- /', '-', $dates);
				break;
			case 3:
				break;
		}
	} else {
		switch( $type ){
			case 1:
				$dates = explode(' ', $dates);
				if( strpos($dates[1], ',') !== false ) $dates[1] = substr($dates[1], 0, -1);
				$dates = $dates[0] . ' ' . $dates[1];
				break;
			case 2:
				break;
			case 3:
				$dates = explode(' ', $dates);
				if( strpos($dates[1], ',') !== false ) $dates[1] = substr($dates[1], 0, -1);
				$dates = $dates[0] . ' ' . $dates[1];
				break;
      case 4:
        return $dates;
        break;
		}
	}
  if( $abbv ) return preg_replace('/([A-Za-z]{3})( |.+? )/', '\1 ', $dates);
  if( $add_reg !== false ) {
    $registration_date = 'No registration deadline.';
    if( $add_reg !== 0 ) {
      $registration_date = date('F d, Y', strtotime('-' . intval($add_reg) . ' days', strtotime($start_date)));
    }
    return array($dates, $registration_date);
  }
	return $dates;
}