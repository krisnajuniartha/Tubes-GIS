<?php

namespace App\Helpers;

use GeoJson\GeoJson;
use GeoJson\Geometry\LineString;

class PolylineHelper
{
    public static function encode(array $data)
    {
        return GeoJson::jsonUnserialize($data);
    }

    public static function decode($encodedPolyline)
    {
        return $encodedPolyline; // Tidak perlu dekode di sisi server, kembalikan nilai asli
    }
}