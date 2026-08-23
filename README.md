# Brew Competition Online Entry & Management

### Please check the _[Good to Know](https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues?q=is%3Aissue+label%3A%22good+to+know%21%22)_ list before posting any issue. ###

---

Working repository of BCOE&M.

Website: https://www.brewingcompetitions.com
Helpful Articles:
 - [License](https://info.brewingcompetitions.com/license)
 - [Release Notes](https://info.brewingcompetitions.com/release-notes)
 - [Installation Instructions](https://info.brewingcompetitions.com/install-instructions)
 - [Upgrade Instructions](https://info.brewingcompetitions.com/upgrade-instructions)

The Brew Competition Online Entry and Management (BCOE&M) system is an online application to assist homebrew competition organizers - of the beer/mead/cider variety - to collect, store, and manage their competition entry, organization, and scoring data.

The biggest challenges of organizing a homebrewing competition is knowing who has entered what and how many, organizing judging efficiently, and reporting the results of the competition in a timely manner. BCOE&M provides a single online interface to collect entry and participant data, organize judging tables and assignments, input scoring data, and report the results. Features include, but certainly aren't limited to:
- Collecting entry information from participants.
- Four major style guideline collections to use: BJCP 2021, BJCP 2015, Brewers Association (BA), Australian Amateur Brewing Championship (AABC).
- Defining categories and styles customized to your competition's needs.
- Facilitating online entry fee payments (via PayPal).
- Organizing and assigning participants as judges, stewards, and staff.
- Defining tables/flights and assigning judges and stewards to them.
- Mobile entry check-in using [QR and/or barcodes](https://info.brewingcompetitions.com/barcode-check-in).
- [Electronic scoresheets](https://info.brewingcompetitions.com/setup-electronic-scoresheets) for use in [virtual](https://brewingcompetitions.com/virtual-judging) and/or in-person [judging](https://brewingcompetitions.com/judging-with-electronic-scoresheets).
- Scoresheet [upload](https://info.brewingcompetitions.com/upload-scoresheets).
- 60+ reports for use before, during, and after judging.
- 20+ data export options.
- Custom modules for information/functionality unique to your competition.

The best part: **BCOE&M is free and open-source**. Hundreds of competitions around the world have utilized BCOE&M since its [first release](https://brewingcompetitions.com/change-log) back in 2009.

## Download
The latest version is available for [download here](https://github.com/geoffhumphrey/brewcompetitiononlineentry/releases). The [latest committed code](https://github.com/geoffhumphrey/brewcompetitiononlineentry/archive/master.zip) is also available for testers and contributors.

## Install or Upgrade
Step by step [installation](https://info.brewingcompetitions.com/install-instructions) and [upgrade](https://info.brewingcompetitions.com/upgrade-instructions) instructions are available.

After configuration to your environment, installation is a breeze via the online setup interface.

## Requirements
- PHP 8.3 or newer (PHP 8.3, 8.4, and 8.5 are exercised in CI).
- MySQL 5.7 or newer (integration tests run against MySQL 8.0).
- [Composer](https://getcomposer.org) is required to install dependencies; the `vendor/` directory is part of the deployment.

## Fallback Installation
There are times when the online setup encounters issues that prevent the installation from successfully completing. That's why there's a [Fallback Installation](https://info.brewingcompetitions.com/install-instructions) method. For those experiencing any issues related to the initial browser-based setup, the bcoem_baseline_3.X.X.sql document is available in the package's /sql/ folder. This document contains the necessary database structure and dummy data for a new installation that can be installed manually via phpMyAdmin or shell access. Be sure to follow the directions in the document **BEFORE** use.

## Issue Reporting and Bug Fixes
Many bugs and issues reported to this repository are corrected before an official release is available. Before reporting a bug, be sure to check the [Issues](https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues) list to see if it has been addressed already. If it has, chances are the latest commit package contains code to fix the issue. Keep an eye out for the [*fixed in latest master commit*](https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues?q=is%3Aissue+is%3Aopen+label%3A%22in+latest+master+commit%22) tag. Needless to say, however, the master and other branch commits housed here in the repository are **NOT FOR PRODUCTION**! Bugs may be present.

## Help and Resources
Help is integrated into the application. Just look for the question-mark icon in the main navigation.

There is also a growing number of instructive resources available on the [companion website](https://info.brewingcompetitions.com) for various options, including the following:
- [Competition Organization with BCOE&M](https://info.brewingcompetitions.com/comp-org) - an end to end guide to using BCOE&M as your main organizational tool
- [Load Libraries Locally](https://info.brewingcompetitions.com/local-load) - disable CDN loading of external libraries such as jQuery, Bootstrap, DataTables, etc.
- [Setup BCOE&M Electronic Scoresheets](https://info.brewingcompetitions.com/setup-electronic-scoresheets) - primer for Admins to effectively set up and use Electronic Scoresheets
- [Virtual Judging](https://info.brewingcompetitions.com/virtual-judging) - information and suggestions for judges particpating in virtual judging sessions.
- [Virtual Judging Tips for Judges](https://info.brewingcompetitions.com/virtual-judging/tips) - tips and tricks for evaluating homebrew entries virtually.
- [Upload Scanned Judges' Scoresheets](https://info.brewingcompetitions.com/upload-scoresheets) - procedure for scanning and uploading scoresheets to make available to entrants via BCOE&M
- [Reset Competition Information](https://info.brewingcompetitions.com/reset-comp) - get your site ready for your next competition iteration
- [Barcode or QR Code Entry Check-in](https://info.brewingcompetitions.com/barcode-check-in) - utilize the barcode/QR code enabled bottle labels to efficiently check-in entries
- [Implement PayPal Instant Payment Notifications](https://info.brewingcompetitions.com/paypal-ipn) - receive and process PayPal payment data to update entrant payment status instantly

## Wanna Help with Development?
Fork this repo and share your code!

## Credits
BCOE&M is developed by Geoff Humphrey with code contributions by the GitHub community, and utilizes a number of extensions and functions with gratitude to their respective developers and online communities:
- jQuery 3.1.0 — https://jquery.com
- Bootstrap 3.3.7 — https://getbootstrap.com
- DataTables 1.10.12 — https://www.datatables.net
- Fancybox 2.1.5 — https://fancyapps.com
- TinyMCE 4.4.0 — https://www.tinymce.com
- Jasny Bootstrap 3.1.3 — https://jasny.net/bootstrap
- DropZone 4.2.0 — https://dropzonejs.com
- Bootstrap Form Validator 0.9.0 — https://1000hz.github.io/bootstrap-validator
- Bootstrap-Select 1.12.0 — https://silviomoreto.github.io/bootstrap-select
- Font Awesome 4.5.0 — https://fontawesome.com
- FPDF 1.6 — https://fpdf.org
- PHPass 0.3 — https://www.openwall.com/phpass
- Tiny But Strong 3.10.1 — https://tinybutstrong.com
- HTML Purifier 4.9.3 — https://htmlpurifier.org/
- PHPMailer 6.0.7 — https://github.com/PHPMailer/PHPMailer
- Bootstrap Markdown Editor 2.0.1 — https://github.com/inacho/bootstrap-markdown-editor

