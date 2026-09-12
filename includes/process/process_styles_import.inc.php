<?php
/*
 * Module:      process_styles_import.inc.php
 * Description: Confirm+insert and whole-set delete for the Admin-Uploaded
 *              Style Sets feature. Phase 1 (upload/parse/validate) lives
 *              in admin/styles_import.admin.php and stages its result in
 *              $_SESSION['styles_import_staged']; this file only ever
 *              acts on that already-validated payload.
 */

if ((isset($_SERVER['HTTP_REFERER'])) && (isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] == 0)) {

	$styles_db_table = $prefix."styles";
	$style_sets_imported_db_table = $prefix."style_sets_imported";

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if ($action == "styles_import") {

		if ((!isset($_SESSION['styles_import_staged'])) || (!isset($_SESSION['styles_import_staged_at'])) || ((time() - $_SESSION['styles_import_staged_at']) >= 1800)) {

			unset($_SESSION['styles_import_staged']);
			unset($_SESSION['styles_import_staged_at']);
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=40");

		}

		else {

			$staged = json_decode($_SESSION['styles_import_staged'], true);
			$meta = $staged['meta'];

			// Defensive re-check - another tab could have imported/renamed a
			// colliding set between phase 1 (validate) and this confirm step.
			require (INCLUDES.'styles.inc.php');
			$name_taken = FALSE;
			foreach ($style_sets as $existing_set) {
				if ((!empty($existing_set['style_set_name'])) && (strcasecmp($existing_set['style_set_name'], $meta['style_set_name']) === 0)) {
					$name_taken = TRUE;
					break;
				}
			}

			if ($name_taken) {
				unset($_SESSION['styles_import_staged']);
				unset($_SESSION['styles_import_staged_at']);
				$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=41");
			}

			else {

				$include_row = array();
				if ((isset($_POST['include_row'])) && (is_array($_POST['include_row']))) {
					foreach ($_POST['include_row'] as $idx) $include_row[(int)$idx] = TRUE;
				}

				require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
				$config_html_purifier = HTMLPurifier_Config::createDefault();
				$purifier = new HTMLPurifier($config_html_purifier);

				$rows_to_insert = array();
				$derived_categories = array();
				$derived_overall_categories = array();
				$derived_mead = array();
				$derived_cider = array();

				foreach ($staged['rows'] as $idx => $r) {

					if ((!$r['valid']) || (!isset($include_row[$idx]))) continue;

					$rows_to_insert[] = array(
						'brewStyle' => blank_to_null($purifier->purify(sterilize($r['brewStyle']))),
						'brewStyleOG' => blank_to_null(sterilize($r['brewStyleOG'])),
						'brewStyleOGMax' => blank_to_null(sterilize($r['brewStyleOGMax'])),
						'brewStyleFG' => blank_to_null(sterilize($r['brewStyleFG'])),
						'brewStyleFGMax' => blank_to_null(sterilize($r['brewStyleFGMax'])),
						'brewStyleABV' => blank_to_null(sterilize($r['brewStyleABV'])),
						'brewStyleABVMax' => blank_to_null(sterilize($r['brewStyleABVMax'])),
						'brewStyleIBU' => blank_to_null(sterilize($r['brewStyleIBU'])),
						'brewStyleIBUMax' => blank_to_null(sterilize($r['brewStyleIBUMax'])),
						'brewStyleSRM' => blank_to_null(sterilize($r['brewStyleSRM'])),
						'brewStyleSRMMax' => blank_to_null(sterilize($r['brewStyleSRMMax'])),
						'brewStyleType' => blank_to_null(sterilize($r['brewStyleType'])),
						'brewStyleInfo' => blank_to_null($purifier->purify(sterilize($r['brewStyleInfo']))),
						'brewStyleLink' => blank_to_null(sterilize($r['brewStyleLink'])),
						'brewStyleGroup' => blank_to_null(sterilize($r['brewStyleGroup'])),
						'brewStyleNum' => blank_to_null(sterilize($r['brewStyleNum'])),
						'brewStyleActive' => 'Y',
						'brewStyleOwn' => 'imported',
						'brewStyleVersion' => $meta['style_set_name'],
						'brewStyleReqSpec' => (int)$r['brewStyleReqSpec'],
						'brewStyleStrength' => (int)$r['brewStyleStrength'],
						'brewStyleCarb' => (int)$r['brewStyleCarb'],
						'brewStyleSweet' => (int)$r['brewStyleSweet'],
						'brewStyleEntry' => blank_to_null($purifier->purify(sterilize($r['brewStyleEntry'])))
					);

					if ((!empty($r['brewStyleGroup'])) && (!empty($r['brewStyleCategory'])) && (!isset($derived_categories[$r['brewStyleGroup']]))) {
						$derived_categories[$r['brewStyleGroup']] = $r['brewStyleCategory'];
					}

					if ((!empty($r['brewStyleGroup'])) && (!empty($r['brewStyleOverallCategory'])) && (!isset($derived_overall_categories[$r['brewStyleGroup']]))) {
						$derived_overall_categories[$r['brewStyleGroup']] = $r['brewStyleOverallCategory'];
					}

					$type_name_lc = strtolower(trim((string)($r['style_type_name'] ?? '')));
					if (($type_name_lc == "mead") && (!empty($r['brewStyleGroup'])) && (!in_array($r['brewStyleGroup'], $derived_mead))) $derived_mead[] = $r['brewStyleGroup'];
					if (($type_name_lc == "cider") && (!empty($r['brewStyleGroup'])) && (!in_array($r['brewStyleGroup'], $derived_cider))) $derived_cider[] = $r['brewStyleGroup'];

				}

				if (empty($rows_to_insert)) {
					$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=41");
				}

				else {

					/**
					 * {prefix}style_sets_imported is InnoDB (the only InnoDB
					 * table in this schema) so startTransaction()/commit()/
					 * rollback() give real atomicity for its one metadata
					 * row. {prefix}styles itself stays MyISAM (matching
					 * every other table in this schema) which has NO
					 * transaction support - MysqliDb's transaction calls are
					 * silent no-ops against it - so a failure partway
					 * through the styles insert loop below needs a manual
					 * compensating delete of whatever rows DID make it in,
					 * rather than relying on rollback() to undo them. Do
					 * not copy startTransaction()/commit()/rollback() alone
					 * as "this insert is now atomic" anywhere else in this
					 * app that touches {prefix}styles - it silently isn't.
					 */
					$inserted_style_ids = array();
					$insert_failed = FALSE;

					$db_conn->startTransaction();

					$set_metadata = array(
						'style_set_name' => $meta['style_set_name'],
						'style_set_long_name' => blank_to_null($meta['style_set_long_name']),
						'style_set_short_name' => blank_to_null($meta['style_set_short_name']),
						'style_set_display_separator' => blank_to_null($meta['style_set_display_separator']),
						'style_set_system_separator' => '-',
						'style_set_sub_style_method' => $meta['style_set_sub_style_method'],
						'style_set_categories' => json_encode($derived_categories),
						'style_set_overall_categories' => json_encode($derived_overall_categories),
						'style_set_beer_end' => $meta['style_set_beer_end'],
						'style_set_mead' => json_encode($derived_mead),
						'style_set_cider' => json_encode($derived_cider),
						'style_set_category_end' => $meta['style_set_category_end'],
						'style_set_no_numbering' => !empty($meta['style_set_no_numbering']) ? 1 : 0,
						'createdBy' => $_SESSION['loginUsername'],
						'createdOn' => time()
					);

					$metadata_id = $db_conn->insert($style_sets_imported_db_table, $set_metadata);
					if (!$metadata_id) $insert_failed = TRUE;

					if (!$insert_failed) {
						foreach ($rows_to_insert as $row_to_insert) {
							$new_id = $db_conn->insert($styles_db_table, $row_to_insert);
							if (!$new_id) { $insert_failed = TRUE; break; }
							$inserted_style_ids[] = $new_id;
						}
					}

					if ($insert_failed) {

						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;

						$db_conn->rollback();

						if (!empty($inserted_style_ids)) {
							$db_conn->where('id', $inserted_style_ids, 'IN');
							$db_conn->delete($styles_db_table);
						}

						if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
						$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=41");

					}

					else {

						$db_conn->commit();

						unset($_SESSION['styles_import_staged']);
						unset($_SESSION['styles_import_staged_at']);

						$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=39");

					}

				}

			}

		}

	} // end if ($action == "styles_import")

	if ($action == "styles_import_delete") {

		$db_conn->where('id', $id);
		$row_imported_set = $db_conn->getOne($style_sets_imported_db_table, "id,style_set_name");

		if (!$row_imported_set) {
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=41");
		}

		elseif ($row_imported_set['style_set_name'] == $_SESSION['prefsStyleSet']) {
			// Never trust the disabled delete button alone - re-verify server-side.
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=41");
		}

		else {

			// {prefix}styles is MyISAM (no transaction support) - delete it
			// first and only proceed to the InnoDB metadata row if that
			// succeeds, same ordering reasoning as the insert path above.
			$db_conn->where('brewStyleVersion', $row_imported_set['style_set_name']);
			$db_conn->where('brewStyleOwn', 'imported');
			$styles_delete_result = $db_conn->delete($styles_db_table);

			if (!$styles_delete_result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
				if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
				$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=41");
			}

			else {

				$db_conn->where('id', $row_imported_set['id']);
				$result = $db_conn->delete($style_sets_imported_db_table);

				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
					if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
					$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=41");
				}
				else $redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=42");

			}

		}

	} // end if ($action == "styles_import_delete")

	if ($action == "styles_import_edit") {

		require_once (LIB.'styles_import.lib.php');

		$db_conn->where('id', $id);
		$row_imported_set = $db_conn->getOne($style_sets_imported_db_table);

		if (!$row_imported_set) {
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=41");
		}

		else {

			$old_name = $row_imported_set['style_set_name'];

			$meta = array(
				'style_set_name' => sterilize($_POST['style_set_name'] ?? ''),
				'style_set_long_name' => sterilize($_POST['style_set_long_name'] ?? ''),
				'style_set_short_name' => sterilize($_POST['style_set_short_name'] ?? ''),
				'style_set_display_separator' => sterilize($_POST['style_set_display_separator'] ?? ''),
				'style_set_sub_style_method' => sterilize($_POST['style_set_sub_style_method'] ?? '0'),
				'style_set_beer_end' => sterilize($_POST['style_set_beer_end'] ?? ''),
				'style_set_category_end' => sterilize($_POST['style_set_category_end'] ?? ''),
				'style_set_no_numbering' => (isset($_POST['style_set_no_numbering']) && ($_POST['style_set_no_numbering'] == "1"))
			);

			// Defensive re-check against live data, same reasoning as the confirm
			// step above - exclude this set's own current name so leaving it
			// unchanged (or renaming only by case) doesn't trip "already exists".
			require (INCLUDES.'styles.inc.php');
			$errors_meta = styles_import_validate_meta($meta, $style_sets, $old_name);

			if (!empty($errors_meta)) {
				$_SESSION['styles_import_edit_errors'] = $errors_meta;
				$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&action=edit&id=".$row_imported_set['id']);
			}

			else {

				$new_name = $meta['style_set_name'];

				$db_conn->where('id', $row_imported_set['id']);
				$result = $db_conn->update($style_sets_imported_db_table, array(
					'style_set_name' => $new_name,
					'style_set_long_name' => blank_to_null($meta['style_set_long_name']),
					'style_set_short_name' => blank_to_null($meta['style_set_short_name']),
					'style_set_display_separator' => blank_to_null($meta['style_set_display_separator']),
					'style_set_sub_style_method' => $meta['style_set_sub_style_method'],
					'style_set_beer_end' => $meta['style_set_beer_end'],
					'style_set_category_end' => $meta['style_set_category_end'],
					'style_set_no_numbering' => !empty($meta['style_set_no_numbering']) ? 1 : 0
				));

				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
					if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
					$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&action=edit&id=".$row_imported_set['id']);
				}

				else {

					// The set's identifier changed - cascade it everywhere else
					// that stores the name as a plain string, or every style/
					// preference/archive still pointing at the old name silently
					// stops matching. {prefix}styles rows are scoped to
					// brewStyleOwn='imported' only - brewStyleVersion on
					// brewStyleOwn='custom' rows is an unrelated, independently
					// managed "which set was active when this custom style was
					// last touched" snapshot, not a pointer to this set.
					if ($new_name !== $old_name) {

						$db_conn->where('brewStyleVersion', $old_name);
						$db_conn->where('brewStyleOwn', 'imported');
						$db_conn->update($styles_db_table, array('brewStyleVersion' => $new_name));

						$db_conn->where('id', 1);
						$row_prefs = $db_conn->getOne($prefix."preferences", "prefsStyleSet,prefsSelectedStyles");

						if ($row_prefs) {

							if ($row_prefs['prefsStyleSet'] == $old_name) {
								$db_conn->where('id', 1);
								$db_conn->update($prefix."preferences", array('prefsStyleSet' => $new_name));
							}

							$selected_styles = json_decode($row_prefs['prefsSelectedStyles'], true);
							if (is_array($selected_styles)) {
								$selected_styles_changed = FALSE;
								foreach ($selected_styles as $selected_id => $selected_style) {
									if ((isset($selected_style['brewStyleVersion'])) && ($selected_style['brewStyleVersion'] === $old_name)) {
										$selected_styles[$selected_id]['brewStyleVersion'] = $new_name;
										$selected_styles_changed = TRUE;
									}
								}
								if ($selected_styles_changed) {
									$db_conn->where('id', 1);
									$db_conn->update($prefix."preferences", array('prefsSelectedStyles' => json_encode($selected_styles)));
								}
							}

						}

						if (table_exists($prefix."archive")) {
							$db_conn->where('archiveStyleSet', $old_name);
							$db_conn->update($prefix."archive", array('archiveStyleSet' => $new_name));
						}

					}

					$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=styles_import&msg=45");

				}

			}

		}

	} // end if ($action == "styles_import_edit")

}

else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>
