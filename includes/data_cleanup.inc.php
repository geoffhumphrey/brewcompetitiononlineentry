<?php
require('../paths.php');
require(INCLUDES.'url_variables.inc.php');
require(LIB.'common.lib.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == "0")) {
	
	// User initiated purging of data 
	
	if ($action == "purge") {
		
		// Purge unconfirmed and/or entries that require special ingredients that do not have special ingredient data
		if ($go == "unconfirmed") {
			
			purge_entries("unconfirmed", 0);
			purge_entries("special", 0); 
			header(sprintf("Location: %s", $base_url."index.php?section=admin&go=entries&purge=true"));
			
		}
		
		// Purge entry and associated data
		if (($go == "entries") || ($go == "purge-all")) {
			
			// Purge all data from brewing table
			$updateSQL1 = sprintf("TRUNCATE %s",$brewing_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
			
			// Purge all data from judging_scores table
			$updateSQL1 = sprintf("TRUNCATE %s",$judging_scores_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
			
			// Purge all data from judging_scores bos table
			$updateSQL1 = sprintf("TRUNCATE %s",$judging_scores_bos_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
			
			// Purge all data from special_best_data table
			$updateSQL1 = sprintf("TRUNCATE %s",$special_best_data_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
			
			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));
			
		}
		
		// Purge participant and associated data
		if (($go == "participants") || ($go == "purge-all")) {
			
			// Purge all data from brewer and users tables (except admins)
			
			$query_admin = sprintf("SELECT id,user_name FROM %s WHERE userLevel = '2' ORDER BY id", $users_db_table);
			$admin = mysql_query($query_admin, $brewing) or die(mysql_error());
			$row_admin = mysql_fetch_assoc($admin);
			$totalRows_admin = mysql_num_rows($admin);
			
			do {
				
				$updateSQL1 = sprintf("DELETE FROM %s WHERE uid='%s'", $brewer_db_table, $row_admin['id']);
				mysql_real_escape_string($updateSQL1); 
				$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
				
				$updateSQL1 = sprintf("DELETE FROM %s WHERE id='%s'", $users_db_table, $row_admin['id']);
				mysql_real_escape_string($updateSQL1); 
				$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
				
			} while ($row_admin = mysql_fetch_assoc($admin));
			
			
			// Purge all data from judging_assignments table
			$updateSQL1 = sprintf("TRUNCATE %s",$judging_assignments_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
			
			// Purge all data from staff table
			$updateSQL1 = sprintf("TRUNCATE %s",$staff_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
			
			
			// Clean up any strays
			$query_strays = sprintf("SELECT id,uid FROM %s", $brewer_db_table);
			$strays = mysql_query($query_strays, $brewing) or die(mysql_error());
			$row_strays = mysql_fetch_assoc($strays);
			
			do {
				$query_stray = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE id='%s'", $users_db_table, $row_strays['id']);
				$stray = mysql_query($query_stray, $brewing) or die(mysql_error());
				$row_stray = mysql_fetch_assoc($stray);
				
				if ($row_stray['count'] == 0) {
					$updateSQL1 = sprintf("DELETE FROM %s WHERE id='%s'", $brewer_db_table, $row_strays['id']);
					mysql_real_escape_string($updateSQL1); 
					$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
				}
				
				
			} while ($row_strays = mysql_fetch_assoc($strays));
			
			
			// Clean up any strays
			$query_strays2 = sprintf("SELECT id FROM %s", $users_db_table);
			$strays2 = mysql_query($query_strays2, $brewing) or die(mysql_error());
			$row_strays2 = mysql_fetch_assoc($strays2);
			
			do {
				$query_stray2 = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE uid='%s'", $brewer_db_table, $row_strays2['id']);
				$stray2 = mysql_query($query_stray2, $brewing) or die(mysql_error());
				$row_stray2 = mysql_fetch_assoc($stray2);
				
				if ($row_stray2['count'] == 0) {
					$updateSQL1 = sprintf("DELETE FROM %s WHERE id='%s'", $user_db_table, $row_strays2['id']);
					mysql_real_escape_string($updateSQL1); 
					$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
				}
				
				
			} while ($row_strays2 = mysql_fetch_assoc($strays2));
			
			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));
			
		}
		
		// Purge scores and associated data
		if (($go == "scores") || ($go == "purge-all")) {
			
			// Purge all data from judging_scores table
			$updateSQL1 = sprintf("TRUNCATE %s",$judging_scores_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
			
			// Purge all data from judging_scores bos table
			$updateSQL1 = sprintf("TRUNCATE %s",$judging_scores_bos_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
			
			// Purge all daa from special_best_data table
			$updateSQL1 = sprintf("TRUNCATE %s",$special_best_data_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
			
			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));
			
		}
		
		// Purge judging tables and associated data
		if (($go == "tables") || ($go == "purge-all")) {
			
			// Purge all data from judging_tables table
			$updateSQL1 = sprintf("TRUNCATE %s",$judging_tables_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
			
			// Purge all data from judging_assignments table
			$updateSQL1 = sprintf("TRUNCATE %s",$judging_assignments_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());		
			
			// Purge all data from judging_flights table
			$updateSQL1 = sprintf("TRUNCATE %s",$judging_flights_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
			
			// Purge all data from judging_scores table
			$updateSQL1 = sprintf("TRUNCATE %s",$judging_scores_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
			
			// Purge all data from judging_scores_bos table
			$updateSQL1 = sprintf("TRUNCATE %s",$judging_scores_bos_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());	
			
			// Purge all daa from special_best_data table
			$updateSQL1 = sprintf("TRUNCATE %s",$special_best_data_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
			
			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));
			
		}
		
		if (($go == "custom") || ($go == "purge-all")) {
			
			// Purge all data from special best info table
			$updateSQL1 = sprintf("TRUNCATE %s",$special_best_info_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
			
			// Purge all daa from special_best_data table
			$updateSQL1 = sprintf("TRUNCATE %s",$special_best_data_db_table);
			mysql_real_escape_string($updateSQL1);
			$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
			
			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));
			
		}
		
		
		
	}
	
	// User initiated triggering of data integrity check
	
	if ($action == "cleanup") {
		data_integrity_check();
		header(sprintf("Location: %s", $base_url."index.php?section=admin&purge=cleanup"));
	}

} else echo "<p>Not available.</p>";

?>