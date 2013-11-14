<?php 

session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');
require(DB.'winners.db.php');
require(DB.'output_results.db.php');
require(LIB.'output.lib.php');

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
	
	if ($view == "pdf") {
		require(CLASSES.'fpdf/html_table.php');
		$pdf=new PDF();
		$pdf->AddPage();
		
	}
	
	if ($view == "html") {
		$header .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		$header .= '<html xmlns="http://www.w3.org/1999/xhtml">';
		$header .= '<head>';
		$header .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		$header .= '<title>Results - '.$_SESSION['contestName'].'</title>';
		$header .= '</head>';
		$header .= '<body>';
	}
	
	if ($go == "judging_scores") {
		$html = '';
		
		if ($view == "pdf") {
			$pdf->SetFont('Arial','B',16);
			$pdf->Write(5,'Results - '.$_SESSION['contestName']);
			$pdf->SetFont('Arial','',7);	
		}
		
		$filename = str_replace(" ","_",$_SESSION['contestName']).'_Results.'.$view;
		
		if ($_SESSION['prefsWinnerMethod'] == 0) {
			
			do { 
				$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
				if ($entry_count > 0) { 
				$html .= '<br><br><strong>Table '.$row_tables['tableNumber'].': '.$row_tables['tableName'].' ('.$entry_count.' entries)</strong><br>';
				$html .= '<table border="1">';
				$html .= '<tr>';
				$html .= '<td width="35" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>Pl.</strong></td>';
				$html .= '<td width="150" align="center" bgcolor="#cccccc"><strong>Brewer(s)</strong></td>';
				$html .= '<td width="200" align="center" bgcolor="#cccccc"><strong>Entry Name</strong></td>';
				$html .= '<td width="200" align="center" bgcolor="#cccccc"><strong>Style</strong></td>';
				$html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>Club</strong></td>';
				$html .= '</tr>';
			
				include(DB.'scores.db.php');
				
				do { 
						$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
						$html .= '<tr>';
						$html .= '<td width="35">'.display_place($row_scores['scorePlace'],1).'</td>';
						$html .= '<td width="150">'.$row_scores['brewerFirstName'].' '.$row_scores['brewerLastName'].'</td>';
						$html .= '<td width="200">';
						if ($row_scores['brewName'] != '') $html .= strtr($row_scores['brewName'],$html_remove); else $html .= '&nbsp;';
						$html .= '</td>';
						$html .= '<td width="200">';
						if ($row_scores['brewStyle'] != '') $html .= $row_scores['brewStyle']; else $html .= "&nbsp;";
						$html .= '</td>';
						$html .= '<td width="175">';
						if ($row_scores['brewerClubs'] != "") $html .=strtr($row_scores['brewerClubs'],$html_remove);
						else $html .= "&nbsp;";
						$html .= '</td>';
						$html .= '</tr>';
					} while ($row_scores = mysql_fetch_assoc($scores));
				$html .= '</table>';
				} 
			} while ($row_tables = mysql_fetch_assoc($tables));
			
		} // end if ($_SESSION['prefsWinnerMethod'] == 0)
		
		if ($_SESSION['prefsWinnerMethod'] == 1) {
			
			$style = styles_active(0);
	
			foreach (array_unique($style) as $style) {
			
				$results_count = results_count($style);
				$results_count = explode("^",$results_count);
				
				if (($results_count[0] > 0) && ($results_count[1] > 0)) {
					
					$html .= '<br><br><strong>Category '.ltrim($style,"0").': '.style_convert($style,"1").' ('.$results_count[0].' entries)</strong><br>';
					$html .= '<table border="1">';
					$html .= '<tr>';
					$html .= '<td width="35" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>Pl.</strong></td>';
					$html .= '<td width="150" align="center" bgcolor="#cccccc"><strong>Brewer(s)</strong></td>';
					$html .= '<td width="200" align="center" bgcolor="#cccccc"><strong>Entry Name</strong></td>';
					$html .= '<td width="200" align="center" bgcolor="#cccccc"><strong>Style</strong></td>';
					$html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>Club</strong></td>';
					$html .= '</tr>';
			 
				include(DB.'scores.db.php');
						
					do { 
						$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
						$html .= '<tr>';
						$html .= '<td width="35">'.display_place($row_scores['scorePlace'],1).'</td>';
						$html .= '<td width="150">'.$row_scores['brewerFirstName'].' '.$row_scores['brewerLastName'].'</td>';
						$html .= '<td width="200">';
						if ($row_scores['brewName'] != '') $html .= strtr($row_scores['brewName'],$html_remove); else $html .= '&nbsp;';
						$html .= '</td>';
						$html .= '<td width="200">';
						if ($row_scores['brewStyle'] != '') $html .= $row_scores['brewStyle']; else $html .= "&nbsp;";
						$html .= '</td>';
						$html .= '<td width="175">';
						if ($row_scores['brewerClubs'] != "") $html .=strtr($row_scores['brewerClubs'],$html_remove);
						else $html .= "&nbsp;";
						$html .= '</td>';
						$html .= '</tr>';
					} while ($row_scores = mysql_fetch_assoc($scores));
				
				$html .= '</table>';
				
				} // if (($results_count[0] > 0) && ($results_count[1] > 0))
				
			} // end foreach
			
		} // end if if ($_SESSION['prefsWinnerMethod'] == 1)
		
		if ($_SESSION['prefsWinnerMethod'] == 2) {
			$styles = styles_active(2);
			
			foreach (array_unique($styles) as $style) {
				
				$style = explode("^",$style);
				include(DB.'winners_subcategory.db.php');
				
				if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {
					
					$html .= '<br><br><strong>Category '.ltrim($style[0],"0").$style[1].': '.$style[2].' ('.$row_entry_count['count'].' entries)</strong><br>';
					$html .= '<table border="1">';
					$html .= '<tr>';
					$html .= '<td width="35" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>Pl.</strong></td>';
					$html .= '<td width="150" align="center" bgcolor="#cccccc"><strong>Brewer(s)</strong></td>';
					$html .= '<td width="200" align="center" bgcolor="#cccccc"><strong>Entry Name</strong></td>';
					$html .= '<td width="200" align="center" bgcolor="#cccccc"><strong>Style</strong></td>';
					$html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>Club</strong></td>';
					$html .= '</tr>';
				 
					include(DB.'scores.db.php');
					
					do { 
						$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
						$html .= '<tr>';
						$html .= '<td width="35">'.display_place($row_scores['scorePlace'],1).'</td>';
						$html .= '<td width="150">'.$row_scores['brewerFirstName'].' '.$row_scores['brewerLastName'].'</td>';
						$html .= '<td width="200">';
						if ($row_scores['brewName'] != '') $html .= strtr($row_scores['brewName'],$html_remove); else $html .= '&nbsp;';
						$html .= '</td>';
						$html .= '<td width="200">';
						if ($row_scores['brewStyle'] != '') $html .= $row_scores['brewStyle']; else $html .= "&nbsp;";
						$html .= '</td>';
						$html .= '<td width="175">';
						if ($row_scores['brewerClubs'] != "") $html .=strtr($row_scores['brewerClubs'],$html_remove);
						else $html .= "&nbsp;";
						$html .= '</td>';
						$html .= '</tr>';
					} while ($row_scores = mysql_fetch_assoc($scores));
					
					$html .= '</table>';
					
				}
				
			}
			
		}
		
	}
	
	if ($go == "judging_scores_bos") {
		$html = '';
		
		if ($view == "pdf") {
			$pdf->SetFont('Arial','B',16);
			$pdf->Write(5,'Best of Show Results - '.$_SESSION['contestName']);
			$pdf->SetFont('Arial','',7);	
		}
		$filename = str_replace(" ","_",$_SESSION['contestName']).'_BOS_Results.'.$view;
		
		do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));
		
		if ($view == "html") $html .= '<h1>BOS - '.$_SESSION['contestName'].'</h1>';
		
		sort($a);
		
		foreach (array_unique($a) as $type) {
			
			include(DB.'output_results_download_bos.db.php');
			
			if ($totalRows_bos > 0) {
				
				$html .= '<br><br><strong>'.$row_style_type['styleTypeName'].'</strong><br>';
				$html .= '<table border="1">';
				$html .= '<tr>';
				$html .= '<td width="35"  align="center" bgcolor="#cccccc" nowrap="nowrap"><strong>Place</strong></td>';
				$html .= '<td width="150" align="center" bgcolor="#cccccc"><strong>Brewer(s)</strong></td>';
				$html .= '<td width="200" align="center" bgcolor="#cccccc"><strong>Entry Name</strong></td>';
				$html .= '<td width="200" align="center" bgcolor="#cccccc"><strong>Style</strong></td>';
				$html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>Club</strong></td>';
				$html .= '</tr>';
				
				do {
					
					$style = $row_bos['brewCategory'].$row_bos['brewSubCategory'];
				
					$html .= '<tr>';
					$html .= '<td width="35" nowrap="nowrap">'.display_place($row_bos['scorePlace'],1).'</td>';
					$html .= '<td width="150">'.$row_bos['brewerFirstName'].' '.$row_bos['brewerLastName'];
					if ($row_entries['brewCoBrewer'] != "") $html .=', '.$row_bos['brewCoBrewer'];
					$html .= '</td>';
					$html .= '<td width="200">'.strtr($row_bos['brewName'],$html_remove).'</td>';
					$html .= '<td width="200">'.$style.': '.$row_bos['brewStyle'].'</td>';
					$html .= '<td width="175">';
					if ($row_bos['brewerClubs'] != "") $html .=strtr($row_bos['brewerClubs'],$html_remove);
					else $html .= "&nbsp;";
					$html .= '</td>';
					$html .= '</tr>';
					
					
				} while ($row_bos = mysql_fetch_assoc($bos)); 
				
				
				mysql_free_result($bos);
				$html .= '</table>';
				
			} // end if ($totalRows_bos > 0)
			
		} // end foreach (array_unique($a) as $type)
		
		if ($totalRows_sbi > 0) {
			
			do {
				
				include(DB.'output_results_download_sbd.db.php');
				
				$html .= '<br><br><strong>'.strtr($row_sbi['sbi_name'],$html_remove).'</strong>';
				$html .= '<br>'.strtr($row_sbi['sbi_description'],$html_remove).'<br>';
				$html .= '<table border="1">';
				$html .= '<tr>';
				if ($row_sbi['sbi_display_places'] == "1") $html .= '<td width="35" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>Place</strong></td>';
				$html .= '<td width="150" align="center" bgcolor="#cccccc"><strong>Brewer(s)</strong></td>';
				$html .= '<td width="200" align="center" bgcolor="#cccccc"><strong>Entry Name</strong></td>';
				$html .= '<td width="200" align="center" bgcolor="#cccccc"><strong>Style</strong></td>';
				$html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>Club</strong></td>';
				$html .= '</tr>';
				
				do { 
					$brewer_info = explode("^",brewer_info($row_sbd['bid']));
					$entry_info = explode("^",entry_info($row_sbd['eid']));
					$style = $entry_info['5'].$entry_info['2'];
					$html .= '<tr>';
					if ($row_sbi['sbi_display_places'] == "1") { $html .= '<td width="35" nowrap="nowrap">'.display_place($row_sbd['sbd_place'],4).'</td>'; }
					$html .= '<td width="150">'.$brewer_info['0']." ".$brewer_info['1']; 
						if ($row_entries['brewCoBrewer'] != "") $html .= "<br />Co-Brewer: ".$entry_info['4']; 
					$html .= '</td>';
					$html .= '<td width="200">'.strtr($entry_info['0'],$html_remove).'</td>';
					$html .= '<td width="200">'.$style.": ".$entry_info['3'].'</td>';
					$html .= '<td width="175">';
					if ($brewer_info['7'] != "") $html .=strtr($brewer_info['8'],$html_remove);
					else $html .= "&nbsp;";
					$html .= '</td>';   
					$html .= '</tr>';
					if ($row_sbd['sbd_comments'] != "") {
						if ($row_sbi['sbi_display_places'] == "1") $html .= '<td width="760" colspan="5"><em>'.$row_sbd['sbd_comments'].'</em></td>';
						else $html .= '<td width="725" colspan="4"><em>'.$row_sbd['sbd_comments'].'</em></td>';
						$html .= '</tr>';
					}
				} while ($row_sbd = mysql_fetch_assoc($sbd));
				
				$html .= '</table>';
				
			} while ($row_sbi = mysql_fetch_assoc($sbi));
			
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
		header("Content-Disposition: attachment; filename=".$filename);
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $header.$html.$footer;
		exit();
	}
		
} else echo "<p>Not available.</p>";
?>
