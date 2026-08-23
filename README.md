# BCOE&M — Modernization Fork

> [!IMPORTANT]
> This repository is a **fork** of
> [`geoffhumphrey/brewcompetitiononlineentry`](https://github.com/geoffhumphrey/brewcompetitiononlineentry)
> (BCOE&M — Brew Competition Online Entry & Management), developed by Geoff
> Humphrey with contributions from the GitHub community. All credit for the
> application itself belongs upstream.
>
> - **Competition organizers looking to run a competition:** use
>   [upstream](https://github.com/geoffhumphrey/brewcompetitiononlineentry)
>   — it has the official releases, documentation, and issue tracker.
> - **This fork** exists primarily to *modernize the codebase*. It tracks
>   upstream merges but is a working/deployment fork, not the canonical
>   distribution. Bug fixes and compatible improvements are offered
>   upstream where practical.

## Why This Fork Exists

Upstream BCOE&M is a mature PHP application with roots stretching back to
2009. This fork's goal is to modernize it incrementally — without breaking
existing installations or diverging from upstream more than necessary:

- **PHP 8.3+ floor.** Removed functions and patterns dropped in modern PHP
  (`each()`, `eregi()`, `mysql_*`, `FILTER_SANITIZE_STRING`, …); the codebase
  runs cleanly on PHP 8.3, 8.4, and 8.5.
- **Typed domain layer.** A generated, typed data-access layer under `src/`
  (PSR-4 `BCOEM\`): readonly row classes (`src/Domain/`), repositories
  (`src/Repository/`), a database `Connection` wrapper, and a typed session
  accessor — coexisting with the legacy code while call sites migrate.
- **Automated tests.** A PHPUnit suite (`tests/`) covering sanitization,
  session preferences, scoring, repository round-trips, timezone/DST epoch
  conversion, and upgrade backfills; MySQL-gated integration tests run
  against MySQL 8.0.
- **Static analysis.** PHPStan on both the legacy tree (level 4,
  baselined) and `src/` (level 6 + strict rules); Rector configured.
- **CI.** GitHub Actions workflow linting and testing against the PHP
  8.3–8.5 × MySQL 8.0 matrix on every push and pull request.
- **Generator tooling.** `tools/generate_row_types.php` and
  `tools/generate_repositories.php` regenerate the domain layer from the
  baseline SQL schema (maintainer-only; not part of runtime deployment).

See [`CHANGELOG.md`](CHANGELOG.md) for the detailed, ongoing list of
modernization changes, fixes, and upstream merges.

## About BCOE&M

BCOE&M is an online application that assists homebrew competition organizers
— of the beer/mead/cider variety — to collect, store, and manage competition
entry, organization, and scoring data. It provides a single online interface
to collect entry and participant data, organize judging tables and
assignments, input scoring data, and report results. Features include:

- Collecting entry information from participants.
- Four major style guideline collections: BJCP 2021/2015, Brewers
  Association (BA), Australian Amateur Brewing Championship (AABC).
- Defining categories and styles customized to your competition's needs.
- Facilitating online entry fee payments (via PayPal).
- Organizing and assigning participants as judges, stewards, and staff.
- Defining tables/flights and assigning judges and stewards to them.
- Mobile entry check-in using QR and/or barcodes.
- Electronic scoresheets for virtual and/or in-person judging.
- Scoresheet upload.
- 60+ reports for use before, during, and after judging.
- 20+ data export options.
- Custom modules for information/functionality unique to your competition.

Hundreds of competitions around the world have used BCOE&M since its first
release in 2009.

## Requirements

- PHP 8.3 or newer (PHP 8.3, 8.4, and 8.5 are exercised in CI).
- MySQL 5.7 or newer (integration tests run against MySQL 8.0).
- [Composer](https://getcomposer.org) for development tooling; the
  `vendor/` directory is part of the deployment.

## Install or Upgrade

Step-by-step [installation](https://info.brewingcompetitions.com/install-instructions)
and [upgrade](https://info.brewingcompetitions.com/upgrade-instructions)
instructions are maintained upstream. After configuration to your
environment, installation completes via the online setup interface.

### Fallback Installation

If the browser-based setup encounters issues, the
`bcoem_baseline_3.X.X.sql` document in `/sql/` contains the database
structure and dummy data for a manual installation via phpMyAdmin or shell
access. Follow the directions in the document **before** use.

## Development

```sh
composer install
composer test      # PHPUnit
composer analyse   # PHPStan
```

Regenerate the typed domain layer after schema changes (maintainers):

```sh
php tools/generate_row_types.php
php tools/generate_repositories.php
```

## License

This fork is free software, covered under the
[General Public License](https://opensource.org/licenses/gpl-license.php)
as stated by
[upstream](https://info.brewingcompetitions.com/license); upstream does not
declare a specific GPL version, and this fork makes no broader licensing
claims than upstream. It is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

Bundled third-party libraries retain their own licenses — see the credits
below and each library under `classes/`.

For licensing inquiries, contact upstream via
https://www.brewingcompetitions.com/contact.

## Help and Resources

Help is integrated into the application (question-mark icon in the main
navigation). Upstream also maintains instructive resources on the
[companion website](https://info.brewingcompetitions.com):

- [Competition Organization with BCOE&M](https://info.brewingcompetitions.com/comp-org)
- [Load Libraries Locally](https://info.brewingcompetitions.com/local-load)
- [Setup Electronic Scoresheets](https://info.brewingcompetitions.com/setup-electronic-scoresheets)
- [Virtual Judging](https://info.brewingcompetitions.com/virtual-judging) and [tips for judges](https://info.brewingcompetitions.com/virtual-judging/tips)
- [Upload Scanned Judges' Scoresheets](https://info.brewingcompetitions.com/upload-scoresheets)
- [Reset Competition Information](https://info.brewingcompetitions.com/reset-comp)
- [Barcode or QR Code Entry Check-in](https://info.brewingcompetitions.com/barcode-check-in)
- [Implement PayPal IPN](https://info.brewingcompetitions.com/paypal-ipn)

## Credits

BCOE&M is developed by [Geoff Humphrey](https://github.com/geoffhumphrey)
with code contributions by the GitHub community, and utilizes a number of
extensions and functions with gratitude to their respective developers and
online communities:

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
