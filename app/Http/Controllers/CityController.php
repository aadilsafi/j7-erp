<?php

namespace App\Http\Controllers;

use App\DataTables\CityDataTable;
use App\DataTables\CountryDataTable;
use App\Models\City;
use App\Models\Country;
use COM;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(CityDataTable $dataTable, $site_id)
    {
        $data = [
            'site_id' => $site_id
        ];

        return $dataTable->with($data)->render('app.sites.locations.cities.index', $data);
    }
    public function create($site_id)
    {
        $data = [
            'site_id' => $site_id,
            'country' => Country::all()
        ];
        return view('app.sites.locations.cities.create', $data);
    }
    public function store(Request $request, $site_id)
    {
        $request->validate([
            'name' => 'required|unique:cities,name',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id'

        ]);

        $city = City::create(
            [
                'name' => $request->name,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id
            ]

        );

        return redirect()->route('sites.settings.cities.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
    }
    public function edit($site_id, $id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'country' => Country::all(),
            'city' => City::find(decryptParams($id))
        ];
        return view('app.sites.locations.cities.edit', $data);
    }
    public function update(Request $request, $site_id, $id)
    {
        $request->validate([
            'name' => 'required|unique:cities,name,' . decryptParams($id),
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id'

        ]);

        (new City())->find(decryptParams($id))->update([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id
        ]);

        return redirect()->route('sites.settings.cities.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
    }
    public function getCities($stateId)
    {
        $cities = City::select('name', 'id')->where('state_id', $stateId)->get();
        return response()->json([
            'success' => true,
            'cities' => $cities->toArray()
        ], 200);
    }
}
