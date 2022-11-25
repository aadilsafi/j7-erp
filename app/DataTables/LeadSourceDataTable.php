<?php

namespace App\DataTables;

use App\Models\LeadSource;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Barryvdh\DomPDF\Facade\Pdf;
use Str;

class LeadSourceDataTable extends DataTable
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
        $editColumns = (new EloquentDataTable($query))
            ->editColumn('check', function ($leadSource) {
                return $leadSource;
            })
            ->editColumn('created_at', function ($leadSource) {
                return editDateColumn($leadSource->created_at);
            })
            ->editColumn('updated_at', function ($leadSource) {
                return editDateColumn($leadSource->updated_at);
            })
            ->editColumn('actions', function ($leadSource) {
                return view('app.sites.lead-sources.actions', ['site_id' => $this->site_id, 'id' => $leadSource->id]);
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['actions', 'check']));

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
     * @param \App\Models\LeadSource $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(LeadSource $model): QueryBuilder
    {
        return $model->newQuery()->whereSiteId($this->site_id)->orderBy('id', 'desc');
    }

    public function html(): HtmlBuilder
    {
        $createPermission = auth()->user()->can('sites.lead-sources.create');
        $selectedDeletePermission = auth()->user()->can('sites.lead-sources.destroy-selected');

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
            ->setTableId('lead-source-table')
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
                [2, 'desc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $columns = [
            Column::computed('check')->exportable(false)->printable(false)->width(60),
            Column::make('name')->title('Lead Source'),
            Column::make('created_at')->title('Created At')->addClass('text-nowrap'),
            Column::make('updated_at')->title('Updated At')->addClass('text-nowrap'),
        ];

        if (count($this->customFields) > 0) {
            foreach ($this->customFields as $customfields) {
                $columns[] = Column::computed($customfields->slug)->addClass('text-nowrap')->title($customfields->name);
            }
        }
        $columns[] = Column::make('created_at')->addClass('text-nowrap');
        $columns[] = Column::make('updated_at')->addClass('text-nowrap');
        $columns[] = Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center');

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'LeadSource_' . date('YmdHis');
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
