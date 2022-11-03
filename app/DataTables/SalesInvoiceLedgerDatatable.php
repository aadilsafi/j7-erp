<?php

namespace App\DataTables;

use App\Models\AccountAction;
use App\Models\AccountLedger;
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

class SalesInvoiceLedgerDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query)
    {
        $columns = array_column($this->getColumns(), 'data');
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('parent_id', function ($ledger) {
                return Str::of(getRoleParentByParentId($ledger->parent_id))->ucfirst();
            })
            ->setRowId('id')
            ->editColumn('debit', function ($ledger) {
                return number_format($ledger->debit);
            })
            ->editColumn('credit', function ($ledger) {
                return number_format($ledger->credit);
            })
            ->editColumn('balance', function ($ledger) {
                return number_format($ledger->balance);
            })
            ->editColumn('account_action_id', function ($ledger) {
                return $ledger->accountActions->name;
            })
            ->editColumn('created_at', function ($ledger) {
                // return $ledger->created_at->format('D, d-M-Y , H:i:s');
                return editDateColumn($ledger->created_at);
            })
            ->editColumn('updated_at', function ($ledger) {
                return editDateColumn($ledger->updated_at);
            })
            ->editColumn('account_head_code_name', function ($ledger) {
                return $ledger->accountHead->name;
            })
            ->editColumn('account_head_code', function ($ledger) {
                return account_number_format($ledger->account_head_code);
            })
            ->editColumn('origin', function ($ledger) {
                if ($ledger->account_action_id == 1) {
                    return '<a href="' . route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams($ledger->site_id), 'floor_id' => encryptParams($ledger->salesPlan->unit->floor->id), 'unit_id' => encryptParams($ledger->salesPlan->unit->id)]) . '">
                                <span class="badge rounded-pill bg-warning">
                                <i class="bi bi-box-arrow-right" ></i>
                                </span>
                            </a>';
                } else if ($ledger->account_action_id == 2 || $ledger->account_action_id == 9 || $ledger->account_action_id == 10 || $ledger->account_action_id == 11) {
                    return '<a href="' . route('sites.receipts.index', ['site_id' => encryptParams($ledger->site_id)]) . '">
                                <span class="badge rounded-pill bg-warning"><i class="bi bi-box-arrow-right" ></i></span>
                            </a>';
                } else if ($ledger->account_action_id == 5) {
                    return '<a href="' . route('sites.file-managements.file-refund.index', ['site_id' => encryptParams($ledger->site_id)]) . '">
                                <span class="badge rounded-pill bg-warning"><i class="bi bi-box-arrow-right" ></i></span>
                            </a>';
                } else {
                    return  '<span s class="badge rounded-pill bg-warning"><i class="bi bi-box-arrow-right"></i></span>';
                }
            })

            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AccountLedger $model): QueryBuilder
    {
        return $model->newQuery()->with('accountActions', 'salesPlan', 'receipt')->orderBy('id','desc');
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
                // [2, 'asc'],
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
            Column::computed('origin')->title('Origin'),
            Column::make('account_action_id')->name('accountActions.name')->title('Account Action')->addClass('text-nowrap text-center'),
            Column::computed('account_head_code_name')->name('accountHead.name')->title('Account Name')->addClass('text-nowrap text-center'),
            Column::make('account_head_code')->title('Account Code')->addClass('text-nowrap text-center'),
            Column::make('debit')->title('Debit')->addClass('text-nowrap text-center'),
            Column::make('credit')->title('Credit')->addClass('text-nowrap text-center'),
            // Column::make('balance')->title('Balance')->addClass('text-nowrap text-center'),
            // Column::make('nature_of_account'),
            Column::make('created_at')->title('Transaction At')->addClass('text-nowrap text-center'),
            // Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Sales-Invoice-ledger_' . date('YmdHis');
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
