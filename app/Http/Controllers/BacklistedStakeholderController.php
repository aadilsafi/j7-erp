<?php
namespace App\Http\Controllers;

use App\DataTables\BlacklistedStakeholderDataTable;
use App\Models\BacklistedStakeholder;
use Illuminate\Http\Request;
use App\Http\Requests\BlackListStackholder\StoreRequest;
use App\Http\Requests\BlackListStackholder\UpdateRequest;

use App\Models\City;
use App\Models\State;
use Exception;
use App\Models\
{
    Floor, Unit, Site, TempFloor,
};
use App\Models\Country;
use App\Services\BlackListStackholder\
{
    BlacklistStackholderInterface, BlackListStackholderService,
};
use App\Utils\Enums\
{
    UserBatchActionsEnum, UserBatchStatusEnum,
};
class BacklistedStakeholderController extends Controller
{
    private $blacklistStackholderInterface;
    public function __construct(BlacklistStackholderInterface $blacklistStackholderInterface,
)
    {
        $this->blacklistStackholderInterface = $blacklistStackholderInterface;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BlacklistedStakeholderDataTable $dataTable, $site_id)
    {
        $data = ['site_id' => $site_id];

        return $dataTable->with($data)->render('app.sites.stakeholders.blacklisted-stakeholders.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        if (!request()->ajax())
        {

            $data = ['site_id' => $site_id, 'country' => Country::get() , ];

            return view('app.sites.stakeholders.blacklisted-stakeholders.create', $data);

        }
        else
        {
            abort(403);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $site_id)
    {

        try
        {
            if (!request()->ajax())
            {

                $inputs = $request->all();
                $record = $this
                    ->blacklistStackholderInterface
                    ->store($site_id, $inputs);
                return redirect()->route('sites.blacklisted-stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id)) ])->withSuccess(__('lang.commons.data_saved'));

            }
            else
            {
                abort(403);
            }
        }
        catch(Exception $ex)
        {
            return redirect()->route('sites.blacklisted-stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id)) ])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BacklistedStakeholder  $backlistedStakeholder
     * @return \Illuminate\Http\Response
     */
    public function show(BacklistedStakeholder $backlistedStakeholder)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BacklistedStakeholder  $backlistedStakeholder
     * @return \Illuminate\Http\Response
     */
    public function edit($site_id, $id)
    {

        try
        {
            $stakeholder = $this
                ->blacklistStackholderInterface
                ->getById(decryptParams($id));

            if (!empty($stakeholder))
            {
                $data = ['site_id' => $site_id, 'stakeholder' => $stakeholder, 'country' => Country::get() ,

                ];

                return view('app.sites.stakeholders.blacklisted-stakeholders.edit', $data);
            }
            else
            {
                return redirect()->route('sites.blacklisted-stakeholders.index', ['site_id' => encryptParams($site_id) ])->withWarning(__('lang.commons.data_not_found'));
            }

        }
        catch(Exception $ex)
        {
            return redirect()->route('sites.blacklisted-stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id)) ])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BacklistedStakeholder  $backlistedStakeholder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $site_id, $id)
    {

        $id = decryptParams($id);

        try
        {
            if (!request()->ajax())
            {
                $inputs = $request->all();
                $record = $this
                    ->blacklistStackholderInterface
                    ->update($site_id, $inputs, $id);
                return redirect()->route('sites.blacklisted-stakeholders.index', ['site_id' => encryptParams($site_id) ])->withSuccess(__('lang.commons.data_updated'));
            }
            else
            {
                abort(403);
            }
        }
        catch(Exception $ex)
        {
            return redirect()->route('sites.blacklisted-stakeholders.index', ['site_id' => encryptParams($site_id) ])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BacklistedStakeholder  $backlistedStakeholder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $site_id)
    {
        $site_id = decryptParams($site_id);
        try
        {
            $stakeholder = $this
                ->blacklistStackholderInterface
                ->destroySelected($request->input('chkRole'));
            return redirect()
                ->route('sites.blacklisted-stakeholders.index', ['site_id' => encryptParams($site_id) ])->withSuccess(__('lang.commons.data_deleted'));

        }
        catch(Exception $ex)
        {
            return redirect()->route('sites.blacklisted-stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id)) ])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

}

