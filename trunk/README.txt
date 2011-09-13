**********************************************************************
**********************************************************************
**                                                                  **
** Brew Competition Online Entry & Management                       **
** Developed by Geoff Humphrey - zkdigital.com                      **
** Release 1.2.0.2 June 2011                                        **
** This software is free, open source, and is covered under the     **
** General Public License (GPL) from the Open Source Initiative.    **
** As such, you are permitted to download the full source code of   **
** the software for your own use. Feel free to customize it for     **
** your own purposes.                                               **
** http://www.brewcompetition.com                                   **
** http://help.brewcompetition.com                                  **
** Direct inquiries to prost@brewcompetition.com                    **
**                                                                  **
**********************************************************************
**********************************************************************

Please read this entire document before attempting to install or use the application.

This software utilizes PHP 5 and MySQL 4 or 5 to process and store data. 

Your web server needs both of these installed and ready for use. 

Contact your web hosting provider if you have any questions.


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

Requires a web server with PHP 5 and MySQL 4+.



**********************************************************************

Please Note

**********************************************************************

You will need to perform the necessary database updates by importing the each of the necessary upgrade documents document via phpMyAdmin or shell access.



**********************************************************************

Changes in This Version

**********************************************************************

This release address several small bugs and feature enhancements that were reported to Google Code and SourceForge between August 18, 2011 and September 13, 2011.

- Fixed PayPal connection error (Issue 92 on Google Code)
- Added a missing javascript file (Issue 94)
- Fixed Archive data display bug (Issue 95)
- Fixed bug preventing PayPal payments on installations that have discount codes (Issue 96)
- Fixed minor bug that allowed entries to be added without a Entry Name (Issue 97)
- Added reported strength, carbonation, and dryness levels for Cider and Mead styles to pullsheets
- Added coding to convert entry name and brewer name entry text to show proper capitalization
- Added Google Web Fonts API fonts to the css files of all themes



**********************************************************************

Installation: Initial Setup

**********************************************************************

- Create a database on your web server. The methodology for creating a database varies from hosting provider to hosting provider. Check your provider's online documentation.

- Add a user to the database you just created. This is typically done via your web server's control panel.

- Access your new database and import the database schema. The database schema is contained within the bcoev1.X.X.sql document (use the latest version number's file), located in the sql folder of the release package. Typically, you can import the entire document using a tool like PhpMyAdmin.

- Edit the username, password, and database variables the config.php file located in the competition/sites/ directory.

- If necessary for your environment, edit line 55 of the config.php file with the correct path to BCOE&M's root installation (this is for the ability to upload pictures).

- Upload the entire contents of the "bcoem" folder to your webserver via FTP or other method (upload only the *contents* of the folder, not the folder itself).

- Using your ftp program, change the CHMOD (permissions) of the [root]/user_images folder to 755. This enables you to upload files to that directory using BCOE&M.

- Once that is done, you can now set up your installation.



**********************************************************************

Installation: Upgrading

**********************************************************************

1. Upgrade The Database

IF you are upgrading from a version previous to the current version, you must install all previous SQL upgrades FIRST. These are included in the sql folder in the download package.
- Access your installation's BCOE&M database (via phpMyAdmin or shell access).
- Import or copy/paste the information contained in each of the upgrade documents up to and including the 1.X.X_upgrade.sql (current version) document. All can be found in the sql folder. This will update your database's structure and insert relevant data.

2. Upload the New Files

Most versions add and make changes to multiple files. Therefore you should replace all of the folders/files on your web server.
If you have made changes to any code, be sure to back up the affected files and compare with the files in the latest BCOE&M version.

3. Update Your Preferences (optional)

Most versions add new features. As such, you may need to set preferences for them.
- Log in.
- Roll over Admin in the top navigation bar.
- Choose Edit from the list and then choose Preferences.
- Adjust your preferences.



**********************************************************************

Setup

**********************************************************************

After you have created the needed database, uploaded BCOE&M's files to your web server, and edited the config.php file, you can now set up your installation.

- Browse to your installation's web address (e.g., http://www.yoursite.com/competition/ or http://competition.yoursite.com or http://www.yoursite.com if you're feeling fancy).
-- If you are using the hosted option, you will receive an email with instructions and a web address.

- You'll be taken through a series of steps to customize the setup of your installation. Do not skip these steps. Vital information is collected that optimize the installation's behavior and display of data. During the set up process you will:
-- Create the administrator's user file and credentials.
-- Define the site's preferences.
-- Input information about your competition (e.g., rules, award structure, etc.)
-- Input drop-off locations.
-- Input judging locations.
-- Define the BJCP styles accepted.
-- Define judging preferences (e.g., whether to use queued judging, flight size, maximum number of rounds, etc.).

- That's it! After set up, you can browse to your installation's address, further customize it, and/or distribute the web address to begin collecting participant data and their associated entries.

- Enjoy your favorite fermented beverage.

