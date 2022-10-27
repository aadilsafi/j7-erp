<?php

namespace App\DataTables;

use App\Models\TempFloor;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportFloorsDataTable extends DataTable
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
            ->addIndexColumn()
            ->editColumn('floor_area', function ($floor) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $floor->id, 'field' => 'floor_area', 'inputtype' => 'number', 'value' => $floor->floor_area]
                );
            })
            ->editColumn('name', function ($floor) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $floor->id, 'field' => 'name', 'inputtype' => 'text', 'value' => $floor->name]
                );
            })
            ->editColumn('short_label', function ($floor) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $floor->id, 'field' => 'short_label', 'inputtype' => 'text', 'value' => $floor->short_label]
                );
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TempFloor $model): QueryBuilder
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
            ->serverSide()
            ->processing()
            ->deferRender()
            ->lengthMenu([10, 20, 30, 50, 70, 100]);
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
            Column::computed('name')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'col' => 0,
                'name' => 'name'
            ])->render()),
            Column::computed('floor_area')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'col' => 1,
                'name' => 'floor_area'

            ])->render()),
            Column::computed('short_label')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'col' => 2,
                'name' => 'short_label'
            ])->render()),

        ];
    }
}
