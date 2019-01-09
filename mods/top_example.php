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

<div class="jumbotron">
  <h1>Welcome to Our Competition Website!</h1>
  <h2>Important Information Here (with a Bit Less Shouting)</h2>
  <h3>Less Important Information Here</h3>
  <p>This notice is a simple, yet nifty example of putting BCOE&amp;M&rsquo;s Custom Module feature to work using <a class="hide-loader" href="http://getbootstrap.com/components/#jumbotron" target="_blank">Bootstrap&rsquo;s &ldquo;Jumbotron&rdquo; component</a>. All native Bootstrap 3 components and JavaScript widgets will work with BCOE&amp;M.</p>
  <p><a role="button" class="btn btn-warning btn-lg btn-block" href="<?php echo $base_url; ?>index.php?section=register"><span class="fa fa-beer"></span> Register Now!</a></p>
</div>