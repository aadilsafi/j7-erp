<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Receipt;
use App\Models\Stakeholder;
use Illuminate\Http\Request;
use App\Models\FileManagement;
use App\Models\UnitStakeholder;
use App\DataTables\ViewFilesDatatable;
use App\Models\RebateIncentiveModel;
use App\Utils\Enums\StakeholderTypeEnum;
use App\Services\Stakeholder\Interface\StakeholderInterface;

class FileReleaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $stakeholderInterface;

    public function __construct(StakeholderInterface $stakeholderInterface)
    {
        $this->stakeholderInterface = $stakeholderInterface;
    }

    public function index(ViewFilesDatatable $dataTable, Request $request, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
        ];

        $data['unit_ids'] = (new UnitStakeholder())->whereSiteId($data['site_id'])->get()->pluck('unit_id')->toArray();

        return $dataTable->with($data)->render('app.sites.file-managements.files.files-actions.file-resale.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($site_id, $unit_id, $customer_id)
    {
        if (!request()->ajax()) {
            $unit = Unit::find(decryptParams($unit_id));
            $receipts = Receipt::where('unit_id',decryptParams($unit_id))->where('sales_plan_id',$unit->salesPlan[0]['id'])->get();
            $total_paid_amount = $receipts->sum('amount_in_numbers');
            $rebate_incentive = RebateIncentiveModel::where('unit_id',$unit->id)->where('stakeholder_id',decryptParams($customer_id))->first();
            if(isset($rebate_incentive)){
                $rebate_total = $rebate_incentive->commision_total;
            }
            else{
                $rebate_total = 0;
            }
            $data = [
                'site_id' => decryptParams($site_id),
                'unit' => Unit::find(decryptParams($unit_id)),
                'customer' => Stakeholder::find(decryptParams($customer_id)),
                'file' => FileManagement::where('unit_id', decryptParams($unit_id))->where('stakeholder_id', decryptParams($customer_id))->first(),
                'total_paid_amount' => $total_paid_amount,
                'stakeholders' => $this->stakeholderInterface->getAllWithTree(),
                'stakeholderTypes' => StakeholderTypeEnum::array(),
                'emptyRecord' => [$this->stakeholderInterface->getEmptyInstance()],
                'rebate_incentive' => $rebate_incentive,
                'rebate_total'=> $rebate_total,
            ];

            unset($data['emptyRecord'][0]['stakeholder_types']);
            return view('app.sites.file-managements.files.files-actions.file-resale.create', $data);
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
    public function store(Request $request)
    {
        //
        dd($request->all());
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
