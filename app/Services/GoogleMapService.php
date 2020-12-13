<?php

namespace App\Services;

use App\Services\CurlService;

class GoogleMapService
{
    protected $baseUrl;
    private $findParameter;
    private $detailParameter;
    private $key;

    public function __construct()
    {
        $this->baseUrl = 'https://maps.googleapis.com/maps/api/place/';
        $this->findParameter = 'findplacefromtext/json?';
        $this->detailParameter = 'details/json?';
        $this->key = config('app.google_api_key');
    }

    public function getPlaceId(string $address)
    {
        $url = 
            $this->baseUrl . $this->findParameter .
           'key=' . $this->key .
           '&input=' . urlencode($address) .
           '&inputtype=textquery';

        $code = 0;
        while ($code != 200) {
            $code = CurlService::fetchHttpResponse(array(
                    'url' => $url,
                ), $response);

            $response = json_decode($response, true);
        }

        $placeId = $response['candidates'][0]['place_id'];

        return $placeId;
    }

    public function detail(string $placeId)
    {
        $url = $this->baseUrl . $this->detailParameter .
               'key=' . $this->key .
               '&place_id=' . $placeId;

        $code = 0;
        while ($code != 200) {
            $code = CurlService::fetchHttpResponse(array(
                    'url' => $url,
                ), $response);

            $response = json_decode($response, true);
        }

        return $response['result'];
    }
}