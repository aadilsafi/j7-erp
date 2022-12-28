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
use Route;

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
        $data  = [
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Disapproved',
            3 => 'Cancelled',
        ];
        $columns = array_column($this->getColumns(), 'data');
        return (new EloquentDataTable($query))
            ->editColumn('check', function ($salesPlan) {
                return $salesPlan;
            })
            ->editColumn('user_id', function ($salesPlan) {
                return $salesPlan->user->name;
            })
            ->editColumn('status', function ($salesPlan) {
                if ($salesPlan->status == 0) {
                    return '<span class="badge badge-glow bg-warning">Pending</span>';
                } elseif ($salesPlan->status == 1) {
                    return '<span class="badge badge-glow bg-success">Approved</span>';
                } elseif ($salesPlan->status == 3) {
                    return '<span class="badge badge-glow bg-danger">Cancelled</span>';
                } else {
                    return '<span class="badge badge-glow bg-danger">Disapproved</span>';
                }
            })
            ->editColumn('unit_id', function ($salesPlan) {
                return $salesPlan->unit->floor_unit_number;
            })
            ->editColumn('stakeholder_id', function ($salesPlan) {

                // $staleholder = json_decode($salesPlan->stakeholder_data);
                // return $staleholder->full_name;

                return $salesPlan->stakeholder->full_name;
            })
            ->editColumn('created_at', function ($salesPlan) {
                return editDateColumn($salesPlan->created_date);
            })
            ->editColumn('updated_at', function ($salesPlan) {
                return editDateColumn($salesPlan->updated_at);
            })
            ->editColumn('salesplanstatus', function ($salesPlan) use ($data) {
                return $data[$salesPlan->status];
            })
            ->editColumn('actions', function ($salesPlan) {
                return view('app.sites.floors.units.sales-plan.actions', ['site_id' => $salesPlan->unit->floor->site->id, 'floor_id' => $salesPlan->unit->floor_id, 'unit_id' => $salesPlan->unit_id, 'id' => $salesPlan->id, 'created_date' => $salesPlan->created_date, 'status' => $salesPlan->status, 'unit_status' => $salesPlan->unit->status_id, 'sales_plan_id' => $salesPlan->id]);
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
        if (Route::current()->getName() != 'sites.sales_plan.show') {

            return $model->newQuery()->with('stakeholder', 'unit')->where('unit_id', $this->unit->id)->orderBy('status', 'asc');
        } else {
            if (Auth::user()->hasRole('CRM')) {
                return $model->newQuery()->with('stakeholder', 'unit')->where('is_from_crm', true)->orderBy('status', 'asc');
            } else {
                return $model->newQuery()->with('stakeholder', 'unit')->orderBy('status', 'asc');
            }
        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        if (Route::current()->getName() != 'sites.sales_plan.show') {
            $unitStatus = Unit::find($this->unit->id)->status_id;
        }
        $createPermission =  Auth::user()->hasPermissionTo('sites.floors.units.sales-plans.create');
        // $selectedDeletePermission = Auth::user()->hasPermissionTo('sites.floors.units.sales-plans.destroy-selected');
        $selectedDeletePermission = 0;

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

        if ($createPermission) {
            if (Route::current()->getName() != 'sites.sales_plan.show') {
                if ($unitStatus == 1 || $unitStatus == 6) {
                    $addNewButton = Button::raw('add-new')
                        ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                        ->text('<i class="bi bi-plus"></i> Add New')
                        ->attr([
                            'onclick' => 'addNew()',
                        ]);
                    array_unshift($buttons, $addNewButton);
                }
            } else {
                $addNewButton = Button::raw('add-new')
                    ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                    ->text('<i class="bi bi-plus"></i> Add New')
                    ->attr([
                        'onclick' => 'addNew()',
                    ]);
                array_unshift($buttons, $addNewButton);
            }
        }

        if ($selectedDeletePermission) {
            $buttons[] =  Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
                ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')
                ->attr([
                    'onclick' => 'deleteSelected()',
                ]);
        }

        return $this->builder()
            ->addTableClass(['table-hover'])
            ->setTableId('sales-plan-table')
            ->columns($this->getColumns())
            ->deferRender()
            ->scrollX()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
            ->rowGroupDataSrc('salesplanstatus')
            // ->columnDefs([
            //     [
            //         'targets' => 0,
            //         'className' => 'text-center text-primary',
            //         'width' => '10%',
            //         'orderable' => false,
            //         'searchable' => false,
            //         'responsivePriority' => 0,
            //         'render' => "function (data, type, full, setting) {
            //         var tableRow = JSON.parse(data);
            //         return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" onchange=\"changeTableRowColor(this)\" type=\"checkbox\" value=\"' + tableRow.id + '\" name=\"chkTableRow[]\" id=\"chkTableRow_' + tableRow.id + '\" /><label class=\"form-check-label\" for=\"chkTableRow_' + tableRow.id + '\"></label></div>';
            //         }",
            //         'checkboxes' => [
            //             'selectAllRender' =>  '<div class="form-check"> <input class="form-check-input" onchange="changeAllTableRowColor()" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
            //         ]
            //     ],
            // ])
            ->orders([
                [0, 'desc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        // $destroyPermission = Auth::user()->hasPermissionTo('sites.floors.units.sales-plans.destroy-selected');
        $destroyPermission = 0;

        $columns = [
            Column::make('serial_no')->title('Serial Number')->addClass('text-nowrap'),
            Column::computed('unit_id')->name('unit.floor_unit_number')->title('Unit Number'),
            Column::computed('user_id')->name('user.name')->title('Sales Person'),
            Column::computed('stakeholder_id')->name('stakeholder.full_name')->title('Stakeholder'),
            Column::computed('salesplanstatus')->visible(false),
            Column::make('status')->title('Status')->addClass('text-center'),
            Column::make('created_at')->title('Created At')->addClass('text-nowrap'),
        ];

        if ($destroyPermission) {
            $newCol = Column::computed('check')->exportable(false)->printable(false)->width(60);
            array_unshift($columns, $newCol);
        }

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
