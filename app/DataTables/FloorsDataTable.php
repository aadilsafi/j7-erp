<?php

namespace App\DataTables;

use App\Models\Floor;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class FloorsDataTable extends DataTable
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
            ->editColumn('check', function ($floor) {
                return $floor;
            })
            ->editColumn('width', function ($floor) {
                return $floor->width . '\'\'';
            })
            ->editColumn('length', function ($floor) {
                return $floor->length . '\'\'';
            })
            ->editColumn('units_count', function ($floor) {
                $count = $floor->units->where('has_sub_units', false)->count();
                if (!is_null($count)) {
                    return $count > 0 ? $count : '-';
                }
                return '-';
            })
            ->editColumn('units_open_count', function ($floor) {
                $count = $floor->units->where('has_sub_units', false)->where('status_id', 1)->count();
                if (!is_null($count)) {
                    return $count > 0 ? $count : '-';
                }
                return '-';
            })
            ->editColumn('units_sold_count', function ($floor) {
                $count = $floor->units->where('has_sub_units', false)->where('status_id', 5)->count();
                if (!is_null($count)) {
                    return $count > 0 ? $count : '-';
                }
                return '-';
            })
            ->editColumn('units_token_count', function ($floor) {
                $count = $floor->units->where('has_sub_units', false)->where('status_id', 2)->count();
                if (!is_null($count)) {
                    return $count > 0 ? $count : '-';
                }
                return '-';
            })
            ->editColumn('units_hold_count', function ($floor) {
                $count = $floor->units->where('has_sub_units', false)->where('status_id', 4)->count();
                if (!is_null($count)) {
                    return $count > 0 ? $count : '-';
                }
                return '-';
            })
            ->editColumn('units_dp_count', function ($floor) {
                $count = $floor->units->where('has_sub_units', false)->where('status_id', 3)->count();
                if (!is_null($count)) {
                    return $count > 0 ? $count : '-';
                }
                return '-';
            })
            ->editColumn('created_at', function ($floor) {
                return editDateColumn($floor->created_at);
            })
            ->editColumn('updated_at', function ($floor) {
                return editDateColumn($floor->updated_at);
            })
            ->editColumn('actions', function ($floor) {
                return view('app.sites.floors.actions', ['site_id' => $floor->site_id, 'id' => $floor->id]);
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Floor $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Floor $model): QueryBuilder
    {
        return $model->newQuery()->whereActive(true)->whereSiteId($this->site_id);
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
            Column::computed('check')->exportable(false)->printable(false)->width(60),
            Column::make('name')->title('Floors'),
            Column::make('order'),
            Column::make('floor_area'),
            Column::make('short_label'),
            Column::computed('units_count')->title('Units'),
            Column::computed('units_open_count')->title('Open'),
            Column::computed('units_sold_count')->title('Sold'),
            Column::computed('units_token_count')->title('Token'),
            Column::computed('units_hold_count')->title('Hold'),
            Column::computed('units_dp_count')->title('Partial Paid'),
            Column::make('created_at')->addClass('text-nowrap'),
            Column::make('updated_at')->addClass('text-nowrap'),
            Column::computed('actions')->exportable(false)->printable(false)->addClass('text-center p-1'),
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
