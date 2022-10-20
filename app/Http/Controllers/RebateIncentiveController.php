<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\DataTables\RebateIncentiveDataTable;
use App\Models\RebateIncentiveModel;
use App\Models\StakeholderType;
use App\Services\RebateIncentive\RebateIncentiveInterface;
use App\Services\CustomFields\CustomFieldInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Rebateincentive\storeRequest;

class RebateIncentiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $rebateIncentive;

    public function __construct(
        RebateIncentiveInterface $rebateIncentive, CustomFieldInterface $customFieldInterface
    ) {
        $this->rebateIncentive = $rebateIncentive;
        $this->customFieldInterface = $customFieldInterface;
    }

    public function index(RebateIncentiveDataTable $dataTable, Request $request, $site_id)
    {
        //
        $data = [
            'site_id' => decryptParams($site_id),
        ];

        return $dataTable->with($data)->render('app.sites.file-managements.files.rebate-incentive.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {

        if (!request()->ajax()) {

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->rebateIncentive->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $data = [
                'site_id' => decryptParams($site_id),
                'units' => Unit::where('status_id', 5)->where('is_for_rebate', true)->with('floor', 'type')->get(),
                'rebate_files' => RebateIncentiveModel::pluck('unit_id')->toArray(),
                'dealer_data' => StakeholderType::where('type','D')->where('status',1)->with('stakeholder')->get(),
                'customFields' => $customFields

            ];

            return view('app.sites.file-managements.files.rebate-incentive.create', $data);
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
    public function store(storeRequest $request, $site_id)
    {
       // dd($request->all());
        try {
            $inputs = $request->input();

            $record = $this->rebateIncentive->store(decryptParams($site_id), $inputs);
            return redirect()->route('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess('Data Saved!');
        } catch (Exception $ex) {
            Log::error($ex->getLine() . " Message => " . $ex->getMessage() );
            return redirect()->route('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong'));
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
    public function edit($site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        try {
            $rebate_data = $this->rebateIncentive->getById($site_id, $id);
            if ($rebate_data && !empty($rebate_data)) {

                $data = [
                    'site_id' => $site_id,
                    'id' => $id,
                    'rebate_data' => $rebate_data,
                    'edit_unit' => Unit::find($rebate_data->unit_id),
                    'dealer_data' => StakeholderType::where('type','D')->where('status',1)->with('stakeholder')->get(),
                ];

                return view('app.sites.file-managements.files.rebate-incentive.edit', $data);
            }

            return redirect()->route('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        try {
            if (!request()->ajax()) {
                $inputs = $request->all();

                $record = $this->rebateIncentive->update($site_id, $id, $inputs);
                return redirect()->route('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
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

    public function getData(Request $request)
    {
        // dd($request->all());
        $unit = Unit::find($request->unit_id);
        $stakeholder = $unit->salesPlan[0]['stakeholder'];
        $leadSource = $unit->salesPlan[0]['leadSource'];
        $salesPlan = $unit->salesPlan[0];
        $floor = $unit->floor->short_label;

        return response()->json([
            'success' => true,
            'unit' => $unit,
            'stakeholder' => $stakeholder,
            'leadSource' => $leadSource,
            'cnic' => cnicFormat($stakeholder->cnic),
            'salesPlan' => $salesPlan,
            'floor' => $floor,
            'facing' => $unit->facing,
            // 'corner' => $unit->corner,
        ], 200);
    }
}
