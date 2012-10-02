**********************************************************************
**********************************************************************
**                                                                  **
** Brew Competition Online Entry & Management                       **
** Developed by Geoff Humphrey - zkdigital.com                      **
** With contributions by Mark Alston and Bruce Buerger              **
** Release 1.2.1.1 October 1, 2012                                  **
** This software is free, open source, and is covered under the     **
** General Public License (GPL) from the Open Source Initiative.    **
** Therefore, you are permitted to download the full source code of **
** the software for your own use and customize it for your own      **
** purposes.                                                        **
** http://www.brewcompetition.com                                   **
** http://help.brewcompetition.com                                  **
** Direct inquiries to prost@brewcompetition.com                    **
**                                                                  **
**********************************************************************
**********************************************************************

Please read this entire document before attempting to install or use the application.

- This software utilizes PHP 5.3+ and MySQL 5+ to process and store data. 
- Your web server needs both of these installed and ready for use. 
- Contact your web hosting provider if you have any questions.


**********************************************************************

Help

**********************************************************************

Online documentation and help is available at http://help.brewcompetition.com


**********************************************************************

Hosting

**********************************************************************

If you are unable to set up your installation, HOSTING IS AVAILABLE!
Go to http://www.brewcompetition.com and click "Hosting" for more information.


**********************************************************************

About

**********************************************************************

The Brew Competition Online Entry and Management (BCOE&M) system is an online application to assist homebrew competition hosts (of the beer variety) to collect, store, and manage their competition entry and scoring data.

The biggest challenges of organizing a homebrewing competition is knowing who has entered what and how many, organizing judging efficiently, and reporting the results of the competition in a timely manner. BCOE&M provides a single online interface to collect entry and participant data, organize judging tables and assignments, collect scores, and report the results.

BCOE&M is free and open-source.


**********************************************************************

Changes in This Version

**********************************************************************

This release features mostly bug fixes and a couple of enhancements.

Addresses Google Code Issues 160, 163, 164, 166, 167, 169, 171, 172, 173, 174, 175, 176, and 177
Access: http://code.google.com/p/brewcompetitiononlineentry/issues

**********************************************************************

Installation: Initial Setup

**********************************************************************

1. 	Create a MySQL Database and Authorized User
	- BCOE&M must have access to a MySQL database to function. The database can either be one dedicated to your installation or one that is shared with other applications.
	- For a dedicated MySQL database:
	   -- Using your web hosting provider's chosen methodology, create a database to connect your BCOE&M installation to. Consult your hosting provider's documentation as needed.
	   -- Create and/or add an authorized user to the database. Again, consult your hosting provider's documentation as needed.
	   -- Make sure the user has the following privileges (at minimum): 
		  --- Alter
		  --- Create
		  --- Delete
		  --- Insert
		  --- Update
		  --- Drop
		  --- Select
	- For use with a shared database:
	   -- Obtain the database name and authorized user credentials.
	   -- See Upload Files to Your Webserver below.

2. 	Upload Files to Your Webserver
	- Unzip the BCOE&M archive file locally.
	- Locate the "sites" sub-folder in the "competition" folder of the unzipped archive.
	- Using a text editor or your favorite WYSIWYG editor, open the config.php file in the "sites" folder. Add username, password, and database variables for your BCOE&M installation's MySQL database.
	- If the installation will be using a shared MySQL database, you must designate a prefix in the $prefix variable (e.g., comp_).
	- Change the $setup_free_access variable to TRUE. Set up cannot be run if this variable is set to FALSE.
	- Connect to your webserver via FTP.
	- Upload all files and folders from the "competition" folder ONLY.
	- Finally, if you are receiving server errors after trying to access your installation, check here.

3. 	Proceed Through the Set Up Process
	- Browse to your installation's web address. If you are using the hosted option, you will receive an email with instructions and a web address.
	- You'll be taken through a series of steps to install the needed database tables and to customize your installation. 
	- Do not skip these steps. Vital information is collected that optimize the installation's behavior and display of data. 
	- During the set up process you will: 
	   -- Add the necessary database tables. 
	   -- Create the administrator's user file and credentials.
	   -- Define the site's preferences.
	   -- Input information about your competition (e.g., rules, award structure, etc.)
	   -- Input drop-off locations.
	   -- Input judging locations.
	   -- Define the BJCP styles accepted.
	   -- Define judging preferences (e.g., whether to use queued judging, flight size, maximum number of rounds, etc.).
   
4. 	After Set Up is Complete
	- EDIT the config.php file. Change the $setup_free_access variable back to FALSE and re-upload to your server. This is a security measure.

That's it! After set up, you can browse to your installation's address, further customize it, and/or distribute the web address to begin collecting participant data and their associated entries.

Enjoy your favorite fermented beverage.


**********************************************************************

Installation: Upgrading

**********************************************************************

1. 	Update the Database
	- As of version 1.2.1.0, there is no need to perform a manual update to the MySQL database (via phpMyAdmin or other means).

2. 	Upload the New Files
	- Most versions add or make changes to multiple files. Therefore, you should replace all of the folders/files on your web server.
	- If you have made changes to any code, be sure to back up the appropriate files and compare with the files in the latest BCOE&M version.
	- Unzip the BCOE&M archive file locally.
	- Locate the "sites" sub-folder in the "competition" folder of the unzipped archive.
	- Using a text editor or your favorite WYSIWYG editor, open the config.php file in the "sites" folder. Add username, password, and database variables for your BCOE&M installation's MySQL database.
	- Connect to your webserver via FTP.
	- Upload all files and folders from the "competition" folder ONLY.
	- After uploading the files, navigate to your site's web address.
	- Proceed through the update process. Only users with administrative access can initiate the update process.
	
4.  Proceed through the Updata Process

5. 	Update Your Site Preferences
	Most versions add new features. Consequentlyu, you may need to set preferences for them.
	- Log in.
	- Roll over Admin in the top navigation bar.
	- Choose Defining Preferences > Define > Site Preferences.
	- Adjust your site's preferences.

6. 	Choose your Competition Organization Preferences
	- Log in.
	- Roll over Admin in the top navigation bar.
	- Choose Defining Preferences > Define > Competition Organization Preferences.
	- Adjust your site's competition organization preferences.