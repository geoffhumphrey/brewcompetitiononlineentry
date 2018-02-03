<?php

/**
 * Module:      export.output.php

 * Revision History:
 * - fixed point output errors for judges and BOS judges
 * - programming now accounts for multiple roles (e.g., judge/staff, steward/staff, bos judge/staff, etc.)
 * - XML output is fully compliant with the BJCP Database Interface Specifications
 *   -- http://www.bjcp.org/it/docs/BJCP%20Database%20XML%20Interface%20Spec%202.1.pdf
 */

require('../paths.php');
require(CONFIG.'bootstrap.php');
require(LIB.'output.lib.php');
require(INCLUDES.'scrubber.inc.php');

$header = "";

// Establish standard widths
// Total of 760 px for Portrait
// Total of 1100 px for landscape
$td_width_place = 35;
$td_width_name = 150;
$td_width_entry = 200;
$td_width_style = 200;
$td_width_club = 175;

$BOM = "\xEF\xBB\xBF"; // UTF-8 byte order mark (BOM)

if (!function_exists('fputcsv')) {
    function fputcsv(&$handle, $fields = array(), $delimiter = ',', $enclosure = '"') {

        // Sanity Check
        if (!is_resource($handle)) {
            trigger_error('fputcsv() expects parameter 1 to be resource, ' .
                gettype($handle) . ' given', E_USER_WARNING);
            return false;
        }

        if ($delimiter!=NULL) {
            if( strlen($delimiter) < 1 ) {
                trigger_error('delimiter must be a character', E_USER_WARNING);
                return false;
            }elseif( strlen($delimiter) > 1 ) {
                trigger_error('delimiter must be a single character', E_USER_NOTICE);
            }

            /* use first character from string */
            $delimiter = $delimiter[0];
        }

        if( $enclosure!=NULL ) {
             if( strlen($enclosure) < 1 ) {
                trigger_error('enclosure must be a character', E_USER_WARNING);
                return false;
            }elseif( strlen($enclosure) > 1 ) {
                trigger_error('enclosure must be a single character', E_USER_NOTICE);
            }

            /* use first character from string */
            $enclosure = $enclosure[0];
       }

        $i = 0;
        $csvline = '';
        $escape_char = '\\';
        $field_cnt = count($fields);
        $enc_is_quote = in_array($enclosure, array('"',"'"));
        reset($fields);

        foreach( $fields AS $field ) {

            /* enclose a field that contains a delimiter, an enclosure character, or a newline */
            if( is_string($field) && (
                strpos($field, $delimiter)!==false ||
                strpos($field, $enclosure)!==false ||
                strpos($field, $escape_char)!==false ||
                strpos($field, "\n")!==false ||
                strpos($field, "\r")!==false ||
                strpos($field, "\t")!==false ||
                strpos($field, ' ')!==false ) ) {

                $field_len = strlen($field);
                $escaped = 0;

                $csvline .= $enclosure;
                for( $ch = 0; $ch < $field_len; $ch++ )    {
                    if( $field[$ch] == $escape_char && $field[$ch+1] == $enclosure && $enc_is_quote ) {
                        continue;
                    }elseif( $field[$ch] == $escape_char ) {
                        $escaped = 1;
                    }elseif( !$escaped && $field[$ch] == $enclosure ) {
                        $csvline .= $enclosure;
                    }else{
                        $escaped = 0;
                    }
                    $csvline .= $field[$ch];
                }
                $csvline .= $enclosure;
            } else {
                $csvline .= $field;
            }

            if( $i++ != $field_cnt ) {
                $csvline .= $delimiter;
            }
        }

        $csvline .= "\n";

        return fwrite($handle, $csvline);
    }
}

if (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) || (((judging_date_return() == 0) && ($registration_open == 2) && ($entry_window_open == 2)))) {

/* -------------- ENTRY Exports -------------- */

	if ($section == "entries") {

		$type = "entries";

		if ($go == "csv") { $separator = ","; $extension = ".csv"; }
		if ($go == "tab") { $separator = chr(9); $extension = ".tab"; }
		$contest = str_replace(' ', '_', $_SESSION['contestName']);
		if ($section == "loc") $loc = "_".str_replace(' ', '_', $row_judging['judgingLocName']);
		else $loc = "";
		$date = date("m-d-Y");
		$filename = ltrim(filename($contest)."_Entries".filename($filter).filename($action).filename($view).filename($date).$loc.$extension,"_");

		include (DB.'output_entries_export.db.php');

		function mysqli_field_name($result, $field_offset)	{
			$properties = mysqli_fetch_field_direct($result, $field_offset);
			return is_object($properties) ? $properties->name : null;
		}

		if (($go == "csv") && ($action == "all") && ($filter == "all")) {
			$headers = array();

            $headers[] = "Last Name";
            $headers[] = "First Name";
            $headers[] = "Email";
            $headers[] = "Address";
            $headers[] = "City";
            $headers[] = "State/Province";
            $headers[] = "Zip/Postal Code";
            $headers[] = "Country";

			for ($i = 0; $i < $num_fields; $i++) {
				$header_name = mysqli_fetch_field_direct($sql, $i)->name;
                $header_name = ltrim($header_name,"brew");
                if ($header_name == "id") $headers[] = "Entry Number";
                elseif ($header_name == "Name") $headers[] = "Entry Name";
                elseif ($header_name == "Info") $headers[] = "Required Info";
                elseif ($header_name == "InfoOptional") $headers[] = "Optional Info";
                elseif ($header_name == "Mead1") $headers[] = "Carbonation";
                elseif ($header_name == "Mead2") $headers[] = "Sweetness";
                elseif ($header_name == "Mead3") $headers[] = "Strength";
                elseif ($header_name == "Comments") $headers[] = "Brewer Specifics";
                elseif ($header_name == "Updated") $headers[] = "Date/Time Updated";
                else $headers[] = preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), ' $0', $header_name);
			 }

				$headers[] = "Table";
				$headers[] = "Flight";
				$headers[] = "Round";
				$headers[] = "Score";
				$headers[] = "Place";
				$headers[] = "BOS Place";
				$headers[] = "Style Type";
				$headers[] = "Location";

			header("Content-Type: text/csv; charset=utf-8");
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Pragma: no-cache');
			header('Expires: 0');

			$fp = fopen('php://output', 'w');
			fwrite($fp, $BOM);

			if ($fp && $sql) {

				fputcsv($fp, $headers);

				do {

                    include (DB.'output_entries_export_extend.db.php');

                    if (isset($row_sql['brewBrewerID'])) $brewer_info = explode("^", brewer_info($row_sql['brewBrewerID']));
                    else $brewer_info = "";

                    if (isset($row_sql['brewBrewerFirstName'])) $brewerFirstName = strtr($row_sql['brewBrewerFirstName'],$html_remove);
                    else $brewerFirstName = "";

                    if (isset($row_sql['brewBrewerLastName'])) $brewerLastName = strtr($row_sql['brewBrewerLastName'],$html_remove);
                    else $brewerLastName = "";

                    if (!empty($brewer_info)) $fields0 = array($brewerFirstName,$brewerLastName,$brewer_info[6],$brewer_info[10],$brewer_info[11],$brewer_info[12],$brewer_info[13],$brewer_info[14]);
                    else $fields0 = array($brewerFirstName,$brewerLastName,"","","","","","");

                    $fields1 = array_values($row_sql);
					$fields2 = array($table_name,$row_flight['flightNumber'],$row_flight['flightRound'],sprintf("%02s",$row_scores['scoreEntry']),$row_scores['scorePlace'],$bos_place,$style_type_entry,$location[2]);
					$fields = array_merge($fields0,$fields1,$fields2);

                    fputcsv($fp, $fields);

				}

				while ($row_sql = mysqli_fetch_assoc($sql));
			die;
			}
		}

		else {
			//first name, last name, email, category, subcategory, entry #, judging #, brewinfo, brewmead1, brewmead2, brewmead3, address, city, state, zip
			if (($go == "csv") && ($action == "hccp") && ($filter != "winners")) {
				$a[] = array('First Name','Last Name','Entry Number','Category','Style','Style Name','Entry Number','Judging Number','Brew Name','Required Info','Sweetness','Carb','Strength');
			}

			if (($go == "csv") && (($action == "default") || ($action == "email")) && ($filter != "winners")) {
                $a[] = array('First Name','Last Name','Email', 'Address','City','State/Province','Zip/Postal Code','Country','Entry Number','Judging Number','Category','SubCategory','Style Name','Brew Name', 'Required Info','Specifics','Sweetness','Carb','Strength','Table','Location','Flight','Round','Score','Place','BOS Place');
            }

			if (($go == "csv") && ($action == "default") && ($filter == "winners")) {
				$a[] = array('Table Number','Table Name','Category','Style','Style Name','Place','Last Name','First Name','Email','Address','City','State/Province','Zip/Postal Code','Country','Phone','Entry Name','Club','Co Brewer');
			}

			// Required and optional info only headers
			if (($go == "csv") && ($action == "required") && ($filter == "required")) {
				$a[] = array('Entry Number','Judging Number','Category','Style','Style Name','Required Info','Optional Info','Specifics','Sweetness','Carb','Strength');
			}

			do {

				if (isset($row_sql['brewBrewerFirstName'])) $brewerFirstName = strtr($row_sql['brewBrewerFirstName'],$html_remove);
				else $brewerFirstName = "";

				if (isset($row_sql['brewBrewerLastName'])) $brewerLastName = strtr($row_sql['brewBrewerLastName'],$html_remove);
				else $brewerLastName = "";

				if (isset($row_sql['brewBrewerLastName'])) $brewName = strtr($row_sql['brewName'],$html_remove);
				else $brewName = "";

				if (isset($row_sql['brewInfo'])) {
					$brewInfo = str_replace("^"," | ",$row_sql['brewInfo']);
					$brewInfo = strtr($brewInfo,$html_remove);
				}
				else $brewInfo = "";

				if (isset($row_sql['brewComments'])) $brewSpecifics = strtr($row_sql['brewComments'],$html_remove);
				else $brewSpecifics = "";

				if (isset($row_sql['brewInfoOptional'])) $brewInfoOptional = strtr($row_sql['brewInfoOptional'],$html_remove);
				else $brewInfoOptional = "";

				$entryNo = sprintf("%04s",$row_sql['id']);

				if (isset($row_sql['brewJudgingNumber'])) $judgingNo = sprintf("%06s",$row_sql['brewJudgingNumber']);
				else $judgingNo = "";

				if (isset($row_sql['brewBrewerID'])) $brewer_info = explode("^", brewer_info($row_sql['brewBrewerID']));
				else $brewer_info = "";

				// Winner Downloads
				if (($action == "default") && ($filter == "winners") && ($_SESSION['prefsWinnerMethod'] == 0)) {
					include (DB.'output_entries_export_winner.db.php');
				} // end if (($action == "default") && ($filter == "winners"))

				// No participant email addresses
				if (($action == "hccp") && ($filter != "winners"))
				$a[] = array($brewerFirstName,$brewerLastName,$row_sql['brewCategory'],$row_sql['brewSubCategory'],$row_sql['brewStyle'],$entryNo,$brewName,$brewInfo,$row_sql['brewMead2'],$row_sql['brewMead1'],$row_sql['brewMead3']);

				// With email addresses of participants.
				if ((($action == "default") || ($action == "email")) && ($go == "csv") && ($filter != "winners")) {
					include (DB.'output_entries_export_extend.db.php');
					if (!empty($brewer_info)) $a[] = array($brewerFirstName,$brewerLastName,$brewer_info[6],$brewer_info[10],$brewer_info[11],$brewer_info[12],$brewer_info[13],$brewer_info[14],$entryNo,$judgingNo,$row_sql['brewCategory'],$row_sql['brewSubCategory'],$row_sql['brewStyle'],$brewName,$brewInfo,$brewSpecifics,$row_sql['brewMead1'],$row_sql['brewMead2'],$row_sql['brewMead3'],$table_name,$location[2],$row_flight['flightNumber'],$row_flight['flightRound'],sprintf("%02s",$row_scores['scoreEntry']),$row_scores['scorePlace'],$bos_place);
				}



				if (($go == "csv") && ($action == "required") && ($filter == "required")) {

					$a[] = array($entryNo,
								 $judgingNo,
								 $row_sql['brewCategory'],
								 $row_sql['brewSubCategory'],
								 $row_sql['brewStyle'],
								 $brewInfo,
								 $brewInfoOptional,
								 $brewSpecifics,
								 $row_sql['brewMead1'],
								 $row_sql['brewMead2'],
								 $row_sql['brewMead3']
								);

				}

			} while ($row_sql = mysqli_fetch_assoc($sql));

			if (($action == "default") && ($filter == "winners") && ($_SESSION['prefsWinnerMethod'] >= 0)) {
				include (DB.'output_entries_export_winner.db.php');
			}

			header("Content-Type: text/csv; charset=utf-8");
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Pragma: no-cache');
			header('Expires: 0');

			$fp = fopen('php://output', 'w');
			fwrite($fp, $BOM);

			foreach ($a as $fields) {
				fputcsv($fp,$fields,$separator);
			}

			fclose($fp);

		}

	} // END if ($section == "entries")

/* -------------- EMAIL Exports -------------- */

	if ($section == "emails") {

		include (DB.'output_email_export.db.php');

		$separator = ",";
		$extension = ".csv";
		$contest = str_replace(' ', '_', $_SESSION['contestName']);
		$contest = ltrim(filename($contest),"_");
		if ($filter == "default") $type = "All_Participants";
		else $type = $filter;
		if ($section == "loc") {
			 $loc = str_replace(' ', '_', $row_judging['judgingLocName']);
			 $loc = filename($loc);
		}
		else $loc = "";
		$date = date("Y-m-d");

		// Appropriately name the CSV file for each type of download
		if ($filter == "judges") 				$filename = $contest."_Assigned_Judge_Email_Addresses_".$date.$loc.$extension;
		elseif ($filter == "stewards") 			$filename = $contest."_Assigned_Steward_Email_Addresses_".$date.$loc.$extension;
		elseif ($filter == "staff") 			$filename = $contest."_Assigned_Staff_Email_Addresses_".$date.$loc.$extension;
		elseif ($filter == "avail_judges") 		$filename = $contest."_Available_Judge_Email_Addresses_".$date.$loc.$extension;
		elseif ($filter == "avail_stewards") 	$filename = $contest."_Available_Steward_Email_Addresses_".$date.$loc.$extension;
		else  									$filename = $contest."_All_Participant_Email_Addresses_".$date.$loc.$extension;

		// Set the header row of the CSV for each type of download
		if (($filter == "judges") || ($filter == "avail_judges")) $a [] = array('First Name','Last Name','Email','Rank','BJCP ID','Availability','Likes','Dislikes','Entries In...');
		elseif (($filter == "stewards") || ($filter == "avail_stewards")) $a [] = array('First Name','Last Name','Email','Availability','Entries In...');
		elseif ($filter == "staff") $a [] = array('First Name','Last Name','Email','Entries In...');
		else $a [] = array('First Name','Last Name','Email','Address','City','State/Province','Zip','Country','Phone','Club','Entries In...');

		do {
			$brewerAddress = "";
			$brewerCity = "";
			$phone = "";
			$brewerFirstName = strtr($row_sql['brewerFirstName'],$html_remove);
			$brewerLastName = strtr($row_sql['brewerLastName'],$html_remove);
			if ($filter == "default") {
				$brewerAddress = strtr($row_sql['brewerAddress'],$html_remove);
				$brewerCity = strtr($row_sql['brewerCity'],$html_remove);
				if ($row_sql['brewerCountry'] == "United States") $phone = format_phone_us($row_sql['brewerPhone1']); else $phone = $row_sql['brewerPhone1'];
			}

			$judge_avail = judge_steward_availability($row_sql['brewerJudgeLocation'],2,$prefix);
			$steward_avail = judge_steward_availability($row_sql['brewerStewardLocation'],2,$prefix);

			if (($filter == "judges") || ($filter == "avail_judges")) 			$a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],str_replace(",",", ",$row_sql['brewerJudgeRank']),strtoupper(strtr($row_sql['brewerJudgeID'],$bjcp_num_replace)),$judge_avail,style_convert($row_sql['brewerJudgeLikes'],'6'),style_convert($row_sql['brewerJudgeDislikes'],'6'),judge_entries($row_sql['uid'],0));
			elseif (($filter == "stewards") || ($filter == "avail_stewards")) 	$a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],$steward_avail,judge_entries($row_sql['uid'],0));
			elseif ($filter == "staff") 										$a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],judge_entries($row_sql['uid'],0));
			else 																$a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],$brewerAddress,$brewerCity,$row_sql['brewerState'],$row_sql['brewerZip'],$row_sql['brewerCountry'],$phone,$row_sql['brewerClubs'],judge_entries($row_sql['uid'],0));

		} while ($row_sql = mysqli_fetch_assoc($sql));

		header("Content-Type: text/csv; charset=utf-8");
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Pragma: no-cache');
		header('Expires: 0');

		$fp = fopen('php://output', 'w');
		fwrite($fp, $BOM);

		foreach ($a as $fields) {
			fputcsv($fp,$fields,$separator);
		}

		fclose($fp);


	} // END if ($section == "emails")

/* -------------- PARTICIPANTS Exports -------------- */

	if ($section == "participants") {

		include (DB.'output_participants_export.db.php');

		if ($go == "csv") { $separator = ","; $extension = ".csv"; }
		if ($go == "tab") { $separator = "\t"; $extension = ".tab"; }
		$contest = str_replace(' ', '_', $_SESSION['contestName']);
		if ($section == "loc") $loc = "_".str_replace(' ', '_', $row_judging['judgingLocName']);
		else $loc = "";
		$date = date("m-d-Y");

		$a[] = array('First Name','Last Name','Address','City','State/Province','Zip','Country','Phone','Email','Clubs','Entries In...','Assignment','Judge ID','Judge Rank','Likes','Dislikes');

		//echo $query_sql;

		do {
			$brewerFirstName = strtr($row_sql['brewerFirstName'],$html_remove);
			$brewerLastName = strtr($row_sql['brewerLastName'],$html_remove);
			$brewerAddress = strtr($row_sql['brewerAddress'],$html_remove);
			$brewerCity = strtr($row_sql['brewerCity'],$html_remove);
			$assignment = brewer_assignment($row_sql['uid'],"1","blah","default","default");
			$assignment = strtolower($assignment);
			$assignment = str_replace(", ", ",", $assignment);
			$assignment = explode(",", $assignment);
			$assign = array();

			foreach ($assignment as $value) {
				if (strpos($value,"judge") !== false) $assign[] = "Judge";
				if (strpos($value,"bos") !== false) $assign[] = "BOS";
				if (strpos($value,"steward") !== false) $assign[] = "Steward";
			}

			$assignment = implode(", ", $assign);

			if ($row_sql['brewerCountry'] == "United States") $phone = format_phone_us($row_sql['brewerPhone1']); else $phone = $row_sql['brewerPhone1'];
			$a[] = array($brewerFirstName,$brewerLastName,$brewerAddress,$brewerCity,$row_sql['brewerState'],$row_sql['brewerZip'],$row_sql['brewerCountry'],$phone,$row_sql['brewerEmail'],$row_sql['brewerClubs'],judge_entries($row_sql['uid'],0),$assignment,$row_sql['brewerJudgeID'],str_replace(",",", ",$row_sql['brewerJudgeRank']),style_convert($row_sql['brewerJudgeLikes'],'6'),style_convert($row_sql['brewerJudgeDislikes'],'6'));
		} while ($row_sql = mysqli_fetch_assoc($sql));

		$filename = ltrim(filename($contest)."_Participants".filename($date).$loc.$extension,"_");

		header("Content-Type: text/csv; charset=utf-8");
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Pragma: no-cache');
		header('Expires: 0');

		$fp = fopen('php://output', 'w');

		fwrite($fp, $BOM);

		foreach ($a as $fields) {
			fputcsv($fp,$fields,$separator);
		}

		fclose($fp);
	}


/* -------------- PROMO Exports -------------- */

	if ($section == "promo") {

		include (DB.'dropoff.db.php');
		include (DB.'sponsors.db.php');
		include (DB.'contacts.db.php');
		include (DB.'styles.db.php');
		include (DB.'judging_locations.db.php');
		include (DB.'entry_info.db.php');

		if ($_SESSION['contestHostWebsite'] != "") $website = $_SESSION['contestHostWebsite'];
		else $website = $_SERVER['SERVER_NAME'];

		if ($action == "word") {
			$filename = str_replace(" ", "_", $_SESSION['contestName'])."_Promo.doc";
			header('Content-Type: application/msword;');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
		}

		if ($action == "html") {
			$filename = str_replace(" ", "_", $_SESSION['contestName'])."_Promo.html";
			header('Content-Type: text/plain;');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
		}

		if ($action == "bbcode") {
			$filename= str_replace(" ", "_", $_SESSION['contestName'])."_Promo.txt";
			header('Content-Type: text/plain;');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
		}

		$output = "";
		if ($action != "bbcode") {
		$output .= "<html>\n";
		$output .= "<body>\n";
		}

		$output .= "<h1>".$_SESSION['contestName']."</h1>\n";
		$output .= "<p>".$_SESSION['contestHost']." announces the ".$_SESSION['contestName'].".</p>\n";
		$output .= "<h2>Entries</h2>\n";
		$output .= "<p>To enter, please go to ".$website." and proceed through the registration process.</p>\n";
		$output .= "<h3>Entry Deadline</h3>\n";
		$output .= "<p>All entries must be received by our shipping location or at a drop-off location by "; $output .=  getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").". Entries will not be accepted beyond this date.</p>\n";
		$output .= "<h3>Registration Deadline</h3>\n";
		$output .= "<p>Registration will close on "; $output .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").". Please note: registered users will <em>not</em> be able to add, view, edit, or delete entries on the registration website after  this date.</p>\n";
		$output .= "<h2>Call for Judges and Stewards</h2>\n";
		$output .= "<p>If you are willing to be a judge or steward, please go to ".$website.", register, and fill out the appropriate information.</p>\n";
		$output .= "<h2>Competition Officials</h2>\n";

		$output .= "<ul>\n";
		do {
		$output .= "\t<li>".$row_contact['contactFirstName']." ".$row_contact['contactLastName']." &mdash; ".$row_contact['contactPosition']." (".$row_contact['contactEmail'].")</li>\n";
		} while ($row_contact = mysqli_fetch_assoc($contact));
		$output .= "</ul>\n";
		if ($_SESSION['prefsSponsors'] == "Y") {
		if ($totalRows_sponsors > 0) {
		$output .= "<h2>Sponsors</h2>\n";
		$output .= "<ul>\n";
			do { $output .= "\t<li>".$row_sponsors['sponsorName']."</li>\n"; } while ($row_sponsors = mysqli_fetch_assoc($sponsors));
		$output .= "</ul>\n";
			}
		} // end if prefs dictate display sponsors

		$output .= "<h2>Competition Rules</h2>\n";
		$output .= $row_contest_info['contestRules']."\n";
		$output .= "<h3>Entry Fee</h3>\n";
		$output .= "<p>".$currency_symbol.$_SESSION['contestEntryFee']." per entry."; if ($_SESSION['contestEntryFeeDiscount'] == "Y") $output .= $currency_symbol.number_format($_SESSION['contestEntryFee2'], 2)." per entry after ".$_SESSION['contestEntryFeeDiscountNum']." entries. "; if ($_SESSION['contestEntryCap'] != "") $output .= $currency_symbol.number_format($_SESSION['contestEntryCap'], 2)." for unlimited entries.";
		$output .= "</p>\n";
		$output .= "<h3>Payment</h3>\n";
		$output .= "<p>After registering and inputting entries, all participants must pay their entry fee(s). Accepted payment methods include:</p>\n";
		$output .= "<ul>\n";
			if ($_SESSION['prefsCash'] == "Y") $output .= "\t<li>Cash</li>\n";
			if ($_SESSION['prefsCheck'] == "Y") $output .= "\t<li>Check, made out to <em>".$_SESSION['prefsCheckPayee']."</em></li>\n";
			if ($_SESSION['prefsPaypal'] == "Y") $output .= "\t<li>PayPal (once registered on the website, click <em>Pay Entry Fees</em> from the main navigation after inputting all entries).</li>\n";
		$output .= "</ul>\n";

		$output .= "<h3>Competition Date"; if ($totalRows_judging > 1) $output .= "s"; $output .= "</h3>\n";
		if ($totalRows_judging == 0) $output .= "<p>The competition judging date is yet to be determined. Please check back later.</p>\n"; else {
		  do {
		  $output .= "<p>";
		  $output .= "<strong>".$row_judging['judgingLocName']."</strong><br />";
			if ($row_judging['judgingDate'] != "") $output .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
			if ($row_judging['judgingLocation'] != "") $output .= "<br />".$row_judging['judgingLocation'];
		  $output .= "</p>\n";
			} while ($row_judging = mysqli_fetch_assoc($judging));
		}

		$output .= "<h3>Styles Accepted</h3>\n";
		$output .= "<ul>\n";
		do {
		  $output .= "\t<li>".ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum']." ".$row_styles['brewStyle']; if ($row_styles['brewStyleOwn'] == "custom") $output .= " (Special style: ".$_SESSION['contestName'].")"; $output .= "</li>\n";
		} while ($row_styles = mysqli_fetch_assoc($styles));
		$output .= "</ul>\n";

		if ($row_contest_info['contestBottles'] != "") {
			$output .= "<h3>Entry Acceptance Rules</h3>\n";
			$output .= $row_contest_info['contestBottles']."\n";
		}

		if ($_SESSION['contestShippingAddress'] != "") {
			$output .= "<h3>Shipping Address</h3>\n";
			$output .= "<p>".$_SESSION['contestShippingAddress']."</p>\n";
			$output .= "<h4>Packing &amp; Shipping Hints</h4>\n";
			$output .= "<ol>\n";
			$output .= "\t<li>Carefully pack your entries in a sturdy box. Line the inside of your carton with a plastic trash bag. Partition and pack each bottle with adequate packaging material. Do not over pack! Write clearly: \"Fragile. This Side Up.\" on the package. Your package should weigh less than 25 pounds. Please refrain from using &quot;messy&quot; packaging materials such a Styrofoam &quot;peanuts&quot; or pellets; please use packaging material such as bubble wrap.</li>\n";
			$output .= "\t<li>Every reasonable effort will be made to contact entrants whose bottles have broken to make arrangements for sending replacement bottles.</li>\n";
			$output .= "\t<li>It is not against any Bureau of Alcohol, Tobacco and Firearms (ATF) regulations or federal laws to ship your entries via privately owned shipping company for analytical purposes. However, <strong>IT IS ILLEGAL TO SHIP ALCOHOLIC BEVERAGES VIA THE U.S. POSTAL SERVICE</strong>. Private shipping companies have the right to refuse your shipment if they are informed that the package contains glass and/or alcoholic beverages. Be aware that entries mailed internationally are often required by customs to have proper documentation. These entries might be opened and/or returned to the shipper by customs' officials at their discretion. It is solely the entrant's responsibility to follow all applicable laws and regulations.</li>\n";
			$output .= "</ol>\n";
		}

		if ($totalRows_dropoff > 0)  {
			$output .= "<h3>Drop-Off Locations</h3>\n";
			do {
				$output .= "<p><strong>".$row_dropoff['dropLocationName']."</strong><br />".$row_dropoff['dropLocation']."<br />".$row_dropoff['dropLocationPhone']."</p>\n";
			} while($row_dropoff = mysqli_fetch_assoc($dropoff));
		}

		if ($row_contest_info['contestBOSAward'] != "")
			$output .= "<h2>Best of Show</h2>\n";
			$output .= "<p>".$row_contest_info['contestBOSAward']."</p>\n";

		if ($row_contest_info['contestAwards'] != "") {
			$output .= "<h2>Awards</h2>\n";
			$output .= "<p>".$row_contest_info['contestAwards']."</p>\n";
		 }

		if ($row_contest_info['contestAwardsLocName'] != "") {
			$output .= "<h3>Award Ceremony</h3>\n";
			$output .= "<p>";
				if ($row_contest_info['contestAwardsLocDate'] != "")
				$output .= "<strong>".$row_contest_info['contestAwardsLocName']."</strong><br />";
				$output .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_info['contestAwardsLocTime'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
				if ($_SESSION['contestAwardsLocation'] != "") $output .= "<br />".$_SESSION['contestAwardsLocation'];
			$output .= "</p>\n";
		 }

		if ($action != "bbcode") {
			$output .= "</body>\n";
			$output .= "</html>\n";
		}

		if ($action == "bbcode") {
			$html   = array("<p>","</p>","<strong>","</strong>","<ul>","</ul>","<ol>","</ol>","<li>","</li>","<h1>","</h1>","<h2>","</h2>","<h3>","</h3>","<h4>","</h4>","<body>","</body>","<html>","</html>","<br />","<em>","</em>","&amp;","&mdash;");
			$bbcode = array("[left]","[/left]\r\n\r","[b]","[/b]","[list type=disc]\r","[/list]\r\n\r","[list type=upper-roman]\r","[/list]\r\n\r","[li]","[/li]\r","[size=xx-large]","[/size]\r\n\r","[size=x-large]","[/size]\r\n\r","[size=large]","[/size]\r\n\r","[b]","[/b]","","","","","\n","[i]","[/i]","&","-");
			$output = str_replace($html,$bbcode,$output);
		}
		echo $output;

	} // END if ($section == "promo")


/* -------------- RESULTS Exports -------------- */

	if ($section == "results") {

		if ($_SESSION['prefsProEdition'] == 1) {
			$td_width_place = 100;
			$td_width_name = 333;
			$td_width_entry = 333;
			$td_width_style = 334;
			$td_width_club = 0;
		}

		else {
			$td_width_place = 50;
			$td_width_name = 250;
			$td_width_entry = 300;
			$td_width_style = 300;
			$td_width_club = 200;
		}


		require(DB.'winners.db.php');
		require(DB.'output_results.db.php');

		if ($_SESSION['prefsProEdition'] == 1) $label_brewer = $label_organization; else $label_brewer = $label_brewer;

		if ($view == "pdf") {
			require(CLASSES.'fpdf/html_table.php');
			$pdf=new PDF();
			$pdf->AddPage('L');

		}

		if ($view == "html") {
			$header .= '<!DOCTYPE html>';
			$header .= '<head>';
			$header .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			$header .= '<title>Results - '.$_SESSION['contestName'].'</title>';
			$header .= '<style>';
			$header .= 'body {font-family: Arial,sans-serif; font-size: 12px; }';
			$header .= '</style>';
			$header .= '</head>';
			$header .= '<body>';
		}

		if ($go == "judging_scores") {
			$html = '';

			if ($view == "pdf") {
				$pdf->SetFont('Arial','B',16);
				$pdf->Write(5,'Results - '.$_SESSION['contestName']);
				$pdf->SetFont('Arial','',8);
			}

			if ($view == "html") $html .= '<h1>Winners - '.$_SESSION['contestName'].'</h1>';

			$filename = str_replace(" ","_",$_SESSION['contestName']).'_Results.'.$view;

			if ($_SESSION['prefsWinnerMethod'] == 0) {

				do {
					$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");

					if ($entry_count > 1) $entries = strtolower($label_entries);
					else $entries = strtolower($label_entry);

					if ($entry_count > 0) {

					if ($view == "pdf") $html .= '<br><br><strong>'.$label_table.' '.$row_tables['tableNumber'].': '.$row_tables['tableName'].' ('.$entry_count.' '.$entries.')</strong><br>';
					else $html .= '<h2>'.$label_table.' '.$row_tables['tableNumber'].': '.$row_tables['tableName'].' ('.$entry_count.' '.$entries.')</h2>';
					$html .= '<table border="1" cellpadding="5" cellspacing="0">';
					$html .= '<tr>';
					$html .= '<td width="'.$td_width_place.'" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>'.$label_place.'</strong></td>';
					$html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc"><strong>'.$label_brewer.'</strong></td>';
					$html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_entry.' '.$label_name.'</strong></td>';
					$html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_style.'</strong></td>';
					if ($_SESSION['prefsProEdition'] == 0) $html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>'.$label_club.'</strong></td>';
					$html .= '</tr>';

					include (DB.'scores.db.php');

					do {
							$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
							$html .= '<tr>';
							$html .= '<td width="'.$td_width_place.'">'.display_place($row_scores['scorePlace'],1).'</td>';
							if ($_SESSION['prefsProEdition'] == 1) $html .= '<td width="'.$td_width_name.'">'.strtr($row_scores['brewerBreweryName'],$html_remove).'</td>';
							else $html .= '<td width="'.$td_width_name.'">'.$row_scores['brewerFirstName'].' '.$row_scores['brewerLastName'].'</td>';
							$html .= '<td width="'.$td_width_entry.'">';
							if ($row_scores['brewName'] != '') $html .= strtr($row_scores['brewName'],$html_remove); else $html .= '&nbsp;';
							$html .= '</td>';
							$html .= '<td width="'.$td_width_style.'">';
							if ($row_scores['brewStyle'] != '') $html .= $row_scores['brewStyle']; else $html .= "&nbsp;";
							$html .= '</td>';
							if (($_SESSION['prefsProEdition'] == 0) && ($row_scores['brewerClubs'] != "")) {
								$html .= '<td width="175">';
								$html .= strtr($row_scores['brewerClubs'],$html_remove);
								$html .= '</td>';
							}
							$html .= '</tr>';
						} while ($row_scores = mysqli_fetch_assoc($scores));
					$html .= '</table>';
					}
				} while ($row_tables = mysqli_fetch_assoc($tables));

			} // end if ($_SESSION['prefsWinnerMethod'] == 0)

			if ($_SESSION['prefsWinnerMethod'] == 1) {

				$a = styles_active(0);

				foreach (array_unique($a) as $style) {

					if ($style > 0) {

						include (DB.'winners_category.db.php');

						//echo $style."<br>";
						//echo $row_entry_count['count']."<br>";
						//echo $row_score_count['count']."<br><br>";

						// Display all winners
						if ($row_entry_count['count'] > 1) $entries = strtolower($label_entries); else $entries = strtolower($label_entry);
						if ($row_score_count['count'] > 0)   {

							$style_trimmed = ltrim($style,"0");

							if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
								if ($view == "pdf") $html .= '<br><br><strong>Style '.ltrim($style,"0").': '.style_convert($style,"1").' ('.$row_entry_count['count'].' '.$entries.')</strong><br>';
								else $html .= '<h2>Style '.$style_trimmed.': '.style_convert($style,"1").' ('.$row_entry_count['count'].' '.$entries.')</h2>';
							}

							else {
								include (INCLUDES.'ba_constants.inc.php');
								if ($view == "pdf") $html .= '<br><br><strong>'.$ba_category_names[$style].' ('.$row_entry_count['count'].' '.$entries.')</strong><br>';
								else $html .= '<h2>'.$ba_category_names[$style].' ('.$row_entry_count['count'].' '.$entries.')</h2>';
							}

							$html .= '<table border="1" cellpadding="5" cellspacing="0">';
							$html .= '<tr>';
							$html .= '<td width="'.$td_width_place.'" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>'.$label_place.'</strong></td>';
							$html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc"><strong>'.$label_brewer.'</strong></td>';
							$html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_entry.' '.$label_name.'</strong></td>';
							$html .= '<td width="'.$td_width_style.'" align="center" bgcolor="#cccccc"><strong>'.$label_style.'</strong></td>';
							if ($_SESSION['prefsProEdition'] == 0) $html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>'.$label_club.'</strong></td>';
							$html .= '</tr>';

						include (DB.'scores.db.php');

							do {
								$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
								$html .= '<tr>';
								$html .= '<td width="'.$td_width_place.'">'.display_place($row_scores['scorePlace'],1).'</td>';
								if ($_SESSION['prefsProEdition'] == 1) $html .= '<td width="'.$td_width_name.'">'.strtr($row_scores['brewerBreweryName'],$html_remove).'</td>';
								else $html .= '<td width="'.$td_width_name.'">'.$row_scores['brewerFirstName'].' '.$row_scores['brewerLastName'].'</td>';
								$html .= '<td width="'.$td_width_entry.'">';
								if ($row_scores['brewName'] != '') $html .= strtr($row_scores['brewName'],$html_remove); else $html .= '&nbsp;';
								$html .= '</td>';
								$html .= '<td width="'.$td_width_style.'">';
								if ($row_scores['brewStyle'] != '') $html .= $row_scores['brewStyle']; else $html .= "&nbsp;";
								$html .= '</td>';
								if (($_SESSION['prefsProEdition'] == 0) && ($row_scores['brewerClubs'] != "")) {
									$html .= '<td width="175">';
									$html .= strtr($row_scores['brewerClubs'],$html_remove);
									$html .= '</td>';
								}
								$html .= '</tr>';
							} while ($row_scores = mysqli_fetch_assoc($scores));

						$html .= '</table>';

						} // if (($results_count[0] > 0) && ($results_count[1] > 0))

					}

				} // end foreach

			} // end if if ($_SESSION['prefsWinnerMethod'] == 1)

			if ($_SESSION['prefsWinnerMethod'] == 2) {
				$styles = styles_active(2);

				foreach (array_unique($styles) as $style) {

					$style = explode("^",$style);
					include (DB.'winners_subcategory.db.php');

					if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {

						if ($row_entry_count['count'] > 1) $entries = strtolower($label_entries);
						else $entries = strtolower($label_entry);


						if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
							if ($view == "pdf") $html .= '<br><br><strong>Style '.ltrim($style[0],"0").$style[1].': '.$style[2].' ('.$row_entry_count['count'].' '.$entries.')</strong><br>';
							else $html .= '<h2>Style '.ltrim($style[0],"0").$style[1].': '.$style[2].' ('.$row_entry_count['count'].' '.$entries.')</h2>';
						}

						else {
							if ($view == "pdf") $html .= '<br><br><strong>'.$style[2].' ('.$row_entry_count['count'].' '.$entries.')</strong><br>';
							else $html .= '<h2>'.$style[2].' ('.$row_entry_count['count'].' '.$entries.')</h2>';
						}



						$html .= '<table border="1" cellpadding="5" cellspacing="0">';
						$html .= '<tr>';
						$html .= '<td width="'.$td_width_place.'" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>'.$label_place.'</strong></td>';
						$html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc"><strong>'.$label_brewer.'</strong></td>';
						$html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_entry.' '.$label_name.'</strong></td>';
						$html .= '<td width="'.$td_width_style.'" align="center" bgcolor="#cccccc"><strong>'.$label_style.'</strong></td>';
						if ($_SESSION['prefsProEdition'] == 0) $html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>'.$label_club.'</strong></td>';
						$html .= '</tr>';

						include (DB.'scores.db.php');

						do {
							$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
							$html .= '<tr>';
							$html .= '<td width="'.$td_width_place.'">'.display_place($row_scores['scorePlace'],1).'</td>';
							if ($_SESSION['prefsProEdition'] == 1) $html .= '<td width="'.$td_width_name.'">'.strtr($row_scores['brewerBreweryName'],$html_remove).'</td>';
							else $html .= '<td width="'.$td_width_name.'">'.$row_scores['brewerFirstName'].' '.$row_scores['brewerLastName'].'</td>';
							$html .= '<td width="'.$td_width_entry.'">';
							if ($row_scores['brewName'] != '') $html .= strtr($row_scores['brewName'],$html_remove); else $html .= '&nbsp;';
							$html .= '</td>';
							$html .= '<td width="'.$td_width_style.'">';
							if ($row_scores['brewStyle'] != '') $html .= $row_scores['brewStyle']; else $html .= "&nbsp;";
							$html .= '</td>';
							if (($_SESSION['prefsProEdition'] == 0) && ($row_scores['brewerClubs'] != "")) {
								$html .= '<td width="175">';
								$html .= strtr($row_scores['brewerClubs'],$html_remove);
								$html .= '</td>';
							}
							$html .= '</tr>';
						} while ($row_scores = mysqli_fetch_assoc($scores));

						$html .= '</table>';

					}

				}

			}

		}

		if ($go == "judging_scores_bos") {

			if ($_SESSION['prefsProEdition'] == 1) $label_brewer = $label_organization; else $label_brewer = $label_brewer;

			$html = '';

			if ($view == "pdf") {
				$pdf->SetFont('Arial','B',16);
				$pdf->Write(5,'Best of Show Results - '.$_SESSION['contestName']);
				$pdf->SetFont('Arial','',7);
			}
			$filename = str_replace(" ","_",$_SESSION['contestName']).'_BOS_Results.'.$view;

            $a = array();

            do { $a[] = $row_style_types['id']; } while ($row_style_types = mysqli_fetch_assoc($style_types));

			if ($view == "html") $html .= '<h1>BOS - '.$_SESSION['contestName'].'</h1>';

			sort($a);

			foreach (array_unique($a) as $type) {

				include (DB.'output_results_download_bos.db.php');

				if ($totalRows_bos > 0) {

					if ($view == "pdf") $html .= '<br><br><strong>'.$row_style_type['styleTypeName'].'</strong><br>';
					else $html .= '<h2>'.$row_style_type['styleTypeName'].'</h2>';
					$html .= '<table border="1" cellpadding="5" cellspacing="0">';
					$html .= '<tr>';
					$html .= '<td width="'.$td_width_place.'" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>'.$label_place.'</strong></td>';
					$html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc"><strong>'.$label_brewer.'</strong></td>';
					$html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_entry.' '.$label_name.'</strong></td>';
					$html .= '<td width="'.$td_width_style.'" align="center" bgcolor="#cccccc"><strong>'.$label_style.'</strong></td>';
					if ($_SESSION['prefsProEdition'] == 0) $html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>'.$label_club.'</strong></td>';
					$html .= '</tr>';

					do {

						$style = $row_bos['brewCategory'].$row_bos['brewSubCategory'];

						$html .= '<tr>';
						$html .= '<td width="'.$td_width_place.'" nowrap="nowrap">'.display_place($row_bos['scorePlace'],1).'</td>';
						if ($_SESSION['prefsProEdition'] == 1) $html .= '<td width="'.$td_width_name.'">'.strtr($row_bos['brewerBreweryName'],$html_remove).'</td>';
						else $html .= '<td width="'.$td_width_name.'">'.$row_bos['brewerFirstName'].' '.$row_bos['brewerLastName'].'</td>';
						if (($_SESSION['prefsProEdition'] == 0) && ($row_bos['brewCoBrewer'] != "")) $html .=', '.$row_bos['brewCoBrewer'];
						$html .= '</td>';
						$html .= '<td width="'.$td_width_entry.'">'.strtr($row_bos['brewName'],$html_remove).'</td>';
						$html .= '<td width="'.$td_width_style.'">'.$row_bos['brewStyle'].'</td>';

						if ($_SESSION['prefsProEdition'] == 0) {
							$html .= '<td width="175">';
							if ($row_bos['brewerClubs'] != "") $html .= strtr($row_bos['brewerClubs'],$html_remove);
							else $html .= "&nbsp;";
							$html .= '</td>';
						}

						$html .= '</tr>';


					} while ($row_bos = mysqli_fetch_assoc($bos));

					$html .= '</table>';

				} // end if ($totalRows_bos > 0)

			} // end foreach (array_unique($a) as $type)

			if ($totalRows_sbi > 0) {

				do {

					include (DB.'output_results_download_sbd.db.php');

					if ($totalRows_sbd > 0) {
					if ($view == "pdf") $html .= '<br><br><strong>'.strtr($row_sbi['sbi_name'],$html_remove).'</strong>';
					else $html .= '<h2>'.strtr($row_sbi['sbi_name'],$html_remove).'</h2>';
					$html .= '<br>'.strtr($row_sbi['sbi_description'],$html_remove).'<br>';
					$html .= '<table border="1" cellpadding="5" cellspacing="0">';
					$html .= '<tr>';
					if ($row_sbi['sbi_display_places'] == "1") $html .= '<td width="'.$td_width_place.'" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>'.$label_place.'</strong></td>';
					$html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc"><strong>'.$label_brewer.'</strong></td>';
					$html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_entry.' '.$label_name.'</strong></td>';
					$html .= '<td width="'.$td_width_style.'" align="center" bgcolor="#cccccc"><strong>'.$label_style.'</strong></td>';
					if ($_SESSION['prefsProEdition'] == 0) $html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>'.$label_club.'</strong></td>';
					$html .= '</tr>';

					do {
						$brewer_info = explode("^",brewer_info($row_sbd['bid']));
						$entry_info = explode("^",entry_info($row_sbd['eid']));
						$style = $entry_info['5'].$entry_info['2'];
						$html .= '<tr>';
						if ($row_sbi['sbi_display_places'] == "1") { $html .= '<td width="'.$td_width_place.'" nowrap="nowrap">'.display_place($row_sbd['sbd_place'],4).'</td>'; }
						$html .= '<td width="'.$td_width_name.'">'.$brewer_info['0']." ".$brewer_info['1'];
							if ($row_entries['brewCoBrewer'] != "") $html .= "<br />Co-Brewer: ".$entry_info['4'];
						$html .= '</td>';
						$html .= '<td width="'.$td_width_entry.'">'.strtr($entry_info['0'],$html_remove).'</td>';
						$html .= '<td width="'.$td_width_style.'">'.$entry_info['3'].'</td>';
						$html .= '<td width="175">';
						if (($_SESSION['prefsProEdition'] == 0) && ($brewer_info['8'] != "")) $html .=strtr($brewer_info['8'],$html_remove);
						else $html .= "&nbsp;";
						$html .= '</td>';
						$html .= '</tr>';
						if ($row_sbd['sbd_comments'] != "") {
							$html .= '<tr>';
							if ($row_sbi['sbi_display_places'] == "1") $html .= '<td width="760" colspan="5"><em>'.$row_sbd['sbd_comments'].'</em></td>';
							else $html .= '<td width="725" colspan="4"><em>'.$row_sbd['sbd_comments'].'</em></td>';
							$html .= '</tr>';
						}
					} while ($row_sbd = mysqli_fetch_assoc($sbd));

					$html .= '</table>';

					}

				} while ($row_sbi = mysqli_fetch_assoc($sbi));

			} // end if ($totalRows_sbi > 0)

		} // end if ($go == "judging_scores_bos")

		if ($view == "pdf") {
			$html = iconv('UTF-8', 'windows-1252', $html);
			$pdf->WriteHTML($html);
			$pdf->Output($filename,'D');
			//echo $html;
		}

		if ($view == "html") {
			$footer = '</body>';
			$footer .= '</html>';
			header("Content-Type: application/force-download");
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $header.$html.$footer;
			exit();
		}

	} // END if ($section == "results")


/* -------------- STAFF Exports -------------- */

	if ($section == "staff") {

		include (DB.'judging_locations.db.php');
		include (DB.'styles.db.php');
		include (DB.'admin_common.db.php');
		include (DB.'output_staff_points.db.php');

		// Get total amount of paid and received entries
		$total_entries = total_paid_received("judging_scores","default");
		//$total_entries = 750;

		$st_running_total = "";

		// Figure out whether BOS Judge Points are awarded or not
		// "BOS points may only be awarded if a competition has at least 30 entries in at least five beer and/or three mead/cider categories."
        $beer_styles = array();
        $mead_styles = array();
        $cider_styles = array();

		$beer_styles[] = 0;
		$mead_styles[] = 0;
		$cider_styles[] = 0;

		do {

			if ($row_styles2['brewStyleType'] = "Cider") { $beer_syles[] = 0; $mead_styles[] = 0; $cider_styles[] = 1; }
			elseif ($row_styles2['brewStyleType'] = "Mead") { $beer_syles[] = 0; $mead_styles[] = 1; $cider_styles[] = 0; }
			else { $beer_syles[] = 1; $mead_styles[] = 0; $cider_styles[] = 0; }

		} while ($row_styles2 = mysqli_fetch_assoc($styles2));

		$beer_styles_total = array_sum($beer_styles);
		$mead_styles_total = array_sum($mead_styles);
		$cider_styles_total = array_sum($cider_styles);
		$mead_cider_total = $mead_styles_total+$cider_styles_total;

		if (($total_entries >= 30) && (($beer_styles_total >= 5) || ($mead_cider_total >= 3))) $bos_judge_points = 0.5;
		else $bos_judge_points = 0.0;

		if ($view == "pdf") {

			$filename = str_replace(" ","_",$_SESSION['contestName']).'_BJCP_Points_Report.'.$view;
			require(CLASSES.'fpdf/html_table.php');
			$pdf=new PDF();
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',16);
			$pdf->Write(5,strtr($_SESSION['contestName'],$html_remove).' BJCP Points Report');
			$pdf->SetFont('Arial','',10);

			$html = '<br><br><strong>BJCP Competition ID</strong>: '.$_SESSION['contestID'].'<br>';
			$html .= '<br><strong>Total Entries</strong>: '.$total_entries.'<br>';
			$html .= '<br><strong>Total Days</strong>: '.total_days().'<br>';
			$html .= '<br><strong>Total Sessions</strong>: '.total_sessions().'<br>';
			$html .= '<br><strong>Total Flights</strong>: '.total_flights().' (includes Best of Show)<br>';

				if ($totalRows_organizer > 0) {
				$html .= '<br><br><strong>Organizer</strong><br>';
				$html .= '<table border="1">';
				$html .= '<tr>';
				$html .= '<td width="300" align="center" bgcolor="#cccccc">Name</td>';
				$html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc">BJCP ID</td>';
				$html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc">Points</td>';
				$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td width="300">'.$row_org['brewerLastName'].", ".$row_org['brewerFirstName'].'</td>';
					$html .= '<td width="'.$td_width_name.'">';
						if ($row_org['brewerJudgeID'] != "") $html .=  strtoupper(strtr($row_org['brewerJudgeID'],$bjcp_num_replace)); else $html .= '&nbsp;';
					$html .= '</td>';
					$html .= '<td width="'.$td_width_name.'">'.$organ_max_points.'</td>';
					$html .= '</tr>';
				$html .= '</table>';
				}

				if ($totalRows_judges > 0) {
				$html .= '<br><br><strong>Judges</strong><br>';
				$html .= '<table border="1">';
				$html .= '<tr>';
				$html .= '<td width="300" align="center"  bgcolor="#cccccc">Name</td>';
				$html .= '<td width="'.$td_width_name.'" align="center"  bgcolor="#cccccc">BJCP ID</td>';
				$html .= '<td width="'.$td_width_name.'" align="center"  bgcolor="#cccccc">Points</td>';
				$html .= '</tr>';
                $j = array();
				do { $j[] = $row_judges['uid']; } while ($row_judges = mysqli_fetch_assoc($judges));
				sort($j);
				foreach (array_unique($j) as $uid) {
					$judge_info = explode("^",brewer_info($uid));
					$judge_points = judge_points($uid,$judge_info['5']);
					$bos_judge = bos_points($uid);
					if ($judge_points > 0) {
						$judge_name = strtr(ucwords(strtolower($judge_info['1'])),$html_remove).", ".strtr(ucwords(strtolower($judge_info['0'])),$html_remove);
						$html .= '<tr>';
						$html .= '<td width="300">'.$judge_name;
						if ($bos_judge) $html .= " (BOS Judge)";
						$html .= '</td>';
						$html .= '<td width="'.$td_width_name.'">';
							if (validate_bjcp_id($judge_info['4'])) $html .= strtoupper(strtr($judge_info['4'],$bjcp_num_replace)); else $html .= '&nbsp;';
						$html .= '</td>';
						if ($bos_judge) $html .= '<td width="'.$td_width_name.'">'.(judge_points($uid,$judge_info['5'])+$bos_judge_points).'</td>';
						else $html .= '<td width="'.$td_width_name.'">'.judge_points($uid,$judge_info['5']).'</td>';
						$html .= '</tr>';
						}
					}
				foreach (array_unique($bos_judge_no_assignment) as $uid) {
					$judge_info = explode("^",brewer_info($uid));
					if (!empty($uid)) {
						$judge_name = strtr(ucwords(strtolower($judge_info['1'])),$html_remove).", ".strtr(ucwords(strtolower($judge_info['0'])),$html_remove);
						$html .= '<tr>';
						$html .= '<td width="300">'.$judge_name;
						$html .= " (BOS Judge)";
						$html .= '</td>';
						$html .= '<td width="'.$td_width_name.'">';
							if (validate_bjcp_id($judge_info['4'])) $html .= strtoupper(strtr($judge_info['4'],$bjcp_num_replace)); else $html .= '&nbsp;';
						$html .= '</td>';
						$html .= '<td width="'.$td_width_name.'">1.0</td>';
						$html .= '</tr>';
					}
				}
				$html .= '</table>';
				}

				if ($totalRows_stewards > 0) {
				$html .= '<br><br><strong>Stewards</strong><br>';
				$html .= '<table border="1">';
				$html .= '<tr>';
				$html .= '<td width="300" align="center" bgcolor="#cccccc">Name</td>';
				$html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc">BJCP ID</td>';
				$html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc">Points</td>';
				$html .= '</tr>';
                $s = array();
				do { $s[] = $row_stewards['uid']; } while ($row_stewards = mysqli_fetch_assoc($stewards));
				foreach (array_unique($s) as $uid) {
					$steward_info = explode("^",brewer_info($uid));
					$steward_points = steward_points($uid);
					if ($steward_points > 0) {
						$steward_name = ucwords(strtolower($steward_info['1'])).", ".ucwords(strtolower($steward_info['0']));
						$html .= '<tr>';
						$html .= '<td width="300">'.$steward_name.'</td>';
						$html .= '<td width="'.$td_width_name.'">';
							if (validate_bjcp_id($steward_info['4'])) $html .= strtoupper(strtr($steward_info['4'],$bjcp_num_replace)); else $html .= '&nbsp;';
						$html .= '</td>';
						$html .= '<td width="'.$td_width_name.'">'.steward_points($uid).'</td>';
						$html .= '</tr>';
						}
					}
				$html .= '</table>';
				}

				if ($totalRows_staff > 0) {
				$html .= '<br><br><strong>Staff</strong><br>';
				$html .= '<table border="1">';
				$html .= '<tr>';
				$html .= '<td width="300" align="center" bgcolor="#cccccc">Name</td>';
				$html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc">BJCP ID</td>';
				$html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc">Points</td>';
				$html .= '</tr>';
                $st = array();
				do { $st[] = $row_staff['uid']; } while ($row_staff = mysqli_fetch_assoc($staff));
				foreach (array_unique($st) as $uid) {
					if (array_sum($st_running_total) < $staff_max_points) {
						$staff_info = explode("^",brewer_info($uid));
						$staff_name = ucwords(strtolower($staff_info['1'])).", ".ucwords(strtolower($staff_info['0']));
						$html .= '<tr>';
						$html .= '<td width="300">'.$staff_name.'</td>';
						$html .= '<td width="'.$td_width_name.'">';
							if (validate_bjcp_id($staff_info['4'])) $html .= strtoupper(strtr($staff_info['4'],$bjcp_num_replace)); else $html .= '&nbsp;';
						$html .= '</td>';
						$html .= '<td width="'.$td_width_name.'">';
						if ((array_sum($st_running_total) <= $staff_max_points) && ($staff_points < $organ_max_points)) $html .= $staff_points;
						else $html .= $organ_max_points;
						$html .= '</td>';
						$html .= '</tr>';
					}
					}
				$html .= '</table>';
				}
				$html = iconv('UTF-8', 'windows-1252', $html);
				$pdf->WriteHTML($html);
				$pdf->Output($filename,'D');
		}


		// ------------------------------------------------------------------------------------------------------
		// BJCP XML Output
		// BJCP XML Specification: http://www.bjcp.org/it/docs/BJCP%20Database%20XML%20Interface%20Spec%202.1.pdf
		// ------------------------------------------------------------------------------------------------------

		if ($view == "xml") {
			$total_days = total_days();
            $j = array();
            $s = array();
            $st = array();
            $o = array();
			do { $j[] = $row_judges['uid']; } while ($row_judges = mysqli_fetch_assoc($judges));
			do { $s[] = $row_stewards['uid']; } while ($row_stewards = mysqli_fetch_assoc($stewards));
			do { $st[] = $row_staff['uid']; } while ($row_staff = mysqli_fetch_assoc($staff));
			do { $o[] = $row_organizer['uid']; } while ($row_organizer = mysqli_fetch_assoc($organizer));
			$filename = str_replace(" ","_",$_SESSION['contestName'])."_BJCP_Points_Report.xml";
			$output = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
			$output .= "<OrgReport>\n";
			$output .= "\t<CompData>\n";
			$output .= "\t\t<CompID>".$_SESSION['contestID']."</CompID>\n";
			$output .= "\t\t<CompName>".$_SESSION['contestName']."</CompName>\n";
			$output .= "\t\t<CompDate>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "date-no-gmt")."</CompDate>\n";
			$output .= "\t\t<CompEntries>".$total_entries."</CompEntries>\n";
			$output .= "\t\t<CompDays>".$total_days."</CompDays>\n";
			$output .= "\t\t<CompSessions>".total_sessions()."</CompSessions>\n";
			$output .= "\t\t<CompFlights>".total_flights()."</CompFlights>\n";
			$output .= "\t</CompData>\n";
			$output .= "\t<BJCPpoints>\n";

				// Judges with a properly formatted BJCP IDs in the system
				foreach (array_unique($j) as $uid) {
				$judge_info = explode("^",brewer_info($uid));
				$judge_points = judge_points($uid,$judge_info['5']);
				if ($judge_points > 0) {
					$bos_judge = bos_points($uid);
					if (($bos_judge) && (!in_array($uid,$bos_judge_no_assignment))) $assignment = "Judge + BOS";
					else $assignment = "Judge";
						if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (validate_bjcp_id($judge_info['4']))) {
								$judge_name = strtr(ucwords(strtolower($judge_info['0'])),$html_remove)." ".strtr(ucwords(strtolower($judge_info['1'])),$html_remove);
								$output .= "\t\t<JudgeData>\n";
								$output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
								$output .= "\t\t\t<JudgeID>".strtoupper(strtr($judge_info['4'],$bjcp_num_replace))."</JudgeID>\n";
								$output .= "\t\t\t<JudgeRole>".$assignment."</JudgeRole>\n";
								if ($bos_judge) $output .= "\t\t\t<JudgePts>".number_format((judge_points($uid,$judge_info['5'])+$bos_judge_points),1)."</JudgePts>\n";
								else $output .= "\t\t\t<JudgePts>".judge_points($uid,$judge_info['5'])."</JudgePts>\n";
								$output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
								$output .= "\t\t</JudgeData>\n";
						}
					}
				}

				// Loner BOS Judges (no assignment to any table)
				foreach (array_unique($bos_judge_no_assignment) as $uid) {
					$judge_info = explode("^",brewer_info($uid));
					if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (validate_bjcp_id($judge_info['4']))) {
						if (!empty($uid)) {
							$judge_name = strtr(ucwords(strtolower($judge_info['0'])),$html_remove)." ".strtr(ucwords(strtolower($judge_info['1'])),$html_remove);
							$output .= "\t\t<JudgeData>\n";
							$output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
							$output .= "\t\t\t<JudgeID>".strtoupper(strtr($judge_info['4'],$bjcp_num_replace))."</JudgeID>\n";
							$output .= "\t\t\t<JudgeRole>BOS Judge</JudgeRole>\n";
							$output .= "\t\t\t<JudgePts>1.0</JudgePts>\n";
							$output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
							$output .= "\t\t</JudgeData>\n";
						}
					}
				}

				// Stewards with a properly formatted BJCP IDs in the system
				foreach (array_unique($s) as $uid) {
				$steward_info = explode("^",brewer_info($uid));
				$steward_points = steward_points($uid);
				if ($steward_points > 0) {
					$steward_name = ucwords(strtolower($steward_info['0']))." ".ucwords(strtolower($steward_info['1']));
					if (($steward_info['0'] != "") && ($steward_info['1'] != "") && (validate_bjcp_id($steward_info['4']))) {
						$output .= "\t\t<JudgeData>\n";
						$output .= "\t\t\t<JudgeName>".$steward_name."</JudgeName>\n";
						$output .= "\t\t\t<JudgeID>".strtoupper(strtr($steward_info['4'],$bjcp_num_replace))."</JudgeID>\n";
						$output .= "\t\t\t<JudgeRole>Steward</JudgeRole>\n";
						$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
						$output .= "\t\t\t<NonJudgePts>".$steward_points."</NonJudgePts>\n";
						$output .= "\t\t</JudgeData>\n";
						}
					}
				}

				//Staff Members with a properly formatted BJCP IDs in the system
				foreach (array_unique($st) as $uid) {
				if (array_sum($st_running_total) < $staff_max_points) {
					$staff_info = explode("^",brewer_info($uid));
						if (($staff_info['0'] != "") && ($staff_info['1'] != "") && (validate_bjcp_id($staff_info['4']))) {
							$staff_name = ucwords(strtolower($staff_info['0']))." ".ucwords(strtolower($staff_info['1']));
							$st_running_total[] .= $staff_points;
							$output .= "\t\t<JudgeData>\n";
							$output .= "\t\t\t<JudgeName>".$staff_name."</JudgeName>\n";
							$output .= "\t\t\t<JudgeID>".strtoupper(strtr($staff_info['4'],$bjcp_num_replace))."</JudgeID>\n";
							$output .= "\t\t\t<JudgeRole>Staff</JudgeRole>\n";
							$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
							$output .= "\t\t\t<NonJudgePts>".$staff_points."</NonJudgePts>\n";
							$output .= "\t\t</JudgeData>\n";
						}
					}
				}

				// Organizer with a properly formatted BJCP ID in the system
				foreach (array_unique($o) as $uid) {
				$organizer_info = explode("^",brewer_info($uid));
					if (($organizer_info['0'] != "") && ($organizer_info['1'] != "") && (validate_bjcp_id($organizer_info['4']))) {
						$output .= "\t\t<JudgeData>\n";
						$output .= "\t\t\t<JudgeName>".$organizer_info['0']." ".$organizer_info['1']."</JudgeName>\n";
						$output .= "\t\t\t<JudgeID>".strtoupper(strtr($organizer_info['4'],$bjcp_num_replace))."</JudgeID>\n";
						$output .= "\t\t\t<JudgeRole>Organizer</JudgeRole>\n";
						$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
						$output .= "\t\t\t<NonJudgePts>".$organ_max_points."</NonJudgePts>\n";
						$output .= "\t\t</JudgeData>\n";
					}
				}

				$output .= "\t</BJCPpoints>\n";

				$output .= "\t<NonBJCP>\n";

				// Judges without a properly formatted BJCP IDs in the system
				foreach (array_unique($j) as $uid) {
				$judge_info = explode("^",brewer_info($uid));
				$judge_points = judge_points($uid,$judge_info['5']);
				if ($judge_points > 0) {
					$bos_judge = bos_points($uid);
					if ($bos_judge) $assignment = "Judge + BOS";
					else $assignment = "Judge";
						if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (!validate_bjcp_id($judge_info['4']))) {
							$judge_name = ucwords(strtolower($judge_info['0']))." ".ucwords(strtolower($judge_info['1']));
								$output .= "\t\t<JudgeData>\n";
								$output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
								$output .= "\t\t\t<JudgeRole>".$assignment."</JudgeRole>\n";
								if ($bos_judge) $output .= "\t\t\t<JudgePts>".number_format((judge_points($uid,$judge_info['5'])+$bos_judge_points),1)."</JudgePts>\n";
								else $output .= "\t\t\t<JudgePts>".judge_points($uid,$judge_info['5'])."</JudgePts>\n";
								$output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
								$output .= "\t\t</JudgeData>\n";
						}
					}
				}

				// Loner BOS Judges (no assignment to any table)
				foreach (array_unique($bos_judge_no_assignment) as $uid) {
					$judge_info = explode("^",brewer_info($uid));
					if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (!validate_bjcp_id($judge_info['4']))) {
						if (!empty($uid)) {
							$judge_name = strtr(ucwords(strtolower($judge_info['0'])),$html_remove)." ".strtr(ucwords(strtolower($judge_info['1'])),$html_remove);
							$output .= "\t\t<JudgeData>\n";
							$output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
							$output .= "\t\t\t<JudgeID>".strtoupper(strtr($judge_info['4'],$bjcp_num_replace))."</JudgeID>\n";
							$output .= "\t\t\t<JudgeRole>BOS Judge</JudgeRole>\n";
							$output .= "\t\t\t<JudgePts>1.0</JudgePts>\n";
							$output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
							$output .= "\t\t</JudgeData>\n";
						}
					}
				}

				// Stewards without a properly formatted BJCP IDs in the system
				foreach (array_unique($s) as $uid) {
				$steward_points = steward_points($uid);
				if ($steward_points > 0) {
					$steward_info = explode("^",brewer_info($uid));
						if (($steward_info['0'] != "") && ($steward_info['1'] != "") && (!validate_bjcp_id($steward_info['4']))) {
								$steward_name = ucwords(strtolower($steward_info['0']))." ".ucwords(strtolower($steward_info['1']));
								$output .= "\t\t<JudgeData>\n";
								$output .= "\t\t\t<JudgeName>".$steward_name."</JudgeName>\n";
								$output .= "\t\t\t<JudgeRole>Steward</JudgeRole>\n";
								$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
								$output .= "\t\t\t<NonJudgePts>".$steward_points."</NonJudgePts>\n";
								$output .= "\t\t</JudgeData>\n";
						}
					}
				}

				// Staff Members without a properly formatted BJCP IDs in the system
				foreach (array_unique($st) as $uid) {
				if (array_sum($st_running_total) < $staff_max_points) {
					$staff_info = explode("^",brewer_info($uid));
						if (($staff_info['0'] != "") && ($staff_info['1'] != "") && (!validate_bjcp_id($staff_info['4']))) {
							$st_running_total[] = $staff_points;
							$staff_name = ucwords(strtolower($staff_info['0']))." ".ucwords(strtolower($staff_info['1']));
							$output .= "\t\t<JudgeData>\n";
							$output .= "\t\t\t<JudgeName>".$staff_name."</JudgeName>\n";
							$output .= "\t\t\t<JudgeRole>Staff</JudgeRole>\n";
							$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
							if ($staff_points < $organ_max_points) $output .= "\t\t\t<NonJudgePts>".$staff_points."</NonJudgePts>\n";
							else $output .= "\t\t\t<NonJudgePts>".$organ_max_points."</NonJudgePts>\n";
							$output .= "\t\t</JudgeData>\n";
						}
					}
				}

				// Organizer without a properly formatted BJCP ID in the system
				foreach (array_unique($o) as $uid) {
				$organizer_info = explode("^",brewer_info($uid));
					if (($organizer_info['0'] != "") && ($organizer_info['1'] != "") && (!validate_bjcp_id($organizer_info['4']))) {
						$output .= "\t\t<JudgeData>\n";
						$output .= "\t\t\t<JudgeName>".$organizer_info['0']." ".$organizer_info['1']."</JudgeName>\n";
						$output .= "\t\t\t<JudgeRole>Organizer</JudgeRole>\n";
						$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
						$output .= "\t\t\t<NonJudgePts>".$organ_max_points."</NonJudgePts>\n";
						$output .= "\t\t</JudgeData>\n";
					}
				}
				$output .= "\t</NonBJCP>\n";

				// BOS Reporting
				$output .= "\t<Comments>\n";
				$output .= "Submitter Email: ".$_SESSION['user_name']."\n";


                $a = array();
				do { $a[] = $row_style_type['id']; } while ($row_style_type = mysqli_fetch_assoc($style_type));
				sort($a);

				foreach ($a as $type) {

					include (DB.'output_results_download_bos.db.php');

					if ($totalRows_bos > 0) {
						$output .= "BOS Winner, Brewer's Name: ".$row_bos['brewerFirstName']." ".$row_bos['brewerLastName']."\n";
						$output .= "BOS Winner, BJCP Style: ".$row_bos['brewCategory'].$row_bos['brewSubCategory']." ".$row_bos['brewStyle']."\n";
						$output .= "BOS Winner, City & State: ".$row_bos['brewerCity'].", ".$row_bos['brewerState']."\n";
					}
				}


				$output .= "\t</Comments>\n";

				$output .= "\t<SubmissionDate>".date('l j F Y h:i:s A')."</SubmissionDate>\n";
				$output .= "</OrgReport>";

				header('Content-Type: application/force-download');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Pragma: no-cache');
				header('Expires: 0');

				echo $output;
				exit();
		}

	} // END if ($section == "staff")

} // end if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))
?>