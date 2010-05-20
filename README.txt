**********************************************************************
**********************************************************************
**                                                                  **
** Brew Contest Online Signup by Geoff Humphrey - zkdigital.com     **
** Release 1.1.4 Jun 2010                                           **
** This software is free, open source, and is covered under the     **
** General Public License (GPL) from the Open Source Initiative.    **
** As such, you are permitted to download the full source code of   **
** the software for your own use. Feel free to customize it for     **
** your own purposes.                                               **
** Direct inquiries to prost@brewcompetition.com                    **
**                                                                  **
**********************************************************************
**********************************************************************


Thanks for downloading the Brew Contest Online Signup application. 

Please read this entire document before attempting to install or use the application.

This software utilizes PHP 5 and MySQL 4 or 5 to process and store data. 

Your web server needs both of these installed and ready for use. 

Contact your web hosting provider if you have any questions.


**********************************************************************

About

**********************************************************************

The Brew Competition Online Entry (BCOE) system is an online application to assist homebrew contest hosts (of the beer variety) to collect, store, and output participant and entry data.

One of the biggest challenges of organizing a homebrewing contest is knowing who has entered what and how many come contest day. BCOE provides an online interface to collect this information beforehand and output it to compliant software for use on contest day.

BCOE collects contest participant information including name, address, phone, and email address. It also collects and associates entries for each participant based upon 2008 BJCP styles. Participants can have the option to enter their recipes by hand or import them using BeerXML-compliant files.

Once the participant enters their brews, they can print the necessary documentation consisting of BJCP style entry forms and bottle labels.

Administrators can output participant and entry information to interface with Homebrew Competition Coordination Program (HCCP - http://www.folsoms.net/hccp/) - a Windows-based program - or CSV files.

Requires a web server with PHP 5 and MySQL 4+.


**********************************************************************

Please Note

**********************************************************************

You will need to perform the necessary database updates by importing the each of the necessary upgrade documents document via phpMyAdmin or shell access.


**********************************************************************

Installation: Initial Setup

**********************************************************************

1. Create a database on your webserver. The methodology for creating a database varies from hosting provider to hosting provider. Check your provider's online documentation.

2. Add a user to the database you just created. This is typically done via your webserver's control panel.

3. Access your new database and import the database schema. The database schema is contained within the bcoev1.1.4.sql document, located in the sql folder of the release package. Typically, you can import the entire document using PhpMyAdmin.

4. Edit the username, password, and database variables the config.php file located in the competition/Connections/ directory.

4. Edit line 55 of the config.php file with the correct path to BCOE's root installation (this is for the ability to upload pictures).

5. Upload the entire contents of the "bcoe" folder to your webserver via FTP or other method (upload only the *contents* of the folder, not the folder itself).

6. Using your ftp program, change the CHMOD (permissions) of the [root]/user_images folder to 755. This enables you to upload files to that directory using BCOE.

7. Once that is done, you can now set up your installation.


**********************************************************************

Setup

**********************************************************************

1. Browse to your installation's web address (e.g., http://www.yoursite.com/competition/ or http://competition.yoursite.com or http://www.yoursite.com if you're feeling fancy).

2. You'll be taken through a series of steps to customize the setup of your installation. DO NOT SKIP THESE STEPS. Vital information is collected that optimize the installation's behavior and display of data.

3. That's it! You can now browse to your installation's address, further customize it, and/or distribute the web address to begin collecting participants and their associated entries.

4. Enjoy your favorite malted beverage.

