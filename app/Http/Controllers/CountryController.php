<?php

namespace App\Http\Controllers;

use App\DataTables\CountryDataTable;
use App\Models\Country;
use App\Models\Site;
use Exception;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(CountryDataTable $dataTable, $site_id)
    {
        $data = [
            'site_id' => $site_id
        ];

        return $dataTable->with($data)->render('app.sites.locations.countries.index', $data);
    }

    public function create($site_id)
    {
        $data = [
            'site_id' => $site_id,
        ];
        return view('app.sites.locations.countries.create', $data);
    }

    public function store(Request $request, $site_id)
    {
        $request->validate([
            'name' => 'required|unique:countries,name',
            'capital' => 'required|unique:countries,capital',
            'short_label' => 'required|unique:countries,iso3',
        ]);
        $country = Country::create(
            [
                'name' => $request->name,
                'capital' => $request->capital,
                'iso3' => $request->short_label,
                'phonecode' => $request->phone_code,
            ]
        );
        return redirect()->route('sites.settings.countries.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
    }

    public function edit($site_id, $id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'country' => Country::find(decryptParams($id))
        ];
        return view('app.sites.locations.countries.edit', $data);
    }
    public function update(Request $request, $site_id, $id)
    {
        $request->validate([
            'name' => 'required|unique:countries,name,' .  decryptParams($id),
            'capital' => 'required|unique:countries,capital,' . decryptParams($id),
            'short_label' => 'required|unique:countries,iso3,' .  decryptParams($id),
        ]);

        (new Country())->find(decryptParams($id))->update([
            'name' => $request->name,
            'capital' => $request->capital,
            'iso3' => $request->short_label,
            'phonecode' => $request->phone_code,
        ]);

        return redirect()->route('sites.settings.countries.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
    }

    public function getCities(Request $request)
    {
        // dd(Site::find(1)->country);
        try {
            if ($request->has('country')) {
                $country    = (new Country())->find($request->country);
                $cities     = $country->cities;
                return response()->json($cities, 200);
            } else {
                return response()->json(__('lang.commons.please_select_at_least_one_item'));
            }
        } catch (Exception $ex) {
            return response()->json(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
}
