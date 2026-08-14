<?php
declare(strict_types=1);

namespace BCOEM\Session;

/**
 * Typed accessor for the site-preferences session keys.
 *
 * The legacy code loads every `prefs*` column from the `preferences` table
 * directly into `$_SESSION['prefsFoo']` (see includes/db/common.db.php).
 * This class gives typed getters/setters over that same backing store so
 * unconverted files can keep reading `$_SESSION['prefsFoo']` unchanged.
 *
 * A call to an unknown key is a PHPStan error (the typo class dies here).
 *
 * @see includes/db/common.db.php
 */
final class Prefs
{
    private function __construct()
    {
    }

    private static function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    private static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    // ------------------------------------------------------------------
    // Email
    // ------------------------------------------------------------------

    public static function emailSMTP(): ?int { return self::int('prefsEmailSMTP'); }
    public static function setEmailSMTP(?int $v): void { self::set('prefsEmailSMTP', $v); }

    public static function emailHost(): ?string { return self::str('prefsEmailHost'); }
    public static function setEmailHost(?string $v): void { self::set('prefsEmailHost', $v); }

    public static function emailFrom(): ?string { return self::str('prefsEmailFrom'); }
    public static function setEmailFrom(?string $v): void { self::set('prefsEmailFrom', $v); }

    public static function emailUsername(): ?string { return self::str('prefsEmailUsername'); }
    public static function setEmailUsername(?string $v): void { self::set('prefsEmailUsername', $v); }

    public static function emailPassword(): ?string { return self::str('prefsEmailPassword'); }
    public static function setEmailPassword(?string $v): void { self::set('prefsEmailPassword', $v); }

    public static function emailEncrypt(): ?string { return self::str('prefsEmailEncrypt'); }
    public static function setEmailEncrypt(?string $v): void { self::set('prefsEmailEncrypt', $v); }

    public static function emailPort(): ?string { return self::str('prefsEmailPort'); }
    public static function setEmailPort(?string $v): void { self::set('prefsEmailPort', $v); }

    public static function emailCC(): ?int { return self::int('prefsEmailCC'); }
    public static function setEmailCC(?int $v): void { self::set('prefsEmailCC', $v); }

    public static function emailRegConfirm(): ?int { return self::int('prefsEmailRegConfirm'); }
    public static function setEmailRegConfirm(?int $v): void { self::set('prefsEmailRegConfirm', $v); }

    // ------------------------------------------------------------------
    // Entry
    // ------------------------------------------------------------------

    public static function entryLimit(): ?int { return self::int('prefsEntryLimit'); }
    public static function setEntryLimit(?int $v): void { self::set('prefsEntryLimit', $v); }

    public static function entryLimitPaid(): ?int { return self::int('prefsEntryLimitPaid'); }
    public static function setEntryLimitPaid(?int $v): void { self::set('prefsEntryLimitPaid', $v); }

    public static function entryForm(): ?string { return self::str('prefsEntryForm'); }
    public static function setEntryForm(?string $v): void { self::set('prefsEntryForm', $v); }

    public static function specialCharLimit(): ?int { return self::int('prefsSpecialCharLimit'); }
    public static function setSpecialCharLimit(?int $v): void { self::set('prefsSpecialCharLimit', $v); }

    public static function styleLimits(): ?string { return self::str('prefsStyleLimits'); }
    public static function setStyleLimits(?string $v): void { self::set('prefsStyleLimits', $v); }

    public static function selectedStyles(): ?string { return self::str('prefsSelectedStyles'); }
    public static function setSelectedStyles(?string $v): void { self::set('prefsSelectedStyles', $v); }

    public static function eval(): ?int { return self::int('prefsEval'); }
    public static function setEval(?int $v): void { self::set('prefsEval', $v); }

    public static function userEntryLimit(): ?string { return self::str('prefsUserEntryLimit'); }
    public static function setUserEntryLimit(?string $v): void { self::set('prefsUserEntryLimit', $v); }

    public static function userSubCatLimit(): ?string { return self::str('prefsUserSubCatLimit'); }
    public static function setUserSubCatLimit(?string $v): void { self::set('prefsUserSubCatLimit', $v); }

    public static function usclEx(): ?string { return self::str('prefsUSCLEx'); }
    public static function setUsclEx(?string $v): void { self::set('prefsUSCLEx', $v); }

    public static function usclExLimit(): ?string { return self::str('prefsUSCLExLimit'); }
    public static function setUsclExLimit(?string $v): void { self::set('prefsUSCLExLimit', $v); }

    public static function userEntryLimitDates(): ?string { return self::str('prefsUserEntryLimitDates'); }
    public static function setUserEntryLimitDates(?string $v): void { self::set('prefsUserEntryLimitDates', $v); }

    // ------------------------------------------------------------------
    // Judging / Scoring
    // ------------------------------------------------------------------

    public static function firstPlacePts(): ?int { return self::int('prefsFirstPlacePts'); }
    public static function setFirstPlacePts(?int $v): void { self::set('prefsFirstPlacePts', $v); }

    public static function secondPlacePts(): ?int { return self::int('prefsSecondPlacePts'); }
    public static function setSecondPlacePts(?int $v): void { self::set('prefsSecondPlacePts', $v); }

    public static function thirdPlacePts(): ?int { return self::int('prefsThirdPlacePts'); }
    public static function setThirdPlacePts(?int $v): void { self::set('prefsThirdPlacePts', $v); }

    public static function fourthPlacePts(): ?int { return self::int('prefsFourthPlacePts'); }
    public static function setFourthPlacePts(?int $v): void { self::set('prefsFourthPlacePts', $v); }

    public static function hmPts(): ?int { return self::int('prefsHMPts'); }
    public static function setHmPts(?int $v): void { self::set('prefsHMPts', $v); }

    public static function mhpDisplay(): ?int { return self::int('prefsMHPDisplay'); }
    public static function setMhpDisplay(?int $v): void { self::set('prefsMHPDisplay', $v); }

    public static function tieBreakRule1(): ?string { return self::str('prefsTieBreakRule1'); }
    public static function setTieBreakRule1(?string $v): void { self::set('prefsTieBreakRule1', $v); }

    public static function tieBreakRule2(): ?string { return self::str('prefsTieBreakRule2'); }
    public static function setTieBreakRule2(?string $v): void { self::set('prefsTieBreakRule2', $v); }

    public static function tieBreakRule3(): ?string { return self::str('prefsTieBreakRule3'); }
    public static function setTieBreakRule3(?string $v): void { self::set('prefsTieBreakRule3', $v); }

    public static function tieBreakRule4(): ?string { return self::str('prefsTieBreakRule4'); }
    public static function setTieBreakRule4(?string $v): void { self::set('prefsTieBreakRule4', $v); }

    public static function tieBreakRule5(): ?string { return self::str('prefsTieBreakRule5'); }
    public static function setTieBreakRule5(?string $v): void { self::set('prefsTieBreakRule5', $v); }

    public static function tieBreakRule6(): ?string { return self::str('prefsTieBreakRule6'); }
    public static function setTieBreakRule6(?string $v): void { self::set('prefsTieBreakRule6', $v); }

    public static function winnerMethod(): ?int { return self::int('prefsWinnerMethod'); }
    public static function setWinnerMethod(?int $v): void { self::set('prefsWinnerMethod', $v); }

    public static function winnerDelay(): ?string { return self::str('prefsWinnerDelay'); }
    public static function setWinnerDelay(?string $v): void { self::set('prefsWinnerDelay', $v); }

    public static function bosCider(): ?string { return self::str('prefsBOSCider'); }
    public static function setBosCider(?string $v): void { self::set('prefsBOSCider', $v); }

    public static function bosMead(): ?string { return self::str('prefsBOSMead'); }
    public static function setBosMead(?string $v): void { self::set('prefsBOSMead', $v); }

    public static function bestUseBOS(): ?int { return self::int('prefsBestUseBOS'); }
    public static function setBestUseBOS(?int $v): void { self::set('prefsBestUseBOS', $v); }

    public static function displaySpecial(): ?string { return self::str('prefsDisplaySpecial'); }
    public static function setDisplaySpecial(?string $v): void { self::set('prefsDisplaySpecial', $v); }

    public static function displayWinners(): ?string { return self::str('prefsDisplayWinners'); }
    public static function setDisplayWinners(?string $v): void { self::set('prefsDisplayWinners', $v); }

    public static function scoringCOA(): ?int { return self::int('prefsScoringCOA'); }
    public static function setScoringCOA(?int $v): void { self::set('prefsScoringCOA', $v); }

    // ------------------------------------------------------------------
    // Payments
    // ------------------------------------------------------------------

    public static function cash(): ?string { return self::str('prefsCash'); }
    public static function setCash(?string $v): void { self::set('prefsCash', $v); }

    public static function check(): ?string { return self::str('prefsCheck'); }
    public static function setCheck(?string $v): void { self::set('prefsCheck', $v); }

    public static function checkPayee(): ?string { return self::str('prefsCheckPayee'); }
    public static function setCheckPayee(?string $v): void { self::set('prefsCheckPayee', $v); }

    public static function paypal(): ?string { return self::str('prefsPaypal'); }
    public static function setPaypal(?string $v): void { self::set('prefsPaypal', $v); }

    public static function paypalAccount(): ?string { return self::str('prefsPaypalAccount'); }
    public static function setPaypalAccount(?string $v): void { self::set('prefsPaypalAccount', $v); }

    public static function paypalIPN(): ?int { return self::int('prefsPaypalIPN'); }
    public static function setPaypalIPN(?int $v): void { self::set('prefsPaypalIPN', $v); }

    public static function payToPrint(): ?string { return self::str('prefsPayToPrint'); }
    public static function setPayToPrint(?string $v): void { self::set('prefsPayToPrint', $v); }

    public static function transFee(): ?string { return self::str('prefsTransFee'); }
    public static function setTransFee(?string $v): void { self::set('prefsTransFee', $v); }

    public static function currency(): ?string { return self::str('prefsCurrency'); }
    public static function setCurrency(?string $v): void { self::set('prefsCurrency', $v); }

    // ------------------------------------------------------------------
    // Display / Locale
    // ------------------------------------------------------------------

    public static function language(): ?string { return self::str('prefsLanguage'); }
    public static function setLanguage(?string $v): void { self::set('prefsLanguage', $v); }

    public static function theme(): ?string { return self::str('prefsTheme'); }
    public static function setTheme(?string $v): void { self::set('prefsTheme', $v); }

    public static function dateFormat(): ?string { return self::str('prefsDateFormat'); }
    public static function setDateFormat(?string $v): void { self::set('prefsDateFormat', $v); }

    public static function timeFormat(): ?int { return self::int('prefsTimeFormat'); }
    public static function setTimeFormat(?int $v): void { self::set('prefsTimeFormat', $v); }

    public static function timeZone(): ?float { return self::float('prefsTimeZone'); }
    public static function setTimeZone(?float $v): void { self::set('prefsTimeZone', $v); }

    public static function compLogoSize(): ?string { return self::str('prefsCompLogoSize'); }
    public static function setCompLogoSize(?string $v): void { self::set('prefsCompLogoSize', $v); }

    public static function sponsorLogos(): ?string { return self::str('prefsSponsorLogos'); }
    public static function setSponsorLogos(?string $v): void { self::set('prefsSponsorLogos', $v); }

    public static function sponsors(): ?string { return self::str('prefsSponsors'); }
    public static function setSponsors(?string $v): void { self::set('prefsSponsors', $v); }

    public static function specific(): ?int { return self::int('prefsSpecific'); }
    public static function setSpecific(?int $v): void { self::set('prefsSpecific', $v); }

    // ------------------------------------------------------------------
    // Misc
    // ------------------------------------------------------------------

    public static function autoPurge(): ?int { return self::int('prefsAutoPurge'); }
    public static function setAutoPurge(?int $v): void { self::set('prefsAutoPurge', $v); }

    public static function contact(): ?string { return self::str('prefsContact'); }
    public static function setContact(?string $v): void { self::set('prefsContact', $v); }

    public static function dropOff(): ?int { return self::int('prefsDropOff'); }
    public static function setDropOff(?int $v): void { self::set('prefsDropOff', $v); }

    public static function shipping(): ?int { return self::int('prefsShipping'); }
    public static function setShipping(?int $v): void { self::set('prefsShipping', $v); }

    public static function showBestBrewer(): ?int { return self::int('prefsShowBestBrewer'); }
    public static function setShowBestBrewer(?int $v): void { self::set('prefsShowBestBrewer', $v); }

    public static function bestBrewerTitle(): ?string { return self::str('prefsBestBrewerTitle'); }
    public static function setBestBrewerTitle(?string $v): void { self::set('prefsBestBrewerTitle', $v); }

    public static function showBestClub(): ?int { return self::int('prefsShowBestClub'); }
    public static function setShowBestClub(?int $v): void { self::set('prefsShowBestClub', $v); }

    public static function bestClubTitle(): ?string { return self::str('prefsBestClubTitle'); }
    public static function setBestClubTitle(?string $v): void { self::set('prefsBestClubTitle', $v); }

    public static function hideRecipe(): ?string { return self::str('prefsHideRecipe'); }
    public static function setHideRecipe(?string $v): void { self::set('prefsHideRecipe', $v); }

    public static function useMods(): ?string { return self::str('prefsUseMods'); }
    public static function setUseMods(?string $v): void { self::set('prefsUseMods', $v); }

    public static function sef(): ?string { return self::str('prefsSEF'); }
    public static function setSef(?string $v): void { self::set('prefsSEF', $v); }

    public static function proEdition(): ?int { return self::int('prefsProEdition'); }
    public static function setProEdition(?int $v): void { self::set('prefsProEdition', $v); }

    public static function recordLimit(): ?int { return self::int('prefsRecordLimit'); }
    public static function setRecordLimit(?int $v): void { self::set('prefsRecordLimit', $v); }

    public static function recordPaging(): ?int { return self::int('prefsRecordPaging'); }
    public static function setRecordPaging(?int $v): void { self::set('prefsRecordPaging', $v); }

    public static function captcha(): ?int { return self::int('prefsCAPTCHA'); }
    public static function setCaptcha(?int $v): void { self::set('prefsCAPTCHA', $v); }

    public static function googleAccount(): ?string { return self::str('prefsGoogleAccount'); }
    public static function setGoogleAccount(?string $v): void { self::set('prefsGoogleAccount', $v); }

    // ------------------------------------------------------------------
    // Style
    // ------------------------------------------------------------------

    public static function styleSet(): ?string { return self::str('prefsStyleSet'); }
    public static function setStyleSet(?string $v): void { self::set('prefsStyleSet', $v); }

    // ------------------------------------------------------------------
    // Typed coercion helpers
    // ------------------------------------------------------------------

    private static function int(string $key): ?int
    {
        $v = self::get($key);
        if ($v === null || $v === '') {
            return null;
        }
        return (int) $v;
    }

    private static function float(string $key): ?float
    {
        $v = self::get($key);
        if ($v === null || $v === '') {
            return null;
        }
        return (float) $v;
    }

    private static function str(string $key): ?string
    {
        $v = self::get($key);
        if ($v === null) {
            return null;
        }
        return (string) $v;
    }
}