<?php

namespace App\Services;

class GeoLocationService
{
    /**
     * Menghitung jarak antara dua koordinat GPS dalam satuan meter menggunakan Haversine Formula.
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $earthRadius = 6371000; // Radius bumi dalam satuan meter

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return (int) round($angle * $earthRadius);
    }

    /**
     * Memisahkan string koordinat "latitude,longitude" menjadi array [latitude, longitude].
     *
     * @return array{0: float, 1: float}|null
     */
    public static function parseCoordinates(?string $coordString): ?array
    {
        if (! $coordString || ! str_contains($coordString, ',')) {
            return null;
        }

        $parts = explode(',', $coordString);
        if (count($parts) < 2) {
            return null;
        }

        $lat = filter_var(trim($parts[0]), FILTER_VALIDATE_FLOAT);
        $lon = filter_var(trim($parts[1]), FILTER_VALIDATE_FLOAT);

        if ($lat === false || $lon === false) {
            return null;
        }

        return [(float) $lat, (float) $lon];
    }
}
