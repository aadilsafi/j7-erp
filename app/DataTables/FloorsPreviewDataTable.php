<?php

namespace App\DataTables;

use App\Models\Floor;
use App\Models\Unit;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class FloorsPreviewDataTable extends DataTable
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
            ->editColumn('type_id', function ($unit) {
                // return view(
                //     'app.components.unit-preview-cell',
                //     ['id' => $unit->id, 'field' => 'type_id', 'inputtype' => 'select', 'value' => $unit->type->name]
                // );
                return $unit->type->name;
            })
            ->editColumn('status_id', function ($unit) {
                return $unit->status->name;
            })
            ->editColumn('name', function ($unit) {
                return $unit->name;
            })
            ->editColumn('created_at', function ($unit) {
                return editDateColumn($unit->created_at);
            })
            ->editColumn('width', function ($unit) {
                return $unit->width;
            })
            ->editColumn('length', function ($unit) {
                return $unit->length;
            })
            // ->editColumn('is_corner', function ($unit) {
            //     return view(
            //         'app.components.checkbox',
            //         ['id' => $unit->id, 'data' => 'null', 'field' => 'is_corner', 'is_true' => $unit->is_corner]
            //     );
            // })
            // ->editColumn('is_facing', function ($unit) {
            //     return view(
            //         'app.components.checkbox',
            //         ['id' => $unit->id, 'data' => $unit, 'field' => 'is_facing', 'is_true' => $unit->is_facing]
            //     );
            // })
            ->editColumn('net_area', function ($unit) {
                return $unit->net_area;
            })
            ->editColumn('gross_area', function ($unit) {
                return $unit->gross_area;
            })
            ->editColumn('price_sqft', function ($unit) {
                return $unit->price_sqft;
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
        return $model->newQuery()->where('floor_id', $this->floor_id)->where('active', 0);
    }

    public function html(): HtmlBuilder
    {
        $createPermission =  auth()->user()->can('sites.floors.create');
        $selectedDeletePermission =  auth()->user()->can('sites.floors.destroy-selected');
        $copyPermission =  auth()->user()->can('sites.floors.copyView');

        $buttons = [];

        if ($createPermission) {
            $buttons[] = Button::raw('add-new')->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i class="bi bi-plus"></i> Add New')->attr(['onclick' => 'addNew()']);
        }

        if ($copyPermission) {
            $buttons[] = Button::raw('copy-floor')->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i class="bi bi-clipboard-check"></i> Copy Floor')->attr(['onclick' => 'copyFloor()']);
        }

        $buttons = array_merge($buttons, [
            Button::make('export')->addClass('btn btn-relief-outline-secondary waves-effect waves-float waves-light dropdown-toggle')->buttons([
                Button::make('print')->addClass('dropdown-item'),
                Button::make('copy')->addClass('dropdown-item'),
                Button::make('csv')->addClass('dropdown-item'),
                Button::make('excel')->addClass('dropdown-item'),
                Button::make('pdf')->addClass('dropdown-item'),
            ]),
            Button::make('reset')->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light'),
            Button::make('reload')->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light'),
        ]);

        if ($selectedDeletePermission) {

            $buttons[] = Button::raw('delete-selected')->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
                ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')->attr(['onclick' => 'deleteSelected()']);
        }

        return $this->builder()
            ->setTableId('floors-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide()
            ->processing()
            ->deferRender()
            // ->scrollX()s
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
            // ->rowGroupDataSrc('parent_id')
            ->columnDefs([
                [
                    'targets' => 0,
                    'className' => 'text-center text-primary',
                    'width' => '10%',
                    'orderable' => false,
                    'searchable' => false,
                    'responsivePriority' => 3,
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
                [9, 'desc'],
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
            Column::computed('name')->searchable(true)->addClass('text-nowrap'),
            Column::computed('created_at')->addClass('text-nowrap'),
            Column::computed('width')->addClass('text-nowrap'),
            Column::computed('length')->addClass('text-nowrap'),
            // Column::computed('is_corner')->addClass('text-nowrap'),
            // Column::computed('is_facing')->addClass('text-nowrap'),
            Column::computed('type_id')->addClass('text-nowrap'),
            Column::computed('status_id')->addClass('text-nowrap'),
            Column::computed('net_area')->addClass('text-nowrap'),
            Column::computed('gross_area')->addClass('text-nowrap'),
            Column::computed('price_sqft')->addClass('text-nowrap')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Floors_' . date('YmdHis');
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
