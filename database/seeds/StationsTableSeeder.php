<?php

use App\Station;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Database\Seeder;
use Symfony\Component\DomCrawler\Crawler;

class StationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guzzleClient = new GuzzleClient();
        $client = new Client();
        $crawler = $client->request('GET', "https://sunrail.com/");
        $stations = $crawler->filter('#menu-item-1566 .sub-menu a');

        $stations->each(function (Crawler $station) use ($client, $guzzleClient) {
            $name = $station->text();
            $page = $client->click($station->link());
            $addressParts = explode(',', $page->filter('.address')->text());
            $address = trim($addressParts[0]);
            $city = trim($addressParts[1]);
            $statePostalCode = explode(' ', trim($addressParts[2]));
            $state = $statePostalCode[0];
            $postalCode = $statePostalCode[1];
            $details = $page->filter('.vc_row.wpb_row.vc_inner.vc_row-fluid.full-width.padding-60')->last();
            $details = trim(str_replace(PHP_EOL, '', $details->text()));
            $details = preg_replace('/\s\s+/', ' ', $details);

            try {
                $county = $guzzleClient->request('GET', 'https://www.geonames.org/postalCodeLookupJSON', [
                    'query' => [
                        'country' => 'USA',
                        'postalcode' => $postalCode,
                    ],
                ]);

                $county = json_decode($county->getBody()->getContents())->postalcodes[0]->adminName2;
            } catch (Throwable $exception) {
                $county = '';
            }

            $station = Station::query()->firstOrCreate(['name' => $name], [
                'address' => $address,
                'city' =>  $city,
                'county' => $county,
                'state' => $state,
                'postal_code' => $postalCode,
                'details' => $details,
            ]);

            // Reference the current county value.
            $currentCounty = $station->getAttribute('county');

            if (!$currentCounty && $county) {
                // Check for missing county and set it if there's a county.
                $station->setAttribute('county', $county)->save();
            } elseif (!$currentCounty && !$county) {
                $message = "Failed getting county for '{$name}'. Geonames is down for maintenance so try again later.";

                // Log an error message about failing to get a county and to try again later.
                \Log::error($message);
                dump($message);
            }
        });
    }
}
