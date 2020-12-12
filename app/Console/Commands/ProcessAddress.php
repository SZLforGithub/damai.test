<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\City;
use App\Models\Area;
use App\Models\Street;


class ProcessAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:address';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $city;
    protected $area;
    protected $street;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(City $city, Area $area, Street $street)
    {
        parent::__construct();

        $this->city = $city;
        $this->area = $area;
        $this->street = $street;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $address_dir = base_path() . '/address/';

        if (!file_exists($address_dir)) {
            $this->extractAddress();
        }

        list($city_path, $street_path) = $this->getCityAndAddressPath($address_dir);

        $this->insertCityAndAreaData($city_path[0]);
        $this->insertStreetData($street_path);

        return 0;
    }

    private function extractAddress()
    {
        $zip = new \ZipArchive;

        if($zip->open(base_path() . '/address.zip') === TRUE){
            $zip->extractTo(base_path());
            $zip->close();
        }

        echo '解壓縮成功';

        return;
    }

    private function getCityAndAddressPath($dir)
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

        $city_path = array();
        $street_path = array(); 
        foreach ($files as $file) {
            if (
                $file->isDir() ||
                strpos($file->getPathname(), '.DS_Store') !== false
            ) {
                continue;
            }

            if (strpos($file->getPathname(), '/0.json')) {
                $city_path[] = $file->getPathname();
            } else {
                $street_path[] = $file->getPathname();
            }
        }

        return array($city_path, $street_path);
    }

    private function insertCityAndAreaData($city_path)
    {
        $data = json_decode(file_get_contents($city_path), true);

        foreach ($data as $key => $value) {
            $city = $this->city::firstOrCreate([
                'name' => $value['city'],
            ]);

            foreach ($value['data'] as $key => $data) {
                $city->areas()->firstOrCreate($data);
            }
        }

        return;
    }

    private function insertStreetData($street_path)
    {
        foreach ($street_path as $key => $path) {
            $data = json_decode(file_get_contents($path), true);
            $filename = pathinfo($path)['filename'];

            $area = $this->area->where('filename', $filename)->first();
            foreach ($data as $key => $value) {
                $area->streets()->firstOrCreate($value);
            }
        }            
    }
}
