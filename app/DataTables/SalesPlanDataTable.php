<?php

namespace App\DataTables;

use App\Models\Unit;
use App\Models\SalesPlan;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SalesPlanTemplate;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;


class SalesPlanDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $columns = array_column($this->getColumns(), 'data');
        return (new EloquentDataTable($query))
            ->editColumn('check', function ($salesPlan) {
                return $salesPlan;
            })
            ->editColumn('user_id', function ($salesPlan) {
                return $salesPlan->user->name;
            })
            ->editColumn('status', function ($salesPlan) {
                ($salesPlan->status == 0) ? '<span class="badge badge-glow bg-success">Pending</span>' : "Dont Eat";
                if ($salesPlan->status == 0) {
                    return '<span class="badge badge-glow bg-warning">Pending</span>';
                } elseif ($salesPlan->status == 1) {
                    return '<span class="badge badge-glow bg-success">Approved</span>';
                } else {
                    return '<span class="badge badge-glow bg-danger">Disapproved</span>';
                }
                // return $salesPlan->status == 1 ? '<span class="badge badge-glow bg-success">Approved</span>' : '<span class="badge badge-glow bg-warning">Not Approved</span>';
            })
            ->editColumn('stakeholder_id', function ($salesPlan) {
                return $salesPlan->stakeholder->full_name;
            })
            ->editColumn('created_at', function ($salesPlan) {
                return editDateColumn($salesPlan->created_at);
            })
            ->editColumn('updated_at', function ($salesPlan) {
                return editDateColumn($salesPlan->updated_at);
            })
            ->editColumn('salesplanstatus', function ($salesPlan) {

                switch ($salesPlan->status) {
                    case 0:
                        return 'Pending';
                        break;

                    case 1:
                        return 'Approved';
                        break;

                    case 2:
                        return 'Disapproved';
                        break;

                    default:
                        # code...
                        break;
                }
            })
            ->editColumn('actions', function ($salesPlan) {
                return view('app.sites.floors.units.sales-plan.actions', ['site_id' => $salesPlan->unit->floor->site->id, 'floor_id' => $salesPlan->unit->floor_id, 'unit_id' => $salesPlan->unit_id, 'id' => $salesPlan->id, 'status' => $salesPlan->status]);
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SalesPlanDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SalesPlan $model): QueryBuilder
    {
        return $model->newQuery()->with('stakeholder')->where('unit_id', $this->unit->id)->orderBy('status', 'asc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        $unitStatus = Unit::find($this->unit->id)->status_id;
        $createPermission =  Auth::user()->hasPermissionTo('sites.floors.units.sales-plans.create');
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.floors.units.sales-plans.destroy-selected');
        return $this->builder()
            ->addTableClass(['table-hover'])
            ->setTableId('sales-plan-table')
            ->columns($this->getColumns())
            ->deferRender()
            ->scrollX()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons(
                (
                    ($createPermission && $unitStatus == 1) ?
                    Button::raw('add-new')
                    ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                    ->text('<i class="bi bi-plus"></i> Add New')
                    ->attr([
                        'onclick' => 'addNew()',
                    ])
                    :
                    Button::raw('add-new')
                    ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light hidden')
                    ->text('<i class="bi bi-plus"></i> Add New')
                    ->attr([
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
                ($selectedDeletePermission ?
                    Button::raw('delete-selected')
                    ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
                    ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')
                    ->attr([
                        'onclick' => 'deleteSelected()',
                    ])
                    :
                    Button::raw('delete-selected')
                    ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light hidden')
                    ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')
                    ->attr([
                        'onclick' => 'deleteSelected()',
                    ])
                ),

            )
            ->rowGroupDataSrc('salesplanstatus')
            ->columnDefs([
                [
                    'targets' => 0,
                    'className' => 'text-center text-primary',
                    'width' => '10%',
                    'orderable' => false,
                    'searchable' => false,
                    'responsivePriority' => 0,
                    'render' => "function (data, type, full, setting) {
                    var tableRow = JSON.parse(data);
                    return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" onchange=\"changeTableRowColor(this)\" type=\"checkbox\" value=\"' + tableRow.id + '\" name=\"chkTableRow[]\" id=\"chkTableRow_' + tableRow.id + '\" /><label class=\"form-check-label\" for=\"chkTableRow_' + tableRow.id + '\"></label></div>';
                }",
                    'checkboxes' => [
                        'selectAllRender' =>  '<div class="form-check"> <input class="form-check-input" onchange="changeAllTableRowColor()" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
                    ]
                ],
            ])
            ->orders([
                [3, 'desc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $destroyPermission =  Auth::user()->hasPermissionTo('sites.floors.units.sales-plans.destroy-selected');
        $printPermission =  Auth::user()->hasPermissionTo('sites.floors.units.sales-plans.templates.print');
        return [
            (
                ($destroyPermission) ?
                Column::computed('check')->exportable(false)->printable(false)->width(60)
                :
                Column::computed('check')->exportable(false)->printable(false)->width(60)->addClass('hidden')
            ),

            Column::make('user_id')->title('Sales Person'),
            Column::make('stakeholder_id')->name('stakeholder.full_name')->title('Stakeholder'),
            Column::computed('salesplanstatus')->visible(false),
            Column::make('status')->title('Status')->addClass('text-center'),
            Column::make('created_at')->title('Created At')->addClass('text-nowrap'),
            (
                ($printPermission) ?
                Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')
                :
                Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('hidden')
            ),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'SalesPlan_' . date('YmdHis');
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
