<?php

namespace App\Http\Controllers;

use App\DataTables\CityDataTable;
use App\DataTables\CountryDataTable;
use App\Models\Country;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(CityDataTable $dataTable, $site_id)
    {
        $data = [
            'site_id' => $site_id
        ];

        return $dataTable->with($data)->render('app.sites.cities.index', $data);
    }
}
