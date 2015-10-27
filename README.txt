**********************************************************************
**********************************************************************
**                                                                  **
** Brew Competition Online Entry & Management                       **
** Developed by Geoff Humphrey - zkdigital.com                      **
** With contributions by Mark Alston, Bruce Buerger, Oskar Stephens **
** and Luis Balbinot                                                **
** Release 1.3.2.0 October, 2015                                    **
** This software is free, open source, and is covered under the     **
** General Public License (GPL) from the Open Source Initiative.    **
** Therefore, you are permitted to download the full source code of **
** the software for your own use and customize it for your own      **
** purposes (http://www.brewcompetition.com/license).               **
** http://www.brewcompetition.com                                   **
** http://help.brewcompetition.com                                  **
** Direct inquiries to prost@brewcompetition.com                    **
**                                                                  **
**********************************************************************
**********************************************************************

Please read this entire document before attempting to install or use 
the application.
- This software utilizes PHP 5.3+ and MySQL 5+ to process and store 
data. 
- Your web server needs both of these installed and ready for use. 
- Contact your web host if you have any questions.
- To install on a local machine, we HIGHLY suggest you download 
  and install XAMPP - http://www.apachefriends.org/en/xampp.html - 
  a free Apache web server package that includes both PHP and MySQL. 
  -- Mac: http://www.apachefriends.org/en/xampp-macosx.html
  -- Windows: http://www.apachefriends.org/en/xampp-windows.html
  -- Linux: http://www.apachefriends.org/en/xampp-linux.html
  
**********************************************************************
Help
**********************************************************************
Online documentation and help is available at 
http://help.brewcompetition.com

**********************************************************************
About
**********************************************************************
The Brew Competition Online Entry and Management (BCOE&M) system is an 
online application to assist homebrew competition hosts (of the beer 
variety) to collect, store, and manage their competition entry and 
scoring data.

The biggest challenges of organizing a homebrewing competition is 
knowing who has entered what and how many, organizing judging 
efficiently, and reporting the results of the competition in a timely 
manner. BCOE&M provides a single online interface to collect entry and 
participant data, organize judging tables and assignments, input  
scoring data, and report the results.

BCOE&M is free and open-source.

**********************************************************************
Changes in This Version
**********************************************************************
Various bug fixes. Addresses GitHub issues 448, 480, 481, 482, 483, 486,
489, 490, 491, 492, 498, 500, 502, 505, 506.

Added support for A4 mailing labels.
Added manual triggering of database cleanup and purge functions.

For those experiencing any issues related to initial browser-based 
setup of BCOE&M, the bcoem_baseline_1.3.2.X.sql document is available in 
the package. It contains the necessary database structure and dummy 
data for a new installation that can be installed manually via 
phpMyAdmin or shell access. Be sure to follow the directions in the
document *before* use.