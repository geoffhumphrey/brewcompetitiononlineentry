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
<div class="container">
<h2>Explore Further</h2>
<p>This is an example of a custom module placed at the bottom of the page with <a class="hide-loader" href="http://getbootstrap.com/" target="_blank">Bootstrap 3</a> markup. In this case, a simple button group with split dropup menu.</p>
<div class="btn-group dropup" role="group" aria-label="...">
	<a class="hide-loader" href="http://www.brewcompetition.com" target="_blank" role="button" class="btn btn-default">BCOE&amp;M</a>
    <a class="hide-loader" href="http://bjcp.org/stylecenter.php" target="_blank" role="button" class="btn btn-default">BJCP Styles</a>
    <a class="hide-loader" href="http://www.homebrewersassociation.org" target="_blank" role="button" class="btn btn-default">American Homebrewers Association</a>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default">More... </span></button>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    		<span class="caret"></span>
    		<span class="sr-only">Toggle Dropdown</span>
  		</button>
        <ul class="dropdown-menu">
            <li><a class="hide-loader" href="https://www.homebrewersassociation.org/forum/" target="_blank">AHA Forum</a></li>
            <li><a class="hide-loader" href="http://www.beerxml.com/" target="_blank">Beer XML Standard</a></li>
        </ul>
    </div>
</div>
</div>