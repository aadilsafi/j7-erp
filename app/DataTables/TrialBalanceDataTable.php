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
use Carbon\Carbon;

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
            ->editColumn('created_at', function ($accountHead) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $accountHead->created_at)
                ->format('m/d/Y');
            })
            ->editColumn('starting_balance', function ($accountHead) {
                if (count($accountHead->accountLedgers) > 0) {
                    if($accountHead->accountLedgers->where('created_at','<',Carbon::today()->subDays()))
                    {
                        $credits = $accountHead->accountLedgers->where('created_at','<',Carbon::today()->subDays())->pluck('credit')->sum();
                        $debits = $accountHead->accountLedgers->where('created_at','<',Carbon::today()->subDays())->pluck('debit')->sum();
                        if((substr($accountHead->account_head_code, 0, 2) == 10) || substr($accountHead->account_head_code, 0, 2) == 12)
                        {
                            return number_format($credits - $debits);
                        }else{
                            return number_format($debits - $credits);
                        }

                    }else{
                        return 0;
                    }
                }
            })
            ->editColumn('debit', function ($accountHead) {
                if (count($accountHead->accountLedgers) > 0) {
                    return number_format($accountHead->accountLedgers->pluck('debit')->sum());
                }
            })
            ->editColumn('credit', function ($accountHead) {
                if (count($accountHead->accountLedgers) > 0) {
                    return number_format($accountHead->accountLedgers->pluck('credit')->sum());
                }
            })
            ->editColumn('ending_balance', function ($accountHead) {
                if (count($accountHead->accountLedgers) > 0) {
                    $credits = $accountHead->accountLedgers->pluck('credit')->sum();
                    $debits = $accountHead->accountLedgers->pluck('debit')->sum();
                    if((substr($accountHead->account_head_code, 0, 2) == 10) || substr($accountHead->account_head_code, 0, 2) == 12)
                    {
                        return number_format($credits - $debits);
                    }else{
                        return number_format($debits - $credits);
                    }
                }
            })
            ->editColumn('fitter_trial_balance', function ($accountHead) {
                if (count($accountHead->accountLedgers) > 0) {
                    return view('app.sites.accounts.trial_balance.action', ['site_id' => ($this->site_id), 'account_head_code' => $accountHead->code]);
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
        return $model->where('level', 5)->whereHas('accountLedgers')->orderBy('code', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('accountHead-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([5000])
            ->scrollX(true)
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
            ->columnDefs([])
            ->orders([
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
            Column::make('code')->title('Account Codes')->addClass('text-nowrap'),
            Column::make('name')->title('Account Name')->addClass('text-nowrap'),
            Column::make('starting_balance')->title('Starting Balance')->addClass('text-nowrap')->searchable(false)->orderable(false),
            Column::make('debit')->title('Debit')->addClass('text-nowrap')->searchable(false)->orderable(false),
            Column::make('credit')->title('Credit')->addClass('text-nowrap')->searchable(false)->orderable(false),
            Column::make('ending_balance')->title('Ending Balance')->addClass('text-nowrap')->searchable(false)->orderable(false),
            Column::make('created_at')->title('Transactions At')->addClass('text-nowrap')->searchable(false)->orderable(false),
            Column::make('fitter_trial_balance')->title('Filter Trial Balance')->addClass('text-nowrap')->searchable(false)->orderable(false),
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
