<?php

namespace App\DataTables;

use App\Models\Type;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\Services\Interfaces\UnitTypeInterface;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class TypesDataTable extends DataTable
{

    private $unitTypeInterface;

    public function __construct(UnitTypeInterface $unitTypeInterface)
    {
        $this->unitTypeInterface = $unitTypeInterface;
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
            ->editColumn('parent_id', function ($type) {
                return Str::of(getTypeParentByParentId($type->parent_id))->ucfirst();
            })
            ->editColumn('created_at', function ($type) {
                return editDateColumn($type->created_at);
            })
            ->editColumn('updated_at', function ($type) {
                return editDateColumn($type->updated_at);
            })
            ->editColumn('actions', function ($type) {
                return view('app.sites.types.actions', ['site_id' => decryptParams($this->site_id), 'id' => $type->id]);
            })
            ->editColumn('check', function ($type) {
                return $type;
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): QueryBuilder
    {
        return $this->unitTypeInterface->model()->newQuery()->where('site_id', decryptParams($this->site_id));
    }

    public function html(): HtmlBuilder
    {

        $createPermission =  Auth::user()->hasPermissionTo('sites.types.create');
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.types.destroy-selected');

        $buttons = [];

        if ($createPermission) {
            $buttons[] = Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i class="bi bi-plus"></i> Add New')
                ->attr([
                    'onclick' => 'addNew()',
                ]);
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

            $buttons[] = Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
                ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')
                ->attr([
                    'onclick' => 'deleteSelected()',
                ]);
        }

        return $this->builder()
            ->setTableId('types-table')
            ->addTableClass(['table-hover'])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
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
                        var role = JSON.parse(data);
                        return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" onchange=\"changeTableRowColor(this)\" type=\"checkbox\" value=\"' + role.id + '\" name=\"chkRole[]\" id=\"chkRole_' + role.id + '\" /><label class=\"form-check-label\" for=\"chkRole_' + role.id + '\"></label></div>';
                    }",
                    'checkboxes' => [
                        'selectAllRender' =>  '<div class="form-check"> <input class="form-check-input" onchange="changeAllTableRowColor()" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
                    ]
                ],
            ])
            ->orders([
                [2, 'asc'],
                [4, 'desc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.types.destroy-selected');
        $editPermission =  Auth::user()->hasPermissionTo('sites.types.edit');
        return [
            ($selectedDeletePermission ?
                Column::computed('check')->exportable(false)->printable(false)->width(60)
                :
                Column::computed('check')->exportable(false)->printable(false)->width(60)->addClass('hidden')
            ),

            Column::make('name')->title('Type Name'),
            Column::make('parent_id')->title('Parent'),
            Column::make('created_at'),
            Column::make('updated_at'),
            ($editPermission ?
                Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')
                :
                Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            )

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Types_' . date('YmdHis');
    }
}
