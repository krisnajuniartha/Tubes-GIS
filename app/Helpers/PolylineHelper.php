<?php

namespace App\Helpers;

class PolylineHelper
{
    public static function decodePolyline($encoded)
    {
        $points = [];
        $index = 0;
        $lat = 0;
        $lng = 0;

        while ($index < strlen($encoded)) {
            $shift = $result = 0;
            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $lat += (($result & 1) ? ~($result >> 1) : ($result >> 1));

            $shift = $result = 0;
            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $lng += (($result & 1) ? ~($result >> 1) : ($result >> 1));

            $points[] = [$lat / 1e5, $lng / 1e5];
        }

        return $points;
    }
}
