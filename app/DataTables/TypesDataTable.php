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
use Barryvdh\DomPDF\Facade\Pdf;

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
        $editColumns = (new EloquentDataTable($query))
            ->editColumn('parent_id', function ($type) {
                return Str::of(getTypeParentByParentId($type->parent_id))->ucfirst();
            })
            ->editColumn('account_number', function ($type) {
                return  account_number_format($type->account_number);
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
        if (count($this->customFields) > 0) {
            foreach ($this->customFields as $customfields) {
                $editColumns->addColumn($customfields->slug, function ($data) use ($customfields) {
                    $val = $customfields->CustomFieldValue->where('modelable_id', $data->id)->first();
                    if ($val) {
                        return Str::title($val->value);
                    } else {
                        return '-';
                    }
                });
            }
        }

        return $editColumns;
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
        $importPermission =  Auth::user()->hasPermissionTo('sites.types.importTypes');
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.types.destroy-selected');

        $buttons = [];

        $buttons = [
            Button::make('export')->addClass('btn btn-relief-outline-secondary waves-effect waves-float waves-light dropdown-toggle')->buttons([
                Button::make('print')->addClass('dropdown-item'),
                Button::make('copy')->addClass('dropdown-item'),
                Button::make('csv')->addClass('dropdown-item'),
                Button::make('excel')->addClass('dropdown-item'),
                Button::make('pdf')->addClass('dropdown-item'),
            ]),
            Button::make('reset')->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light'),
            Button::make('reload')->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light'),
        ];

        if ($importPermission) {
            $importButton =  Button::raw('import')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i data-feather="upload"></i> Import Types')
                ->attr([
                    'onclick' => 'Import()',
                ]);
            array_unshift($buttons, $importButton);
        }

        if ($createPermission) {
            $newButton = Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i class="bi bi-plus"></i> Add New')
                ->attr([
                    'onclick' => 'addNew()',
                ]);
            array_unshift($buttons, $newButton);
        }



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
            ->scrollX()
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


        $columns = [
            Column::make('name')->title('Type Name')->addClass('text-nowrap'),
            Column::make('parent_id')->title('Parent'),
            Column::make('account_number')->title('Account Number')->addClass('text-nowrap'),
         
        ];
        if (count($this->customFields) > 0) {
            foreach ($this->customFields as $customfields) {
                $columns[] = Column::computed($customfields->slug)->addClass('text-nowrap')->title($customfields->name);
            }
        }
        $columns[] = Column::make('created_at')->addClass('text-nowrap');
        $columns[] = Column::make('updated_at')->addClass('text-nowrap');
        $columns[] = Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center');


        if ($selectedDeletePermission) {
            $newColumn = Column::computed('check')->exportable(false)->printable(false)->width(60);
            array_unshift($columns, $newColumn);
        }

        return $columns;
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
