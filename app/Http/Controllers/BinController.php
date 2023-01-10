<?php

namespace App\Http\Controllers;

use App\DataTables\TypesBinDataTable;
use App\DataTables\UnitsBinDataTable;
use App\DataTables\AdditionalCostsBinDataTable;


use Illuminate\Http\Request;

class BinController extends Controller
{
    public function type(TypesBinDataTable $dataTable, $site_id)
    {
        $data = [
            'site_id' => $site_id
        ];

        return $dataTable->with($data)->render('app.sites.settings.bin.index', $data);
    }

    // public function unit(UnitsBinDataTable $dataTable, $site_id)
    // {
    //     $data = [
    //         'site_id' => $site_id
    //     ];

    //     return $dataTable->with($data)->render('app.sites.settings.bin.index', $data);
    // }

    // public function unit(AdditionalCostsBinDataTable $dataTable, $site_id)
    // {
    //     $data = [
    //         'site_id' => $site_id
    //     ];

    //     return $dataTable->with($data)->render('app.sites.settings.bin.index', $data);
    // }
}
