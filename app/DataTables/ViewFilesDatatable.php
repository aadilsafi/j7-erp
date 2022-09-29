<?php

namespace App\DataTables;

use App\Models\FileManagement;
use App\Models\Stakeholder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewFilesDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $columns = array_column($this->getColumns(), 'data');
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('father_name', function ($fileManagement) {
                return strlen($fileManagement->stakeholder->father_name) > 0 ? $fileManagement->stakeholder->father_name : '-';
            })
            ->editColumn('full_name', function ($fileManagement) {
                return strlen($fileManagement->stakeholder->full_name) > 0 ? $fileManagement->stakeholder->full_name : '-';
            })
            ->editColumn('unit_no', function ($fileManagement) {
                return strlen($fileManagement->unit->floor_unit_number) > 0 ? $fileManagement->unit->floor_unit_number : '-';
            })
            ->editColumn('unit_name', function ($fileManagement) {
                return strlen($fileManagement->unit->name) > 0 ? $fileManagement->unit->name : '-';
            })

            ->editColumn('cnic', function ($fileManagement) {
                return strlen($fileManagement->stakeholder->cnic) > 0 ? cnicFormat($fileManagement->stakeholder->cnic) : '-';
            })
            ->editColumn('contact', function ($fileManagement) {
                return strlen($fileManagement->stakeholder->contact) > 0 ? $fileManagement->stakeholder->contact : '-';
            })
            ->editColumn('created_at', function ($fileManagement) {
                return editDateColumn($fileManagement->created_at);
            })
            ->editColumn('updated_at', function ($fileManagement) {
                return editDateColumn($fileManagement->updated_at);
            })
            // ->editColumn('actions', function ($fileManagement) {
            //     return view('app.sites.file-managements.customers.actions', ['site_id' => $this->site_id, 'customer_id' => $fileManagement->id]);
            // })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Stakeholder $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(FileManagement $model): QueryBuilder
    {
        return $model->newQuery()->where('site_id', $this->site_id);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
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

        return $this->builder()
            ->addIndex()
            ->addTableClass(['table-hover', 'table-striped'])
            ->setTableId('file-management-table')
            ->columns($this->getColumns())
            ->deferRender()
            ->scrollX()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
            ->orders([
                [3, 'desc'],
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
            Column::computed('DT_RowIndex')->title('#'),
            Column::computed('unit_no')->name('unit.floor_unit_number')->addClass('text-nowrap'),
            Column::computed('unit_name')->name('unit.name')->addClass('text-nowrap'),
            Column::computed('full_name')->name('stakeholder.full_name')->addClass('text-nowrap'),
            Column::computed('father_name')->name('stakeholder.father_name')->addClass('text-nowrap'),
            Column::computed('cnic')->name('stakeholder.cnic'),
            Column::computed('contact')->name('stakeholder.contact'),
            Column::computed('created_at')->title('Created At')->addClass('text-nowrap'),
            Column::computed('updated_at')->title('Updated At')->addClass('text-nowrap'),
            // Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Customers_Files_' . date('YmdHis');
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
