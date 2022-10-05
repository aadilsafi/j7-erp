<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Services\User\Interface\UserInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;

class UserDataTable extends DataTable
{

    private $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
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
            ->editColumn('phone_no', function ($user) {
                return Str::substrReplace($user->phone_no,'-',4,0);
            })
            ->editColumn('created_at', function ($user) {
                return editDateColumn($user->created_at);
            })
            ->editColumn('actions', function ($user) {
                return view('app.sites.users.actions', ['site_id' => decryptParams($this->site_id), 'id' => $user->id]);
            })
            ->editColumn('check', function ($user) {
                return $user;
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
        return $this->userInterface->model()->newQuery()->where('site_id', decryptParams($this->site_id));
    }

    public function html(): HtmlBuilder
    {
        $createPermission =  Auth::user()->hasPermissionTo('sites.users.create');
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.users.destroy-selected');

        return $this->builder()
            ->setTableId('user-table')
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
                        return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" onchange=\"changeTableRowColor(this)\" type=\"checkbox\" value=\"' + role.id + '\" name=\"chkUsers[]\" id=\"chkUsers_' + role.id + '\" /><label class=\"form-check-label\" for=\"chkUsers_' + role.id + '\"></label></div>';
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
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.users.destroy-selected');
        $editPermission =  Auth::user()->hasPermissionTo('sites.users.edit');
        return [
            ($selectedDeletePermission ?
                Column::computed('check')->exportable(false)->printable(false)->width(60)
                :
                Column::computed('check')->exportable(false)->printable(false)->width(60)->addClass('hidden')
            ),

            Column::make('name')->title('Name'),
            Column::make('email')->title('Email')->addClass('text-nowrap'),
            Column::make('phone_no')->title('Phone No'),
            Column::make('created_at')->title('Created At')->addClass('text-nowrap'),
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
        return 'Users_' . date('YmdHis');
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