<?php

/**
 * Parses a local or remote ICAL file
 * 
 * Example usage
 * 
 *      $ical = new EO_ICAL_Parser();
 *      $ical->parse( 'http://www.dol.govt.nz/er/holidaysandleave/publicholidays/publicholidaydates/ical/auckland.ics' );
 *      
 *      $ical->events; //Array of events
 *      $ical->venues; //Array of venue names
 *      $ical->categories; //Array of category names
 *      $ical->errors; //Array of WP_Error errors
 * 
 * You can configire default settings by passing an array to the class constructor.
 * 
 *      $ical = new EO_ICAL_Parser( array( ..., 'default_status' => 'published', ... ) );
 * 
 * Available settings include:
 * 
 *  *  **status_map** - How to interpret the ICAL STATUS property.
 *  *  **default_status** - Default status of posts (unless otherwise specified by STATUS). Default is 'draft'
 * 
 * @author stephen
 * @package ical-functions
 *
 */
class EO_ICAL_Parser{

	var $events = array();
	var $venues = array();
	var $categories = array();
	var $errors = array();

	var $events_parsed = 0;
	var $venue_parsed = 0;
	var $categories_parsed = 0;

	var $current_event = array();

	function __construct( $args = array() ){

		$args = array_merge( array(
					'status_map' => array(
						'CONFIRMED' => 'publish',
						'CANCELLED' => 'trash',
						'TENTATIVE' => 'draft',
					),
					'default_status' => 'draft',		
				), $args );
		

		$this->calendar_timezone = eo_get_blog_timezone();
		
		$this->default_status = $args['default_status'];
		$this->status_map = $args['status_map'];
	}


	function parse( $file ){
		
		//Local file
		if( is_file($file) && file_exists($file)  ){
			$this->ical_array = $this->file_to_array( $file );

		//Remote file
		}elseif( preg_match('!^(http|https|ftp)://!i', $file) ){
			$this->ical_array = $this->url_to_array( $file );

		}else{
			$this->ical_array = false;
		}

		if( !$this->ical_array )
			return false;

		//Go through array and parse events
		$result = $this->parse_ical_array();

		$this->events_parsed = count( $this->events );
		$this->venue_parsed = count( $this->venues );
		$this->categories_parsed = count( $this->categories );
			
		echo '<pre>';
		print_r( $this );
		wp_die('');
	}
	
	/**
	 * Fetches ICAL calendar from a feed url and returns its contents as an array.
	 * 
	 * @param sring $url The url of the ICAL feed 
	 * @return array|bool Array of line in ICAL feed, false on error 
	 */
	function url_to_array( $url ){
		$contents = wp_remote_retrieve_body( wp_remote_get( $url ) );
		if( $contents )
			return explode( "\n", $contents );
		return false;
	}

	/**
	 * Fetches ICAL calendar from a file and returns its contents as an array.
	 *
	 * @param sring $url The ICAL file
	 * @return array|bool Array of line in ICAL feed, false on error
	 */
	function file_to_array( $file ){

		$file_handle = @fopen( $file, "rb");
		$lines = array();

		if( !$file_handle )
			return false;

		//Feed lines into array
		while (!feof( $file_handle ) ):
			$line_of_text = fgets( $file_handle, 4096 );
			$lines[]= $line_of_text;
		endwhile;

		fclose($file_handle);

		return $lines;
	}


	/**
	 * Parses through an array of lines (of an ICAL file)
	 */
	function parse_ical_array(){

		$state = "NONE";//Initial state

		//Read through each line
		for ( $n = 0; $n < count ( $this->ical_array ) && empty( $this->errors ); $n++ ):
			$buff = trim(  $this->ical_array[$n] );

			if( !empty( $buff ) ):
				$line = explode(':',$buff,2);

				//On the right side of the line we may have DTSTART;TZID= or DTSTART;VALUE=
				$modifiers = explode( ';', $line[0] );
				$property = array_shift( $modifiers );
				$value = ( isset( $line[1] ) ? trim( $line[1] ) : '' );

				//If we are in EVENT state
				if ( $state == "VEVENT" ) {

					//If END:VEVENT, add event to parsed events and clear $event
					if( $property=='END' && $value=='VEVENT' ){
						$state = "VCALENDAR";
						$this->events[] = $this->current_event;
						$this->current_event = array();

					//Otherwise, parse event property
					}else{
						try{
							while( isset( $this->ical_array[$n+1] ) && $this->ical_array[$n+1][0] == ' ' ){
								$value .= $this->ical_array[$n+1];
								$n++;
							}
						
							$this->parse_event_property( $property, $value, $modifiers );

						}catch( Exception $e ){
							$this->report_error( $n+1, 'event-property-error', $e->getMessage() );
							$state = "VCALENDAR";//Abort parsing event
						}
					}

				// If we are in CALENDAR state
				}elseif ($state == "VCALENDAR") {

					//Begin event
					if( $property=='BEGIN' && $value=='VEVENT'){
						$state = "VEVENT";
						$this->current_event = array();

					}elseif ( $property=='END' && $value=='VCALENDAR'){
						$state = "NONE";
		
					}elseif($property=='X-WR-TIMEZONE'){
						try{
							$this->calendar_timezone = $this->parse_timezone($value);
						}catch(Exception $e){
							$this->report_error( $n+1, 'timezone-parser-error', $e->getMessage() );
							break;
						}
					}

				//Other
				}elseif($state == "NONE" && $property=='BEGIN' && $value=='VCALENDAR') {
					$state = "VCALENDAR";
				}
			endif; //If line is not empty
		endfor; //For each line
	}


	function report_error( $line, $type, $message ){

		$this->errors[] = new WP_Error(
				$type,
				sprintf( __( 'Line: %1$d', 'eventorganiser' ), $line ).'   '.$message
		);
	}


	function parse_event_property( $property, $value, $modifiers ){

		if( !empty( $modifiers ) ):
			foreach( $modifiers as $modifier ):
				if ( stristr( $modifier, 'TZID' ) ){
					$date_tz = $this->parse_timezone( substr( $modifier, 5 ) );

				}elseif( stristr( $modifier, 'VALUE' ) ){
					$meta = substr( $modifier, 6 );
				}
			endforeach;
		endif;

		//For dates - if there is not an associated timezone, use calendar default.
		if( empty( $date_tz ) )
			$date_tz = $this->calendar_timezone;

		switch( $property ):
		case 'UID':
			$this->current_event['UID'] = $value;
		break;

		case 'CREATED':
		case 'DTSTART':
		case 'DTEND':
			if( isset( $meta ) && $meta == 'DATE' ):
				$date = $this->parse_ical_date( $value );
				$allday = 1;
			else:
				$date = $this->parse_ical_datetime( $value, $date_tz );
				$allday = 0;
			endif;

			if( empty( $date ) )
				break;

			switch( $property ):
				case'DTSTART':
					$this->current_event['start'] = $date;
					$this->current_event['all_day'] = $allday;
				break;

				case 'DTEND':
					if( $allday == 1 )
						$date->modify('-1 second');
					$this->current_event['end'] = $date;
				break;

				case 'CREATED':
					$date->setTimezone( new DateTimeZone('utc') );
					$this->current_event['post_date_gmt'] = $date->format('Y-m-d H:i:s');
				break;

			endswitch;
		break;

		case 'EXDATE':
		case 'RDATE':
			//The modifiers have been dealt with above. We do similiar to above, except for an array of dates...
			$value_array = explode( ',', $value );

			//Note, we only consider the Date part and ignore the time
			foreach( $value_array as $val ):
				$date = $this->parse_ical_date( $val );
				
				if( $property == 'EXDATE' ){
					$this->current_event['exclude'][] = $date;
				}else{
					$this->current_event['include'][] = $date;
				}
			endforeach;
		break;

			//Reoccurrence rule properties
		case 'RRULE':
			$this->current_event += $this->parse_RRule($value);
		break;

			//The event's summary (AKA post title)
		case 'SUMMARY':
			$this->current_event['post_title'] = $this->parse_ical_text( $value );
		break;

			//The event's description (AKA post content)
		case 'DESCRIPTION':
			$this->current_event['post_content'] = $this->parse_ical_text( $value );
		break;

			//Event venues, assign to existing venue - or if set, create new one
		case 'LOCATION':
			if( !empty( $value ) ):

			$venue_name = trim($value);
				
			if( !isset( $this->venues[$venue_name] ) )
				$this->venues[$venue_name] = $venue_name;
				
			$this->current_event['event_venue'] = $venue_name;
			endif;
		break;

		case 'CATEGORIES':
			$cats = explode( ',', $value );
			if( !empty( $cats ) ):

			foreach ($cats as $cat_name):
			$cat_name = trim($cat_name);

			if( !isset( $this->categories[$cat_name] ) )
				$this->categories[$cat_name] = $cat_name;
				
			if( isset($this->current_event['event_category']) && !in_array( $cat_name, $this->current_event['event_category']) )
				$this->current_event['event_category'][] = $cat_name;
				
			endforeach;

			endif;
		break;

			//The event's status
		case 'STATUS':
			$map = $this->status_map;

			$this->current_event['post_status'] = isset( $map[$value] ) ? $map[$value] : $this->default_status;
		break;

			//An url associated with the event
		case 'URL':
			$this->current_event['url'] = $value;
		break;

			endswitch;

	}


	/**
	 * Takes escaped text and returns the text unescaped.
	 *
	 * @param string $text - the escaped test
	 * @return string $text - the text, unescaped.
	 */
	function parse_ical_text($text){
		//Get rid of carriage returns:
		$text = str_replace("\r\n","\n",$text);
		$text = str_replace("\r","\n",$text);

		//Some calendar apps break up text
		$text = str_replace("\n ","",$text);
		$text = str_replace("\r ","",$text);

		//Any intended carriage returns/new-lines converted to HTML
		$text = str_replace("\\r\\n","",$text);
		$text = str_replace("\\n","</br>",$text);
		$text = stripslashes($text);
		return $text;
	}

	/**
	 * Takes a date-time in ICAL and returns a datetime object
	 *
	 * @param string $tzid - the value of the ICAL TZID property
	 * @return DateTimeZone - the timezone with the given identifier or false if it isn't recognised
	 */
	function parse_timezone( $tzid ){
		$tzid = str_replace( '-', '/', $tzid );
		$tz = new DateTimeZone( $tzid );
		return $tz;
	}

	/**
	 * Takes a date in ICAL and returns a datetime object
	 * 
	 * Expects date in yyyymmdd format
	 * 
	 * @param string $ical_date - date in ICAL format
	 * @return DateTime - the $ical_date as DateTime object
	 */
	function parse_ical_date( $ical_date ){

		preg_match('/^(\d{8})*/', $ical_date, $matches);

		if( count( $matches ) !=2 ){
			throw new Exception(__('Invalid date. Date expected in YYYYMMDD format.','eventorganiser'));
		}

		$datetime = new DateTime( $matches[1], $this->calendar_timezone );

		return $datetime;
	}

	/**
	 * Takes a date-time in ICAL and returns a datetime object
	 * 
	 * It returns the datetime in the specified 
	 * 
	 * Expects
	 *  * utc:  YYYYMMDDTHHiissZ
	 *  * local:  YYYYMMDDTHHiiss
	 *
	 * @param string $ical_date - date-time in ICAL format
	 * @param DateTimeZone $tz - Timezone 'local' is interpreted as
	 * @return DateTime - the $ical_date as DateTime object
	 */
	function parse_ical_datetime( $ical_date, $tz ){
		
		preg_match('/^((\d{8}T\d{6})(Z)?)/', $ical_date, $matches);

		if( count( $matches ) == 3 ){
			//floating / local date

		}elseif( count($matches) == 4 ){
			$tz = new DateTimeZone('UTC');

		}else{
			throw new Exception(__('Invalid datetime. Date expected in YYYYMMDDTHHiissZ or YYYYMMDDTHHiiss format.','eventorganiser'));
			return false;
		}

		$datetime = new DateTime( $matches[2], $tz );

		return $datetime;
	}

	/**
	 * Takes a date-time in ICAL and returns a datetime object

	 * @since 1.1.0
	 *
	 * @param string $RRule - the value of the ICAL RRule property
	 * @return array - a reoccurrence rule array as understood by Event Organiser
	 */
	function parse_RRule($RRule){
		//RRule is a sequence of rule parts seperated by ';'
		$rule_parts = explode(';',$RRule);

		foreach ($rule_parts as $rule_part):

		//Each rule part is of the form PROPERTY=VALUE
		$prop_value =  explode('=',$rule_part, 2);
		$property = $prop_value[0];
		$value = $prop_value[1];

		switch($property):
		case 'FREQ':
			$rule_array['schedule'] =strtolower($value);
		break;

		case 'INTERVAL':
			$rule_array['frequency'] =intval($value);
			break;

		case 'UNTIL':
			//Is the scheduled end a date-time or just a date?
			if(preg_match('/^((\d{8}T\d{6})(Z)?)/', $value))
				$date = $this->parse_ical_datetime( $value, new DateTimeZone('UTC') );
			else
				$date = $this->parse_ical_date( $value );
			
			$rule_array['schedule_last'] = $date;
			break;

		case 'BYDAY':
			$byday = $value;
			break;

		case 'BYMONTHDAY':
			$bymonthday = $value;
			break;
			endswitch;

			endforeach;

			//Meta-data for Weekly and Monthly schedules
			if($rule_array['schedule']=='monthly'):
			if(isset($byday)){
				preg_match('/(\d+)([a-zA-Z]+)/', $byday, $matches);
				$rule_array['schedule_meta'] ='BYDAY='.$matches[1].$matches[2];

			}elseif(isset($bymonthday)){
				$rule_array['schedule_meta'] ='BYMONTHDAY='.$bymonthday;

			}else{
				throw new Exception('Incomplete scheduling information');
			}

			elseif($rule_array['schedule']=='weekly'):
			preg_match('/([a-zA-Z,]+)/', $byday, $matches);
			$rule_array['schedule_meta'] =explode(',',$matches[1]);

			endif;

			return $rule_array;
	}

}