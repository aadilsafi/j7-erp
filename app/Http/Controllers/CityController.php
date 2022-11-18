<?php

namespace App\Http\Controllers;

use App\DataTables\CountryDataTable;
use App\Models\Country;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(CountryDataTable $dataTable, $site_id)
    {
        $data = [
            'site_id' => $site_id
        ];

        return $dataTable->with($data)->render('app.sites.countries.index', $data);
    }
}
