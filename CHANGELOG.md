# Changelog

All notable changes to this repository are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to the versioning used by the upstream
[brewcompetitiononlineentry](https://github.com/geoffhumphrey/brewcompetitiononlineentry)
repository.

## [Unreleased] — modernization branch

### Added
- PHPUnit test suite (`tests/`) — 59 tests / 233 assertions covering
  sanitization, session prefs, best-brewer CoA scoring, repository
  round-trips, contact-token URL round-trips, timezone/DST epoch
  conversion, and the 3.1.0 timestamp backfill; MySQL-gated integration
  tests run against a MySQL 8.0 service in CI on PHP 8.3, 8.4, and 8.5.
- Composer-based tooling: `composer.json` (PHP ^8.3), PHPStan configs
  (`phpstan.neon` legacy level 4, `phpstan.src.neon` level 6 + strict rules),
  PHPUnit config, and a CI workflow (`.github/workflows/ci.yml`).
- Typed domain layer under `src/`: 24 readonly row classes
  (`src/Domain/`), 24 repositories (`src/Repository/`), a `Connection`
  wrapper, and a typed session prefs accessor (`src/Session/Prefs.php`).
- `tools/` generator scripts that produce the row classes and repositories
  from `sql/bcoem_baseline_3.0.X.sql` (maintainers only; not part of the
  runtime deployment).

### Changed
- Documented PHP floor raised to 8.3 (`README.txt`, `README.md`,
  `.htaccess`); the codebase is modernized for PHP 8.3/8.4/8.5, with the
  live deployment target (PHP 8.3) and 8.4/8.5 exercised in CI.
- Merged upstream v3.1.0 (MysqliDb protocol conversion, `a8092f18`).

### Fixed
- PHP 8-removed functions: `each()`, `eregi()`, `mysql_*`, and
  `FILTER_SANITIZE_STRING` usages removed.
- Contact-token URLs no longer corrupt tokens containing `+`, `/`, or `=`
  (`rawurlencode` on the token link; upstream issue #1708).
- Competition times no longer shift during DST: `to_utc_epoch()` returns the
  true UTC epoch without double-applying the timezone offset (upstream issue
  #1716 / PR #1718).
- The 3.1.0 upgrade backfills already-stored competition timestamps
  (`contest_info`, `judging_preferences`, `judging_locations`,
  `preferences.prefsWinnerDelay`) to UTC epochs via
  `normalize_competition_ts()`, so pre-3.1.0 data agrees with new
  timezone-consistent storage. Archived competitions are left untouched.
