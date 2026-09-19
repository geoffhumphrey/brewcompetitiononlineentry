<?php
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 0))) {
    if (function_exists('redirect_or_exit')) redirect_or_exit("../../403.php");
    else { header("Location: ../../403.php"); exit(); }
}

require (INCLUDES.'styles.inc.php');
require_once (LIB.'styles_import.lib.php');

function is_valid_styles_import_token() {
    return (
        isset($_POST['user_session_token']) &&
        isset($_SESSION['user_session_token']) &&
        (hash_equals($_SESSION['user_session_token'], $_POST['user_session_token']))
    );
}

// Extension allow-list (content is parsed, never stored to disk) plus the
// same dangerous-extension deny-list handle.php already checks uploads
// against, kept here as defense-in-depth even though our allow-list alone
// already excludes every deny-listed extension.
$styles_import_ext_allowed = array('.json', '.csv');
$styles_import_ext_denied = array('php', 'php3', 'php4', 'phtml', 'exe');
$styles_import_max_size = 5000000; // 5 MB - style set text data, not media

$styles_import_report = null;
$styles_import_upload_error = "";

/**
 * Phase 1 - upload, parse, validate. Self-posts to this same page (mirrors
 * admin/hero_images.admin.php's self-post + is_valid_*_token() pattern,
 * not the central process.inc.php dispatcher, since this step needs to
 * render a validation report inline rather than redirect).
 */
if (($_SERVER['REQUEST_METHOD'] == "POST") && (isset($_POST['styles_import_upload']))) {

    if (!is_valid_styles_import_token()) {
        $styles_import_upload_error = "Security token validation failed. Please refresh and try again.";
    }

    else {

        if ((!isset($_FILES['styles_import_file'])) || (!is_array($_FILES['styles_import_file'])) || (!isset($_FILES['styles_import_file']['error']))) {
            $styles_import_upload_error = "Please choose a file to upload.";
        }

        elseif ($_FILES['styles_import_file']['error'] !== UPLOAD_ERR_OK) {
            $styles_import_upload_error = "The file upload failed. Please try again.";
        }

        elseif ($_FILES['styles_import_file']['size'] > $styles_import_max_size) {
            $styles_import_upload_error = "The uploaded file is too large. Maximum size is 5 MB.";
        }

        else {

            $orig_name = $_FILES['styles_import_file']['name'];
            $file_extension_array = explode('.', $orig_name);
            $file_extension_bare = strtolower(end($file_extension_array));
            $file_extension = "." . $file_extension_bare;

            // Server-side MIME detection, never trust $_FILES[...]['type'] -
            // matches handle.php's own reasoning (client-reported types are
            // unreliable across browsers/OSes).
            $file_mime = mime_content_type($_FILES['styles_import_file']['tmp_name']);

            if (in_array($file_extension_bare, $styles_import_ext_denied)) {
                $styles_import_upload_error = "That file type is not allowed.";
            }

            elseif (!in_array($file_extension, $styles_import_ext_allowed)) {
                $styles_import_upload_error = "Please upload a .json or .csv file.";
            }

            elseif (($file_extension == ".json") && (stripos($file_mime, "text") === FALSE) && (stripos($file_mime, "json") === FALSE)) {
                $styles_import_upload_error = "The uploaded file does not appear to be a valid JSON file.";
            }

            elseif (($file_extension == ".csv") && (stripos($file_mime, "text") === FALSE) && (stripos($file_mime, "csv") === FALSE)) {
                $styles_import_upload_error = "The uploaded file does not appear to be a valid CSV file.";
            }

            else {

                $file_contents = file_get_contents($_FILES['styles_import_file']['tmp_name']);

                if ($file_extension == ".json") {
                    $parsed = styles_import_parse_json($file_contents);
                }
                else {
                    $companion_meta = array(
                        'style_set_name' => sterilize($_POST['style_set_name'] ?? ''),
                        'style_set_long_name' => sterilize($_POST['style_set_long_name'] ?? ''),
                        'style_set_short_name' => sterilize($_POST['style_set_short_name'] ?? ''),
                        'style_set_display_separator' => sterilize($_POST['style_set_display_separator'] ?? ''),
                        'style_set_sub_style_method' => sterilize($_POST['style_set_sub_style_method'] ?? '0'),
                        'style_set_beer_end' => sterilize($_POST['style_set_beer_end'] ?? ''),
                        'style_set_category_end' => sterilize($_POST['style_set_category_end'] ?? ''),
                        'style_set_no_numbering' => (isset($_POST['style_set_no_numbering']) && ($_POST['style_set_no_numbering'] == "1"))
                    );
                    $parsed = styles_import_parse_csv($file_contents, $companion_meta);
                }

                if (!$parsed['ok']) {
                    $styles_import_upload_error = $parsed['error'];
                }

                else {

                    $styles_import_report = styles_import_validate($parsed['meta'], $parsed['rows'], $style_sets, $prefix, $db_conn);

                    // Only persist to session (surviving navigation, for up to 30
                    // minutes) when there's actually a Confirm Import step to come
                    // back to. A report that's all meta errors, or where every row
                    // failed, has no such step - staging it anyway made its error
                    // banner reappear every time the admin revisited this page
                    // until the 30-minute window happened to lapse, with no way
                    // to dismiss it sooner. Render it once for this response only.
                    $has_valid_rows = FALSE;
                    if (empty($styles_import_report['meta_errors'])) {
                        foreach ($styles_import_report['rows'] as $r) { if ($r['valid']) { $has_valid_rows = TRUE; break; } }
                    }

                    if ($has_valid_rows) {
                        $_SESSION['styles_import_staged'] = json_encode($styles_import_report);
                        $_SESSION['styles_import_staged_at'] = time();
                    }
                    else {
                        unset($_SESSION['styles_import_staged']);
                        unset($_SESSION['styles_import_staged_at']);
                    }

                    $staged_report_immediate = $styles_import_report;

                }

            }

        }

    }

}

/**
 * Normalizes an uploaded JSON style set into the common row shape.
 */
function styles_import_parse_json($file_contents) {

    $decoded = json_decode($file_contents, true);

    if ((json_last_error() !== JSON_ERROR_NONE) || (!is_array($decoded))) {
        return array('ok' => FALSE, 'error' => "The uploaded JSON file could not be parsed: " . json_last_error_msg());
    }

    $meta = array(
        'style_set_name' => sterilize($decoded['style_set_name'] ?? ''),
        'style_set_long_name' => sterilize($decoded['style_set_long_name'] ?? ''),
        'style_set_short_name' => sterilize($decoded['style_set_short_name'] ?? ''),
        'style_set_display_separator' => sterilize($decoded['style_set_display_separator'] ?? ''),
        'style_set_sub_style_method' => sterilize($decoded['style_set_sub_style_method'] ?? '0'),
        'style_set_beer_end' => sterilize($decoded['style_set_beer_end'] ?? ''),
        'style_set_category_end' => sterilize($decoded['style_set_category_end'] ?? ''),
        'style_set_no_numbering' => !empty($decoded['style_set_no_numbering'])
    );

    if ((!isset($decoded['styles'])) || (!is_array($decoded['styles'])) || (empty($decoded['styles']))) {
        return array('ok' => FALSE, 'error' => "The uploaded JSON file has no \"styles\" array (or it's empty). Nothing to import.");
    }

    $rows = array();
    foreach ($decoded['styles'] as $row) {
        if (is_array($row)) $rows[] = $row;
    }

    if (empty($rows)) {
        return array('ok' => FALSE, 'error' => "The uploaded JSON file's \"styles\" array doesn't contain any valid style entries.");
    }

    return array('ok' => TRUE, 'meta' => $meta, 'rows' => $rows);

}

/**
 * Normalizes an uploaded CSV style set (+ companion form metadata) into
 * the common row shape. Header-row driven, order-independent.
 */
function styles_import_parse_csv($file_contents, $companion_meta) {

    // Strip a leading UTF-8 BOM (the export feature writes one, matching this
    // app's other CSV exports, and some spreadsheet software adds one too) -
    // left in place, it prefixes the first line's first character, which
    // broke the "starts with #" comment-line check below for a line that
    // otherwise looked identical to every other comment line.
    if (substr($file_contents, 0, 3) === "\xEF\xBB\xBF") $file_contents = substr($file_contents, 3);

    $lines = preg_split('/\r\n|\r|\n/', trim($file_contents));
    if (count($lines) < 2) {
        return array('ok' => FALSE, 'error' => "The uploaded CSV file has no data rows.");
    }

    $handle = fopen('php://temp', 'r+');
    fwrite($handle, $file_contents);
    rewind($handle);

    // Skip leading "#" comment lines - the style-set export feature emits the
    // set-level metadata (which this CSV format doesn't otherwise carry) as
    // comment lines for the admin to read/copy, so an exported file can be
    // re-uploaded as-is without hand-editing.
    $header = fgetcsv($handle);
    while (($header !== FALSE) && (isset($header[0])) && (strpos(trim((string)$header[0]), '#') === 0)) {
        $header = fgetcsv($handle);
    }

    if (!$header) {
        fclose($handle);
        return array('ok' => FALSE, 'error' => "The uploaded CSV file's header row could not be read.");
    }

    $header = array_map('trim', $header);

    // Catch a wrong/unrelated CSV outright with one clear message, rather
    // than silently processing it into rows that are all missing every
    // required column - which would otherwise surface as a wall of
    // identical per-row errors instead of one obvious cause.
    $required_columns = array('brewStyleGroup', 'brewStyleNum', 'brewStyle', 'brewStyleCategory', 'style_type');
    $missing_columns = array_diff($required_columns, $header);
    if (!empty($missing_columns)) {
        fclose($handle);
        return array('ok' => FALSE, 'error' => "The uploaded CSV file is missing required column(s): " . implode(", ", $missing_columns) . ".");
    }

    $rows = array();
    while (($csv_row = fgetcsv($handle)) !== FALSE) {
        if ((count($csv_row) == 1) && (trim((string)$csv_row[0]) === '')) continue; // skip blank lines
        $row = array();
        foreach ($header as $i => $col_name) {
            $row[$col_name] = $csv_row[$i] ?? '';
        }
        $rows[] = $row;
    }

    fclose($handle);

    return array('ok' => TRUE, 'meta' => $companion_meta, 'rows' => $rows);

}

// Existing imported sets, for the management list. Guarded by
// table_exists() - a site that hasn't yet run the 3.2.0 update.php
// migration won't have this table, and MysqliDb throws on a query
// against a table that doesn't exist rather than returning false.
$rows_imported_sets = array();
if (table_exists($prefix."style_sets_imported")) {
    $db_conn->orderBy("style_set_long_name", "ASC");
    $rows_imported_sets = $db_conn->get($prefix."style_sets_imported");
    if (!$rows_imported_sets) $rows_imported_sets = array();
}

// Built-in sets, for the export-only list below - every $style_sets entry
// that ISN'T one of the imported sets just queried above. No hardcoded
// list of built-in names to maintain: this stays correct automatically if
// a built-in set is ever added to or retired from includes/styles.inc.php.
$imported_set_names = array_column($rows_imported_sets, 'style_set_name');
$builtin_sets = array();
foreach ($style_sets as $set_entry) {
    if (empty($set_entry['style_set_name'])) continue;
    if (in_array($set_entry['style_set_name'], $imported_set_names)) continue;
    $builtin_sets[] = $set_entry;
}

// Edit mode - ?action=edit&id=X shows a prefilled metadata-edit form in
// place of the upload form below, instead of a developer having to fix a
// misspelled name/etc directly in the database. Metadata only (name,
// long/short name, display separator, sub-style method, beer/category end,
// no numbering) - the per-style category and overall-category maps are
// derived from the imported rows themselves and aren't edited here.
$editing_set = null;
if (($action == "edit") && (table_exists($prefix."style_sets_imported"))) {
    $db_conn->where('id', $id);
    $editing_set = $db_conn->getOne($prefix."style_sets_imported");
}

// Prefer the report just computed above (covers the meta-errors / all-rows-
// invalid cases, which are deliberately never written to session - see the
// upload handler above) over whatever's staged in session, so this same
// response still shows it once even though it won't persist past this load.
if (isset($staged_report_immediate)) {
    $staged_report = $staged_report_immediate;
}
else {
    $staged_report = null;
    if ((isset($_SESSION['styles_import_staged'])) && (isset($_SESSION['styles_import_staged_at'])) && ((time() - $_SESSION['styles_import_staged_at']) < 1800)) {
        $staged_report = json_decode($_SESSION['styles_import_staged'], true);
    }
}

// Whether the validation report below actually has a Confirm Import path -
// false for the meta-errors case and the all-rows-invalid case, both of
// which render a report with no way forward. The upload button/form is
// hidden whenever $staged_report isn't null, so without this an admin who
// uploaded the wrong file (duplicate name, malformed file, etc.) has no
// visible way to try again short of navigating away and back.
$staged_report_confirmable = FALSE;
if (($staged_report !== null) && (empty($staged_report['meta_errors']))) {
    foreach ($staged_report['rows'] as $r) { if ($r['valid']) { $staged_report_confirmable = TRUE; break; } }
}

?>
<?php if (!empty($styles_import_upload_error)) { ?>
<div class="alert alert-danger"><?php echo h($styles_import_upload_error); ?></div>
<?php } ?>
<?php if ($editing_set === null) { ?>
<h3>Existing Imported Sets</h3>
<?php if (empty($rows_imported_sets)) { ?>
<p>No style sets have been imported yet.</p>
<?php } else { ?>
<p>Your currently selected competition style set is <?php echo $_SESSION['prefsStyleSet']; ?>. To select an imported set below as your competition's official style set, go to <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences&amp;action=entries">Entry Preferences</a>.</p>
<table class="table table-bordered table-striped" id="sortable-imported-sets">
<thead>
    <tr>
        <th width="25%">Name</th>
        <th width="40%">Long Name</th>
        <th width="20%">Styles</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
<?php foreach ($rows_imported_sets as $row_imported_set) {
    $is_active = ($row_imported_set['style_set_name'] == $_SESSION['prefsStyleSet']);
    $imported_set_row_count = count(style_set_export_rows($row_imported_set['style_set_name'], $prefix, $db_conn));
?>
<tr>
    <td><?php echo h($row_imported_set['style_set_name']); ?></td>
    <td><?php echo h($row_imported_set['style_set_long_name']); ?></td>
    <td><?php echo $imported_set_row_count; ?></td>
    <td>
    <a class="hide-loader" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles_import&amp;action=edit&amp;id=<?php echo $row_imported_set['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit the <?php echo h($row_imported_set['style_set_name']); ?> style set parameters. To edit individual styles, go to the Admin Dashboard > Manage Styles Accepted screen."><span class="fa fa-lg fa-fw fa-pencil"></span></a>
    <a target="_blank" href="<?php echo $base_url; ?>includes/output.inc.php?section=export-styles&amp;go=json&amp;filter=<?php echo urlencode($row_imported_set['style_set_name']); ?>" data-toggle="tooltip" data-placement="top" title="Export <?php echo h($row_imported_set['style_set_name']); ?> as JSON"><span class="fa fa-lg fa-fw fa-file-code"></span></a>
    <a target="_blank" href="<?php echo $base_url; ?>includes/output.inc.php?section=export-styles&amp;go=csv&amp;filter=<?php echo urlencode($row_imported_set['style_set_name']); ?>" data-toggle="tooltip" data-placement="top" title="Export <?php echo h($row_imported_set['style_set_name']); ?> as CSV"><span class="fa fa-lg fa-fw fa-file-excel"></span></a>
    <?php if ($is_active) { ?>
        <span class="fa fa-lg fa-trash-o text-muted" data-toggle="tooltip" data-placement="top" title="The currently active style set cannot be deleted - switch to a different set first."></span>
    <?php } else { ?>
        <a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=styles_import&amp;action=styles_import_delete&amp;id=<?php echo $row_imported_set['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete <?php echo h($row_imported_set['style_set_name']); ?>" data-confirm="Are you sure you want to delete the imported style set &quot;<?php echo h($row_imported_set['style_set_name']); ?>&quot;? This will remove all of its styles. This cannot be undone."><span class="fa fa-lg fa-fw fa-trash-o"></span></a>
    <?php } ?>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
<?php if (!$staged_report_confirmable) { ?>
<button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#upload-style-set" aria-expanded="false" aria-controls="upload-style-set">Import a Style Set</button>
<p class="alert alert-info" style="margin-top: 15px;"><i class="fa fa-lg fa-fw fa-info-circle"></i> When importing, acceptable file types are <code>.json</code> or <code>.csv</code>. Choosing a CSV (Comma Separated Value) file will reveal the additional fields that the file type requires. JSON (Javascript Object Notation) files are required to have this information within the file itself. Maximum file size is 5 MB.</p>
<p class="well" style="margin-top: 15px;"><i class="fa fa-lg fa-fw fa-download"></i> Download <a class="hide-loader" href="https://info.brewingcompetitions.com/00_downloads/import-style-set-templates.zip" target="_blank">JSON/CSV starter templates</a> (zip archive) - be sure to read the information in the JSON_CSV_File_Preparation_Instructions.txt file prior to attempting an import.</p>
<div style="margin-top:15px;" class="collapse" id="upload-style-set">
    <h3>Import a Style Set</h3>
    <p class="bcoem-admin-element">Upload a self-contained style set (JSON or CSV) instead of waiting for a developer to hand-write a migration for the app core. Once imported, the set appears in the Style Set dropdown on Site Preferences like any built-in set.</p>
    <form class="form-horizontal hide-loader-form-submit" data-toggle="validator" role="form"  method="post" action="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles_import" enctype="multipart/form-data" id="styles-import-form" novalidate>
    <input type="hidden" name="user_session_token" value="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="styles_import_upload" value="1">
    <div class="form-group">
        <label for="styles_import_file" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">File</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <span class="btn btn-default btn-file"><span>Choose JSON or CSV File</span><input type="file" name="styles_import_file" id="styles_import_file" accept=".json,.csv" required onchange="styles_import_toggle_csv_fields(this);" /></span>
                <span class="fileinput-filename text-success"></span> <span class="fileinput-new text-danger">No file chosen...</span>
            </div>
        </div>
    </div>

    <div id="styles-import-csv-meta" style="display:none;">
        <h4>Style Set Details (CSV only)</h4>
        <div class="form-group">
            <label for="style_set_name" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Set Name</label>
            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                <input class="form-control" type="text" name="style_set_name" id="style_set_name" maxlength="20" pattern="[A-Za-z0-9_\-]{1,20}" data-error="The set name is required and must be 1-20 characters, letters/numbers/underscore/hyphen only (no spaces)" required>
                <div class="help-block">No spaces. 1-20 characters, letters/numbers/underscore/hyphen only. Must be unique. Matches the value stored for each style in this set.</div>
            </div>
        </div>
        <div class="form-group">
            <label for="style_set_long_name" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Long Name</label>
            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                <input class="form-control" type="text" name="style_set_long_name" id="style_set_long_name" data-error="The long name is required" required>
            </div>
        </div>
        <div class="form-group">
            <label for="style_set_short_name" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Short Name</label>
            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                <input class="form-control" type="text" name="style_set_short_name" id="style_set_short_name" data-error="The short name is required" required>
            </div>
        </div>
        <div class="form-group">
            <label for="style_set_display_separator" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Display Separator</label>
            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                <input class="form-control" type="text" name="style_set_display_separator" id="style_set_display_separator" maxlength="5">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Sub-Style Method</label>
            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                <label class="radio-inline"><input type="radio" name="style_set_sub_style_method" value="0" checked required> Alpha</label>
                <label class="radio-inline"><input type="radio" name="style_set_sub_style_method" value="1" required> Numeric</label>
            </div>
        </div>
        <div class="form-group">
            <label for="style_set_beer_end" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Beer Category End #</label>
            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                <input class="form-control" type="text" name="style_set_beer_end" id="style_set_beer_end" maxlength="3" pattern="[0-9]{1,3}" data-error="The beer category end number is required and must be numeric" required>
            </div>
        </div>
        <div class="form-group">
            <label for="style_set_category_end" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Last Category #</label>
            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                <input class="form-control" type="text" name="style_set_category_end" id="style_set_category_end" maxlength="3" pattern="[0-9]{1,3}" data-error="The last category number is required and must be numeric" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">No Numbering</label>
            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                <label class="radio-inline"><input type="radio" name="style_set_no_numbering" value="1" required> Yes</label>
                <label class="radio-inline"><input type="radio" name="style_set_no_numbering" value="0" checked required> No</label>
                <div class="help-block">Set to Yes only if this set's group/num numbering is purely this application's own bookkeeping, not part of the set's own official guidelines.</div>
                <div class="well">
                    <p><strong>CSV columns:</strong> brewStyleGroup, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOverallCategory, style_type, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleInfo, brewStyleLink, brewStyleEntry, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet.</p>
                    <p>Column order does not matter, but brewStyleGroup, brewStyleNum, brewStyle, brewStyleCategory, and style_type columns must all be present and every row must have a value in each - rows missing one are rejected. brewStyleEntry is also required, but only for rows with brewStyleReqSpec set to 1. brewStyleOverallCategory is optional - a broader grouping spanning multiple categories (e.g. GABF's "Lager Beer Styles" covering many numbered categories); leave blank if your set doesn't use one.</p>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12"></div>
        <div class="bcoem-admin-element hidden-print col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <input type="submit" class="btn btn-primary" value="Import and Validate">
        </div>
    </div>
</form>
</div>
<?php } ?>
<?php if ($staged_report !== null) { ?>
<h3>Validation: <?php echo h($staged_report['meta']['style_set_name']); ?></h3>
<?php if (!empty($staged_report['meta_errors'])) { ?>
<div class="alert alert-danger">
    <strong>This upload cannot be imported:</strong>
    <ul>
    <?php foreach ($staged_report['meta_errors'] as $meta_error) { ?>
        <li><?php echo h($meta_error); ?></li>
    <?php } ?>
    </ul>
</div>
<?php } else {
    $valid_count = 0;
    $invalid_count = 0;
    foreach ($staged_report['rows'] as $r) { if ($r['valid']) $valid_count++; else $invalid_count++; }
?>
<p class="alert alert-info" style="margin-bottom: 15px;"><i class="fa fa-lg fa-fw fa-info-circle"></i> Scroll through to view the imported styles. Select or deselect individual styles to import, then select the Confirm or Abort buttons at the bottom of the list.</p>
<p><?php echo $valid_count; ?> row(s) valid, <?php echo $invalid_count; ?> row(s) with errors. Rows with errors will not be imported unless fixed and re-uploaded.</p>
<?php if ($valid_count > 0) { ?>
<form method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=styles_import&amp;action=styles_import">
<input type="hidden" name="user_session_token" value="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<?php } ?>
<table class="table table-bordered table-striped" id="styles-import-validation-table">
    <tr>
        <th width="1%"><?php if ($valid_count > 0) { ?><input type="checkbox" id="select-all-import-rows" checked title="Select/deselect all rows to import"><?php } ?></th>
        <th>Group</th>
        <th>Num</th>
        <th>Style</th>
        <th>Type</th>
        <th>Errors</th>
    </tr>
</thead>
<tbody>
<?php foreach ($staged_report['rows'] as $i => $r) { ?>
<tr class="<?php echo $r['valid'] ? '' : 'danger'; ?>">
    <td><?php if ($r['valid']) { ?><input type="checkbox" name="include_row[]" value="<?php echo $i; ?>" checked><?php } else { ?><span class="fa fa-fw fa-times text-danger"></span><?php } ?></td>
    <td><?php echo h($r['brewStyleGroup']); ?></td>
    <td><?php echo h($r['brewStyleNum']); ?></td>
    <td><?php echo h($r['brewStyle']); ?></td>
    <td><?php echo h($r['style_type_name']); ?></td>
    <td><?php if (!empty($r['errors'])) echo h(implode(" ", $r['errors'])); ?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php if ($valid_count > 0) { ?>
<script type="text/javascript" language="javascript">
function syncSelectAllImportRows($selectAll, $group) {
  const allChecked = $group.length === $group.filter(':checked').length;
  $selectAll.prop('checked', allChecked);
}

function handleSelectAllImportRows($selectAll, $group) {
  const allChecked = $group.length === $group.filter(':checked').length;
  $group.prop('checked', !allChecked);
  $selectAll.prop('checked', !allChecked);
}

$(document).ready(function () {

  const $selectAllImportRows = $('#select-all-import-rows');
  const $importRowBoxes      = $('#styles-import-validation-table tbody input[name="include_row[]"]');

  $selectAllImportRows.on('change', function () {
    handleSelectAllImportRows($selectAllImportRows, $importRowBoxes);
  });

  $importRowBoxes.on('change', function () {
    syncSelectAllImportRows($selectAllImportRows, $importRowBoxes);
  });

});
</script>
<input type="submit" class="btn btn-primary" value="Confirm Import">
<a class="btn btn-danger hide-loader" style="margin-left:10px;" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=styles_import&amp;action=styles_import_abort" data-confirm="Are you sure you want to abort this import? The uploaded data will be discarded and nothing will be saved.">Abort Import</a>
</form>
<?php } ?>
<?php } ?>
<?php } ?>
<h3 style="margin-top: 25px;">Built-In Style Sets</h3>
<p class="bcoem-admin-element">Below are style sets that ship with this app. Read-only here (no edit or delete) - export them for backup/portability the same way as an imported set.</p>
<?php if (empty($builtin_sets)) { ?>
<p><em>No built-in style sets found.</em></p>
<?php } else { ?>
<table class="table table-bordered table-striped" id="sortable-builtin-sets">
<thead>
    <tr>
        <th width="25%">Name</th>
        <th width="40%">Long Name</th>
        <th width="20%">Styles</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
<?php foreach ($builtin_sets as $builtin_set) {
    $builtin_set_row_count = count(style_set_export_rows($builtin_set['style_set_name'], $prefix, $db_conn));
?>
<tr>
    <td><?php echo h($builtin_set['style_set_name']); ?></td>
    <td><?php echo h($builtin_set['style_set_long_name']); ?></td>
    <td><?php echo $builtin_set_row_count; ?></td>
    <td>
    <a target="_blank" href="<?php echo $base_url; ?>includes/output.inc.php?section=export-styles&amp;go=json&amp;filter=<?php echo urlencode($builtin_set['style_set_name']); ?>" data-toggle="tooltip" data-placement="top" title="Export <?php echo h($builtin_set['style_set_name']); ?> as JSON"><span class="fa fa-fw fa-lg fa-file-code"></span></a>
    <a target="_blank" href="<?php echo $base_url; ?>includes/output.inc.php?section=export-styles&amp;go=csv&amp;filter=<?php echo urlencode($builtin_set['style_set_name']); ?>" data-toggle="tooltip" data-placement="top" title="Export <?php echo h($builtin_set['style_set_name']); ?> as CSV"><span class="fa fa-fw fa-lg fa-file-excel"></span></a>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('#sortable-builtin-sets').dataTable( {
		"bPaginate" : <?php echo $output_datatables_bPaginate; ?>,
		"sPaginationType" : "<?php echo $output_datatables_sPaginationType; ?>",
		"bLengthChange" : <?php echo $output_datatables_bLengthChange; ?>,
		"iDisplayLength" : <?php echo round($_SESSION['prefsRecordPaging']); ?>,
		"sDom": '<?php echo $output_datatables_sDom; ?>',
		"bStateSave" : <?php echo $output_datatables_bStateSave; ?>,
		"aaSorting": [[0,'asc']],
		"bProcessing" : <?php echo $output_datatables_bProcessing; ?>,
		"aoColumns": [ null, null, null, { "bSortable": false } ]
	} );
	$('#sortable-imported-sets').dataTable( {
		"bPaginate" : <?php echo $output_datatables_bPaginate; ?>,
		"sPaginationType" : "<?php echo $output_datatables_sPaginationType; ?>",
		"bLengthChange" : <?php echo $output_datatables_bLengthChange; ?>,
		"iDisplayLength" : <?php echo round($_SESSION['prefsRecordPaging']); ?>,
		"sDom": '<?php echo $output_datatables_sDom; ?>',
		"bStateSave" : <?php echo $output_datatables_bStateSave; ?>,
		"aaSorting": [[0,'asc']],
		"bProcessing" : <?php echo $output_datatables_bProcessing; ?>,
		"aoColumns": [ null, null, null, { "bSortable": false } ]
	} );
} );
</script>
<?php } // end if ($editing_set === null) ?>

<?php if ($editing_set !== null) { ?>
<h3>Edit Style Set: <?php echo h($editing_set['style_set_name']); ?></h3>
<?php if (!empty($_SESSION['styles_import_edit_errors'])) { ?>
<div class="alert alert-danger">
    <strong>This style set could not be saved:</strong>
    <ul>
    <?php foreach ($_SESSION['styles_import_edit_errors'] as $edit_error) { ?>
        <li><?php echo h($edit_error); ?></li>
    <?php } unset($_SESSION['styles_import_edit_errors']); ?>
    </ul>
</div>
<?php } ?>

<div class="alert alert-info">Renaming the Set Name below updates every style, preference, and archived competition already using this style set to match - nothing else needs to change by hand.</div>

<form class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=styles_import&amp;action=styles_import_edit&amp;id=<?php echo (int)$editing_set['id']; ?>">
<input type="hidden" name="user_session_token" value="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">

<div class="form-group">
    <label for="style_set_name" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Set Name</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" type="text" name="style_set_name" id="style_set_name" maxlength="20" pattern="[A-Za-z0-9_\-]{1,20}" data-error="The set name is required and must be 1-20 characters, letters/numbers/underscore/hyphen only (no spaces)" required value="<?php echo h($editing_set['style_set_name']); ?>">
        <div class="help-block">No spaces. 1-20 characters, letters/numbers/underscore/hyphen only. Must be unique. Matches the value stored for each style in this set.</div>
    </div>
</div>
<div class="form-group">
    <label for="style_set_long_name" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Long Name</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" type="text" name="style_set_long_name" id="style_set_long_name" data-error="The long name is required" required value="<?php echo h($editing_set['style_set_long_name']); ?>">
    </div>
</div>
<div class="form-group">
    <label for="style_set_short_name" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Short Name</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" type="text" name="style_set_short_name" id="style_set_short_name" data-error="The short name is required" required value="<?php echo h($editing_set['style_set_short_name']); ?>">
    </div>
</div>
<div class="form-group">
    <label for="style_set_display_separator" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Display Separator</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" type="text" name="style_set_display_separator" id="style_set_display_separator" maxlength="5" value="<?php echo h($editing_set['style_set_display_separator']); ?>">
    </div>
</div>
<div class="form-group">
    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Sub-Style Method</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <label class="radio-inline"><input type="radio" name="style_set_sub_style_method" value="0" <?php if ($editing_set['style_set_sub_style_method'] == "0") echo "checked"; ?> required> Alpha</label>
        <label class="radio-inline"><input type="radio" name="style_set_sub_style_method" value="1" <?php if ($editing_set['style_set_sub_style_method'] == "1") echo "checked"; ?> required> Numeric</label>
    </div>
</div>
<div class="form-group">
    <label for="style_set_beer_end" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Beer Category End #</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" type="text" name="style_set_beer_end" id="style_set_beer_end" maxlength="3" pattern="[0-9]{1,3}" data-error="The beer category end number is required and must be numeric" required value="<?php echo h($editing_set['style_set_beer_end']); ?>">
    </div>
</div>
<div class="form-group">
    <label for="style_set_category_end" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Last Category #</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" type="text" name="style_set_category_end" id="style_set_category_end" maxlength="3" pattern="[0-9]{1,3}" data-error="The last category number is required and must be numeric" required value="<?php echo h($editing_set['style_set_category_end']); ?>">
    </div>
</div>
<div class="form-group">
    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">No Numbering</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <label class="radio-inline"><input type="radio" name="style_set_no_numbering" value="1" <?php if (!empty($editing_set['style_set_no_numbering'])) echo "checked"; ?> required> Yes</label>
        <label class="radio-inline"><input type="radio" name="style_set_no_numbering" value="0" <?php if (empty($editing_set['style_set_no_numbering'])) echo "checked"; ?> required> No</label>
        <div class="help-block">Set to Yes only if this set's group/num numbering is purely this application's own bookkeeping, not part of the set's own official guidelines.</div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12"></div>
    <div class="bcoem-admin-element hidden-print col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input type="submit" class="btn btn-primary" value="Save Changes">
        <a style="margin-left:10px;" class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles_import">Cancel</a>
    </div>
</div>
</form>

<?php } else { ?>
<script>
function styles_import_toggle_csv_fields(input) {
    var filename = (input.files && input.files[0]) ? input.files[0].name : input.value;
    var ext = filename.split('.').pop().toLowerCase();
    document.getElementById('styles-import-csv-meta').style.display = (ext === 'csv') ? 'block' : 'none';
}
</script>

<?php } ?>
