<?php

namespace App\DataTables;

use App\Models\Floor;
use App\Models\Status;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;

class UnitsPreviewDataTable extends DataTable
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
            ->editColumn('type_id', function ($unit) {
                return view('app.components.unit-preview-cell',
                    ['id'=>$unit->id,'field'=>'type_id','inputtype'=>'select','value'=>$unit->type->name]);
            })
            ->editColumn('status_id', function ($unit) {
                return view('app.components.unit-preview-cell',
                    ['id'=>$unit->id,'field'=>'status_id','inputtype'=>'select','value'=>$unit->status->name]);
            })
            ->editColumn('name', function ($unit) {
                return view('app.components.unit-preview-cell',
                                ['id'=>$unit->id,'field'=>'name','inputtype'=>'text','value'=>$unit->name]);
            })
            ->editColumn('width', function ($unit) {
                return view('app.components.unit-preview-cell',
                                ['id'=>$unit->id,'field'=>'width','inputtype'=>'number','value'=>$unit->width]);
            })
            ->editColumn('length', function ($unit) {
                return view('app.components.unit-preview-cell',
                                ['id'=>$unit->id,'field'=>'length','inputtype'=>'number','value'=>$unit->length]);
            })
            ->editColumn('net_area', function ($unit) {
                return view('app.components.unit-preview-cell',
                                ['id'=>$unit->id,'field'=>'net_area','inputtype'=>'number','value'=>$unit->net_area]);
            })
            ->editColumn('gross_area', function ($unit) {
                return view('app.components.unit-preview-cell',
                                ['id'=>$unit->id,'field'=>'gross_area','inputtype'=>'number','value'=>$unit->gross_area]);
            })
            ->editColumn('price_sqft', function ($unit) {
                return view('app.components.unit-preview-cell',
                                ['id'=>$unit->id,'field'=>'price_sqft','inputtype'=>'number','value'=>$unit->price_sqft]);
            })
            ->editColumn('is_corner', function ($unit) {
                return view('app.components.checkbox',
                 ['id' => $unit->id, 'data'=> 'null' ,'field' => 'is_corner', 'is_true' => $unit->is_corner]);
            })
            ->editColumn('is_facing', function ($unit) {
                return view('app.components.checkbox',
                 ['id' => $unit->id,'data'=> $unit, 'field' => 'is_facing', 'is_true' => $unit->is_facing]);
            })
            ->editColumn('created_at', function ($unit) {
                return editDateColumn($unit->created_at);
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, []));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Unit $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Unit $model): QueryBuilder
    {
        return $model->newQuery()->select('units.*')->with(['type', 'status'])->whereFloorId($this->floor->id)->where('active', false);
    }

    public function html(): HtmlBuilder
    {
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
                Button::make('reload')->addClass('btn btn-relief-outline-primary'),
                Button::raw('save-units')
                ->addClass('btn btn-relief-outline-success')
                ->text('<i class=""></i> Save Changes')->attr([
                    'onclick' => 'saveUnits()',
                ]),

            )
            ->columnDefs([
                [
                    'targets' => 0,
                    'className' => 'text-center text-primary',
                    'width' => '10%',
                    'orderable' => false,
                    'searchable' => false,
                    'responsivePriority' => 0,

                ],
            ])
            ->orders([
                [10, 'desc'],
            ])
            ;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::make('name')->title('Units')->addClass('text-center'),
            Column::make('type_id')->name('type.name')->title('Type'),
            Column::make('status_id')->name('status.name')->title('Status')->addClass('text-center'),
            Column::make('width'),
            Column::make('length'),
            Column::make('net_area'),
            Column::make('gross_area'),
            Column::make('price_sqft'),
            Column::make('is_corner'),
            Column::make('is_facing'),
            Column::make('created_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'UnitsPreview_' . date('YmdHis');
    }
}
