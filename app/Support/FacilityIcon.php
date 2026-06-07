<?php

namespace App\Support;

use App\Models\Facility;

class FacilityIcon
{
    /**
     * Whitelist of valid Material Symbols icon names used by facilities.
     */
    private static array $whitelist = [
        'shower', 'bed', 'styler', 'desk', 'chair', 'ac_unit', 'mode_fan',
        'tv', 'wifi', 'local_parking', 'countertops', 'videocam',
        'living', 'local_laundry_service', 'kitchen', 'cooking',
        'pillow', 'water_heater', 'water_drop', 'bolt', 'nest_eco',
        'king_bed', 'door_sliding', 'table_restaurant', 'inventory_2',
        'wc', 'coffee', 'fitness_center', 'check_circle', 'info',
    ];

    /**
     * Map facility names (lowercase) to Material Symbols icon names.
     *
     * Higher-specificity keys (whole name) take priority over partial matches.
     * The order matters: first match wins.
     */
    private static array $nameMap = [
        'kamar mandi dalam' => 'shower',
        'kamar mandi luar' => 'wc',
        'kamar mandi' => 'shower',
        'kasur' => 'bed',
        'spring bed' => 'bed',
        'lemari' => 'inventory_2',
        'meja belajar' => 'desk',
        'meja' => 'desk',
        'kursi' => 'chair',
        'ac' => 'ac_unit',
        'kipas angin' => 'mode_fan',
        'kipas' => 'mode_fan',
        'tv' => 'tv',
        'televisi' => 'tv',
        'wifi' => 'wifi',
        'wi-fi' => 'wifi',
        'parkiran' => 'local_parking',
        'parkir motor' => 'local_parking',
        'parkir mobil' => 'local_parking',
        'area parkir' => 'local_parking',
        'parkir' => 'local_parking',
        'dapur bersama' => 'countertops',
        'dapur' => 'countertops',
        'cctv 24 jam' => 'videocam',
        'cctv' => 'videocam',
        'ruang tamu' => 'living',
        'tempat cuci' => 'local_laundry_service',
        'laundry' => 'local_laundry_service',
        'kulkas' => 'kitchen',
        'kompor' => 'cooking',
        'air panas' => 'water_heater',
        'air' => 'water_drop',
        'listrik' => 'bolt',
        'tamasy' => 'nest_eco',
        'taman' => 'nest_eco',
        'bantal' => 'pillow',
        'coffee' => 'coffee',
        'gym' => 'fitness_center',
        'fitness' => 'fitness_center',
    ];

    /**
     * Resolve a valid Material Symbols icon name for a facility.
     *
     * Priority:
     * 1. Name-based lookup ($nameMap)
     * 2. $facility->icon if it's in the whitelist
     * 3. 'check_circle' as fallback
     */
    public static function resolve(Facility $facility): string
    {
        $lower = strtolower(trim($facility->name));

        if (isset(self::$nameMap[$lower])) {
            return self::$nameMap[$lower];
        }

        if (in_array($facility->icon, self::$whitelist, true)) {
            return $facility->icon;
        }

        return 'check_circle';
    }

    /**
     * Render a facility icon as an HTML string.
     */
    public static function render(Facility $facility, string $size = 'md'): string
    {
        $icon = self::resolve($facility);
        $class = 'facility-icon'.($size === 'sm' ? ' facility-icon-sm' : '');

        return '<span class="material-symbols-outlined '.$class.'" style="font-variation-settings:\'FILL\' 0;">'.$icon.'</span>';
    }

    /**
     * Return the whitelist as a flat array of icon names.
     */
    public static function whitelist(): array
    {
        return self::$whitelist;
    }

    /**
     * Return icon options for form dropdowns.
     *
     * @return array<string, string> icon name => human-readable label
     */
    public static function options(): array
    {
        $labelMap = [
            'shower' => 'Kamar Mandi',
            'bed' => 'Kasur / Spring Bed',
            'inventory_2' => 'Lemari',
            'desk' => 'Meja',
            'chair' => 'Kursi',
            'ac_unit' => 'AC',
            'mode_fan' => 'Kipas Angin',
            'tv' => 'TV',
            'wifi' => 'WiFi',
            'local_parking' => 'Parkir',
            'countertops' => 'Dapur',
            'videocam' => 'CCTV',
            'living' => 'Ruang Tamu',
            'local_laundry_service' => 'Laundry',
            'kitchen' => 'Kulkas / Dapur',
            'cooking' => 'Kompor',
            'pillow' => 'Bantal',
            'water_heater' => 'Air Panas',
            'water_drop' => 'Air',
            'bolt' => 'Listrik',
            'nest_eco' => 'Taman',
            'king_bed' => 'Kasur Besar',
            'door_sliding' => 'Lemari Geser',
            'table_restaurant' => 'Meja Makan',
            'wc' => 'Kamar Mandi Luar',
            'coffee' => 'Kopi',
            'fitness_center' => 'Fitness',
            'styler' => 'Styler',
            'check_circle' => 'Default',
        ];

        $opts = [];
        foreach (self::$whitelist as $icon) {
            $label = $labelMap[$icon] ?? ucfirst(str_replace('_', ' ', $icon));
            $opts[$icon] = $label;
        }

        return $opts;
    }
}
