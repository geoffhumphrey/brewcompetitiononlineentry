<!--
SOME THINGS TO NOTE:
- DO NOT include <html> or <body> tags!!
- All files MUST have a .php extension (e.g., name_of_file.php - some servers running PHP are not configured to include files with other exensions).
- For the program to use any custom module, its information MUST be added into the database.
  -- The Custom Modules option must be enabled via Website Preferences
  -- The EXACT filename and other info (position, rank, description) must be entered into the database via the Add Custom Modules screen
- The corresponding file should be uploaded to the "mods" sub-folder via secure FTP.
- Custom modules can only be placed above content (just below the main navigation) or below content (just above the footer).
- You can have multiple custom modules. They will be displayed in the rank order you choose.

For assistance with Bootstrap elements, reference the Bootstrap website:
- CSS:        http://getbootstrap.com/css/
- Components: http://getbootstrap.com/components/
- JavaScript: http://getbootstrap.com/javascript/

BCOE&M uses Font Awesome icons throughout the core code. To use Font Awesome icons, reference the following:
- Font Awesome icon list:     https://fortawesome.github.io/Font-Awesome/icons/
- Font Awesome icon examples: https://fortawesome.github.io/Font-Awesome/examples/

To use Bootstrap's native icon set, glyphicons, go to http://getbootstrap.com/components/#glyphicons for a list and how to integrate
-->

<div class="panel panel-info">
	<div class="panel-heading">
		<h4 class="panel-title">Twitter Feed</h4>
	</div>
	<div class="panel-body">
    	<a class="twitter-timeline" href="https://twitter.com/RockHoppersBC" data-widget-id="692834488125030400" width="230">Tweets by @RockHoppersBC</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        <p class="small">Example of a custom module for the public pages sidebar.</p>
	</div>
</div>