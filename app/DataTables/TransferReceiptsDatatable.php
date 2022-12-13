<?php

namespace App\DataTables;

use App\Models\Receipt;
use App\Models\TransferReceipt;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class TransferReceiptsDatatable extends DataTable
{

    private $stakeholderInterface;

    public function __construct(StakeholderInterface $stakeholderInterface)
    {
        $this->stakeholderInterface = $stakeholderInterface;
    }

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

            ->editColumn('serial_no', function ($receipt) {
                return $receipt->serial_no != null ? $receipt->serial_no : 'REC-';
            })
            ->editColumn('cnic', function ($receipt) {
                return  cnicFormat($receipt->stakeholder->cnic);
            })
            ->editColumn('name', function ($receipt) {
                return $receipt->stakeholder->full_name;
            })
            ->editColumn('amount', function ($receipt) {
                return  number_format($receipt->amount);
            })
            ->editColumn('status', function ($receipt) {
                if ($receipt->status == 1) {
                    return '<span class="badge badge-glow bg-success">Active</span>';
                } elseif ($receipt->status == 2) {
                    return '<span class="badge badge-glow bg-danger">Cancel</span>';
                } elseif ($receipt->status == 3) {
                    return '<span class="badge badge-glow bg-danger">Reverted</span>';
                } else {
                    return '<span class="badge badge-glow bg-warning">InActive</span>';
                }
                // return $receipt->status == 1 ? '<span class="badge badge-glow bg-success">Active</span>' : '<span class="badge badge-glow bg-warning">InActive</span>';
            })
            ->editColumn('created_date', function ($receipt) {
                return editDateColumn($receipt->created_date);
            })
         
            ->editColumn('actions', function ($receipt) {
                return view('app.sites.receipts.actions', ['site_id' => decryptParams($this->site_id), 'id' => $receipt->id]);
            })
            ->editColumn('check', function ($receipt) {
                return $receipt;
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TransferReceipt $model): QueryBuilder
    {
        return $model->newQuery()->where('site_id', decryptParams($this->site_id));
    }

    public function html(): HtmlBuilder
    {
        $createPermission =  Auth::user()->hasPermissionTo('sites.receipts.create');
        $revertPermission =  Auth::user()->hasPermissionTo('sites.receipts.revert-payment');
        $selectedActivePermission =  Auth::user()->hasPermissionTo('sites.receipts.make-active-selected');

        $buttons = [
            Button::make('export')->addClass('btn btn-relief-outline-secondary waves-effect waves-float waves-light dropdown-toggle')->buttons([
                Button::make('print')->addClass('dropdown-item'),
                Button::make('copy')->addClass('dropdown-item'),
                Button::make('csv')->addClass('dropdown-item'),
                Button::make('excel')->addClass('dropdown-item'),
                Button::make('pdf')->addClass('dropdown-item'),
            ]),
            Button::make('reset')->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light'),
            Button::make('reload')->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light'),

        ];


        // if ($revertPermission) {
        //     $revertButton =  Button::raw('delete-selected')
        //         ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
        //         ->text('<i class="bi bi-trash3-fill"></i> Revert Receipt')->attr([
        //             'onclick' => 'revertPayment()',
        //         ]);
        //     array_unshift($buttons, $revertButton);
        // }

        if ($createPermission) {
            $addButton = Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i class="bi bi-plus"></i> Add New')->attr([
                    'onclick' => 'addNew()',
                ]);

            array_unshift($buttons, $addButton);
        }


        // if ($selectedActivePermission) {
        //     $buttons[] = Button::raw('delete-selected')
        //         ->addClass('btn btn-relief-outline-secondary waves-effect waves-float waves-light')
        //         ->text('<i class="bi bi-pencil"></i> Make Active')->attr([
        //             'onclick' => 'changeStatusSelected()',
        //         ]);
        // }

        $dataTableBuilder = $this->builder()
            ->setTableId('stakeholder-table')
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
            ->rowGroupDataSrc('unit_id')

            ->orders([
                [2, 'asc'],
                [4, 'desc'],
            ]);

        if ($selectedActivePermission) {
            $dataTableBuilder->columnDefs([
                [
                    'targets' => 0,
                    'className' => 'text-center text-primary',
                    'width' => '10%',
                    'orderable' => false,
                    'searchable' => false,
                    'responsivePriority' => 3,
                    'render' => "function (data, type, full, setting) {
                            var role = JSON.parse(data);
                            return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" onchange=\"changeTableRowColor(this)\" type=\"checkbox\" value=\"' + role.id + '\" name=\"chkRole[]\" id=\"chkRole_' + role.id + '\" /><label class=\"form-check-label\" for=\"chkRole_' + role.id + '\"></label></div>';
                        }",
                    'checkboxes' => [
                        'selectAllRender' =>  '<div class="form-check"> <input class="form-check-input" onchange="changeAllTableRowColor()" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
                    ]
                ],
            ]);
        }

        return $dataTableBuilder;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $selectedActivePermission =  Auth::user()->hasPermissionTo('sites.receipts.make-active-selected');

        $columns = [
            Column::make('serial_no')->title('Serial Number')->addClass('text-nowrap')->orderable(false)->searchable(true),
            Column::computed('name')->title('Name')->addClass('text-nowrap'),
            Column::computed('cnic')->title('CNIC')->addClass('text-nowrap'),
            Column::make('amount')->title('Amount Received')->addClass('text-nowrap'),
            Column::computed('status')->title('Status'),
            Column::make('created_date')->title('Created At')->addClass('text-nowrap'),
            // Column::make('updated_at')->addClass('text-nowrap'),
            Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')
        ];

        if ($selectedActivePermission) {
            $activeColumn = Column::computed('check')->exportable(false)->printable(false)->width(60);
            array_unshift($columns, $activeColumn);
        }

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Receipts' . date('YmdHis');
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
