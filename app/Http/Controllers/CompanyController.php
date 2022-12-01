<?php

namespace App\Http\Controllers;

use App\DataTables\CompanyDataTable;
use App\Services\Company\Interface\CompanyInterface;
use Exception;
use Illuminate\Http\Request;
use Validator;

class CompanyController extends Controller
{
    private $companyInterface;

    public function __construct(CompanyInterface $companyInterface)
    {
        $this->companyInterface = $companyInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CompanyDataTable $dataTable, $site_id)
    {

        $data = [
            'site_id' => $site_id,
        ];

        return $dataTable->with($data)->render('app.sites.companies.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        if (!request()->ajax()) {

            $data = [
                'site_id' => decryptParams($site_id),
            ];
            return view('app.sites.companies.create', $data);
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $site_id)
    {
        $validator = $request->validate([
            'email' => 'required|email|unique:companies,email'
        ]);
        try {
            if (!request()->ajax()) {

                $inputs = $request->all();

                $record = $this->companyInterface->store($site_id, $inputs);
                return redirect()->route('sites.settings.companies.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.settings.companies.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
}
