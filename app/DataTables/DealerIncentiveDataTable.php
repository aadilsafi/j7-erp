<?php

namespace App\DataTables;

use App\Models\DealerIncentiveModel;
use App\Services\dealerIncentive\dealerIncentiveInterface;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Services\DealerIncentive\DealerInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class DealerIncentiveDataTable extends DataTable
{

    private $dealerIncentive;

    public function __construct(DealerInterface $dealerIncentive)
    {
        $this->dealerIncentive = $dealerIncentive;
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
            ->editColumn('dealer_id', function ($dealerIncentive) {
                return $dealerIncentive->dealer->full_name;
            })
            ->editColumn('total_dealer_incentive', function ($dealerIncentive) {
                return number_format($dealerIncentive->total_dealer_incentive);
            })
            ->editColumn('created_at', function ($dealerIncentive) {
                return editDateColumn($dealerIncentive->created_at);
            })
            ->editColumn('updated_at', function ($dealerIncentive) {
                return editDateColumn($dealerIncentive->updated_at);
            })
            ->editColumn('status', function ($dealerIncentive) {
                return $dealerIncentive->status == 1 ? '<span class="badge badge-glow bg-success">Active</span>' : '<span class="badge badge-glow bg-warning">InActive</span><br>
                <a onClick="ApproveModal()" id="approveID" dealer_id="' . encryptParams($dealerIncentive->id) . '" class="btn btn-relief-outline-primary waves-effect waves-float waves-light text-center" style="margin: 5px"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Approve request" href="#">
                    Approve Request
                </a>';
            })
            // ->editColumn('actions', function ($dealerIncentive) {
            //     return view('app.sites.file-managements.files.rebate-incentive.actions', ['site_id' => $this->site_id, 'id' => $dealerIncentive->id]);
            // })
            ->editColumn('check', function ($dealerIncentive) {
                return $dealerIncentive;
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DealerIncentiveModel $model): QueryBuilder
    {
        return $model->newQuery()->where('site_id', $this->site_id);
    }

    public function html(): HtmlBuilder
    {
        $createPermission =  Auth::user()->hasPermissionTo('sites.file-managements.rebate-incentive.create');
        // $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.receipts.destroy-selected');
        // $selectedActivePermission =  Auth::user()->hasPermissionTo('sites.receipts.make-active-selected');

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
            ->buttons(
                ($createPermission  ?
                    Button::raw('delete-selected')
                    ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                    ->text('<i class="bi bi-plus"></i> Add New')->attr([
                        'onclick' => 'addNew()',
                    ])
                    :
                    Button::raw('delete-selected')
                    ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light hidden')
                    ->text('<i class="bi bi-plus"></i> Add New')->attr([
                        'onclick' => 'addNew()',
                    ])

                ),

                Button::make('export')->addClass('btn btn-relief-outline-secondary waves-effect waves-float waves-light dropdown-toggle')->buttons([
                    Button::make('print')->addClass('dropdown-item'),
                    Button::make('copy')->addClass('dropdown-item'),
                    Button::make('csv')->addClass('dropdown-item'),
                    Button::make('excel')->addClass('dropdown-item'),
                    Button::make('pdf')->addClass('dropdown-item'),
                ]),
                Button::make('reset')->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light'),
                Button::make('reload')->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light'),

                // Button::raw('delete-selected')
                //     ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
                //     ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')->attr([
                //         'onclick' => 'deleteSelected()',
                //     ])
            )
            ->rowGroupDataSrc('dealer_id')
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
                        return '';
                    }",
                    'checkboxes' => [
                        'selectAllRender' =>  '',
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
        // $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.receipts.destroy-selected');
        // $editPermission =  Auth::user()->hasPermissionTo('sites.receipts.show');
        return [

            Column::computed('check')->exportable(false)->printable(false)->width(60),
            Column::make('dealer_id')->title('Dealer')->addClass('text-nowrap text-center'),
            Column::make('dealer_incentive')->title('Dealer Incentive')->addClass('text-nowrap text-center'),
            Column::make('total_unit_area')->title('Total Unit Area')->addClass('text-nowrap text-center'),
            Column::make('total_dealer_incentive')->title('Total Incentive')->addClass('text-nowrap '),
            Column::make('status')->title('Status')->addClass('text-nowrap text-center'),
            Column::computed('created_at')->title('Created At')->addClass('text-nowrap text-center'),
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
        return 'Rebate_Incentive' . date('YmdHis');
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
