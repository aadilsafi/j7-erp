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

class ChartOfAccountsDataTable extends DataTable
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
            ->editColumn('level', function ($chartOfAccount) {
                return number_format($chartOfAccount->level);
            })
            ->editColumn('code', function ($chartOfAccount) {
                return account_number_format($chartOfAccount->code);
            })
            ->editColumn('account_name', function ($chartOfAccount) {
                // dd($chartOfAccount->accountLedgers);
                if(isset($chartOfAccount->accountLedgers->accountActions))
                {

                    return ($chartOfAccount->accountLedgers->accountActions->name);
                }
                else
                {
                    return 'no name';
                }
            })
            ->editColumn('balance', function ($chartOfAccount) {
                return '0';
            })
            ->editColumn('level', function ($chartOfAccount) {
                if($chartOfAccount->level == 1){
                    return 'First Level';
                }
                if($chartOfAccount->level == 2){
                    return 'Second Level';
                }
                if($chartOfAccount->level == 3){
                    return 'Third Level';
                }
                if($chartOfAccount->level == 4){
                    return 'Fourth Level';
                }
                if($chartOfAccount->level == 5){
                    return 'Fifth Level';
                }
            })
            // ->editColumn('account_type', function ($chartOfAccount) {
            //     if($chartOfAccount->level == 1){
            //         return 'First Level';
            //     }else
            //     {
            //         return 'no name';
            //     }
            // })
            // ->editColumn('created_at', function ($chartOfAccount) {
            //     // return $chartOfAccount->created_at->format('D, d-M-Y , H:i:s');
            //     return editDateColumn($chartOfAccount->created_at);
            // })

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
        return $model->newQuery()->with('modelable','accountLedgers.accountActions')->orderBy('level','asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('chartOfAccount-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->select()
            // ->selectClassName('bg-primary')
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([100,200,300,500])
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
            ->rowGroupDataSrc('level')
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
            Column::make('name')->title('Name'),
            Column::make('level')->title('Account Level')->addClass('text-nowrap ')->searchable(false)->orderable(false),
            Column::make('code')->title('Account Codes')->addClass('text-nowrap ')->orderable(false),
            Column::make('balance')->title('Balance')->addClass('text-nowrap ')->orderable(false)->searchable(false),
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
