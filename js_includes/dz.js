Dropzone.options.uploadWidget = {
  paramName: 'file',
  maxFilesize: 10, // MB
  dictDefaultMessage: 'Drag files here to upload. Or, click here to select from your files.',
  dictFileTooBig: 'Image size is too big. Maximum size is 10mb.',
  acceptedFiles: '.jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF',
  init: function () {
        // Set up any event handlers
		// Refresh page when all images are uploaded
        this.on("queuecomplete", function (file) {
          location.reload();
      });
    }
};