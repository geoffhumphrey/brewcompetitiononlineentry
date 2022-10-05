<?php

namespace rudissaar\fpdf;

use FPDF;

class FPDFPlus extends FPDF
{
    /**
     *
     * @param float|null $height
     * @param string $text
     * @param string $link
     * @param string $encoding
     */
    public function WriteEncoded($height, $text, $link = '', $encoding = 'UTF-8')
    {
        $encodedText = iconv($encoding, 'windows-1252', $text);
        $this->Write($height, $encodedText, $link);
    }

    /**
     *
     * @param float|null $height
     * @param string $text
     * @param string $link
     */
    public function WriteLn($height, $text, $link = '')
    {
        $this->Write($height, $text, $link);
        $this->Ln();
    }

    /**
     *
     * @param float|null $height
     * @param string $text
     * @param string $link
     * @param string $encoding
     */
    public function WriteLnEncoded($height, $text, $link = '', $encoding = 'UTF-8')
    {
        $encodedText = iconv($encoding, 'windows-1252', $text);
        $this->WriteLn($height, $encodedText, $link);
    }

    /**
     *
     * @param float|null $height
     * @param string $html
     * @param string $link
     */
    public function WriteHtml($height, $html, $link = '')
    {
        $this->WriteLn($height, strip_tags($html), $link);
    }

    /**
     *
     * @return float
     */
    public function GetPageHeight()
    {
        $height = $this->h;
        $topMargin = $this->tMargin;
        $bottomMargin = $this->bMargin;

        return (float) ($height - $topMargin - $bottomMargin);
    }

    /**
     *
     * @return float
     */
    public function GetPageWidth()
    {
        $width = $this->w;
        $leftMargin = $this->lMargin;
        $rightMargin = $this->rMargin;

        return (float) ($width - $rightMargin - $leftMargin);
    }

    /**
     *
     * @return float
     */
    public function GetTopMargin()
    {
        return (float) $this->tMargin;
    }

    /**
     *
     * @return float
     */
    public function GetRightMargin()
    {
        return (float) $this->rMargin;
    }

    /**
     *
     * @return float
     */
    public function GetBottomMargin()
    {
        return (float) $this->bMargin;
    }

    /**
     *
     * @return float
     */
    public function GetLeftMargin()
    {
        return (float) $this->lMargin;
    }

    /**
     * This method implements Code 39 barcodes.
     * A Code 39 barcode can encode a string with the following characters: digits (0 to 9),
     * uppercase letters (A to Z) and 8 additional characters (- . space $ / + % *).
     *
     * Source: http://www.fpdf.org/en/script/script46.php
     *
     * @param float $xpos
     * @param float $ypos
     * @param string $code
     * @param float $baseline
     * @param float $height
     */
    public function Code39($xpos, $ypos, $code, $baseline = 0.5, $height = 5)
    {
        $wide = $baseline;
        $narrow = $baseline / 3 ;
        $gap = $narrow;

        $barChar['0'] = 'nnnwwnwnn';
        $barChar['1'] = 'wnnwnnnnw';
        $barChar['2'] = 'nnwwnnnnw';
        $barChar['3'] = 'wnwwnnnnn';
        $barChar['4'] = 'nnnwwnnnw';
        $barChar['5'] = 'wnnwwnnnn';
        $barChar['6'] = 'nnwwwnnnn';
        $barChar['7'] = 'nnnwnnwnw';
        $barChar['8'] = 'wnnwnnwnn';
        $barChar['9'] = 'nnwwnnwnn';
        $barChar['A'] = 'wnnnnwnnw';
        $barChar['B'] = 'nnwnnwnnw';
        $barChar['C'] = 'wnwnnwnnn';
        $barChar['D'] = 'nnnnwwnnw';
        $barChar['E'] = 'wnnnwwnnn';
        $barChar['F'] = 'nnwnwwnnn';
        $barChar['G'] = 'nnnnnwwnw';
        $barChar['H'] = 'wnnnnwwnn';
        $barChar['I'] = 'nnwnnwwnn';
        $barChar['J'] = 'nnnnwwwnn';
        $barChar['K'] = 'wnnnnnnww';
        $barChar['L'] = 'nnwnnnnww';
        $barChar['M'] = 'wnwnnnnwn';
        $barChar['N'] = 'nnnnwnnww';
        $barChar['O'] = 'wnnnwnnwn';
        $barChar['P'] = 'nnwnwnnwn';
        $barChar['Q'] = 'nnnnnnwww';
        $barChar['R'] = 'wnnnnnwwn';
        $barChar['S'] = 'nnwnnnwwn';
        $barChar['T'] = 'nnnnwnwwn';
        $barChar['U'] = 'wwnnnnnnw';
        $barChar['V'] = 'nwwnnnnnw';
        $barChar['W'] = 'wwwnnnnnn';
        $barChar['X'] = 'nwnnwnnnw';
        $barChar['Y'] = 'wwnnwnnnn';
        $barChar['Z'] = 'nwwnwnnnn';
        $barChar['-'] = 'nwnnnnwnw';
        $barChar['.'] = 'wwnnnnwnn';
        $barChar[' '] = 'nwwnnnwnn';
        $barChar['*'] = 'nwnnwnwnn';
        $barChar['$'] = 'nwnwnwnnn';
        $barChar['/'] = 'nwnwnnnwn';
        $barChar['+'] = 'nwnnnwnwn';
        $barChar['%'] = 'nnnwnwnwn';

        $this->SetFillColor(0);
        $code = '*' . strtoupper($code) . '*';

        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
 
            if (!isset($barChar[$char])) {
                $this->Error('Invalid character in barcode: ' . $char);
            }

            $seq = $barChar[$char];

            for ($bar = 0; $bar < 9; $bar++) {
                if ($seq[$bar] === 'n') {
                    $lineWidth = $narrow;
                } else {
                    $lineWidth = $wide;
                }

                if ($bar % 2 === 0) {
                    $this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
                }

                $xpos += $lineWidth;
            }

            $xpos += $gap;
        }
    }
}

?>