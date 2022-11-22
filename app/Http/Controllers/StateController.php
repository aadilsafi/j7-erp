<?php

namespace App\Http\Controllers;

use App\DataTables\CountryDataTable;
use App\DataTables\StateDataTable;
use App\Models\State;
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

    public function getStates($countryId)
    {
        $states = State::select('name', 'id')->where('country_id', $countryId)->get();

        return response()->json([
            'success' => true,
            'states' => $states->toArray()
        ], 200);
    }
}
