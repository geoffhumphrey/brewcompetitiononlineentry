<?php if (($go == "styles") && (($action == "add") || ($action == "edit"))) { ?>
<script>
// Bulleted and numbered HTML lists break the display of styles on the add/edit entry screens
// Remove ability to add bulleted and numbered HTML lists until a solution can be found
tinymce.init({
	selector: "textarea",
	menubar: false,
	plugins: [
    "advlist autolink autosave spellchecker",
    "searchreplace wordcount nonbreaking",
    "paste"
  ],
	toolbar: "cut copy paste | undo redo | bold italic underline | charmap",
	paste_as_text: true
});
</script>
<?php } else { ?>
<script>
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
</script>
<?php } ?>