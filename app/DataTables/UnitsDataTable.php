<?php

namespace App\DataTables;

use App\Models\Unit;
use App\Models\Floor;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class UnitsDataTable extends DataTable
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
            ->editColumn('check', function ($unit) {
                return $unit;
            })
            ->editColumn('status_id', function ($unit) {
                return editBadgeColumn($unit->status->name);
            })
            ->editColumn('type_id', function ($unit) {
                return $unit->type->name;
            })
            ->editColumn('created_at', function ($unit) {
                return editDateColumn($unit->created_at);
            })
            ->editColumn('updated_at', function ($unit) {
                return editDateColumn($unit->updated_at);
            })
            ->editColumn('actions', function ($unit) {
                return view('app.sites.floors.units.actions', ['site_id' => $unit->floor->site->id, 'floor_id' => $unit->floor_id, 'id' => $unit->id, 'status' => $unit->status->id]);
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Unit $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Unit $model): QueryBuilder
    {
        return $model->newQuery()->select('units.*')->with(['type', 'status'])->whereFloorId($this->floor->id)->whereActive(true);
    }

    public function html(): HtmlBuilder
    {
        $createPermission =  Auth::user()->hasPermissionTo('sites.floors.units.create');
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.floors.units.destroy-selected');
        return $this->builder()
            ->addTableClass(['table-striped', 'table-hover'])
            ->setTableId('floors-units-table')
            ->columns($this->getColumns())
            ->deferRender()
            ->scrollX()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons(
                ($createPermission ?
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
            ->rowGroupDataSrc('type_id')
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
                [5, 'desc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $destroyPermission = Auth::user()->hasPermissionTo('sites.floors.units.destroy-selected');
        return [
            (
                ($destroyPermission) ?
                Column::computed('check')->exportable(false)->printable(false)->width(60)
                :
                Column::computed('check')->exportable(false)->printable(false)->width(60)->addClass('hidden')
            ),
            Column::make('floor_unit_number')->title('Unit Number'),
            Column::make('name')->title('Units'),
            Column::make('type_id')->name('type.name')->title('Type'),
            Column::make('status_id')->name('status.name')->title('Status')->addClass('text-center'),
            Column::make('created_at')->addClass('text-nowrap'),
            Column::make('updated_at')->addClass('text-nowrap'),
            Column::computed('actions')->exportable(false)->printable(false)->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Units_' . date('YmdHis');
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
