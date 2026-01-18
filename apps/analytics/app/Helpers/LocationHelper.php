<?php

namespace App\Helpers;

class LocationHelper
{
    private static array $locations = [
        'US' => [
            'name' => 'United States',
            'regions' => [
                'California' => ['Los Angeles', 'San Francisco', 'San Diego', 'San Jose'],
                'New York' => ['New York City', 'Buffalo', 'Rochester'],
                'Texas' => ['Houston', 'Dallas', 'Austin', 'San Antonio'],
                'Florida' => ['Miami', 'Orlando', 'Tampa', 'Jacksonville'],
                'Illinois' => ['Chicago', 'Aurora', 'Naperville'],
                'Pennsylvania' => ['Philadelphia', 'Pittsburgh', 'Allentown'],
            ],
        ],
        'GB' => [
            'name' => 'United Kingdom',
            'regions' => [
                'England' => ['London', 'Manchester', 'Birmingham', 'Leeds', 'Liverpool'],
                'Scotland' => ['Glasgow', 'Edinburgh', 'Aberdeen'],
                'Wales' => ['Cardiff', 'Swansea', 'Newport'],
                'Northern Ireland' => ['Belfast', 'Derry', 'Lisburn'],
            ],
        ],
        'CA' => [
            'name' => 'Canada',
            'regions' => [
                'Ontario' => ['Toronto', 'Ottawa', 'Mississauga', 'Hamilton'],
                'Quebec' => ['Montreal', 'Quebec City', 'Laval'],
                'British Columbia' => ['Vancouver', 'Victoria', 'Surrey'],
                'Alberta' => ['Calgary', 'Edmonton', 'Red Deer'],
            ],
        ],
        'JP' => [
            'name' => 'Japan',
            'regions' => [
                'Kanto' => ['Tokyo', 'Yokohama', 'Kawasaki', 'Saitama'],
                'Kansai' => ['Osaka', 'Kyoto', 'Kobe'],
                'Chubu' => ['Nagoya', 'Shizuoka', 'Hamamatsu'],
                'Hokkaido' => ['Sapporo', 'Asahikawa', 'Hakodate'],
                'Kyushu' => ['Fukuoka', 'Kumamoto', 'Nagasaki'],
            ],
        ],
        'VN' => [
            'name' => 'Vietnam',
            'regions' => [
                'Red River Delta' => ['Hanoi', 'Hai Phong', 'Nam Dinh'],
                'Mekong Delta' => ['Ho Chi Minh City', 'Can Tho', 'My Tho'],
                'Central Coast' => ['Da Nang', 'Hue', 'Nha Trang', 'Quy Nhon'],
                'Central Highlands' => ['Da Lat', 'Buon Ma Thuot', 'Pleiku'],
            ],
        ],
        'DE' => [
            'name' => 'Germany',
            'regions' => [
                'Bavaria' => ['Munich', 'Nuremberg', 'Augsburg'],
                'North Rhine-Westphalia' => ['Cologne', 'Dortmund', 'Essen', 'Düsseldorf'],
                'Baden-Württemberg' => ['Stuttgart', 'Mannheim', 'Karlsruhe'],
                'Hesse' => ['Frankfurt', 'Wiesbaden', 'Kassel'],
                'Berlin' => ['Berlin'],
                'Hamburg' => ['Hamburg'],
            ],
        ],
        'FR' => [
            'name' => 'France',
            'regions' => [
                'Île-de-France' => ['Paris', 'Versailles', 'Boulogne-Billancourt'],
                'Provence-Alpes-Côte d\'Azur' => ['Marseille', 'Nice', 'Toulon'],
                'Auvergne-Rhône-Alpes' => ['Lyon', 'Grenoble', 'Saint-Étienne'],
                'Occitanie' => ['Toulouse', 'Montpellier', 'Nîmes'],
                'Nouvelle-Aquitaine' => ['Bordeaux', 'Limoges', 'Poitiers'],
            ],
        ],
        'AU' => [
            'name' => 'Australia',
            'regions' => [
                'New South Wales' => ['Sydney', 'Newcastle', 'Wollongong'],
                'Victoria' => ['Melbourne', 'Geelong', 'Ballarat'],
                'Queensland' => ['Brisbane', 'Gold Coast', 'Cairns'],
                'Western Australia' => ['Perth', 'Fremantle', 'Mandurah'],
                'South Australia' => ['Adelaide', 'Mount Gambier'],
            ],
        ],
        'CN' => [
            'name' => 'China',
            'regions' => [
                'Beijing Municipality' => ['Beijing'],
                'Shanghai Municipality' => ['Shanghai'],
                'Guangdong' => ['Guangzhou', 'Shenzhen', 'Dongguan', 'Foshan'],
                'Zhejiang' => ['Hangzhou', 'Ningbo', 'Wenzhou'],
                'Jiangsu' => ['Nanjing', 'Suzhou', 'Wuxi'],
                'Sichuan' => ['Chengdu', 'Mianyang'],
            ],
        ],
        'IN' => [
            'name' => 'India',
            'regions' => [
                'Maharashtra' => ['Mumbai', 'Pune', 'Nagpur'],
                'Delhi' => ['Delhi', 'New Delhi'],
                'Karnataka' => ['Bangalore', 'Mysore', 'Mangalore'],
                'Tamil Nadu' => ['Chennai', 'Coimbatore', 'Madurai'],
                'West Bengal' => ['Kolkata', 'Howrah', 'Durgapur'],
                'Telangana' => ['Hyderabad', 'Warangal'],
            ],
        ],
        'BR' => [
            'name' => 'Brazil',
            'regions' => [
                'São Paulo' => ['São Paulo', 'Campinas', 'Santos'],
                'Rio de Janeiro' => ['Rio de Janeiro', 'Niterói', 'Duque de Caxias'],
                'Minas Gerais' => ['Belo Horizonte', 'Uberlândia', 'Juiz de Fora'],
                'Federal District' => ['Brasília'],
                'Bahia' => ['Salvador', 'Feira de Santana'],
            ],
        ],
        'SG' => [
            'name' => 'Singapore',
            'regions' => [
                'Central' => ['Singapore'],
            ],
        ],
        'TH' => [
            'name' => 'Thailand',
            'regions' => [
                'Bangkok Metropolitan' => ['Bangkok'],
                'Northern' => ['Chiang Mai', 'Chiang Rai'],
                'Southern' => ['Phuket', 'Krabi', 'Hat Yai'],
                'Eastern' => ['Pattaya', 'Rayong'],
            ],
        ],
        'MY' => [
            'name' => 'Malaysia',
            'regions' => [
                'Kuala Lumpur' => ['Kuala Lumpur'],
                'Selangor' => ['Petaling Jaya', 'Shah Alam'],
                'Penang' => ['George Town', 'Butterworth'],
                'Johor' => ['Johor Bahru', 'Muar'],
                'Sabah' => ['Kota Kinabalu', 'Sandakan'],
            ],
        ],
        'ID' => [
            'name' => 'Indonesia',
            'regions' => [
                'Jakarta' => ['Jakarta'],
                'West Java' => ['Bandung', 'Bekasi', 'Bogor'],
                'East Java' => ['Surabaya', 'Malang', 'Kediri'],
                'Bali' => ['Denpasar', 'Ubud', 'Kuta'],
                'North Sumatra' => ['Medan', 'Pematang Siantar'],
            ],
        ],
    ];

    /**
     * Get random location with matching country, region, and city
     */
    public static function random(): array
    {
        $countryCode = array_rand(self::$locations);
        $countryData = self::$locations[$countryCode];

        // Pick random region
        $regionName = array_rand($countryData['regions']);
        $cities = $countryData['regions'][$regionName];

        // Pick random city from that region
        $city = fake()->randomElement($cities);

        return [
            'country' => $countryData['name'],
            'country_code' => $countryCode,
            'region' => $regionName,
            'city' => $city,
        ];
    }

    /**
     * Get random location for specific country
     */
    public static function randomFor(string $countryCode): array
    {
        if (! isset(self::$locations[$countryCode])) {
            return [
                'country' => 'Unknown',
                'country_code' => $countryCode,
                'region' => 'Unknown',
                'city' => 'Unknown',
            ];
        }

        $countryData = self::$locations[$countryCode];
        $regionName = array_rand($countryData['regions']);
        $cities = $countryData['regions'][$regionName];

        return [
            'country' => $countryData['name'],
            'country_code' => $countryCode,
            'region' => $regionName,
            'city' => fake()->randomElement($cities),
        ];
    }

    /**
     * Get random location from specific region
     */
    public static function randomFromRegion(string $countryCode, string $regionName): array
    {
        if (! isset(self::$locations[$countryCode]['regions'][$regionName])) {
            return [
                'country' => self::$locations[$countryCode]['name'] ?? 'Unknown',
                'country_code' => $countryCode,
                'region' => $regionName,
                'city' => 'Unknown',
            ];
        }

        $cities = self::$locations[$countryCode]['regions'][$regionName];

        return [
            'country' => self::$locations[$countryCode]['name'],
            'country_code' => $countryCode,
            'region' => $regionName,
            'city' => fake()->randomElement($cities),
        ];
    }

    /**
     * Get all regions for a country
     */
    public static function getRegions(string $countryCode): array
    {
        return array_keys(self::$locations[$countryCode]['regions'] ?? []);
    }

    /**
     * Get all cities in a region
     */
    public static function getCitiesInRegion(string $countryCode, string $regionName): array
    {
        return self::$locations[$countryCode]['regions'][$regionName] ?? [];
    }

    /**
     * Get country name by code
     */
    public static function getCountryName(string $code): string
    {
        return self::$locations[$code]['name'] ?? 'Unknown';
    }

    /**
     * Get all available country codes
     */
    public static function getCodes(): array
    {
        return array_keys(self::$locations);
    }

    /**
     * Get all data
     */
    public static function all(): array
    {
        return self::$locations;
    }
}
