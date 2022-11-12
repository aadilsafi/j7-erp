<?php

namespace App\Http\Controllers;

use App\DataTables\FifthLevelAccountsDatatable;
use App\Http\Requests\AccountCreations\FirstLevelStore;
use App\Models\AccountHead;
use App\Services\AccountCreations\FifthLevel\FifthLevelAccountinterface;
use Exception;
use Illuminate\Http\Request;

class FifthLevelAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $FifthAccountInterface;

    public function __construct(FifthLevelAccountinterface $FifthAccountInterface)
    {
        $this->FifthAccountInterface = $FifthAccountInterface;
    }

    public function index(FifthLevelAccountsDatatable $dataTable, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id)
        ];

        return $dataTable->with($data)->render('app.sites.accounts.account-creation.fifth-level.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        abort_if(request()->ajax(), 403);
        $data = [
            'site_id' => decryptParams($site_id),
            'fourthLevelAccount' => AccountHead::where('level', 4)->get(),
        ];

        return view('app.sites.accounts.account-creation.fifth-level.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FirstLevelStore $request, $site_id)
    {
        //
        try {
            if (!request()->ajax()) {
                $data = $request->all();
                $record = $this->FifthAccountInterface->store($site_id, $data);
                return redirect()->route('sites.settings.accounts.fifth-level.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.settings.accounts.fifth-level.create', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
