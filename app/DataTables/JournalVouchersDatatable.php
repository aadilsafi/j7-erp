<?php

namespace App\DataTables;

use App\Models\JournalVoucher;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Barryvdh\DomPDF\Facade\Pdf;
use Str;

class JournalVouchersDatatable extends DataTable
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
            ->editColumn('check', function ($JournalVoucher) {
                return $JournalVoucher;
            })
            ->editColumn('voucher_date', function ($JournalVoucher) {
                return editDateColumn($JournalVoucher->voucher_Date);
            })
            ->editColumn('updated_at', function ($JournalVoucher) {
                return editDateColumn($JournalVoucher->updated_at);
            })
            ->editColumn('user_id', function ($JournalVoucher) {
                return $JournalVoucher->user->name;
            })
            ->editColumn('checked_by', function ($JournalVoucher) {
                if (isset($JournalVoucher->checkedBy)) {
                    return $JournalVoucher->checkedBy->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('approved_by', function ($JournalVoucher) {
                if (isset($JournalVoucher->postedBy)) {
                    return $JournalVoucher->postedBy->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('total_debit', function ($JournalVoucher) {
                return number_format($JournalVoucher->total_debit);
            })
            ->editColumn('total_credit', function ($JournalVoucher) {
                return number_format($JournalVoucher->total_credit);
            })
            ->editColumn('status', function ($JournalVoucher) {

                if ($JournalVoucher->status == 'pending') {
                    return '<span class="badge badge-glow bg-warning">Pending</span>';
                } elseif ($JournalVoucher->status == 'checked') {
                    return '<span class="badge badge-glow bg-secondary">Checked</span>';
                } elseif ($JournalVoucher->status == 'posted') {
                    return '<span class="badge badge-glow bg-success">Posted</span>';
                } elseif( $JournalVoucher->status == 'reverted') {
                    return '<span class="badge badge-glow bg-danger">Reverted</span>';
                }
                else{
                    return '<span class="badge badge-glow bg-danger">Dis-Approved</span>';
                }
            })
            ->editColumn('actions', function ($JournalVoucher) {
                return view('app.sites.journal-vouchers.actions', ['site_id' => $this->site_id, 'id' => $JournalVoucher->id, 'status' => $JournalVoucher->status]);
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['actions', 'check']));
        return $editColumns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\JournalVoucher $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(JournalVoucher $model): QueryBuilder
    {
        return $model->newQuery()->whereSiteId($this->site_id)->orderBy('id', 'desc')->with('user', 'checkedBy', 'postedBy');
    }

    public function html(): HtmlBuilder
    {
        $createPermission = auth()->user()->can('sites.settings.journal-vouchers.create');
        $selectedDeletePermission = auth()->user()->can('sites.settings.journal-vouchers.destroy-selected');

        $buttons = [];

        if ($createPermission) {
            $buttons[] = Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i class="bi bi-plus"></i> Add New')
                ->attr([
                    'onclick' => 'addNew()',
                ]);
        }

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

        // if ($selectedDeletePermission) {
        //     $buttons[] = Button::raw('delete-selected')
        //         ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
        //         ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')
        //         ->attr([
        //             'onclick' => 'deleteSelected()',
        //         ]);
        // }

        return $this->builder()
            ->setTableId('journal-vouchers-table')
            ->addTableClass(['table-hover'])
            ->scrollX()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
            ->rowGroupDataSrc('status')
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
                [2, 'desc'],
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
            Column::make('serial_number')->title('Voucher Number')->addClass('text-nowrap'),
            // Column::make('name')->title('Voucher Name')->addClass('text-nowrap'),
            Column::computed('user_id')->name('user.name')->title('Created By')->addClass('text-nowrap')->orderable(false)->searchable(true),
            Column::make('total_debit')->title('Debit Amount')->addClass('text-nowrap'),
            Column::make('total_credit')->title('Credit Amount')->addClass('text-nowrap'),
            Column::make('status')->title('Status')->addClass('text-nowrap'),
            Column::computed('checked_by')->name('checkedBy.name')->title('Checked By')->addClass('text-nowrap')->orderable(false)->searchable(true),
            Column::computed('approved_by')->name('postedBy.name')->title('Posted By')->addClass('text-nowrap')->orderable(false)->searchable(true),
            Column::make('voucher_date')->title('Created At')->addClass('text-nowrap'),
        ];
        // $columns[] = Column::make('created_at')->addClass('text-nowrap');
        // $columns[] = Column::make('updated_at')->addClass('text-nowrap');
        $columns[] = Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center');

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Journal_Vouchers' . date('YmdHis');
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
