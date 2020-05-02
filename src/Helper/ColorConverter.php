<?php

declare(strict_types=1);

namespace jkniest\HueIt\Helper;

use OzdemirBurak\Iris\Color\Hex;
use OzdemirBurak\Iris\Color\Rgb;

class ColorConverter
{
    public static function fromXYToRGB(float $x, float $y, int $brightness): array
    {
        $z = 1.0 - $x - $y;
        $Y = $brightness / 100.0;
        $X = ($Y / $y) * $x;
        $Z = ($Y / $y) * $z;

        $red = $X * 1.656492 - $Y * 0.354851 - $Z * 0.255038;
        $green = -$X * 0.707196 + $Y * 1.655397 + $Z * 0.036152;
        $blue = $X * 0.051713 - $Y * 0.121364 + $Z * 1.011530;

        $red = $red <= 0.0031308 ? 12.92 * $red : (1.0 + 0.055) * ($red ** (1.0 / 2.4)) - 0.055;
        $green = $green <= 0.0031308 ? 12.92 * $green : (1.0 + 0.055) * ($green ** (1.0 / 2.4)) - 0.055;
        $blue = $blue <= 0.0031308 ? 12.92 * $blue : (1.0 + 0.055) * ($blue ** (1.0 / 2.4)) - 0.055;

        // This converts these numbers to ranges of 0-255 and ensures numbers are never smaller or higher
        $red = (int) round(min(255, max(0, $red * 255)));
        $green = (int) round(min(255, max(0, $green * 255)));
        $blue = (int) round(min(255, max(0, $blue * 255)));

        return [$red, $green, $blue];
    }

    public static function fromXYToHex(float $x, float $y, int $brightness): string
    {
        $rgb = self::fromXYToRGB($x, $y, $brightness);

        return (new Rgb("rgb({$rgb[0]},{$rgb[1]},{$rgb[2]})"))->toHex()->__toString();
    }

    public static function fromRGBToXY(int $red, int $green, int $blue): array
    {
        $red /= 255.0;
        $green /= 255.0;
        $blue /= 255.0;

        $r = ($red > 0.04045) ? (($red + 0.055) / (1.0 + 0.055)) ** 2.4 : ($red / 12.920);
        $g = ($green > 0.04045) ? (($green + 0.055) / (1.0 + 0.055)) ** 2.4 : ($green / 12.92);
        $b = ($blue > 0.04045) ? (($blue + 0.055) / (1.0 + 0.055)) ** 2.4 : ($blue / 12.92);

        $X = $r * 0.649926 + $g * 0.103455 + $b * 0.197109;
        $Y = $r * 0.234327 + $g * 0.743075 + $b * 0.022598;
        $Z = $r * 0.0000000 + $g * 0.053077 + $b * 1.035763;

        $x = $X / ($X + $Y + $Z);
        $y = $Y / ($X + $Y + $Z);

        return [$x, $y];
    }

    public static function fromHexToXY(string $hex): array
    {
        $rgb = (new Hex($hex))->toRgb();

        return static::fromRGBToXY($rgb->red(), $rgb->green(), $rgb->blue());
    }
}
