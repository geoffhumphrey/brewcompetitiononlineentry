# Brew Competition Online Entry & Management

### Please check the _[Good to Know](https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues?q=is%3Aissue+label%3A%22good+to+know%21%22)_ list before posting any issue. ###

---

Working repository of BCOE&M.

Website: https://www.brewingcompetitions.com
Helpful Articles:
 - [License](https://brewingcompetitions.com/license)
 - [Release Notes](https://www.brewingcompetitions.com/release-notes)
 - [Installation Instructions](https://www.brewingcompetitions.com/install-instructions)
 - [Upgrade Instructions](https://www.brewingcompetitions.com/upgrade-instructions)

The Brew Competition Online Entry and Management (BCOE&M) system is an online application to assist homebrew competition organizers - of the beer/mead/cider variety - to collect, store, and manage their competition entry, organization, and scoring data.

The biggest challenges of organizing a homebrewing competition is knowing who has entered what and how many, organizing judging efficiently, and reporting the results of the competition in a timely manner. BCOE&M provides a single online interface to collect entry and participant data, organize judging tables and assignments, input scoring data, and report the results. Features include, but certainly aren't limited to:
- Collecting entry information from participants.
- Four major style guideline collections to use: BJCP 2021, BJCP 2015, Brewers Association (BA), Australian Amateur Brewing Championship (AABC).
- Defining categories and styles customized to your competition's needs.
- Facilitating online entry fee payments (via PayPal).
- Organizing and assigning participants as judges, stewards, and staff.
- Defining tables/flights and assigning judges and stewards to them.
- Mobile entry check-in using [QR and/or barcodes](https://brewingcompetitions.com/barcode-check-in).
- [Electronic scoresheets](https://brewingcompetitions.com/setup-electronic-scoresheets) for use in [virtual](https://brewingcompetitions.com/virtual-judging) and/or in-person [judging](https://brewingcompetitions.com/judging-with-electronic-scoresheets).
- Scoresheet [upload](https://brewingcompetitions.com/upload-scoresheets).
- 60+ reports for use before, during, and after judging.
- 20+ data export options.
- Custom modules for information/functionality unique to your competition.

The best part: **BCOE&M is free and open-source**. Hundreds of competitions around the world have utilized BCOE&M since its [first release](https://brewingcompetitions.com/change-log) back in 2009.

## Download
The latest version is available for [download here](https://github.com/geoffhumphrey/brewcompetitiononlineentry/releases). The [latest committed code](https://github.com/geoffhumphrey/brewcompetitiononlineentry/archive/master.zip) is also available for testers and contributors.

## Install or Upgrade
Step by step [installation](https://www.brewingcompetitions.com/install-instructions) and [upgrade](https://www.brewingcompetitions.com/upgrade-instructions) instructions are available.

After configuration to your environment, installation is a breeze via the online setup interface.

## Fallback Installation
There are times when the online setup encounters issues that prevent the installation from successfully completing. That's why there's a [Fallback Installation](https://brewingcompetitions.com/install-instructions#fallback) method. For those experiencing any issues related to the initial browser-based setup, the bcoem_baseline_2.X.X.sql document is available in the package's /sql/ folder. This document contains the necessary database structure and dummy data for a new installation that can be installed manually via phpMyAdmin or shell access. Be sure to follow the directions in the document **BEFORE** use.

## Issue Reporting and Bug Fixes
Many bugs and issues reported to this repository are corrected before an official release is available. Before reporting a bug, be sure to check the [Issues](https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues) list to see if it has been addressed already. If it has, chances are the latest commit package contains code to fix the issue. Keep an eye out for the [*fixed in latest master commit*](https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues?q=is%3Aissue+is%3Aopen+label%3A%22in+latest+master+commit%22) tag. Needless to say, however, the master and other branch commits housed here in the repository are **NOT FOR PRODUCTION**! Bugs may be present.

## Help and Resources
Help is integrated into the application. Just look for the question-mark icon in the main navigation.

There is also a growing number of instructive resources available on the [companion website](https://www.brewingcompetitions.com) for various options, including the following:
- [Competition Organization with BCOE&M](https://brewingcompetitions.com/comp-org) - an end to end guide to using BCOE&M as your main organizational tool
- [Load Libraries Locally](https://brewingcompetitions.com/local-load) - disable CDN loading of external libraries such as jQuery, Bootstrap, DataTables, etc.
- [Setup BCOE&M Electronic Scoresheets](https://brewingcompetitions.com/setup-electronic-scoresheets) - primer for Admins to effectively set up and use Electronic Scoresheets
- [Judging with BCOE&M Scoresheets](https://brewingcompetitions.com/judging-with-electronic-scoresheets) - primer for judges on how to evaluate entries using Electronic Scoresheets
- [Virtual Judging](https://brewingcompetitions.com/virtual-judging) - information and suggestions for judges particpating in virtual judging sessions.
- [Virtual Judging Tips for Judges](https://brewingcompetitions.com/virtual-judging/tips) - tips and tricks for evaluating homebrew entries virtually.
- [Upload Scanned Judges' Scoresheets](https://brewingcompetitions.com/upload-scoresheets) - procedure for scanning and uploading scoresheets to make available to entrants via BCOE&M
- [Reset Competition Information](https://brewingcompetitions.com/reset-comp) - get your site ready for your next competition iteration
- [Barcode or QR Code Entry Check-in](https://brewingcompetitions.com/barcode-check-in) - utilize the barcode/QR code enabled bottle labels to efficiently check-in entries
- [Implement PayPal Instant Payment Notifications](https://brewingcompetitions.com/paypal-ipn) - receive and process PayPal payment data to update entrant payment status instantly

## Wanna Help with Development?
Fork this repo and share your code!
