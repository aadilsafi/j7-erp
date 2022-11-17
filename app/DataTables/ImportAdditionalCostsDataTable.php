<?php

namespace App\DataTables;

use App\Models\TempAdditionalCost;
use App\Models\TempFloor;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportAdditionalCostsDataTable extends DataTable
{
    public function __construct($site_id)
    {
        $model = new TempFloor();
        if ($model->count() == 0) {
            return redirect()->route('sites.additional-costs.index', ['site_id' => decryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
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
            ->editColumn('additional_costs_name', function ($floor) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $floor->id, 'field' => 'additional_costs_name', 'inputtype' => 'text', 'value' => $floor->additional_costs_name]
                );
            })
            ->editColumn('site_percentage', function ($floor) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $floor->id, 'field' => 'site_percentage', 'inputtype' => 'number', 'value' => $floor->site_percentage]
                );
            })
            ->editColumn('floor_percentage', function ($floor) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $floor->id, 'field' => 'floor_percentage', 'inputtype' => 'number', 'value' => $floor->floor_percentage]
                );
            })
            ->editColumn('unit_percentage', function ($floor) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $floor->id, 'field' => 'unit_percentage', 'inputtype' => 'number', 'value' => $floor->unit_percentage]
                );
            })
            ->editColumn('is_sub_types', function ($floor) {
                $values = ['yes' => 'Yes', 'no' => 'No'];
                return view(
                    'app.components.input-select-fields',
                    ['id' => $floor->id, 'field' => 'is_sub_types', 'inputtype' => 'text', 'values' => $values, 'selectedValue' => $floor->is_sub_types]
                );
            })
            ->editColumn('parent_type_name', function ($floor) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $floor->id, 'field' => 'parent_type_name', 'inputtype' => 'text', 'value' => $floor->parent_type_name]
                );
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TempAdditionalCost $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {

        return $this->builder()
            ->setTableId('import-table')
            ->addTableClass(['table-hover'])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->ordering(false)
            ->searching(false)
            ->serverSide()
            ->processing()
            ->deferRender()
            ->scrollX(true)
            ->lengthMenu([30, 50, 100, 500, 1000]);
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
            Column::computed('additional_costs_name')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'additional_costs_name'
            ])->render())->addClass('removeTolltip'),
            Column::computed('site_percentage')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'site_percentage'
            ])->render())->addClass('removeTolltip'),
            Column::computed('floor_percentage')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'floor_percentage'
            ])->render())->addClass('removeTolltip'),
            Column::computed('unit_percentage')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'unit_percentage'
            ])->render())->addClass('removeTolltip'),
            Column::computed('is_sub_types')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'is_sub_types'
            ])->render())->addClass('removeTolltip'),
            Column::computed('parent_type_name')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'parent_type_name'
            ])->render())->addClass('removeTolltip'),

        ];
    }
}
