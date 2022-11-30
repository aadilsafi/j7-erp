<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentVoucherDatatable;
use Illuminate\Http\Request;

class PaymentVocuherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaymentVoucherDatatable $dataTable, $site_id)
    {
        //
        $data = [
            'site_id' => $site_id,
        ];
        return $dataTable->with($data)->render('app.sites.payment-voucher.index', $data);
    }
}
