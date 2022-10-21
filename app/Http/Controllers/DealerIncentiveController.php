<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Models\StakeholderType;
use App\Models\RebateIncentiveModel;
use App\DataTables\DealerIncentiveDataTable;
use App\Models\DealerIncentiveModel;
use App\Models\Stakeholder;
use App\Services\DealerIncentive\DealerInterface;
use Exception;
use App\Services\CustomFields\CustomFieldInterface;

class DealerIncentiveController extends Controller
{

    private $dealerIncentiveInterface;

    public function __construct(DealerInterface $dealerIncentiveInterface, CustomFieldInterface $customFieldInterface)
    {
        $this->dealerIncentiveInterface = $dealerIncentiveInterface;
        $this->customFieldInterface = $customFieldInterface;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DealerIncentiveDataTable $dataTable, Request $request, $site_id)
    {
        //
        $data = [
            'site_id' => decryptParams($site_id),
        ];

        return $dataTable->with($data)->render('app.sites.file-managements.files.dealer-incentive.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {

        if (!request()->ajax()) {
            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->dealerIncentiveInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $data = [
                'site_id' => decryptParams($site_id),
                'units' => Unit::where('status_id', 5)->with('floor', 'type')->get(),
                'rebate_files' => RebateIncentiveModel::pluck('id')->toArray(),
                'dealer_data' => StakeholderType::where('type', 'D')->where('status', 1)->with('stakeholder')->get(),
                'stakeholders' => Stakeholder::where('site_id', decryptParams($site_id))->with('dealer_stakeholder', 'stakeholder_types')->get(),
                'incentives' => DealerIncentiveModel::pluck('dealer_id')->toArray(),
                'customFields' => $customFields

            ];

            return view('app.sites.file-managements.files.dealer-incentive.create', $data);
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

        try {

            if (!request()->ajax()) {
                $inputs = $request->all();
                $record = $this->dealerIncentiveInterface->store($site_id, $inputs);
                return redirect()->route('sites.file-managements.dealer-incentive.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));

            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.file-managements.dealer-incentive.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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

    public function getData(Request $request)
    {
        $rebate_incentives = RebateIncentiveModel::with('unit')->where('dealer_id', $request->dealer_id)->get();
        $dealer_incentives =DealerIncentiveModel::where('dealer_id',$request->dealer_id)->get();
        $units= [];
        $already_incentive_paid_to_units = [];
        foreach($dealer_incentives as $dealer_incentives)
        {
            foreach(json_decode($dealer_incentives->unit_IDs) as $uids){
                $already_incentive_paid_to_units[] = $uids;

            }
        }
    
        foreach ($rebate_incentives as $Units) {

            if (in_array($Units->unit_id, $already_incentive_paid_to_units)) {
                
                continue;
            }
            else{
               
                $units[] = Unit::find($Units->unit_id);
                
            }     
        }

        return response()->json([
            'success' => true,
            'units' => $units,
        ], 200);
    }

    public function approve($site_id, $dealer_incentive_id){


        $dealer_incentive = DealerIncentiveModel::find(decryptParams($dealer_incentive_id));
        $dealer_incentive->status = 1;
        $dealer_incentive->update();

        return redirect()->route('sites.file-managements.dealer-incentive.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));

    }
}
