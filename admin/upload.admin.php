<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/dropzone.css"> -->

<script type="text/javascript">
// The camelized version of the ID of the form element
Dropzone.options.myAwesomeDropzone = { 
// set following configuration
autoProcessQueue: false,
uploadMultiple: true,
parallelUploads: 10,
maxFiles: 10,
addRemoveLinks: true,
previewsContainer: ".dropzone-previews",
dictRemoveFile: "Remove",
dictCancelUpload: "Cancel",
dictDefaultMessage: "Drop the images you want to upload here",
dictFileTooBig: "Image size is too big. Max size: 10mb.",
dictMaxFilesExceeded: "Only 10 images allowed per upload.",
acceptedFiles: ".jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF",
// The setting up of the dropzone
init: function() {
var myDropzone = this;
// Upload images when submit button is clicked.
$("#submit-all").click(function (e) {
e.preventDefault();
e.stopPropagation();
myDropzone.processQueue();
});
// Refresh page when all images are uploaded
myDropzone.on("complete", function (file) {
if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) {
window.location.reload();
}
});
}
}
</script>
<script>
    var Dropzone = require("enyo-dropzone");
    Dropzone.autoDiscover = false;
  </script>

  <style>
    html, body {
      height: 100%;
    }
    #actions {
      margin: 2em 0;
    }


    /* Mimic table appearance */
    div.table {
      display: table;
    }
    div.table .file-row {
      display: table-row;
    }
    div.table .file-row > div {
      display: table-cell;
      vertical-align: top;
      border-top: 1px solid #ddd;
      padding: 8px;
    }
    div.table .file-row:nth-child(odd) {
      background: #f9f9f9;
    }



    /* The total progress gets shown by event listeners */
    #total-progress {
      opacity: 0;
      transition: opacity 0.3s linear;
    }

    /* Hide the progress bar when finished */
    #previews .file-row.dz-success .progress {
      opacity: 0;
      transition: opacity 0.3s linear;
    }

    /* Hide the delete button initially */
    #previews .file-row .delete {
      display: none;
    }

    /* Hide the start and cancel buttons and show the delete button */

    #previews .file-row.dz-success .start,
    #previews .file-row.dz-success .cancel {
      display: none;
    }
    #previews .file-row.dz-success .delete {
      display: block;
    }


  </style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
<p class="lead">Click the &ldquo;Add Logos...&rdquo; button below or drag and drop files.</p>
<form action="<?php echo $base_url; ?>includes/upload.inc.php" class="dropzone">
<div id="container">
    <div id="actions" class="row">
    
          <div class="col-lg-7">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button">
                <i class="fa fa-plus-circle"></i>
                <span>Add Logos...</span>
            </span>
            <button type="submit" class="btn btn-primary start">
                <i class="fa fa-upload"></i>
                <span>Upload All</span>
            </button>
            <button type="reset" class="btn btn-warning cancel">
                <i class="fa fa-ban"></i>
                <span>Cancel Upload</span>
            </button>
          </div>
    
          <div class="col-lg-5">
            <!-- The global file processing state -->
            <span class="fileupload-process">
              <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
              </div>
            </span>
          </div>
    
        </div>
    
    
    <!-- HTML heavily inspired by http://blueimp.github.io/jQuery-File-Upload/ -->
    <div class="table table-striped" class="files" id="previews">
    
      <div id="template" class="file-row">
        <!-- This is used as the file preview template -->
        <div>
            <span class="preview"><img data-dz-thumbnail /></span>
        </div>
        <div>
            <p class="name" data-dz-name></p>
            <strong class="error text-danger" data-dz-errormessage></strong>
        </div>
        <div>
            <p class="size" data-dz-size></p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
              <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
            </div>
        </div>
        <div>
          <button class="btn btn-primary start">
              <i class="fa fa-upload"></i>
              <span>Start</span>
          </button>
          <button data-dz-remove class="btn btn-warning cancel">
              <i class="fa fa-ban"></i>
              <span>Cancel</span>
          </button>
          <button data-dz-remove class="btn btn-danger delete">
            <i class="fa fa-trash-o"></i>
            <span>Delete</span>
          </button>
        </div>
      </div>
    
    </div>
</div>
</form>

<script>
// Get the template HTML and remove it from the doumenthe template HTML and remove it from the document
var previewNode = document.querySelector("#template");
previewNode.id = "";
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);

var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
  url: "/target-url", // Set the url
  thumbnailWidth: 80,
  thumbnailHeight: 80,
  parallelUploads: 20,
  previewTemplate: previewTemplate,
  autoQueue: false, // Make sure the files aren't queued until manually added
  previewsContainer: "#previews", // Define the container to display the previews
  clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
});

myDropzone.on("addedfile", function(file) {
  // Hookup the start button
  file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
});

// Update the total progress bar
myDropzone.on("totaluploadprogress", function(progress) {
  document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
});

myDropzone.on("sending", function(file) {
  // Show the total progress bar when upload starts
  document.querySelector("#total-progress").style.opacity = "1";
  // And disable the start button
  file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
});

// Hide the total progress bar when nothing's uploading anymore
myDropzone.on("queuecomplete", function(progress) {
  document.querySelector("#total-progress").style.opacity = "0";
});

// Setup the buttons for all transfers
// The "add files" button doesn't need to be setup because the config
// `clickable` has already been specified.
document.querySelector("#actions .start").onclick = function() {
  myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
};
document.querySelector("#actions .cancel").onclick = function() {
  myDropzone.removeAllFiles(true);
};
</script>