<?php

namespace App\Http\Controllers;

use App\DataTables\FourthLevelAccountsDatatable;
use App\Http\Requests\AccountCreations\FirstLevelStore;
use App\Models\AccountHead;
use App\Services\AccountCreations\FourthLevel\FourthLevelACcountinterface;
use Exception;
use Illuminate\Http\Request;

class FourthLevelAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $FourthAccountInterface;

    public function __construct(FourthLevelACcountinterface $FourthAccountInterface)
    {
        $this->FourthAccountInterface = $FourthAccountInterface;
    }

    public function index(FourthLevelAccountsDatatable $dataTable, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id)
        ];

        return $dataTable->with($data)->render('app.sites.accounts.account-creation.fourth-level.index', $data);
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
            'thirdLevelAccount' => AccountHead::where('level', 3)->get(),
        ];

        return view('app.sites.accounts.account-creation.fourth-level.create', $data);
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
                $record = $this->FourthAccountInterface->store($site_id, $data);
                return redirect()->route('sites.settings.accounts.fourth-level.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.settings.accounts.fourth-third.create', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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