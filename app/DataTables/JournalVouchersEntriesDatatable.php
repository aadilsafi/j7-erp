<?php

namespace App\DataTables;

use App\Models\JournalVoucherEntry;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Barryvdh\DomPDF\Facade\Pdf;
use Str;

class JournalVouchersEntriesDatatable extends DataTable
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
        $editColumns = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('check', function ($JournalVoucherEntry) {
                return $JournalVoucherEntry;
            })
            ->editColumn('created_at', function ($JournalVoucherEntry) {
                return editDateColumn($JournalVoucherEntry->created_at);
            })
            ->editColumn('updated_at', function ($JournalVoucherEntry) {
                return editDateColumn($JournalVoucherEntry->updated_at);
            })
            ->editColumn('account_number', function ($JournalVoucherEntry) {
                return account_number_format($JournalVoucherEntry->account_number);
            })
            ->editColumn('account_head_id', function ($JournalVoucherEntry) {
                return $JournalVoucherEntry->accountHead->name;
            })
            ->editColumn('debit', function ($JournalVoucherEntry) {
                return number_format($JournalVoucherEntry->debit);
            })
            ->editColumn('credit', function ($JournalVoucherEntry) {
                return number_format($JournalVoucherEntry->credit);
            })
            ->editColumn('status', function ($JournalVoucherEntry) {

                if ($JournalVoucherEntry->status == 'pending') {
                    return '<span class="badge badge-glow bg-warning">Pending</span>';
                } elseif ($JournalVoucherEntry->status == 'checked') {
                    return '<span class="badge badge-glow bg-success">Checked</span>';
                } elseif ($JournalVoucherEntry->status == 'post') {
                    return '<span class="badge badge-glow bg-danger">Posted</span>';
                } else {
                    return '<span class="badge badge-glow bg-danger">Disapproved</span>';
                }
            })

            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['actions', 'check']));
        return $editColumns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\JournalVoucherEntryEntry $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(JournalVoucherEntry $model): QueryBuilder
    {
        return $model->newQuery()->whereSiteId($this->site_id)->where('journal_voucher_id',$this->id)->orderBy('id', 'desc');
    }

    public function html(): HtmlBuilder
    {

        $buttons = [];

        $buttons = array_merge($buttons, [
            Button::make('export')->addClass('btn btn-relief-outline-secondary waves-effect waves-float waves-light dropdown-toggle')->buttons([
                Button::make('print')->addClass('dropdown-item'),
                Button::make('copy')->addClass('dropdown-item'),
                Button::make('csv')->addClass('dropdown-item'),
                Button::make('excel')->addClass('dropdown-item'),
                Button::make('pdf')->addClass('dropdown-item'),
            ]),
            Button::make('reset')->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light'),
            Button::make('reload')->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light'),
        ]);

        return $this->builder()
            ->setTableId('journal-vouchers-entries-table')
            ->addTableClass(['table-hover'])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
            // ->rowGroupDataSrc('user_id')
            ->columnDefs([
                // [
                //     'targets' => 0,
                //     'className' => 'text-center text-primary',
                //     'width' => '10%',
                //     'orderable' => false,
                //     'searchable' => false,
                //     'responsivePriority' => 3,
                //     'render' => "function (data, type, full, setting) {
                //         var role = JSON.parse(data);
                //         return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" onchange=\"changeTableRowColor(this)\" type=\"checkbox\" value=\"' + role.id + '\" name=\"chkRole[]\" id=\"chkRole_' + role.id + '\" /><label class=\"form-check-label\" for=\"chkRole_' + role.id + '\"></label></div>';
                //     }",
                //     'checkboxes' => [
                //         'selectAllRender' =>  '<div class="form-check"> <input class="form-check-input" onchange="changeAllTableRowColor()" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
                //     ]
                // ],
            ])
            ->orders([
                [1, 'desc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $columns = [
            // Column::computed('check')->exportable(false)->printable(false)->width(60),
            // Column::computed('DT_RowIndex')->title('#'),
            Column::make('account_number')->title('Account Number')->addClass('text-nowrap'),
            Column::make('account_head_id')->name('accountHead.name')->title('Account Name')->addClass('text-nowrap')->orderable(false)->searchable(false),
            Column::make('debit')->title('Debit')->addClass('text-nowrap'),
            Column::make('credit')->title('Credit')->addClass('text-nowrap'),
            Column::make('remarks')->title('Remarks'),
        ];
        $columns[] = Column::make('created_at')->addClass('text-nowrap');
        // $columns[] = Column::make('updated_at')->addClass('text-nowrap');
        // $columns[] = Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center');
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Journal_Vouchers_Entires' . date('YmdHis');
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
