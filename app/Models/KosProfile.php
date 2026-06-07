<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'address', 'whatsapp_number', 'email', 'owner_name', 'google_maps_url', 'google_maps_embed_url', 'nearby_places', 'logo', 'late_fee_per_day', 'max_late_fee'])]
class KosProfile extends Model
{
    use HasFactory;

    private const ESTIMATE_UNIT_OPTIONS = [
        'minute' => 'Menit',
        'meter' => 'Meter',
        'kilometer' => 'Kilometer',
    ];

    private const ESTIMATE_UNIT_LABELS = [
        'minute' => 'menit',
        'meter' => 'm',
        'kilometer' => 'km',
    ];

    private const TRAVEL_MODE_OPTIONS = [
        'walking' => 'Jalan kaki',
        'motorcycle' => 'Motor',
        'car' => 'Mobil',
    ];

    private const TRAVEL_MODE_LABELS = [
        'walking' => 'jalan kaki',
        'motorcycle' => 'motor',
        'car' => 'mobil',
    ];

    protected $table = 'kos_profiles';

    /**
     * @return array<int, array{name: string, estimate_value: string, estimate_unit: string, travel_mode: string}>
     */
    public function nearbyPlaceItems(): array
    {
        return self::parseNearbyPlaces($this->nearby_places);
    }

    /**
     * @return array<string, string>
     */
    public static function estimateUnitOptions(): array
    {
        return self::ESTIMATE_UNIT_OPTIONS;
    }

    /**
     * @return array<string, string>
     */
    public static function travelModeOptions(): array
    {
        return self::TRAVEL_MODE_OPTIONS;
    }

    /**
     * @return array<int, array{name: string, estimate_value: string, estimate_unit: string, travel_mode: string}>
     */
    public static function parseNearbyPlaces(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            return collect($decoded)
                ->map(fn ($place): array => self::normalizeNearbyPlace($place))
                ->filter(fn (array $place): bool => self::hasNearbyPlaceValue($place))
                ->values()
                ->all();
        }

        return collect(preg_split('/\R/u', $value) ?: [])
            ->map(function ($place): array {
                $parts = preg_split('/\s+-\s+/', trim($place), 2) ?: [];

                return self::normalizeNearbyPlace([
                    'name' => $parts[0] ?? '',
                    'estimate' => $parts[1] ?? '',
                ]);
            })
            ->filter(fn (array $place): bool => self::hasNearbyPlaceValue($place))
            ->values()
            ->all();
    }

    /**
     * @param  array<int, mixed>  $places
     */
    public static function serializeNearbyPlaces(array $places): ?string
    {
        $normalized = collect($places)
            ->map(fn ($place): array => self::normalizeNearbyPlace($place))
            ->filter(fn (array $place): bool => self::hasNearbyPlaceValue($place))
            ->values()
            ->all();

        if ($normalized === []) {
            return null;
        }

        $json = json_encode($normalized, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $json === false ? null : $json;
    }

    /**
     * @param  array{name: string, estimate_value: string, estimate_unit: string, travel_mode: string}  $place
     */
    public static function formatNearbyEstimate(array $place): string
    {
        $value = trim($place['estimate_value']);
        $unit = self::ESTIMATE_UNIT_LABELS[$place['estimate_unit']] ?? '';
        $travelMode = self::TRAVEL_MODE_LABELS[$place['travel_mode']] ?? '';

        if ($value === '' && $unit === '' && $travelMode === '') {
            return '';
        }

        $estimate = trim(implode(' ', array_filter([$value, $unit])));

        return trim(implode(' ', array_filter([$estimate, $travelMode])));
    }

    /**
     * @return array{name: string, estimate_value: string, estimate_unit: string, travel_mode: string}
     */
    private static function normalizeNearbyPlace(mixed $place): array
    {
        if (is_string($place)) {
            return [
                'name' => trim($place),
                'estimate_value' => '',
                'estimate_unit' => '',
                'travel_mode' => '',
            ];
        }

        if (! is_array($place)) {
            return [
                'name' => '',
                'estimate_value' => '',
                'estimate_unit' => '',
                'travel_mode' => '',
            ];
        }

        $parsedEstimate = self::parseEstimate((string) ($place['estimate'] ?? ''));

        return [
            'name' => trim((string) ($place['name'] ?? '')),
            'estimate_value' => trim((string) ($place['estimate_value'] ?? $parsedEstimate['estimate_value'])),
            'estimate_unit' => self::normalizeEstimateUnit($place['estimate_unit'] ?? $parsedEstimate['estimate_unit']),
            'travel_mode' => self::normalizeTravelMode($place['travel_mode'] ?? $parsedEstimate['travel_mode']),
        ];
    }

    /**
     * @return array{estimate_value: string, estimate_unit: string, travel_mode: string}
     */
    private static function parseEstimate(string $estimate): array
    {
        $estimate = trim($estimate);

        if ($estimate === '') {
            return [
                'estimate_value' => '',
                'estimate_unit' => '',
                'travel_mode' => '',
            ];
        }

        if (! preg_match('/^(?<value>\d+(?:[.,]\d+)?)\s*(?<unit>menit|minute|minutes|m|meter|meters|km|kilometer|kilometers)(?:\s+(?<mode>jalan kaki|walking|motor|motorcycle|mobil|car))?$/iu', $estimate, $matches)) {
            return [
                'estimate_value' => $estimate,
                'estimate_unit' => '',
                'travel_mode' => '',
            ];
        }

        return [
            'estimate_value' => trim((string) ($matches['value'] ?? '')),
            'estimate_unit' => self::normalizeEstimateUnit($matches['unit'] ?? ''),
            'travel_mode' => self::normalizeTravelMode($matches['mode'] ?? ''),
        ];
    }

    private static function normalizeEstimateUnit(mixed $value): string
    {
        return match (strtolower(trim((string) $value))) {
            'minute', 'minutes', 'menit' => 'minute',
            'meter', 'meters', 'm' => 'meter',
            'kilometer', 'kilometers', 'km' => 'kilometer',
            default => '',
        };
    }

    private static function normalizeTravelMode(mixed $value): string
    {
        return match (strtolower(trim((string) $value))) {
            'walking', 'jalan kaki' => 'walking',
            'motorcycle', 'motor' => 'motorcycle',
            'car', 'mobil' => 'car',
            default => '',
        };
    }

    /**
     * @param  array{name: string, estimate_value: string, estimate_unit: string, travel_mode: string}  $place
     */
    private static function hasNearbyPlaceValue(array $place): bool
    {
        return $place['name'] !== ''
            || $place['estimate_value'] !== ''
            || $place['estimate_unit'] !== ''
            || $place['travel_mode'] !== '';
    }
}
