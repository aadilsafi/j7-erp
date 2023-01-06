<?php

namespace App\Http\Controllers;

use App\DataTables\CountryDataTable;
use App\DataTables\StateDataTable;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index(StateDataTable $dataTable, $site_id)
    {
        $data = [
            'site_id' => $site_id
        ];

        return $dataTable->with($data)->render('app.sites.states.index', $data);
    }

    public function create($site_id)
    {
        $data = [
            'site_id' => $site_id,
            'country' => Country::all()
        ];
        return view('app.sites.states.create', $data);
    }
    public function store(Request $request, $site_id)
    {
        $request->validate([
            'name' => 'required|unique:states,name',
            'short_label' => 'required',
            'country_id' => 'required|exists:countries,id'
        ]);

        $state = State::create(
            [
                'name' => $request->name,
                'country_id' => $request->country_id,
                'iso2' => $request->short_label,
                'country_code' => $request->country_code
            ]
        );
        return redirect()->route('sites.settings.states.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
    }
    public function edit($site_id, $id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'country' => Country::all(),
            'state' => State::find(decryptParams($id))
        ];
        return view('app.sites.states.edit', $data);
    }
    public function update(Request $request, $site_id, $id)
    {
        $request->validate([
            'name' => 'required|unique:states,name,' . decryptParams($id),
            'short_label' => 'required',
            'country_id' => 'required|exists:countries,id'
        ]);

        (new State())->find(decryptParams($id))->update([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'iso2' => $request->short_label,
            'country_code' => $request->country_code
        ]);

        return redirect()->route('sites.settings.states.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
    }
    public function getStates($countryId)
    // {
    {
        $states = State::select('name', 'id')->where('country_id', $countryId)->whereHas('cities')->get();
        return response()->json([
            'success' => true,
            'states' => $states->toArray(),
        ], 200);
    }
}
