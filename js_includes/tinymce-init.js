// JavaScript Document
tinymce.init({
	selector: "textarea",
	menubar: false,
	plugins: [
    "advlist autolink autosave link image lists charmap preview hr anchor pagebreak spellchecker",
    "searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
    "paste"
  ],
	toolbar: "cut copy paste | undo redo | bold italic underline bullist numlist outdent indent | link unlink | code charmap",
	paste_as_text: true
});