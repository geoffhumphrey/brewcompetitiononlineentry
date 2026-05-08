<?php
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (strpos($section, "step") === FALSE) && ($_SESSION['userLevel'] > 0))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

// Include hero images library
include (LIB.'hero_images.lib.php');

$save_message = "";
$save_error = "";
$all_images = array();
$hero_prefs_json = "{}";

if (($action == "default") || ($action == "hero_images")) {

    // Get all available images from /images folder
    $all_images = get_all_available_hero_images();

    // Get current preferences from site_preferences
    $prefs_table = $prefix."site_preferences";
    $query = sprintf("SELECT prefsHeroImages FROM %s LIMIT 1", $prefs_table);
    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
    $row = mysqli_fetch_assoc($result);

    // Initialize if not set
    if (!$row || empty($row['prefsHeroImages'])) {
        initialize_hero_images_preferences($connection, $prefix);
        $hero_prefs = $all_images;
        // Flatten to get all images
        $hero_images_flat = array();
        foreach ($all_images as $category => $images) {
            foreach ($images as $image) {
                $hero_images_flat[$image] = true;
            }
        }
        $hero_prefs_json = json_encode($hero_images_flat);
    } else {
        $hero_prefs_json = $row['prefsHeroImages'];
    }

    // Handle form submission
    if ((isset($_POST['section'])) && ($_POST['section'] == "hero_images") && (isset($_POST['action'])) && ($_POST['action'] == "save")) {
        
        $images_to_save = array();

        // Get all available images and set their status based on form
        foreach ($all_images as $category => $images) {
            foreach ($images as $image) {
                $checkbox_name = "hero_image_" . preg_replace('/[^a-zA-Z0-9_]/', '_', $image);
                $images_to_save[$image] = (isset($_POST[$checkbox_name])) ? true : false;
            }
        }

        // Save preferences
        if (save_hero_images_preferences($connection, $prefix, $images_to_save)) {
            $save_message = isset($lang['admin_hero_images_saved']) ? $lang['admin_hero_images_saved'] : "Hero images preferences saved successfully.";
            $hero_prefs_json = json_encode($images_to_save);
        } else {
            $save_error = isset($lang['admin_hero_images_error']) ? $lang['admin_hero_images_error'] : "Error saving hero images preferences.";
        }
    }

    // Parse current preferences for display
    $hero_prefs = json_decode($hero_prefs_json, true);
    if (!is_array($hero_prefs)) {
        $hero_prefs = array();
    }

?>

<div id="hero_images_admin" class="admin-section">

    <h2><?php echo isset($lang['admin_hero_images_title']) ? $lang['admin_hero_images_title'] : "Hero Background Images"; ?></h2>
    
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

    <p><?php echo isset($lang['admin_hero_images_description']) ? $lang['admin_hero_images_description'] : "Select which hero background images are displayed on the homepage. Images are randomly selected based on your competition's accepted style types."; ?></p>

    <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="hero_images_form">
        <input type="hidden" name="section" value="hero_images">
        <input type="hidden" name="action" value="save">

        <div class="row">
            <div class="col-md-12">
                
                <!-- Misc Images (Category 0) -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo isset($lang['admin_hero_category_misc']) ? $lang['admin_hero_category_misc'] : "Miscellaneous"; ?></h4>
                        <small><?php echo isset($lang['admin_hero_category_shown_all']) ? $lang['admin_hero_category_shown_all'] : "Shown on all pages"; ?></small>
                    </div>
                    <div class="panel-body">
                        <?php if (empty($all_images["0"])): ?>
                            <p class="text-muted"><?php echo isset($lang['admin_hero_no_images']) ? $lang['admin_hero_no_images'] : "No images found"; ?></p>
                        <?php else: ?>
                            <div class="hero-images-grid">
                                <?php foreach ($all_images["0"] as $image): 
                                    $checkbox_name = "hero_image_" . preg_replace('/[^a-zA-Z0-9_]/', '_', $image);
                                    $is_checked = (isset($hero_prefs[$image])) ? $hero_prefs[$image] : false;
                                ?>
                                <div class="hero-image-item">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="<?php echo $checkbox_name; ?>" value="1" <?php echo ($is_checked) ? 'checked' : ''; ?>>
                                            <span class="image-name"><?php echo htmlspecialchars($image); ?></span>
                                        </label>
                                    </div>
                                    <img src="<?php echo IMAGES_URL.$image; ?>" alt="<?php echo htmlspecialchars($image); ?>" class="hero-thumbnail">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Beer Images (Category 1) -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo isset($lang['admin_hero_category_beer']) ? $lang['admin_hero_category_beer'] : "Beer"; ?></h4>
                        <small><?php echo isset($lang['admin_hero_category_shown_beer']) ? $lang['admin_hero_category_shown_beer'] : "Shown when beer category is active"; ?></small>
                    </div>
                    <div class="panel-body">
                        <?php if (empty($all_images["1"])): ?>
                            <p class="text-muted"><?php echo isset($lang['admin_hero_no_images']) ? $lang['admin_hero_no_images'] : "No images found"; ?></p>
                        <?php else: ?>
                            <div class="hero-images-grid">
                                <?php foreach ($all_images["1"] as $image): 
                                    $checkbox_name = "hero_image_" . preg_replace('/[^a-zA-Z0-9_]/', '_', $image);
                                    $is_checked = (isset($hero_prefs[$image])) ? $hero_prefs[$image] : false;
                                ?>
                                <div class="hero-image-item">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="<?php echo $checkbox_name; ?>" value="1" <?php echo ($is_checked) ? 'checked' : ''; ?>>
                                            <span class="image-name"><?php echo htmlspecialchars($image); ?></span>
                                        </label>
                                    </div>
                                    <img src="<?php echo IMAGES_URL.$image; ?>" alt="<?php echo htmlspecialchars($image); ?>" class="hero-thumbnail">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Cider Images (Category 2) -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo isset($lang['admin_hero_category_cider']) ? $lang['admin_hero_category_cider'] : "Cider"; ?></h4>
                        <small><?php echo isset($lang['admin_hero_category_shown_cider']) ? $lang['admin_hero_category_shown_cider'] : "Shown when cider category is active"; ?></small>
                    </div>
                    <div class="panel-body">
                        <?php if (empty($all_images["2"])): ?>
                            <p class="text-muted"><?php echo isset($lang['admin_hero_no_images']) ? $lang['admin_hero_no_images'] : "No images found"; ?></p>
                        <?php else: ?>
                            <div class="hero-images-grid">
                                <?php foreach ($all_images["2"] as $image): 
                                    $checkbox_name = "hero_image_" . preg_replace('/[^a-zA-Z0-9_]/', '_', $image);
                                    $is_checked = (isset($hero_prefs[$image])) ? $hero_prefs[$image] : false;
                                ?>
                                <div class="hero-image-item">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="<?php echo $checkbox_name; ?>" value="1" <?php echo ($is_checked) ? 'checked' : ''; ?>>
                                            <span class="image-name"><?php echo htmlspecialchars($image); ?></span>
                                        </label>
                                    </div>
                                    <img src="<?php echo IMAGES_URL.$image; ?>" alt="<?php echo htmlspecialchars($image); ?>" class="hero-thumbnail">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Mead Images (Category 3) -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo isset($lang['admin_hero_category_mead']) ? $lang['admin_hero_category_mead'] : "Mead"; ?></h4>
                        <small><?php echo isset($lang['admin_hero_category_shown_mead']) ? $lang['admin_hero_category_shown_mead'] : "Shown when mead category is active"; ?></small>
                    </div>
                    <div class="panel-body">
                        <?php if (empty($all_images["3"])): ?>
                            <p class="text-muted"><?php echo isset($lang['admin_hero_no_images']) ? $lang['admin_hero_no_images'] : "No images found"; ?></p>
                        <?php else: ?>
                            <div class="hero-images-grid">
                                <?php foreach ($all_images["3"] as $image): 
                                    $checkbox_name = "hero_image_" . preg_replace('/[^a-zA-Z0-9_]/', '_', $image);
                                    $is_checked = (isset($hero_prefs[$image])) ? $hero_prefs[$image] : false;
                                ?>
                                <div class="hero-image-item">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="<?php echo $checkbox_name; ?>" value="1" <?php echo ($is_checked) ? 'checked' : ''; ?>>
                                            <span class="image-name"><?php echo htmlspecialchars($image); ?></span>
                                        </label>
                                    </div>
                                    <img src="<?php echo IMAGES_URL.$image; ?>" alt="<?php echo htmlspecialchars($image); ?>" class="hero-thumbnail">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-floppy-disk"></span> <?php echo isset($lang['admin_hero_save_button']) ? $lang['admin_hero_save_button'] : "Save Changes"; ?>
                </button>
                <button type="button" class="btn btn-default" onclick="location.reload();">
                    <span class="glyphicon glyphicon-refresh"></span> <?php echo isset($lang['cancel']) ? $lang['cancel'] : "Cancel"; ?>
                </button>
            </div>
        </div>
    </form>

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
    font-size: 12px;
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

#hero_images_admin .panel-heading small {
    display: block;
    font-weight: normal;
    font-size: 12px;
    color: #999;
    margin-top: 3px;
}
</style>

<?php

} // End if action hero_images

?>


<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo (isset($lang['adminHeroImagesUpload'])) ? $lang['adminHeroImagesUpload'] : "Upload New Hero Image"; ?></h3>
            </div>
            <div class="panel-body">
                <p class="bcoem-admin-element"><?php echo (isset($lang['adminHeroImagesHelp'])) ? $lang['adminHeroImagesHelp'] : "Upload background images for the competition homepage. Recommended size: 3000x500 pixels (6:1 ratio). Acceptable formats: JPG, PNG, GIF, WebP, SVG."; ?></p>

                <form id="hero-image-upload-form" method="post" action="<?php echo $base_url; ?>handle.php?action=hero-images" ENCTYPE="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="user_session_token" value="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="form-group">
                        <label for="hero-image-file" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo (isset($lang['adminHeroImageFile'])) ? $lang['adminHeroImageFile'] : "Image File"; ?></label>
                        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <span class="btn btn-default btn-file"><span><?php echo (isset($lang['adminChooseFile'])) ? $lang['adminChooseFile'] : "Choose File"; ?></span><input type="file" name="file" id="hero-image-file" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml" required /></span>
                                <span class="fileinput-filename text-success"></span> <span class="fileinput-new text-danger"><?php echo (isset($lang['adminNoFileChosen'])) ? $lang['adminNoFileChosen'] : "No file chosen..."; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="hero-image-category" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo (isset($lang['adminHeroImageCategory'])) ? $lang['adminHeroImageCategory'] : "Category"; ?></label>
                        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                            <select name="category" id="hero-image-category" class="form-control" required>
                                <option value=""><?php echo (isset($lang['adminSelectCategory'])) ? $lang['adminSelectCategory'] : "Select a category..."; ?></option>
                                <option value="0"><?php echo $category_labels[0]; ?> (0)</option>
                                <option value="1"><?php echo $category_labels[1]; ?> (1)</option>
                                <option value="2"><?php echo $category_labels[2]; ?> (2)</option>
                                <option value="3"><?php echo $category_labels[3]; ?> (3)</option>
                            </select>
                            <p class="help-block"><?php echo (isset($lang['adminHeroImageCategoryHelp'])) ? $lang['adminHeroImageCategoryHelp'] : "Images are randomly selected based on your competition's accepted style types. Miscellaneous images appear on all pages."; ?></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="hero-image-active" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo (isset($lang['adminActive'])) ? $lang['adminActive'] : "Active"; ?></label>
                        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                            <div class="checkbox">
                                <label><input type="checkbox" name="active" id="hero-image-active" value="1" checked> <?php echo (isset($lang['adminHeroImageActiveHelp'])) ? $lang['adminHeroImageActiveHelp'] : "Include this image in the homepage rotation"; ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-9 col-md-offset-3 col-md-9 col-sm-offset-4 col-sm-8 col-xs-12">
                            <button type="submit" class="btn btn-primary"><span class="fa fa-upload"></span> <?php echo (isset($lang['adminUploadImage'])) ? $lang['adminUploadImage'] : "Upload Image"; ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo (isset($lang['adminHeroImageGuidelines'])) ? $lang['adminHeroImageGuidelines'] : "Guidelines"; ?></h3>
            </div>
            <div class="panel-body">
                <ul>
                    <li><strong><?php echo (isset($lang['adminSize'])) ? $lang['adminSize'] : "Size"; ?>:</strong> 3000x500px</li>
                    <li><strong><?php echo (isset($lang['adminRatio'])) ? $lang['adminRatio'] : "Ratio"; ?>:</strong> 6:1 (width:height)</li>
                    <li><strong><?php echo (isset($lang['adminFormats'])) ? $lang['adminFormats'] : "Formats"; ?>:</strong> JPG, PNG, GIF, WebP, SVG</li>
                    <li><strong><?php echo (isset($lang['adminCategories'])) ? $lang['adminCategories'] : "Categories"; ?>:</strong>
                        <ul style="margin-top: 5px;">
                            <li>0 - <?php echo $category_labels[0]; ?></li>
                            <li>1 - <?php echo $category_labels[1]; ?></li>
                            <li>2 - <?php echo $category_labels[2]; ?></li>
                            <li>3 - <?php echo $category_labels[3]; ?></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($hero_images)) { ?>

<h2><?php echo (isset($lang['adminHeroImagesExisting'])) ? $lang['adminHeroImagesExisting'] : "Existing Hero Images"; ?></h2>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><?php echo (isset($lang['adminPreview'])) ? $lang['adminPreview'] : "Preview"; ?></th>
                <th><?php echo (isset($lang['adminFileName'])) ? $lang['adminFileName'] : "File Name"; ?></th>
                <th><?php echo (isset($lang['adminCategory'])) ? $lang['adminCategory'] : "Category"; ?></th>
                <th><?php echo (isset($lang['adminActive'])) ? $lang['adminActive'] : "Active"; ?></th>
                <th><?php echo (isset($lang['adminActions'])) ? $lang['adminActions'] : "Actions"; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hero_images as $image) { ?>
                <tr>
                    <td>
                        <img src="<?php echo $base_url; ?>images/<?php echo htmlspecialchars($image['filename']); ?>" alt="<?php echo htmlspecialchars($image['filename']); ?>" style="max-height: 60px; max-width: 200px; cursor: pointer;" class="img-thumbnail" data-toggle="modal" data-target="#imageModal<?php echo $image['id']; ?>" title="<?php echo (isset($lang['adminClickToEnlarge'])) ? $lang['adminClickToEnlarge'] : "Click to enlarge"; ?>">
                    </td>
                    <td><?php echo htmlspecialchars($image['filename']); ?></td>
                    <td>
                        <select class="form-control form-control-sm hero-category-select" data-hero-id="<?php echo $image['id']; ?>" style="width: 100px;">
                            <option value="0" <?php echo ($image['category'] == 0) ? "selected" : ""; ?>>0 - <?php echo $category_labels[0]; ?></option>
                            <option value="1" <?php echo ($image['category'] == 1) ? "selected" : ""; ?>>1 - <?php echo $category_labels[1]; ?></option>
                            <option value="2" <?php echo ($image['category'] == 2) ? "selected" : ""; ?>>2 - <?php echo $category_labels[2]; ?></option>
                            <option value="3" <?php echo ($image['category'] == 3) ? "selected" : ""; ?>>3 - <?php echo $category_labels[3]; ?></option>
                        </select>
                    </td>
                    <td>
                        <div class="checkbox" style="margin: 0;">
                            <label><input type="checkbox" class="hero-active-toggle" data-hero-id="<?php echo $image['id']; ?>" <?php echo ($image['active']) ? "checked" : ""; ?>> <?php echo ($image['active']) ? (isset($lang['adminYes']) ? $lang['adminYes'] : "Yes") : (isset($lang['adminNo']) ? $lang['adminNo'] : "No"); ?></label>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-xs btn-danger hero-delete-btn" data-hero-id="<?php echo $image['id']; ?>" data-hero-filename="<?php echo htmlspecialchars($image['filename']); ?>">
                            <span class="fa fa-trash"></span> <?php echo (isset($lang['adminDelete'])) ? $lang['adminDelete'] : "Delete"; ?>
                        </button>
                    </td>
                </tr>

                <!-- Modal for enlarged image view -->
                <div class="modal fade" id="imageModal<?php echo $image['id']; ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><?php echo htmlspecialchars($image['filename']); ?></h4>
                            </div>
                            <div class="modal-body">
                                <img src="<?php echo $base_url; ?>images/<?php echo htmlspecialchars($image['filename']); ?>" alt="<?php echo htmlspecialchars($image['filename']); ?>" style="width: 100%; height: auto;">
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </tbody>
    </table>
</div>

<script type="text/javascript" language="javascript">
$(document).ready(function() {

    // Handle category change
    $('.hero-category-select').on('change', function() {
        var heroId = $(this).data('hero-id');
        var category = $(this).val();
        updateHeroImageCategory(heroId, category);
    });

    // Handle active toggle
    $('.hero-active-toggle').on('change', function() {
        var heroId = $(this).data('hero-id');
        var active = ($(this).is(':checked')) ? 1 : 0;
        updateHeroImageActive(heroId, active);
    });

    // Handle delete
    $('.hero-delete-btn').on('click', function() {
        var heroId = $(this).data('hero-id');
        var filename = $(this).data('hero-filename');
        if (confirm('<?php echo (isset($lang['adminConfirmDelete'])) ? $lang['adminConfirmDelete'] : "Are you sure? This will remove the image named"; ?> ' + filename + ' <?php echo (isset($lang['adminFromServer'])) ? $lang['adminFromServer'] : "from the server."; ?>')) {
            deleteHeroImage(heroId, filename);
        }
    });

});

function updateHeroImageCategory(heroId, category) {
    $.ajax({
        type: "POST",
        url: "<?php echo $base_url; ?>ajax/hero_images.ajax.php",
        data: {
            action: "update_category",
            hero_id: heroId,
            category: category,
            user_session_token: "<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>"
        },
        success: function(response) {
            // Update label
            var label = $('.hero-category-select[data-hero-id="'+heroId+'"]').next('option').text();
        },
        error: function() {
            alert('<?php echo (isset($lang['adminErrorUpdating'])) ? $lang['adminErrorUpdating'] : "Error updating image. Please try again."; ?>');
        }
    });
}

function updateHeroImageActive(heroId, active) {
    $.ajax({
        type: "POST",
        url: "<?php echo $base_url; ?>ajax/hero_images.ajax.php",
        data: {
            action: "update_active",
            hero_id: heroId,
            active: active,
            user_session_token: "<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>"
        },
        error: function() {
            alert('<?php echo (isset($lang['adminErrorUpdating'])) ? $lang['adminErrorUpdating'] : "Error updating image. Please try again."; ?>');
        }
    });
}

function deleteHeroImage(heroId, filename) {
    $.ajax({
        type: "POST",
        url: "<?php echo $base_url; ?>ajax/hero_images.ajax.php",
        data: {
            action: "delete",
            hero_id: heroId,
            filename: filename,
            user_session_token: "<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>"
        },
        success: function(response) {
            location.reload();
        },
        error: function() {
            alert('<?php echo (isset($lang['adminErrorDeleting'])) ? $lang['adminErrorDeleting'] : "Error deleting image. Please try again."; ?>');
        }
    });
}
</script>

<?php } else { ?>

<div class="alert alert-info">
    <p><?php echo (isset($lang['adminNoHeroImages'])) ? $lang['adminNoHeroImages'] : "No hero images have been uploaded yet. Upload your first image above."; ?></p>
</div>

<?php } ?>
