Dropzone.options.uploadWidget = {
  paramName: 'file',
  maxFilesize: 2, // MB
  dictDefaultMessage: 'Drag images here to upload. Or, click here to select images from your files. Only .jpg, .gif, or .png files will be accepted.',
  dictFileTooBig: 'Image size is too big. Maximum size is 2mb.',
  acceptedFiles: '.jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF',
  init: function () {
        // Set up any event handlers
		// Refresh page when all images are uploaded
        this.on("queuecomplete", function (file) {
          location.reload();
      });
    }
};