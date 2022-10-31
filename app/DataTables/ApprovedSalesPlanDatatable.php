<?php

namespace App\DataTables;

use App\Models\AccountAction;
use App\Models\AccountLedger;
use App\Models\SalesPlan;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Barryvdh\DomPDF\Facade\Pdf;

class ApprovedSalesPlanDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query)
    {
        $data  = [
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Disapproved',
            3 => 'Cancelled',
        ];
        $columns = array_column($this->getColumns(), 'data');
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('check', function ($salesPlan) {
                return $salesPlan;
            })
            ->editColumn('user_id', function ($salesPlan) {
                return $salesPlan->user->name;
            })
            ->editColumn('status', function ($salesPlan) {
                if ($salesPlan->status == 0) {
                    return '<span class="badge badge-glow bg-warning">Pending</span>';
                } elseif ($salesPlan->status == 1) {
                    return '<span class="badge badge-glow bg-success">Approved</span>';
                } elseif ($salesPlan->status == 3) {
                    return '<span class="badge badge-glow bg-danger">Cancelled</span>';
                } else {
                    return '<span class="badge badge-glow bg-danger">Disapproved</span>';
                }
            })
            ->editColumn('stakeholder_id', function ($salesPlan) {

                // $staleholder = json_decode($salesPlan->stakeholder_data);
                // return $staleholder->full_name;

                return $salesPlan->stakeholder->full_name;
            })
            ->editColumn('created_at', function ($salesPlan) {
                return editDateColumn($salesPlan->created_at);
            })
            ->editColumn('updated_at', function ($salesPlan) {
                return editDateColumn($salesPlan->updated_at);
            })
            ->editColumn('salesplanstatus', function ($salesPlan) use ($data) {
                return $data[$salesPlan->status];
            })
            ->editColumn('actions', function ($salesPlan) {
                return view('app.sites.floors.units.sales-plan.actions', ['site_id' => $salesPlan->unit->floor->site->id, 'floor_id' => $salesPlan->unit->floor_id, 'unit_id' => $salesPlan->unit_id, 'id' => $salesPlan->id, 'status' => $salesPlan->status, 'unit_status' => $salesPlan->unit->status_id]);
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SalesPlan $model): QueryBuilder
    {
        return $model->newQuery()->with('unit', 'stakeholder')->where('status', 1);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('ledger-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->select()
            // ->selectClassName('bg-primary')
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons(
                Button::make('export')->addClass('btn btn-relief-outline-secondary waves-effect waves-float waves-light dropdown-toggle')->buttons([
                    Button::make('print')->addClass('dropdown-item'),
                    Button::make('copy')->addClass('dropdown-item'),
                    Button::make('csv')->addClass('dropdown-item'),
                    Button::make('excel')->addClass('dropdown-item'),
                    Button::make('pdf')->addClass('dropdown-item'),
                ]),
                Button::make('reset')->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light'),
                Button::make('reload')->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light'),

            )
            // ->rowGroupDataSrc('account_action_id')
            ->columnDefs([])
            ->orders([
                // [4, 'asc'],
                [2, 'asc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('#'),
            Column::make('user_id')->title('Sales Person'),
            Column::computed('stakeholder_id')->title('Stakeholder'),
            Column::computed('salesplanstatus')->visible(false),
            Column::make('status')->title('Status')->addClass('text-center'),
            Column::make('created_at')->title('Created At')->addClass('text-nowrap'),
            // Column::make('created_at')->title('Transaction At')->addClass('text-nowrap text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Approved-Sales-Plans_' . date('YmdHis');
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = Pdf::loadView($this->printPreview, ['data' => $data])->setOption(['defaultFont' => 'sans-serif']);
        return $pdf->download($this->filename() . '.pdf');
    }
}
