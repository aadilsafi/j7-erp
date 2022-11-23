<?php

namespace App\DataTables;

use App\Models\TempUnit;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportUnitsDataTable extends DataTable
{
    public function __construct($site_id)
    {
        $model = new TempUnit();
        if ($model->count() == 0) {
            return redirect()->route('sites.floors.index', ['site_id' => decryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
        }
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
            ->addIndexColumn()
            ->editColumn('floor_short_label', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'floor_short_label', 'inputtype' => 'text', 'value' => $data->floor_short_label]
                );
            })
            ->editColumn('name', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'name', 'inputtype' => 'text', 'value' => $data->name]
                );
            })
            ->editColumn('width', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'width', 'inputtype' => 'number', 'value' => $data->width]
                );
            })
            ->editColumn('length', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'length', 'inputtype' => 'number', 'value' => $data->length]
                );
            })
            ->editColumn('unit_short_label', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'unit_short_label', 'inputtype' => 'text', 'value' => $data->unit_short_label]
                );
            })
            ->editColumn('net_area', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'net_area', 'inputtype' => 'number', 'value' => $data->net_area]
                );
            })
            ->editColumn('gross_area', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'gross_area', 'inputtype' => 'text', 'value' => $data->gross_area]
                );
            })
            ->editColumn('price_sqft', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'price_sqft', 'inputtype' => 'text', 'value' => $data->price_sqft]
                );
            })
            ->editColumn('total_price', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'total_price', 'inputtype' => 'text', 'value' => $data->total_price]
                );
            })
            ->editColumn('unit_type_slug', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'unit_type_slug', 'inputtype' => 'text', 'value' => $data->unit_type_slug]
                );
            })
            ->editColumn('status', function ($data) {
                $values = ['open' => 'Open', 'sold' => 'Sold', 'token' => 'Token', 'partial-paid' => 'Partial Paid', 'hold' => 'Hold'];
                return view(
                    'app.components.input-select-fields',
                    ['id' => $data->id, 'field' => 'status', 'values' => $values, 'selectedValue' => $data->status]
                );
            })
            ->editColumn('parent_unit_short_label', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'parent_unit_short_label', 'inputtype' => 'text', 'value' => $data->parent_unit_short_label]
                );
            })
            ->editColumn('is_corner', function ($data) {
                $values = ['yes' => 'Yes', 'no' => 'No'];
                return view(
                    'app.components.input-select-fields',
                    ['id' => $data->id, 'field' => 'is_corner', 'values' => $values, 'selectedValue' => $data->is_corner]
                );
            })
            ->editColumn('is_facing', function ($data) {
                $values = ['yes' => 'Yes', 'no' => 'No'];
                return view(
                    'app.components.input-select-fields',
                    ['id' => $data->id, 'field' => 'is_facing', 'values' => $values, 'selectedValue' => $data->is_facing]
                );
            })
            ->editColumn('additional_costs_name', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'additional_costs_name', 'inputtype' => 'text', 'value' => $data->additional_costs_name]
                );
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TempUnit $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id');
    }

    public function html(): HtmlBuilder
    {

        return $this->builder()
            ->setTableId('import-table')
            ->addTableClass(['table-hover'])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->ordering(false)
            ->searching(true)
            ->serverSide()
            ->processing()
            ->deferRender()
            ->scrollX(true)
            ->lengthMenu([20, 50, 100, 500, 1000]);
        // ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">');
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::computed('floor_short_label')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'floor_short_label'
            ])->render())->searchable(true)->addClass('removeTolltip'),
            Column::computed('name')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'name'
            ])->render())->searchable(true)->addClass('removeTolltip'),
            Column::computed('unit_short_label')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'unit_short_label'
            ])->render())->searchable(true)->addClass('removeTolltip'),
            Column::computed('unit_type_slug')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'unit_type_slug'
            ])->render())->searchable(true)->addClass('removeTolltip'),
            Column::computed('parent_unit_short_label')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'parent_unit_short_label'
            ])->render())->searchable(true)->addClass('removeTolltip'),
            Column::computed('width')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'width'
            ])->render())->addClass('removeTolltip'),
            Column::computed('length')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'length'
            ])->render())->addClass('removeTolltip'),

            Column::computed('net_area')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'net_area'
            ])->render())->addClass('removeTolltip'),
            Column::computed('gross_area')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'gross_area'
            ])->render())->addClass('removeTolltip'),
            Column::computed('price_sqft')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'price_sqft'
            ])->render())->addClass('removeTolltip'),
            Column::computed('total_price')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'total_price'
            ])->render())->addClass('removeTolltip'),
            Column::computed('is_corner')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'is_corner'
            ])->render())->addClass('removeTolltip'),
            Column::computed('is_facing')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'is_facing'
            ])->render())->addClass('removeTolltip'),
            Column::computed('additional_costs_name')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'additional_costs_name'
            ])->render())->searchable(true)->addClass('removeTolltip'),
        ];
    }
}
