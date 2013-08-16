**********************************************************************
**********************************************************************
**                                                                  **
** Brew Competition Online Entry & Management                       **
** Developed by Geoff Humphrey - zkdigital.com                      **
** With contributions by Mark Alston, Bruce Buerger, Oskar Stephens **
** and Luis Balbinot                                                **
** Release 1.3.0.0 August, 2013                                     **
** This software is free, open source, and is covered under the     **
** General Public License (GPL) from the Open Source Initiative.    **
** Therefore, you are permitted to download the full source code of **
** the software for your own use and customize it for your own      **
** purposes (http://help.brewcompetition.com/files/license.html).   **
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
- To install on a local machine, I HIGHLY suggest you download and install XAMPP (http://www.apachefriends.org/en/xampp.html), a 

free Apache web server that includes both PHP and MySQL. 
  -- Mac Download: http://www.apachefriends.org/en/xampp-macosx.html
  -- Windows Download: http://www.apachefriends.org/en/xampp-windows.html
  -- Linux Download: http://www.apachefriends.org/en/xampp-linux.html

**********************************************************************

Help

**********************************************************************

Online documentation and help is available at http://help.brewcompetition.com

**********************************************************************

Hosting

**********************************************************************

If you are unable to set up your installation, hosting may be an option for you.
Go to http://www.brewcompetition.com and click "Hosting" for more information.

**********************************************************************

About

**********************************************************************

The Brew Competition Online Entry and Management (BCOE&M) system is an online application to assist homebrew competition hosts 
(of the beer variety) to collect, store, and manage their competition entry and scoring data.
The biggest challenges of organizing a homebrewing competition is knowing who has entered what and how many, organizing judging 
efficiently, and reporting the results of the competition in a timely manner. BCOE&M provides a single online interface to 
collect entry and participant data, organize judging tables and assignments, collect scores, and report the results.
BCOE&M is free and open-source.

**********************************************************************

Changes in This Version

**********************************************************************

This release marks some major improvements to stability, security while addressing server load issues:
- Code extensions in the form of custom modules - admins can extend the functionality of BCOE&M with their own HTML or PHP code 

(advanced users only).
- Barcode option for bottle entry forms and entry check-in so comp staff can use a barcode scanner to check-in entries and assign judging numbers them. The methodology was tested in the 8000+ entry National Homebrewers Competition this year and was very well received by the competition staff.
- Entry limit per user option.
- Pre-registration option - users can create their accounts and enter their personal information, judging preferences, etc. before entries are accepted.
- Subcategory entry limit per user option - from unlimited to a single entry per user per subcategory.
- Subcategory entry limit exception option - for those subcategories that lend themselves to a broader range of interpretation (e.g., Specialty Beer, Spice/Herb/Vegetable Beer, Open Category Mead, Specialty Cider and Perry, etc.).
- Admin ability to enable/disable search engine friendly URLs for all public pages.
- Extended printing options including larger round bottle labels, labels with special ingredients, category/subcategory only labels, etc.
- Expansion of recipe-related fields to accommodate more ingredients - malts/grains, other fermentables, and hops increase to 20 fields each, mash schedule increases to 10.
- Enhanced recipe entry with robust checks for the presence of required information for certain styles (e.g., special ingredients for Category 23, strength for mead styles, etc.)
- Extended use of session variables to limit calls to the MySQL database for redundant/constant pieces of information customized for each user. 
- Numerous behind-the-scenes coding clean up and enhancements aimed at improving performance and reducing page load times.
- Enhanced password security 

Addresses the following reported issues on Google Code (http://code.google.com/p/brewcompetitiononlineentry/issues/list):
- Issue 198
- Issue 206
- Issue 207
- Issue 218
- Issue 221
- Issue 222
- Issue 223
- Issue 225
- Issue 226
- Issue 227
- Issue 228
- Issue 229
- Issue 230
- Issue 231
- Issue 232
- Issue 236
- Issue 237
- Issue 240
- Issue 241
- Issue 242
- Issue 243
- Issue 245
- Issue 246
- Issue 247
- Issue 249
- Issue 252
- Issue 253
- Issue 254
- Issue 255
- Issue 256
- Issue 257
- Issue 258
- Issue 260
- Issue 261
- Issue 262
- Issue 263
- Issue 266
- Issue 269
- Issue 271
- Issue 272
- Issue 273
- Issue 274
- Issue 275
- Issue 276

Go to http://help.brewcompetition.com/files/whatsnew.html for more.

**********************************************************************

Installation: Go to http://help.brewcompetition.com/files/installation.html for installation instructions.

Upgrading: Go to http://help.brewcompetition.com/files/upgrading.html for upgrading instructions.

License: Go to http://help.brewcompetition.com/files/license.html
