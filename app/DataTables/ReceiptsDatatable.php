<?php

namespace App\DataTables;

use App\Models\Receipt;
use App\Models\Stakeholder;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptsDatatable extends DataTable
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
            ->editColumn('floor_id', function ($receipt) {
                return  $receipt->unit->floor->name;
            })
            ->editColumn('unit_id', function ($receipt) {
                return  $receipt->unit->name;
            })
            ->editColumn('cnic', function ($receipt) {
                return  cnicFormat($receipt->cnic);
            })
            ->editColumn('installment_number', function ($receipt) {
                return  str_replace(str_split('[]"'), '', $receipt->installment_number);
            })
            ->editColumn('amount_in_numbers', function ($receipt) {
                return  number_format($receipt->amount_in_numbers);
            })
            ->editColumn('status', function ($receipt) {
                return $receipt->status == 1 ? '<span class="badge badge-glow bg-success">Active</span>' : '<span class="badge badge-glow bg-warning">InActive</span>';
            })
            ->editColumn('created_date', function ($receipt) {
                return editDateColumn($receipt->created_date);
            })
            ->editColumn('updated_at', function ($receipt) {
                return editDateColumn($receipt->updated_at);
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
    public function query(Receipt $model): QueryBuilder
    {
        return $model->newQuery()->where('site_id', decryptParams($this->site_id))->where('status', '!=', 2);
    }

    public function html(): HtmlBuilder
    {
        $createPermission =  Auth::user()->hasPermissionTo('sites.receipts.create');
        $revertPermission =  Auth::user()->hasPermissionTo('sites.receipts.revert-payment');
        $selectedActivePermission =  Auth::user()->hasPermissionTo('sites.receipts.make-active-selected');
        $importPermission =  Auth::user()->hasPermissionTo('sites.receipts.importReceipts');

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

        if ($importPermission) {
            // $addButton =  Button::raw('import')
            //     ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
            //     ->text('<i data-feather="upload"></i> Import Receipts')
            //     ->attr([
            //         'onclick' => 'Import()',
            //     ]);

            // array_unshift($buttons, $addButton);

            $addButton =  Button::raw('import')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i data-feather="upload"></i> Import Banks')
                ->attr([
                    'onclick' => 'ImportBanks()',
                ]);

            array_unshift($buttons, $addButton);
        }
        if ($revertPermission) {
            $revertButton =  Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
                ->text('<i class="bi bi-trash3-fill"></i> Revert Receipt')->attr([
                    'onclick' => 'revertPayment()',
                ]);
            array_unshift($buttons, $revertButton);
        }

        if ($createPermission) {
            $addButton = Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i class="bi bi-plus"></i> Add New')->attr([
                    'onclick' => 'addNew()',
                ]);

            array_unshift($buttons, $addButton);
        }



        if ($selectedActivePermission) {
            $buttons[] = Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-secondary waves-effect waves-float waves-light')
                ->text('<i class="bi bi-pencil"></i> Make Active')->attr([
                    'onclick' => 'changeStatusSelected()',
                ]);
        }

        return $this->builder()
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
            ->columnDefs([
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
            ])
            ->orders([
                [2, 'asc'],
                [4, 'desc'],
            ]);
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
            Column::make('name')->title('Name')->addClass('text-nowrap'),
            Column::make('cnic')->title('CNIC'),
            Column::make('amount_in_numbers')->title('Paid Amount'),
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
