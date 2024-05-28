<?php

namespace App\Helpers;

class PolylineHelper
{
    public static function decodePolyline($encoded)
    {
        $length = strlen($encoded);
        $index = 0;
        $points = [];
        $lat = 0;
        $lng = 0;

        while ($index < $length) {
            $result = 1;
            $shift = 0;

            do {
                $b = ord($encoded[$index++]) - 63 - 1;
                $result += $b << $shift;
                $shift += 5;
            } while ($b >= 0x1f);

            $lat += ($result & 1) ? ~($result >> 1) : ($result >> 1);

            $result = 1;
            $shift = 0;

            do {
                $b = ord($encoded[$index++]) - 63 - 1;
                $result += $b << $shift;
                $shift += 5;
            } while ($b >= 0x1f);

            $lng += ($result & 1) ? ~($result >> 1) : ($result >> 1);

            $points[] = [$lat * 1e-5, $lng * 1e-5];
        }

        return $points;
    }
}
