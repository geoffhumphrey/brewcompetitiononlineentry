Brew Competition Online Entry & Management (BCOE&M)
Developed by Geoff Humphrey with code contributions by Mark Alston, Bruce Buerger, 
Oskar Stephens, and Luis Balbinot.

Website:     http://www.brewcompetition.com
GitHub:      https://github.com/geoffhumphrey/brewcompetitiononlineentry
SourceForge: http://sourceforge.net/projects/brewcompetition

Release 2.0.0, February 7, 2016

Developed utilizing the following extensions and functions, with gratitude to their
respective developers and online communities:
- jQuery                    http://jquery.com
- Bootstrap 3               http://getbootstrap.com 
- DataTables                http://www.datatables.net 
- Fancybox                  http://www.fancyapps.com   
- TinyMCE                   http://www.tinymce.com
- Jasny Bootstrap           http://www.jasny.net/bootstrap
- DropZone                  http://www.dropzonejs.com
- Bootstrap Form Validator  http://1000hz.github.io/bootstrap-validator
- Bootstrap-Select          http://silviomoreto.github.io/bootstrap-select
- Font Awesome              http://fortawesome.github.io/Font-Awesome   
- FPDF                      http://www.fpdf.org
- PHPass                    http://www.openwall.com/phpass
- Tiny But Strong           http://www.tinybutstrong.com

This software is free, open source, and is covered under the General Public
License (GPL) from the Open Source Initiative. Therefore, you are permitted to 
download the full source code of the software for your own use and customize it 
for your own purposes - see http://www.brewcompetition.com/license for more.

It is suggested that you fork the GitHub repository to make integration of your 
code with version releases easier. We are always looking for contributers!

Direct inquiries to http://www.brewcompetition.com/contact

***********************************************************************************
PLEASE READ!
***********************************************************************************
Please read this entire document before attempting to install or use the 
application. Step-by-step installation instructions are available at
http://www.brewcompetition.com/install.

This software utilizes PHP 5.3+ and MySQL 5+ to process and store data. 

A modern web browser is also required to take full advantage of the many HTML 5
attributes and functions of the application. The latest versions of Chrome, Firefox,
Internet Explorer, and Safari render BCOE&M correctly.

Your web server needs both of these installed and ready for use. 

Contact your web host if you have any questions.

To install on a local machine, we HIGHLY suggest you download and install XAMPP, a 
free Apache web server package that includes both PHP and MySQL. 
- Home Page: http://www.apachefriends.org/en/xampp.html
- Mac:       http://www.apachefriends.org/en/xampp-macosx.html
- Windows:   http://www.apachefriends.org/en/xampp-windows.html
- Linux:     http://www.apachefriends.org/en/xampp-linux.html
  
***********************************************************************************
Help
***********************************************************************************
Starting with version 2.0.0, online documentation/help is available within the 
application. Click the question mark icon on the top navigation bar when available.
In-app help will be expanded in future release versions.

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
Complete conversion of the user interface code to leverage the popular Bootstrap 3
framework as well as other HTML 5 functions. This version is very mobile friendly!

Details on the official GitHub repository.
Also check http://www.brewcompetition.com/whats-new.

***********************************************************************************
Fallback DB Install
***********************************************************************************
For those experiencing any issues related to initial browser-based setup of BCOE&M, 
the bcoem_baseline_2.0.X.sql document is available in the package. It contains the 
necessary database structure and dummy data for a new installation that can be 
installed manually via phpMyAdmin or shell access. Be sure to follow the directions 
in the document BEFORE use.