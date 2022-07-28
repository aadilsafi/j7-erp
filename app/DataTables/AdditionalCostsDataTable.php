<?php

namespace App\DataTables;

use App\Models\AdditionalCost;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Support\Str;

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
                Button::raw('add-new')
                    ->addClass('btn btn-relief-outline-primary')
                    ->text('<i class="bi bi-plus"></i> Add New')
                    ->attr([
                        'onclick' => 'addNew()',
                    ]),
                Button::make('export')->addClass('btn btn-relief-outline-secondary dropdown-toggle')->buttons([
                    Button::make('print')->addClass('dropdown-item'),
                    Button::make('copy')->addClass('dropdown-item'),
                    Button::make('csv')->addClass('dropdown-item'),
                    Button::make('excel')->addClass('dropdown-item'),
                    Button::make('pdf')->addClass('dropdown-item'),
                ]),
                Button::make('reset')->addClass('btn btn-relief-outline-danger'),
                Button::make('reload')->addClass('btn btn-relief-outline-primary'),
                Button::raw('delete-selected')
                    ->addClass('btn btn-relief-outline-danger')
                    ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')
                    ->attr([
                        'onclick' => 'deleteSelected()',
                    ]),

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
                        return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" type=\"checkbox\" value=\"' + additionalCost.id + '\" name=\"chkAdditionalCost[]\" id=\"chkAdditionalCost_' + additionalCost.id + '\" /><label class=\"form-check-label\" for=\"chkAdditionalCost_' + additionalCost.id + '\"></label></div>';
                    }",
                    'checkboxes' => [
                        'selectAllRender' =>  '<div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
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
        return [
            Column::computed('check')->exportable(false)->printable(false)->width(60),
            Column::make('name')->title('Additional Cost'),
            Column::make('parent_id')->title('Parent'),
            Column::make('has_child'),
            Column::make('applicable_on_site')->addClass('text-center'),
            Column::make('site_percentage')->title('Site (%)')->addClass('text-center'),
            Column::make('applicable_on_floor')->addClass('text-center'),
            Column::make('floor_percentage')->title('Floor (%)')->addClass('text-center'),
            Column::make('applicable_on_unit')->addClass('text-center'),
            Column::make('unit_percentage')->title('Unit (%)')->addClass('text-center'),
            Column::make('created_at'),
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
}
