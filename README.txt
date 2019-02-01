Brew Competition Online Entry & Management (BCOE&M)
Developed by Geoff Humphrey with code contributions by the GitHub community.

Website:     http://www.brewcompetition.com
GitHub:      https://github.com/geoffhumphrey/brewcompetitiononlineentry
SourceForge: http://sourceforge.net/projects/brewcompetition [Archives ONLY]

Release 2.1.16, 2019-02-01

Developed utilizing a number of extensions and functions, with gratitude to their
respective developers and online communities. Tested with the following versions:
- PHP 5.6.24                      http://www.php.net
  -- PHP's Fileinfo extension must be installed and enabled
  -- see http://php.net/manual/en/fileinfo.setup.php
- MySQL 5.5.42                    http://www.mysql.com
- jQuery 3.1.0                    http://jquery.com
- Bootstrap 3.3.7                 http://getbootstrap.com
- DataTables 1.10.12              http://www.datatables.net
- Fancybox 2.1.5                  http://www.fancyapps.com
- TinyMCE 4.4.0                   http://www.tinymce.com
- Jasny Bootstrap 3.1.3           http://www.jasny.net/bootstrap
- DropZone 4.2.0                  http://www.dropzonejs.com
- Bootstrap Form Validator 0.9.0  http://1000hz.github.io/bootstrap-validator
- Bootstrap-Select 1.9.3          http://silviomoreto.github.io/bootstrap-select
- Font Awesome 4.5.0              http://fortawesome.github.io/Font-Awesome
- FPDF 1.6                        http://www.fpdf.org
- PHPass 0.3                      http://www.openwall.com/phpass
- Tiny But Strong 3.10.1          http://www.tinybutstrong.com
- HTML Purifier 4.9.3             http://htmlpurifier.org/

***********************************************************************************
LICENSE
***********************************************************************************
This software is free, open source, and is covered under the General Public
License (GPL) from the Open Source Initiative. Therefore, you are permitted to
download the full source code of the software for your own use and customize it
for your own purposes - see http://www.brewcompetition.com/license for more.

Brew Competition Online Entry & Management (BCOE&M) is distributed in the hope
that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

It is suggested that you fork the GitHub repository to make integration of your
code with version releases easier. We are always looking for contributers!

Direct inquiries to http://www.brewcompetition.com/contact

***********************************************************************************
PLEASE READ!
***********************************************************************************
Please read this entire document before attempting to install or use the
application.

Step-by-step installation instructions are available at
http://brewcompetition.com/install-instructions.

This software utilizes PHP 5.6.X and MySQL 5.5.X to process and store data.

Your web server needs both of these installed, configured, and ready for use.

Contact your web host if you have any questions.

To install on a local machine, we HIGHLY suggest you download and install XAMPP, a
free Apache web server package that includes both PHP and MySQL.
- Home Page: http://www.apachefriends.org/en/xampp.html
- Mac:       http://www.apachefriends.org/en/xampp-macosx.html
- Windows:   http://www.apachefriends.org/en/xampp-windows.html
- Linux:     http://www.apachefriends.org/en/xampp-linux.html

A modern web browser is also required to take full advantage of the many HTML 5
attributes and functions of the application. The latest versions of Chrome, Firefox,
Microsoft Edge, and Safari render BCOE&M correctly.

***********************************************************************************
Help
***********************************************************************************
Installation Instructions:
http://brewcompetition.com/install-instructions

Upgrade Instructinos:
http://brewcompetition.com/upgrade-instructions

Competition Organization with BCOE&M:
http://brewcompetition.com/comp-org

Load Libraries Locally (No CDNs):
http://brewcompetition.com/local-load

Starting with version 2.0.0, online documentation/help is available within the
application. Click the question mark icon on the top navigation bar when available.
In-app help will be augmented and expanded in future releases.

Other info can be found on the GitHub repository page.

Pre-2.0.0 help is available at http://help.brewcompetition.com.

***********************************************************************************
About BCOE&M
***********************************************************************************
Brew Competition Online Entry & Management is an online  application to assist
homebrew competition hosts (of the beer, cider, mead variety) to collect, store,
and manage their competition entry and scoring data.

The biggest challenges of organizing a homebrewing competition is knowing who has
entered what and how many, organizing judging efficiently, and reporting the
results of the competition in a timely manner. BCOE&M provides a single online
interface to collect entry and participant data, organize judging tables and
assignments, input scoring data, and report the results.

BCOE&M is free and open-source.

***********************************************************************************
Changes in This Version
***********************************************************************************
Details on the official GitHub repository:
https://github.com/geoffhumphrey/brewcompetitiononlineentry

Also check the change log at:
http://www.brewcompetition.com/change-log

***********************************************************************************
Fallback DB Install
***********************************************************************************
For those experiencing any issues related to the initial browser-based setup,
the bcoem_baseline_2.1.X.sql document is available in the package. It contains the
necessary database structure and dummy data for a new installation that can be
installed manually via phpMyAdmin or shell access. Be sure to follow the directions
in the document BEFORE use.