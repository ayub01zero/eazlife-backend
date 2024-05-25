<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeocodeController extends Controller
{
    public function getAddressFromLocation(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        $client = new Client();
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$apiKey}");
        $data = json_decode($response->getBody(), true);

        if (isset($data['results'][0])) {
            $formattedAddress = $data['results'][0]['formatted_address'];
            $address = $this->removeCountryFromAddress($formattedAddress);
            return response()->json(['address' => $address]);
        }

        return response()->json(['error' => 'Failed to retrieve address for geolocation'], 500);
    }

    public function getLocationFromAddress(Request $request)
    {
        $address = $request->input('address');

        $client = new Client();
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}");
        $data = json_decode($response->getBody(), true);

        if (isset($data['results'][0])) {
            $lat = $data['results'][0]['geometry']['location']['lat'];
            $lng = $data['results'][0]['geometry']['location']['lng'];
            $formattedAddress = $data['results'][0]['formatted_address'];
            $address = $this->removeCountryFromAddress($formattedAddress);

            return response()->json([
                'latitude' => $lat,
                'longitude' => $lng,
                'address' => $address,
            ]);
        }

        return response()->json(['error' => 'Failed to retrieve location for address'], 500);
    }

    private function removeCountryFromAddress($address)
    {
        $index = strrpos($address, ",");
        return substr($address, 0, $index);
    }

    // Add the getCompaniesFromLocation function here.
    // You will need to implement the logic to query your database
    // and filter the results based on the geolocation data.
}
