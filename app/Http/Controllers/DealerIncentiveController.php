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
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use DB;

class DealerIncentiveController extends Controller
{

    private $dealerIncentiveInterface, $financialTransactionInterface;

    public function __construct(DealerInterface $dealerIncentiveInterface, CustomFieldInterface $customFieldInterface, FinancialTransactionInterface $financialTransactionInterface)
    {
        $this->dealerIncentiveInterface = $dealerIncentiveInterface;
        $this->customFieldInterface = $customFieldInterface;
        $this->financialTransactionInterface = $financialTransactionInterface;
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

            $dealers = RebateIncentiveModel::select('dealer_id as stakeholder_id')->distinct()->whereHas('dealer')->with('stakeholder')->get();

            $data = [
                'site_id' => decryptParams($site_id),
                'units' => Unit::where('status_id', 5)->with('floor', 'type')->get(),
                'rebate_files' => RebateIncentiveModel::pluck('id')->toArray(),
                'dealer_data' => StakeholderType::where('type', 'D')->where('status', 1)->with('stakeholder')->get(),
                'stakeholders' => $dealers,
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

        // $dealer_incentives = DealerIncentiveModel::where('dealer_id', $request->dealer_id)->get();
        // $already_incentive_paid_to_units = [];
        // foreach ($dealer_incentives as $dealer_incentives) {
        //     foreach (json_decode($dealer_incentives->unit_IDs) as $uids) {
        //         $already_incentive_paid_to_units[] = $uids;
        //     }
        // }

        $rebate_units = RebateIncentiveModel::with('unit')->where('dealer_id', $request->dealer_id)
            ->where(['is_for_dealer_incentive'=>true , 'status'=>true])->distinct()->get();
        $units = [];

        if ($rebate_units) {
            foreach ($rebate_units as $Units) {

                $units[] = Unit::find($Units->unit_id);
            }
        }

        $paid_incentives = DealerIncentiveModel::where('dealer_id', $request->dealer_id)->get();

        $paidTable = '';
        if ($paid_incentives) {
            $loopKey = 1;
            foreach ($paid_incentives as $incentive) {
                $unitsIds = json_decode($incentive->unit_IDs);

                foreach ($unitsIds as $key => $Units) {
                    $paid_unit = Unit::find($Units);
                    $paidTable .= '<tr class="text-nowrap text-center">';
                    $paidTable .= '<td class="text-nowrap text-center">' . $loopKey++ . '</td>';
                    $paidTable .= '<td class="text-nowrap text-center">' . $paid_unit->name . '</td>';
                    $paidTable .= '<td class="text-nowrap text-center">' . $paid_unit->floor_unit_number . '</td>';
                    $paidTable .= '<td class="text-nowrap text-center">' . $paid_unit->gross_area . '</td>';
                    $paidTable .= '<td class="text-nowrap text-center">' . $incentive->dealer_incentive . '</td>';
                    $paidTable .= '</tr>';
                }
            }
        }

        return response()->json([
            'success' => true,
            'units' => $units,
            'paidTable' => $paidTable,
        ], 200);
    }

    public function approve($site_id, $dealer_incentive_id)
    {
        DB::transaction(function () use ($site_id, $dealer_incentive_id) {
            // Account ledger transaction
            $transaction = $this->financialTransactionInterface->makeDealerIncentiveTransaction(decryptParams($dealer_incentive_id));

            $dealer_incentive = DealerIncentiveModel::find(decryptParams($dealer_incentive_id));
            $dealer_incentive->status = 1;
            $dealer_incentive->update();

        });
        return redirect()->route('sites.file-managements.dealer-incentive.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
    }
}
