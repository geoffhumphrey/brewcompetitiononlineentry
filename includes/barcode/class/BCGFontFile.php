<?php
/**
 *--------------------------------------------------------------------
 *
 * Holds font family and size.
 *
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGArgumentException.php');
include_once('BCGFont.php');

class BCGFontFile implements BCGFont {
    const PHP_BOX_FIX = 0;

    private $path;
    private $size;
    private $text = '';
    private $rotationAngle = 0;
    private $box;
    private $underlineX;
    private $underlineY;
    private $boxFix;

    /**
     * Constructor.
     *
     * @param string $fontPath path to the file
     * @param int $size size in point
     */
    public function __construct($fontPath, $size) {
        if (!file_exists($fontPath)) {
            throw new BCGArgumentException('The font path is incorrect.', 'fontPath');
        }

        $this->path = $fontPath;
        $this->size = $size;
        $this->setRotationAngle(0);
        $this->setBoxFix(self::PHP_BOX_FIX);
    }

    /**
     * Gets the text associated to the font.
     *
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Sets the text associated to the font.
     *
     * @param string text
     */
    public function setText($text) {
        $this->text = $text;
        $this->rebuildBox();
    }

    /**
     * Gets the rotation in degree.
     *
     * @return int
     */
    public function getRotationAngle() {
        return $this->rotationAngle;
    }

    /**
     * Sets the rotation in degree.
     *
     * @param int
     */
    public function setRotationAngle($rotationAngle) {
        $this->rotationAngle = (int)$rotationAngle;
        if ($this->rotationAngle !== 90 && $this->rotationAngle !== 180 && $this->rotationAngle !== 270) {
            $this->rotationAngle = 0;
        }

        $this->rebuildBox();
    }

    /**
     * Gets the background color.
     *
     * @return BCGColor
     */
    public function getBackgroundColor() {
    }

    /**
     * Sets the background color.
     *
     * @param BCGColor $backgroundColor
     */
    public function setBackgroundColor($backgroundColor) {
    }

    public function getBoxFix() {
        return $this->boxFix;
    }

    public function setBoxFix($value) {
        $this->boxFix = intval($value);
    }

    /**
     * Returns the width and height that the text takes to be written.
     *
     * @return int[]
     */
    public function getDimension() {
        $w = 0.0;
        $h = 0.0;

        if ($this->box !== null) {
            $minX = min(array($this->box[0], $this->box[2], $this->box[4], $this->box[6]));
            $maxX = max(array($this->box[0], $this->box[2], $this->box[4], $this->box[6]));
            $minY = min(array($this->box[1], $this->box[3], $this->box[5], $this->box[7]));
            $maxY = max(array($this->box[1], $this->box[3], $this->box[5], $this->box[7]));
        
            $w = $maxX - $minX;
            $h = $maxY - $minY;
        }

        if ($this->rotationAngle === 90 || $this->rotationAngle === 270) {
            return array($h + self::PHP_BOX_FIX, $w);
        } else {
            return array($w + self::PHP_BOX_FIX, $h);
        }
    }

    /**
     * Draws the text on the image at a specific position.
     * $x and $y represent the left bottom corner.
     *
     * @param resource $im
     * @param int $color
     * @param int $x
     * @param int $y
     */
    public function draw($im, $color, $x, $y) {
        $drawingPosition = $this->getDrawingPosition($x, $y);
        imagettftext($im, $this->size, $this->rotationAngle, $drawingPosition[0], $drawingPosition[1], $color, $this->path, $this->text);
    }

    private function getDrawingPosition($x, $y) {
        $dimension = $this->getDimension();
        if ($this->rotationAngle === 0) {
            $y += abs(min($this->box[5], $this->box[7]));
        } elseif ($this->rotationAngle === 90) {
            $x += abs(min($this->box[5], $this->box[7]));
            $y += $dimension[1];
        } elseif ($this->rotationAngle === 180) {
            $x += $dimension[0];
            $y += abs(max($this->box[1], $this->box[3]));
        } elseif ($this->rotationAngle === 270) {
            $x += abs(max($this->box[1], $this->box[3]));
        }

        return array($x, $y);
    }

    private function rebuildBox() {
        $gd = imagecreate(1, 1);
        $this->box = imagettftext($gd, $this->size, 0, 0, 0, 0, $this->path, $this->text);

        $this->underlineX = abs($this->box[0]);
        $this->underlineY = abs($this->box[1]);

        if ($this->rotationAngle === 90 || $this->rotationAngle === 270) {
            $this->underlineX ^= $this->underlineY ^= $this->underlineX ^= $this->underlineY;
        }
    }
}
?>