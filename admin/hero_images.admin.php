<?php
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (strpos($section, "step") === FALSE) && ($_SESSION['userLevel'] > 0))) {
    if (function_exists('redirect_or_exit')) redirect_or_exit("../../403.php");
    else { header("Location: ../../403.php"); exit(); }
}

include (LIB.'hero_images.lib.php');

$save_message = "";
$save_error = "";
$request_uri = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

$category_prefixes = array(
    "0" => "misc",
    "1" => "beer",
    "2" => "cider",
    "3" => "mead"
);

$allowed_extensions = array("jpg", "jpeg", "png", "gif", "webp");
$allowed_mime_types = array("image/jpeg", "image/png", "image/gif", "image/webp");
$max_upload_bytes = 2 * 1024 * 1024; // 5 MB

function is_valid_hero_token() {
    return (
        isset($_POST['user_session_token']) &&
        isset($_SESSION['user_session_token']) &&
        (hash_equals($_SESSION['user_session_token'], $_POST['user_session_token']))
    );
}

function hero_safe_filename_stem($input) {
    $input = strtolower($input);
    $input = preg_replace('/[^a-z0-9]+/', '-', $input);
    $input = trim($input, '-');
    return $input;
}

function hero_upload_error_message($error_code) {
    switch ((int)$error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return "The uploaded file is larger than the server allows.";
        case UPLOAD_ERR_FORM_SIZE:
            return "The uploaded file is larger than the form allows.";
        case UPLOAD_ERR_PARTIAL:
            return "The file was only partially uploaded.";
        case UPLOAD_ERR_NO_FILE:
            return "No file was uploaded.";
        case UPLOAD_ERR_NO_TMP_DIR:
            return "The server is missing a temporary upload folder.";
        case UPLOAD_ERR_CANT_WRITE:
            return "The server could not write the uploaded file to disk.";
        case UPLOAD_ERR_EXTENSION:
            return "A server extension stopped the upload.";
        default:
            return "Image upload failed. Please try again.";
    }
}

if ((isset($_POST['section'])) && ($_POST['section'] == "hero_images") && (isset($_POST['action'])) && ($_POST['action'] == "upload")) {
    if (!is_valid_hero_token()) {
        $save_error = "Security token validation failed. Please refresh and try again.";
    }
    else {
        $upload_category = (isset($_POST['hero_image_category'])) ? (string)$_POST['hero_image_category'] : "";

        if (!isset($category_prefixes[$upload_category])) {
            $save_error = "Please select a valid category.";
        }
        elseif ((!isset($_FILES['hero_image_file'])) || (!is_array($_FILES['hero_image_file'])) || (!isset($_FILES['hero_image_file']['error']))) {
            $save_error = "Please choose an image file to upload.";
        }
        elseif ($_FILES['hero_image_file']['error'] !== UPLOAD_ERR_OK) {
            $save_error = hero_upload_error_message($_FILES['hero_image_file']['error']);
        }
        elseif (!isset($_FILES['hero_image_file']['size']) || ((int)$_FILES['hero_image_file']['size'] <= 0) || ((int)$_FILES['hero_image_file']['size'] > $max_upload_bytes)) {
            $current_size = (isset($_FILES['hero_image_file']['size'])) ? number_format((int)$_FILES['hero_image_file']['size']) : "unknown";
            $save_error = "Image size must be between 1 byte and 2 MB. Uploaded size: ".$current_size." bytes.";
        }
        elseif ((!isset($_FILES['hero_image_file']['tmp_name'])) || (!is_uploaded_file($_FILES['hero_image_file']['tmp_name']))) {
            $save_error = "Invalid upload payload.";
        }
        else {
            $original_name = (string)$_FILES['hero_image_file']['name'];
            $safe_original_name = htmlspecialchars($original_name, ENT_QUOTES, 'UTF-8');
            $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

            if (!in_array($extension, $allowed_extensions)) {
                $save_error = "Unsupported file type for ".$safe_original_name.". Allowed: JPG, JPEG, PNG, GIF, WebP.";
            }
            else {
                $image_info = @getimagesize($_FILES['hero_image_file']['tmp_name']);
                if (($image_info === false) || (!isset($image_info['mime'])) || (!in_array($image_info['mime'], $allowed_mime_types))) {
                    $save_error = "Uploaded file ".$safe_original_name." is not a valid image file.";
                    $img_width = 0;
                    $img_height = 0;
                    $img_ratio = 0;
                } else {
                    $img_width = (int)$image_info[0];
                    $img_height = (int)$image_info[1];
                    $img_ratio = ($img_height > 0) ? ($img_width / $img_height) : 0;
                }

                if ((empty($save_error)) && (($img_width < 1200) || ($img_ratio < 3.5))) {
                    $save_error = "Image must be a wide banner. Uploaded dimensions: ".$img_width."x".$img_height.". Minimum width 1200px and aspect ratio at least 3.5:1.";
                }

                if (!empty($save_error)) {
                    // Validation failed; skip filename generation and move.
                }
                else {
                $name_without_ext = pathinfo($original_name, PATHINFO_FILENAME);
                $safe_stem = hero_safe_filename_stem($name_without_ext);
                if (empty($safe_stem)) $safe_stem = "banner";

                $base_filename = $category_prefixes[$upload_category]."-".$safe_stem;
                $counter = 0;
                do {
                    $counter_suffix = ($counter > 0) ? "-".$counter : "";
                    $target_filename = $base_filename.$counter_suffix.".".$extension;
                    $target_path = USER_IMAGES.$target_filename;
                    $counter++;
                } while (file_exists($target_path));

                if (!move_uploaded_file($_FILES['hero_image_file']['tmp_name'], $target_path)) {
                    $save_error = "Unable to save uploaded file to /user_images. Check folder permissions.";
                }
                else {
                    $save_message = "Banner image uploaded successfully.";
                }
                }
            }
        }
    }
}

$all_images = get_all_available_hero_images();
$hero_prefs = load_hero_images_preferences($db_conn, $prefix, $all_images);

if ((isset($_POST['section'])) && ($_POST['section'] == "hero_images") && (isset($_POST['action'])) && ($_POST['action'] == "delete")) {
    if (!is_valid_hero_token()) {
        $save_error = "Security token validation failed. Please refresh and try again.";
    }
    else {
        $delete_image = (isset($_POST['hero_image_delete'])) ? basename((string)$_POST['hero_image_delete']) : "";
        $all_known_images = array();

        foreach ($all_images as $images) {
            foreach ($images as $image) {
                $all_known_images[] = $image;
            }
        }

        if (($delete_image === "") || (!in_array($delete_image, $all_known_images))) {
            $save_error = "Please choose a valid image to delete.";
        }
        elseif (is_bundled_hero_image($delete_image)) {
            $save_error = "This is a built-in banner image and can't be deleted - you can still enable or disable it below.";
        }
        else {
            $delete_path = hero_image_directory($delete_image).$delete_image;
            if (!file_exists($delete_path)) {
                $save_error = "The selected image file could not be found.";
            }
            elseif (!@unlink($delete_path)) {
                $save_error = "Unable to delete the selected image. Check folder permissions.";
            }
            else {
                unset($hero_prefs[$delete_image]);
                save_hero_images_preferences($db_conn, $prefix, $hero_prefs);
                $save_message = "Banner image deleted successfully.";
                $all_images = get_all_available_hero_images();
                $hero_prefs = load_hero_images_preferences($db_conn, $prefix, $all_images);
            }
        }
    }
}

if ((isset($_POST['section'])) && ($_POST['section'] == "hero_images") && (isset($_POST['action'])) && ($_POST['action'] == "save")) {
    $token_valid = is_valid_hero_token();
    $images_to_save = array();

    if (!$token_valid) {
        $save_error = "Security token validation failed. Please refresh and try again.";
    }

    if ($token_valid) {
        foreach ($all_images as $images) {
            foreach ($images as $image) {
                $checkbox_name = "hero_image_".preg_replace('/[^a-zA-Z0-9_]/', '_', $image);
                $images_to_save[$image] = (isset($_POST[$checkbox_name])) ? true : false;
            }
        }

        if (save_hero_images_preferences($db_conn, $prefix, $images_to_save)) {
            $save_message = isset($lang['admin_hero_images_saved']) ? $lang['admin_hero_images_saved'] : "Hero images preferences saved successfully.";
            $hero_prefs = $images_to_save;
        }
        else {
            $save_error = isset($lang['admin_hero_images_error']) ? $lang['admin_hero_images_error'] : "Error saving hero images preferences.";
        }
    }
}

?>

<div id="hero_images_admin" class="admin-section">

    <h2><?php echo isset($lang['admin_hero_images_title']) ? $lang['admin_hero_images_title'] : "Banner Images"; ?></h2>

    <?php if (!empty($save_message)): ?>
    <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $save_message; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($save_error)): ?>
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $save_error; ?>
    </div>
    <?php endif; ?>

    <p><?php echo isset($lang['admin_hero_images_description']) ? $lang['admin_hero_images_description'] : "Select which banner images are displayed on the homepage. Images are randomly selected based on your competition's accepted style types."; ?></p>

    <div class="well well-sm">
        <p><strong><?php echo isset($lang['admin_hero_how_it_works_title']) ? $lang['admin_hero_how_it_works_title'] : "How it Works"; ?></strong></p>
        
        <?php echo isset($lang['admin_hero_how_it_works_body']) ? $lang['admin_hero_how_it_works_body'] : "<p>Banner images appear as a large background strip at the top of the competition homepage. One image is picked at random each time a visitor loads the page. Images are grouped by category &ndash; Miscellaneous images can appear at any time, while Beer, Cider, and Mead images only appear when your competition accepts entries in those categories.</p>
        <p>Use the checkboxes below to choose which images are in the rotation, then click <strong>Save Changes</strong>. Please note that if no images are selected here, the fallback is the app's default (random selection of the built-in images).</p>
        <p>To add a new image, use the upload panel and choose the matching category. Built-in images ship with the software &ndash; you can enable or disable their display, but they cannot be deleted here.</p>"; ?>
        
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title"><?php echo isset($lang['hero_images_text_002']) ? $lang['hero_images_text_002'] : "Upload New Banner Image"; ?></h4>
        </div>
        <div class="panel-body">
            <form method="POST" action="<?php echo $request_uri; ?>" enctype="multipart/form-data" class="form-inline">
                <input type="hidden" name="section" value="hero_images">
                <input type="hidden" name="action" value="upload">
                <input type="hidden" name="user_session_token" value="<?php echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">

                <p style="margin-bottom: 12px;">Step 1: choose a category.</p>

                <div class="form-group" style="margin-right: 10px; margin-bottom: 10px;">
                    <label for="hero_image_category" class="sr-only"><?php echo isset($lang['hero_images_text_007']) ? $lang['hero_images_text_007'] : "Category"; ?></label>
                    <select class="form-control" id="hero_image_category" name="hero_image_category" required>
                        <option value=""><?php echo isset($lang['hero_images_text_008']) ? $lang['hero_images_text_008'] : "Select a category..."; ?></option>
                        <option value="0"><?php echo isset($lang['admin_hero_category_misc']) ? $lang['admin_hero_category_misc'] : "Miscellaneous"; ?></option>
                        <option value="1"><?php echo isset($lang['admin_hero_category_beer']) ? $lang['admin_hero_category_beer'] : "Beer"; ?></option>
                        <option value="2"><?php echo isset($lang['admin_hero_category_cider']) ? $lang['admin_hero_category_cider'] : "Cider"; ?></option>
                        <option value="3"><?php echo isset($lang['admin_hero_category_mead']) ? $lang['admin_hero_category_mead'] : "Mead"; ?></option>
                    </select>
                </div>

                <div id="hero_image_file_group" class="fileinput fileinput-new" data-provides="fileinput">
                    <p>Step 2: choose the image file and upload it.</p>
                    <span class="btn btn-default btn-file"><span><?php echo isset($lang['hero_images_text_004']) ? $lang['hero_images_text_004'] : "Choose Image File"; ?></span><input type="file" id="hero_image_file" name="hero_image_file" accept=".jpg,.jpeg,.png,.gif,.webp" required /></span>
                    <span class="fileinput-filename text-success"></span> <span class="fileinput-new text-danger">No file chosen...</span>
                </div>

                <button type="submit" id="hero_image_upload_button" class="btn btn-success" style="margin-bottom: 10px; display:none;" disabled>
                    <i class="fa fa-sm fa-fw fa-upload"></i> <?php echo isset($lang['hero_images_text_012']) ? $lang['hero_images_text_012'] : "Upload Image"; ?>
                </button>
            </form>
            <div style="margin-top:12px;">
                <p><strong><?php echo isset($lang['admin_hero_upload_note_title']) ? $lang['admin_hero_upload_note_title'] : "File naming"; ?>:</strong>
                <?php echo isset($lang['admin_hero_upload_note_body']) ? $lang['admin_hero_upload_note_body'] : "The uploaded file is automatically renamed using the selected category as a prefix &mdash; for example, uploading <em>sunset.jpg</em> in the Beer category saves as <code>beer-sunset.jpg</code>. You do not need to rename the file before uploading."; ?></p>
                <p><strong><?php echo isset($lang['admin_hero_upload_note_size_title']) ? $lang['admin_hero_upload_note_size_title'] : "Size and format"; ?>:</strong> 
                <?php echo isset($lang['admin_hero_upload_note_size_body']) ? $lang['admin_hero_upload_note_size_body'] : "Recommended 3000&times;500 px (6:1 ratio). Minimum width 1200 px with at least a 3.5:1 aspect ratio. Accepted formats: JPG, PNG, GIF, WebP. Maximum file size is 2 MB for an optimal user experience."; ?></p>
            </div>
        </div>
    </div>

    <form method="POST" action="<?php echo $request_uri; ?>" id="hero_images_form">
        <input type="hidden" name="section" value="hero_images">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="user_session_token" value="<?php echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">

        <p><?php echo isset($lang['hero_images_text_009']) ? $lang['hero_images_text_009'] : "Images are randomly selected based on your competition's accepted style types. Miscellaneous images appear on all pages."; ?></p>

        <?php
        $category_blocks = array(
            "0" => array(
                "title" => (isset($lang['admin_hero_category_misc']) ? $lang['admin_hero_category_misc'] : "Miscellaneous"),
                "help" => (isset($lang['admin_hero_category_shown_all']) ? $lang['admin_hero_category_shown_all'] : "Shown on all pages")
            ),
            "1" => array(
                "title" => (isset($lang['admin_hero_category_beer']) ? $lang['admin_hero_category_beer'] : "Beer"),
                "help" => (isset($lang['admin_hero_category_shown_beer']) ? $lang['admin_hero_category_shown_beer'] : "Shown when beer category is active")
            ),
            "2" => array(
                "title" => (isset($lang['admin_hero_category_cider']) ? $lang['admin_hero_category_cider'] : "Cider"),
                "help" => (isset($lang['admin_hero_category_shown_cider']) ? $lang['admin_hero_category_shown_cider'] : "Shown when cider category is active")
            ),
            "3" => array(
                "title" => (isset($lang['admin_hero_category_mead']) ? $lang['admin_hero_category_mead'] : "Mead"),
                "help" => (isset($lang['admin_hero_category_shown_mead']) ? $lang['admin_hero_category_shown_mead'] : "Shown when mead category is active")
            )
        );

        foreach ($category_blocks as $category => $meta) {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="hero-panel-heading-row">
                    <div>
                        <h4 class="panel-title"><?php echo $meta['title']; ?><em style="font-size: .75em; color: #666; margin-left: 15px;"><?php echo $meta['help']; ?></em></h4>   
                                      
                    </div>
                    <?php if (!empty($all_images[$category])): ?>
                    <button type="button" class="btn btn-dark btn-xs hero-toggle-all-button"
                        data-hero-category="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>"
                        data-label-select="<?php echo htmlspecialchars(isset($lang['admin_hero_select_all']) ? $lang['admin_hero_select_all'] : "Select All", ENT_QUOTES, 'UTF-8'); ?>"
                        data-label-deselect="<?php echo htmlspecialchars(isset($lang['admin_hero_deselect_all']) ? $lang['admin_hero_deselect_all'] : "Deselect All", ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo isset($lang['admin_hero_deselect_all']) ? $lang['admin_hero_deselect_all'] : "Deselect All"; ?>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-body">
                <?php if (empty($all_images[$category])): ?>
                    <p class="text-muted"><?php echo isset($lang['admin_hero_no_images']) ? $lang['admin_hero_no_images'] : "No images found"; ?></p>
                <?php else: ?>
                    <div class="hero-images-grid" data-hero-category-grid="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php foreach ($all_images[$category] as $image):
                            $checkbox_name = "hero_image_".preg_replace('/[^a-zA-Z0-9_]/', '_', $image);
                            $is_checked = (isset($hero_prefs[$image])) ? $hero_prefs[$image] : false;
                            $is_bundled = is_bundled_hero_image($image);
                        ?>
                        <div class="hero-image-item">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="<?php echo $checkbox_name; ?>" value="1" <?php echo ($is_checked) ? 'checked' : ''; ?>>
                                    <span class="image-name small"><?php echo htmlspecialchars($image); ?></span>
                                </label>
                            </div>
                            <img src="<?php echo htmlspecialchars(hero_image_url($image, $base_url, $images_url), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($image); ?>" class="hero-thumbnail">
                            <?php if ($is_bundled): ?>
                            <span class="text-muted hero-image-builtin-label"><span class="fa fa-sm fa-fw fa-lock"></span> Built-in</span>
                            <?php else: ?>
                            <button type="button" class="btn btn-link btn-xs hero-image-delete-button" data-hero-image="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>">
                                <span class="fa fa-sm fa-fw fa-trash"></span> <?php echo isset($lang['delete']) ? $lang['delete'] : "Delete"; ?>
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php } ?>

        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-sm fa-fw fa-save"></i></i> <?php echo isset($lang['admin_hero_save_button']) ? $lang['admin_hero_save_button'] : "Save Changes"; ?>
                </button>
                <button type="button" class="btn btn-default" onclick="location.reload();">
                    <i class="fa fa-sm fa-fw fa-rotate-left"></i> <?php echo isset($lang['cancel']) ? $lang['cancel'] : "Cancel"; ?>
                </button>
            </div>
        </div>
    </form>

    <form method="POST" action="<?php echo $request_uri; ?>" id="hero_image_delete_form" style="display:none;">
        <input type="hidden" name="section" value="hero_images">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="user_session_token" value="<?php echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="hero_image_delete" id="hero_image_delete_input" value="">
    </form>

    <div id="hero_image_delete_modal" class="hero-delete-modal" aria-hidden="true" style="display:none;">
        <div class="hero-delete-modal__backdrop"></div>
        <div class="hero-delete-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="hero_image_delete_modal_title">
            <h4 id="hero_image_delete_modal_title">Delete image?</h4>
            <p id="hero_image_delete_modal_body">This action cannot be undone.</p>
            <div class="hero-delete-modal__actions">
                <button type="button" class="btn btn-default btn-sm" id="hero_image_delete_cancel">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="hero_image_delete_confirm">Delete</button>
            </div>
        </div>
    </div>

</div>

<style>
#hero_images_admin .hero-images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 15px;
}

#hero_images_admin .hero-image-item {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    background: #f9f9f9;
}

#hero_images_admin .hero-image-item .checkbox {
    margin: 0 0 10px 0;
}

#hero_images_admin .hero-image-item .image-name {
    display: block;
    color: #666;
    word-break: break-word;
    margin-left: 5px;
}

#hero_images_admin .hero-thumbnail {
    width: 100%;
    height: auto;
    max-height: 150px;
    object-fit: cover;
    border-radius: 3px;
    border: 1px solid #ddd;
}

#hero_images_admin .hero-image-delete-button {
    margin-top: 10px;
    padding-left: 0;
    padding-right: 0;
    color: #888;
    text-decoration: none;
    box-shadow: none;
}

#hero_images_admin .hero-image-builtin-label {
    display: block;
    margin-top: 10px;
    font-size: 12px;
}

#hero_images_admin .hero-image-delete-button:hover,
#hero_images_admin .hero-image-delete-button:focus {
    color: #b94a48;
    text-decoration: underline;
}

#hero_images_admin .hero-delete-modal {
    position: fixed;
    inset: 0;
    z-index: 1050;
}

#hero_images_admin .hero-delete-modal__backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
}

#hero_images_admin .hero-delete-modal__dialog {
    position: relative;
    width: calc(100% - 40px);
    max-width: 420px;
    margin: 12vh auto 0;
    background: #fff;
    border-radius: 6px;
    padding: 20px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.25);
}

#hero_images_admin .hero-delete-modal__dialog h4 {
    margin-top: 0;
}

#hero_images_admin .hero-delete-modal__actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
}

#hero_images_admin .panel-heading small {
    display: block;
    font-weight: normal;
    color: #999;
    margin-top: 3px;
}

#hero_images_admin .hero-panel-heading-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}

#hero_images_admin .hero-panel-heading-row .panel-title {
    margin: 0;
}

#hero_images_admin .hero-toggle-all-button {
    flex-shrink: 0;
}
</style>

<script>
(function () {
    var categorySelect = document.getElementById('hero_image_category');
    var fileInput = document.getElementById('hero_image_file');
    var fileGroup = document.getElementById('hero_image_file_group');
    var uploadButton = document.getElementById('hero_image_upload_button');

    if (!categorySelect || !fileInput || !fileGroup || !uploadButton) {
        return;
    }

    function updateUploadState() {
        var categoryChosen = !!categorySelect.value;
        var fileChosen = !!(fileInput.files && fileInput.files.length > 0);

        fileGroup.style.display = categoryChosen ? 'block' : 'none';
        uploadButton.style.display = categoryChosen ? 'inline-block' : 'none';
        uploadButton.disabled = !(categoryChosen && fileChosen);

        if (!categoryChosen) {
            fileInput.value = '';
        }
    }

    categorySelect.addEventListener('change', updateUploadState);
    fileInput.addEventListener('change', updateUploadState);
    updateUploadState();
})();

(function () {
    var deleteForm = document.getElementById('hero_image_delete_form');
    var deleteInput = document.getElementById('hero_image_delete_input');
    var deleteButtons = document.querySelectorAll('.hero-image-delete-button');
    var deleteModal = document.getElementById('hero_image_delete_modal');
    var deleteModalBody = document.getElementById('hero_image_delete_modal_body');
    var deleteCancel = document.getElementById('hero_image_delete_cancel');
    var deleteConfirm = document.getElementById('hero_image_delete_confirm');
    var pendingDeleteImage = '';

    if (!deleteForm || !deleteInput || !deleteButtons.length || !deleteModal || !deleteModalBody || !deleteCancel || !deleteConfirm) {
        return;
    }

    function openDeleteModal(imageName) {
        pendingDeleteImage = imageName;
        deleteModalBody.textContent = 'Delete ' + imageName + '? This cannot be undone.';
        deleteModal.style.display = 'block';
        deleteModal.setAttribute('aria-hidden', 'false');
        deleteCancel.focus();
    }

    function closeDeleteModal() {
        pendingDeleteImage = '';
        deleteModal.style.display = 'none';
        deleteModal.setAttribute('aria-hidden', 'true');
    }

    for (var i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener('click', function (event) {
            event.preventDefault();
            var imageName = this.getAttribute('data-hero-image');
            if (!imageName) {
                return;
            }

            openDeleteModal(imageName);
        });
    }

    deleteCancel.addEventListener('click', closeDeleteModal);
    deleteConfirm.addEventListener('click', function () {
        if (!pendingDeleteImage) {
            return;
        }

        deleteInput.value = pendingDeleteImage;
        deleteForm.submit();
    });

    deleteModal.querySelector('.hero-delete-modal__backdrop').addEventListener('click', closeDeleteModal);
    document.addEventListener('keydown', function (event) {
        if ((event.key === 'Escape') && (deleteModal.style.display !== 'none')) {
            closeDeleteModal();
        }
    });
})();

(function () {
    var toggleButtons = document.querySelectorAll('.hero-toggle-all-button');

    function categoryCheckboxes(category) {
        var grid = document.querySelector('.hero-images-grid[data-hero-category-grid="' + category + '"]');
        return grid ? grid.querySelectorAll('input[type="checkbox"]') : [];
    }

    function allChecked(checkboxes) {
        if (!checkboxes.length) return false;
        for (var i = 0; i < checkboxes.length; i++) {
            if (!checkboxes[i].checked) return false;
        }
        return true;
    }

    function updateButtonLabel(button, checkboxes) {
        button.textContent = allChecked(checkboxes) ? button.getAttribute('data-label-deselect') : button.getAttribute('data-label-select');
    }

    for (var i = 0; i < toggleButtons.length; i++) {
        (function (button) {
            var checkboxes = categoryCheckboxes(button.getAttribute('data-hero-category'));

            updateButtonLabel(button, checkboxes);

            button.addEventListener('click', function () {
                var checkAll = !allChecked(checkboxes);
                for (var j = 0; j < checkboxes.length; j++) {
                    checkboxes[j].checked = checkAll;
                }
                updateButtonLabel(button, checkboxes);
            });

            for (var k = 0; k < checkboxes.length; k++) {
                checkboxes[k].addEventListener('change', function () {
                    updateButtonLabel(button, checkboxes);
                });
            }
        })(toggleButtons[i]);
    }
})();
</script>
