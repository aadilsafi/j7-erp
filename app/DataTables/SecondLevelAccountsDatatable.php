<?php

namespace App\DataTables;

use App\Models\AccountHead;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Barryvdh\DomPDF\Facade\Pdf;

class SecondLevelAccountsDatatable extends DataTable
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
            ->editColumn('level', function ($ledger) {
                return number_format($ledger->level);
            })
            ->editColumn('code', function ($ledger) {
                return account_number_format($ledger->code);
            })
            ->editColumn('level', function ($ledger) {
                if ($ledger->level == 1) {
                    return '1st Level';
                }
                if ($ledger->level == 2) {
                    return '2nd Level';
                }
                if ($ledger->level == 3) {
                    return '3rd Level';
                }
                if ($ledger->level == 4) {
                    return '4th Level';
                }
                if ($ledger->level == 5) {
                    return '5th Level';
                }
            })
            ->editColumn('created_at', function ($ledger) {
                return editDateColumn($ledger->created_at);
            })
            ->editColumn('updated_at', function ($ledger) {
                return editDateColumn($ledger->updated_at);
            })
            ->editColumn('check', function ($ledger) {
                return $ledger;
            })
            ->editColumn('actions', function ($ledger) {
                return view('app.roles.actions', ['id' => $ledger->id]);
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AccountHead $model): QueryBuilder
    {
        return $model->newQuery()->with('modelable')->where('level', 2)->orderBy('level', 'asc');
    }

    public function html(): HtmlBuilder
    {
        $createPermission =  Auth::user()->hasPermissionTo('sites.settings.accounts.second-level.create');
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.settings.accounts.second-level.destroy-selected');
        return $this->builder()
            ->setTableId('second-level-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->select()
            // ->selectClassName('bg-primary')
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons(
                ($createPermission ?
                    Button::raw('add-new')
                    ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                    ->text('<i class="bi bi-plus"></i> Add New')
                    ->attr([
                        'onclick' => 'addNew()',
                    ])
                    :
                    Button::raw('add-new')
                    ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light hidden')
                    ->text('<i class="bi bi-plus"></i> Add New')
                    ->attr([
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
                ($selectedDeletePermission  ?
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
                [4, 'asc'],
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
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.settings.accounts.second-level.destroy-selected');
        $editPermission =  Auth::user()->hasPermissionTo('sites.settings.accounts.second-level.edit');
        $destroyPermission =  Auth::user()->hasPermissionTo('sites.settings.accounts.second-level.destroy');
        return [
            ($selectedDeletePermission ?
                Column::computed('check')->exportable(false)->printable(false)->width(60)
                :
                Column::computed('check')->exportable(false)->printable(false)->width(60)->addClass('hidden')
            ),
            // Column::computed('DT_RowIndex')->title('#'),
            Column::make('name')->title('Name'),
            Column::make('level')->title('Account Level')->addClass('text-nowrap ')->searchable(false)->orderable(false),
            Column::make('code')->title('Account Codes')->addClass('text-nowrap ')->orderable(false),
            Column::make('created_at')->addClass('text-nowrap'),
            Column::make('updated_at')->addClass('text-nowrap'),
            // (
            //     ($editPermission ||  $destroyPermission) ?
            //     Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')
            //     :
            //     Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('hidden')
            // ),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'second_level_' . date('YmdHis');
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
