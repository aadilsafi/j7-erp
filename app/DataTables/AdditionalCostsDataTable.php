<?php

namespace App\DataTables;

use Illuminate\Support\Str;
use App\Models\AdditionalCost;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Barryvdh\DomPDF\Facade\Pdf;

class AdditionalCostsDataTable extends DataTable
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
            ->editColumn('applicable_on_site', function ($additionalCost) {
                return editBooleanColumn($additionalCost->applicable_on_site);
            })
            ->editColumn('applicable_on_floor', function ($additionalCost) {
                return editBooleanColumn($additionalCost->applicable_on_floor);
            })
            ->editColumn('applicable_on_unit', function ($additionalCost) {
                return editBooleanColumn($additionalCost->applicable_on_unit);
            })
            ->editColumn('site_percentage', function ($additionalCost) {
                return $additionalCost->site_percentage > 0 ? $additionalCost->site_percentage . '%' : '-';
            })
            ->editColumn('floor_percentage', function ($additionalCost) {
                return $additionalCost->floor_percentage > 0 ? $additionalCost->floor_percentage . '%' : '-';
            })
            ->editColumn('unit_percentage', function ($additionalCost) {
                return $additionalCost->unit_percentage > 0 ? $additionalCost->unit_percentage . '%' : '-';
            })
            ->editColumn('parent_id', function ($additionalCost) {
                return Str::of(getAdditionalCostByParentId($additionalCost->parent_id))->ucfirst();
            })
            ->editColumn('created_at', function ($additionalCost) {
                return editDateColumn($additionalCost->created_at);
            })
            ->editColumn('has_child', function ($additionalCost) {
                return editBooleanColumn($additionalCost->has_child);
            })
            ->editColumn('actions', function ($additionalCost) {
                return view('app.additional-costs.actions', ['site_id' => $additionalCost->site_id, 'id' => $additionalCost->id]);
            })
            ->editColumn('check', function ($additionalCost) {
                return $additionalCost;
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AdditionalCost $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AdditionalCost $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        $createPermission =  Auth::user()->hasPermissionTo('sites.additional-costs.create');
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.additional-costs.destroy-selected');
        return $this->builder()
            ->setTableId('additional-costs-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide()
            ->processing()
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
                Button::raw('import')
                    ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                    ->text('<i data-feather="upload"></i> Import Additional Costs')
                    ->attr([
                        'onclick' => 'Import()',
                    ]),

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
            ->rowGroupDataSrc('parent_id')
            ->columnDefs([
                [
                    'targets' => 0,
                    'className' => 'text-center text-primary',
                    'width' => '10%',
                    'orderable' => false,
                    'searchable' => false,
                    'responsivePriority' => 3,
                    'render' => "function (data, type, full, setting) {
                        var additionalCost = JSON.parse(data);
                        return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" onchange=\"changeTableRowColor(this)\" type=\"checkbox\" value=\"' + additionalCost.id + '\" name=\"chkAdditionalCost[]\" id=\"chkAdditionalCost_' + additionalCost.id + '\" /><label class=\"form-check-label\" for=\"chkAdditionalCost_' + additionalCost.id + '\"></label></div>';
                    }",
                    'checkboxes' => [
                        'selectAllRender' =>  '<div class="form-check"> <input class="form-check-input" onchange="changeAllTableRowColor()" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
                    ]
                ],
            ])
            ->orders([
                [2, 'asc'],
                [8, 'desc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.additional-costs.destroy-selected');
        return [
            ($selectedDeletePermission ?
                Column::computed('check')->exportable(false)->printable(false)->width(60)
                :
                Column::computed('check')->exportable(false)->printable(false)->width(60)->addClass('hidden')
            ),
            Column::make('name')->title('Additional Cost')->addClass('text-nowrap'),
            Column::make('parent_id')->title('Parent'),
            Column::make('has_child'),
            Column::make('applicable_on_site')->addClass('text-center'),
            Column::make('site_percentage')->title('Site (%)')->addClass('text-center'),
            Column::make('applicable_on_floor')->addClass('text-center'),
            Column::make('floor_percentage')->title('Floor (%)')->addClass('text-center'),
            Column::make('applicable_on_unit')->addClass('text-center'),
            Column::make('unit_percentage')->title('Unit (%)')->addClass('text-center'),
            Column::make('created_at')->addClass('text-nowrap'),
            Column::computed('actions')->exportable(false)->printable(false)->width(100)->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'AdditionalCost_' . date('YmdHis');
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
