<?php

namespace App\Http\Controllers;

use App\DataTables\CountryDataTable;
use App\DataTables\StateDataTable;
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
}
