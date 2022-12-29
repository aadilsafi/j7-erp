<?php

namespace App\DataTables;

use App\Models\RebateIncentiveModel;
use App\Services\RebateIncentive\RebateIncentiveInterface;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Services\Stakeholder\Interface\rebateIncentive;
use Barryvdh\DomPDF\Facade\Pdf;

class RebateIncentiveDataTable extends DataTable
{

    private $rebateIncentive;

    public function __construct(RebateIncentiveInterface $rebateIncentive)
    {
        $this->rebateIncentive = $rebateIncentive;
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
            ->editColumn('unit_id', function ($rebateIncentive) {
                return $rebateIncentive->unit->floor_unit_number;
            })
            ->editColumn('unit_name', function ($rebateIncentive) {
                return $rebateIncentive->unit->name;
            })
            ->editColumn('unit_area', function ($rebateIncentive) {
                return $rebateIncentive->unit->gross_area;
            })
            ->editColumn('stakeholder_id', function ($rebateIncentive) {
                return $rebateIncentive->stakeholder->full_name;
            })
            ->editColumn('stakeholder_cnic', function ($rebateIncentive) {
                return $rebateIncentive->stakeholder->cnic;
            })
            ->editColumn('stakeholder_contact', function ($rebateIncentive) {
                return $rebateIncentive->stakeholder->contact;
            })
            ->editColumn('commision_percentage', function ($rebateIncentive) {
                return $rebateIncentive->commision_percentage . '%';
            })
            ->editColumn('dealer_id', function ($rebateIncentive) {
                return $rebateIncentive->dealer->full_name;
            })
            ->editColumn('commision_total', function ($rebateIncentive) {
                return number_format($rebateIncentive->commision_total);
            })
            ->editColumn('created_at', function ($rebateIncentive) {
                return editDateColumn($rebateIncentive->created_at);
            })
            ->editColumn('updated_at', function ($rebateIncentive) {
                return editDateColumn($rebateIncentive->updated_at);
            })
            ->editColumn('status', function ($rebateIncentive) {
                $approvePermission =  Auth::user()->hasPermissionTo('sites.file-managements.rebate-incentive.approve');
                $status = $rebateIncentive->status == 1 ? '<span class="badge badge-glow bg-success">Active</span>' : '<span class="badge badge-glow bg-warning">InActive</span>';
                if ($approvePermission && $rebateIncentive->status == 0) {
                    $status .= '  <a onClick="ApproveModal()" id="approveID" rebate_id="' . encryptParams($rebateIncentive->id) . '" class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Approve"
                    href="#" >
                    <i class="bi bi-check" style="font-size: 1.1rem" class="m-10"></i>
                </a>';
                }
                return $status;
            })
            // ->editColumn('actions', function ($rebateIncentive) {
            //     return view('app.sites.file-managements.files.rebate-incentive.actions', ['site_id' => $this->site_id, 'id' => $rebateIncentive->id]);
            // })
            // ->editColumn('check', function ($rebateIncentive) {
            //     return $rebateIncentive;
            // })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(RebateIncentiveModel $model): QueryBuilder
    {
        return $model->newQuery()->where('site_id', $this->site_id)->orderBy('serial_no', 'desc');
    }

    public function html(): HtmlBuilder
    {
        $createPermission =  Auth::user()->hasPermissionTo('sites.file-managements.rebate-incentive.create');
        $selectedDeletePermission =  Auth::user()->can('sites.file-managements.rebate-incentive.destroy-selected');
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
                    // 'targets' => 0,
                    // // 'className' => 'text-center text-primary',
                    // 'width' => '10%',
                    // 'orderable' => false,
                    // 'searchable' => false,
                    // 'responsivePriority' => 3,
                    // 'render' => "function (data, type, full, setting) {
                    //     var role = JSON.parse(data);
                    //     return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" onchange=\"changeTableRowColor(this)\" type=\"checkbox\" value=\"' + role.id + '\" name=\"chkRole[]\" id=\"chkRole_' + role.id + '\" /><label class=\"form-check-label\" for=\"chkRole_' + role.id + '\"></label></div>';
                    // }"
                    // 'checkboxes' => [
                    //     'selectAllRender' =>  '<div class="form-check"> <input class="form-check-input" onchange="changeAllTableRowColor()" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
                    // ]
                ],
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

            // Column::computed('check')->exportable(false)->printable(false)->width(60),
            Column::make('serial_no')->title('Serial Number')->addClass('text-nowrap'),
            Column::computed('stakeholder_id')->title('Stakeholder Name')->addClass('text-nowrap text-center'),
            Column::make('dealer_id')->title('Dealer Name')->addClass('text-nowrap text-center'),// Column::computed('stakeholder_cnic')->title('Cnic / Reg No')->addClass('text-nowrap '),
            // Column::computed('stakeholder_contact')->title('Contact')->addClass('text-nowrap text-center'),
            Column::computed('unit_id')->title('Unit NO#')->addClass('text-nowrap text-center'),
            // Column::computed('unit_name')->title('Unit Name')->addClass('text-nowrap text-center'),
            Column::computed('unit_area')->title('Unit Area')->addClass('text-nowrap text-center'),
            Column::make('deal_type')->title('Deal Type')->addClass('text-nowrap text-center'),
            Column::computed('commision_percentage')->title('Commision %')->addClass('text-nowrap text-center'),
            Column::computed('commision_total')->title('Commision Total')->addClass('text-nowrap text-center'),
            Column::computed('status')->title('Status')->addClass('text-nowrap text-center'),
            Column::make('created_at')->title('Created At')->addClass('text-nowrap text-center'),
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
