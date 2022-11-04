<?php

namespace App\DataTables;

use App\Models\AccountAction;
use App\Models\AccountHead;
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

class TrialBalanceDataTable extends DataTable
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
            ->editColumn('level', function ($accountHead) {
                return number_format($accountHead->level);
            })
            ->editColumn('code', function ($accountHead) {
                return account_number_format($accountHead->code);
            })
            ->editColumn('starting_balance', function ($accountHead) {
                if (count($accountHead->accountLedgers) > 0) {
                    return number_format($accountHead->accountLedgers->pluck('credit')->sum());
                }
            })
            ->editColumn('debit', function ($accountHead) {
                // dd($accountHead->accountLedgers);
                if (count($accountHead->accountLedgers) > 0) {
                    return number_format($accountHead->accountLedgers->pluck('debit')->sum());
                }
                // return '0';
            })
            ->editColumn('credit', function ($accountHead) {
                if (count($accountHead->accountLedgers) > 0) {
                    return number_format($accountHead->accountLedgers->pluck('credit')->sum());
                }
                // return '0';
            })
            ->editColumn('ending_balance', function ($accountHead) {
                if (count($accountHead->accountLedgers) > 0) {
                    return number_format($accountHead->accountLedgers->pluck('credit')->sum());
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
    public function query(AccountHead $model): QueryBuilder
    {
        return $model->where('level', 5)->whereHas('accountLedgers')->newQuery()->with(['modelable', 'accountLedgers' => function ($q) {
            $q->whereNot([['debit', 0], ['credit', 0]])->with('accountActions');
        }])->orderBy('level', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('accountHead-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->select()
            // ->selectClassName('bg-primary')
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([5000])
            // $builder->ajax($attributes);
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
            // ->rowGroupDataSrc('level')
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
            // Column::make('id')->title('id')->addClass('text-nowrap text-center'),
            Column::make('code')->title('Account Codes')->addClass('text-nowrap'),
            Column::make('name')->title('Account Name')->addClass('text-nowrap'),
            Column::make('starting_balance')->title('Starting Balance')->addClass('text-nowrap')->searchable(false)->orderable(false),
            Column::make('debit')->title('Debit')->addClass('text-nowrap')->searchable(false)->orderable(false),
            Column::make('credit')->title('Credit')->addClass('text-nowrap')->searchable(false)->orderable(false),
            Column::make('ending_balance')->title('Ending Balance')->addClass('text-nowrap')->searchable(false)->orderable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ledger_' . date('YmdHis');
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
