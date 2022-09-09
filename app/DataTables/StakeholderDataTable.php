<?php

namespace App\DataTables;

use App\Models\Stakeholder;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Services\Stakeholder\Interface\StakeholderInterface;

class StakeholderDataTable extends DataTable
{

    private $stakeholderInterface;

    public function __construct( StakeholderInterface $stakeholderInterface)
    {
        $this->stakeholderInterface = $stakeholderInterface;
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
            ->editColumn('parent_id', function ($stakeholder) {
                return Str::of(getStakeholderParentByParentId($stakeholder->parent_id))->ucfirst();
            })
            ->editColumn('created_at', function ($stakeholder) {
                return editDateColumn($stakeholder->created_at);
            })
            ->editColumn('updated_at', function ($stakeholder) {
                return editDateColumn($stakeholder->updated_at);
            })
            ->editColumn('actions', function ($stakeholder) {
                return view('app.sites.stakeholders.actions', ['site_id' => decryptParams($this->site_id), 'id' => $stakeholder->id]);
            })
            ->editColumn('check', function ($stakeholder) {
                return $stakeholder;
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
        return $this->stakeholderInterface->model()->newQuery()->where('site_id', decryptParams($this->site_id));
    }

    public function html(): HtmlBuilder
    {
        $createPermission =  Auth::user()->hasPermissionTo('sites.stakeholders.create');
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.stakeholders.destroy-selected');

        return $this->builder()
            ->setTableId('stakeholder-table')
            ->addTableClass(['table-hover'])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons(
                ($createPermission  ?
                    Button::raw('delete-selected')
                    ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                    ->text('<i class="bi bi-plus"></i> Add New')->attr([
                        'onclick' => 'addNew()',
                    ])
                :
                Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light hidden')
                ->text('<i class="bi bi-plus"></i> Add New')->attr([
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
                        ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')->attr([
                            'onclick' => 'deleteSelected()',
                        ])
                        :
                        Button::raw('delete-selected')
                        ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light hidden')
                        ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')->attr([
                            'onclick' => 'deleteSelected()',
                        ])
                ),
            )
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
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.stakeholders.destroy-selected');
        $editPermission =  Auth::user()->hasPermissionTo('sites.stakeholders.edit');
        return [
            ( $selectedDeletePermission ?
                Column::computed('check')->exportable(false)->printable(false)->width(60)
                :
                Column::computed('check')->exportable(false)->printable(false)->width(60)->addClass('hidden')
            ),

            Column::make('full_name')->title('Name'),
            Column::make('father_name')->title('Father Name')->addClass('text-nowrap'),
            Column::make('cnic')->title('CNIC'),
            Column::make('contact')->title('Contact'),
            Column::make('parent_id')->title('Next Of Kin')->addClass('text-nowrap'),
            Column::make('relation')->title('Relation'),
            (
                $editPermission ?
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
        return 'Stakeholders_' . date('YmdHis');
    }
}
