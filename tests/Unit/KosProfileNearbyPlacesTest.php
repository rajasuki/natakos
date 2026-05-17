<?php

namespace Tests\Unit;

use App\Models\KosProfile;
use PHPUnit\Framework\TestCase;

class KosProfileNearbyPlacesTest extends TestCase
{
    public function test_it_serializes_and_parses_structured_nearby_places(): void
    {
        $places = [
            ['name' => 'Kampus ABC', 'estimate_value' => '5', 'estimate_unit' => 'minute', 'travel_mode' => 'walking'],
            ['name' => 'Indomaret', 'estimate_value' => '300', 'estimate_unit' => 'meter', 'travel_mode' => ''],
        ];

        $serialized = KosProfile::serializeNearbyPlaces($places);

        $this->assertNotNull($serialized);
        $this->assertSame($places, KosProfile::parseNearbyPlaces($serialized));
    }

    public function test_it_can_parse_legacy_line_based_nearby_places(): void
    {
        $legacyValue = "Kampus ABC - 5 menit jalan kaki\nIndomaret - 300 m\nMasjid";

        $this->assertSame([
            ['name' => 'Kampus ABC', 'estimate_value' => '5', 'estimate_unit' => 'minute', 'travel_mode' => 'walking'],
            ['name' => 'Indomaret', 'estimate_value' => '300', 'estimate_unit' => 'meter', 'travel_mode' => ''],
            ['name' => 'Masjid', 'estimate_value' => '', 'estimate_unit' => '', 'travel_mode' => ''],
        ], KosProfile::parseNearbyPlaces($legacyValue));
    }

    public function test_it_formats_structured_nearby_estimates(): void
    {
        $this->assertSame('5 menit jalan kaki', KosProfile::formatNearbyEstimate([
            'name' => 'Kampus ABC',
            'estimate_value' => '5',
            'estimate_unit' => 'minute',
            'travel_mode' => 'walking',
        ]));

        $this->assertSame('300 m', KosProfile::formatNearbyEstimate([
            'name' => 'Indomaret',
            'estimate_value' => '300',
            'estimate_unit' => 'meter',
            'travel_mode' => '',
        ]));
    }
}
