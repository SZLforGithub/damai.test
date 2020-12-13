<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleMapService;
use App\Models\Area;

class AddressController extends Controller
{
    protected $googleMapService;
    protected $area;

    public function __construct(GoogleMapService $googleMapService, Area $area)
    {
        $this->googleMapService = $googleMapService;
        $this->area = $area;
    }

    public function GetAddress(Request $request)
    {
        $address = $request->get('address');

        $placeId = $this->googleMapService->getPlaceId($address);

        $data = $this->googleMapService->detail($placeId);

        $output = $this->format($data);

        return response()->json($output, 200, [], JSON_UNESCAPED_UNICODE);
    }

    private function format($data)
    {
        $address = array();
        foreach ($data['address_components'] as $key => $value) {
            switch ($value['types'][0]) {
                case 'subpremise':
                    $address['floor'] = $value['long_name'];
                    break;

                case 'street_number':
                    $address['no'] = $value['long_name'];
                    break;

                case 'route':
                    preg_match("/[\d]*巷/", $value['long_name'], $lane);
                    preg_match("/[\d]*弄/", $value['long_name'], $alley);
                    $road = preg_replace("/([\d]*)巷/", "", $value['long_name']);
                    $road = preg_replace("/([\d]*)弄/", "", $road);
                    $address['road'] = $road ?? '';
                    $address['lane'] = $lane[0] ?? '';
                    $address['alley'] = $alley[0] ?? '';
                    break;

                case 'administrative_area_level_3':
                    $address['area'] = $value['long_name'];
                    break;

                case 'administrative_area_level_1':
                    $address['city'] = $value['long_name'];
                    break;

                case 'postal_code':
                    $address['zip'] = (int)$value['long_name'];
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        $output = array(
            'zip' => $address['zip'],
            'city' => $address['city'], 
            'area' => $address['area'], 
            'road' => $address['road'], 
            'lane' => isset($address['lane']) && !empty($address['lane']) ? (int)$address['lane'] : '', 
            'alley' => isset($address['alley']) && !empty($address['alley']) ? (int)$address['alley'] : '', 
            'no' => isset($address['no']) && !empty($address['no']) ? (int)$address['no'] : '', 
            'floor' => isset($address['floor']) && !empty($address['floor']) ? (int)$address['floor'] : '', 
            'address' => '', 
            'filename' => $this->area
                               ->where([
                                ['zip', (int)$address['zip']],
                                ['area', $address['area']]
                                ])
                               ->first()
                               ->filename,
            'latitude' => (float)$data['geometry']['location']['lat'], 
            'lontitue' => (float)$data['geometry']['location']['lng'], 
            'full_address' => $data['formatted_address'], 
        );

        return $output;
    }
}
