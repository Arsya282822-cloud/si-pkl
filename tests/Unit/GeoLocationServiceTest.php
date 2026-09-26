<?php

namespace Tests\Unit;

use App\Services\GeoLocationService;
use PHPUnit\Framework\TestCase;

class GeoLocationServiceTest extends TestCase
{
    public function test_it_calculates_distance_between_two_coordinates(): void
    {
        // Koordinat 1 (SMK Labor): 0.5256, 101.4485
        // Koordinat 2 (Mall SKA): 0.4958, 101.4172
        $distance = GeoLocationService::calculateDistance(0.5256, 101.4485, 0.4958, 101.4172);

        // Jarak sekitar 4.8 km - 5.0 km
        $this->assertGreaterThan(4500, $distance);
        $this->assertLessThan(5200, $distance);
    }

    public function test_it_returns_zero_for_identical_coordinates(): void
    {
        $distance = GeoLocationService::calculateDistance(0.507068, 101.447779, 0.507068, 101.447779);
        $this->assertEquals(0, $distance);
    }

    public function test_it_parses_coordinate_strings_correctly(): void
    {
        $coords = GeoLocationService::parseCoordinates('0.507068,101.447779');
        $this->assertNotNull($coords);
        $this->assertEquals(0.507068, $coords[0]);
        $this->assertEquals(101.447779, $coords[1]);

        $invalid = GeoLocationService::parseCoordinates('Tanpa GPS');
        $this->assertNull($invalid);

        $empty = GeoLocationService::parseCoordinates(null);
        $this->assertNull($empty);
    }
}
