<?php

namespace App\DataTables;

use App\Models\CustomField;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CustomFieldsDataTable extends DataTable
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
            ->editColumn('check', function ($customField) {
                return $customField;
            })
            ->editColumn('custom_field_model', function ($customField) {
                $data = explode('\\', $customField->custom_field_model);
                return array_pop($data);
            })
            ->editColumn('created_at', function ($customField) {
                return editDateColumn($customField->created_at);
            })
            ->editColumn('updated_at', function ($customField) {
                return editDateColumn($customField->updated_at);
            })
            ->editColumn('actions', function ($customField) {
                $customValues = count($customField->CustomFieldValue);
                return view('app.sites.settings.custom-fields.actions', ['site_id' => $this->site_id, 'id' => $customField->id, 'customValues' => $customValues]);
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CustomField $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CustomField $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        $createPermission = auth()->user()->can('sites.settings.custom-fields.index');
        $selectedDeletePermission = auth()->user()->can('sites.settings.custom-fields.destroy-selected');

        $buttons = [];

        if ($createPermission) {
            $buttons[] = Button::raw('add-new')
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
            ->addTableClass(['table-striped', 'table-hover'])
            ->setTableId('custom-fields-table')
            ->columns($this->getColumns())
            ->deferRender()
            ->scrollX()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
            ->rowGroupDataSrc('custom_field_model')
            ->columnDefs([
                [
                    'targets' => 0,
                    'className' => 'text-center text-primary',
                    'width' => '10%',
                    'orderable' => false,
                    'searchable' => false,
                    'responsivePriority' => 0,
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
                [1, 'desc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $destroyPermission = auth()->user()->can('sites.settings.custom-fields.destroy');

        $columns = [];

        if ($destroyPermission) {
            $columns[] = Column::computed('check')->exportable(false)->printable(false)->width(60);
        }

        $columns = array_merge($columns, [
            Column::make('name')->addClass('text-nowrap'),
            Column::make('type')->addClass('text-nowrap'),
            Column::make('disabled')->addClass('text-nowrap'),
            Column::make('required')->addClass('text-nowrap'),
            Column::make('in_table')->addClass('text-nowrap'),
            Column::make('order')->addClass('text-nowrap'),
            Column::make('custom_field_model')->title('Bind To')->addClass('text-nowrap'),
            Column::make('created_at')->addClass('text-nowrap'),
            Column::make('updated_at')->addClass('text-nowrap'),
            Column::computed('actions')->exportable(false)->printable(false)->addClass('text-center'),
        ]);

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'CustomField_' . date('YmdHis');
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
