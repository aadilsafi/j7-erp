<?php

namespace App\DataTables;

use App\Models\Stakeholder;
use App\Models\Unit;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerUnitsDataTable extends DataTable
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
            ->editColumn('status_id', function ($unit) {
                return editBadgeColumn($unit->status->name);
            })
            ->editColumn('full_name', function ($unit) {
                return $unit->salesPlan[0]['stakeholder']['full_name'];
            })
            ->editColumn('father_name', function ($unit) {
                return $unit->salesPlan[0]['stakeholder']['father_name'];
            })
            ->editColumn('cnic', function ($unit) {
                return cnicFormat($unit->salesPlan[0]['stakeholder']['cnic']);
            })
            ->editColumn('contact', function ($unit) {
                return $unit->salesPlan[0]['stakeholder']['contact'];
            })
            ->editColumn('type_id', function ($unit) {
                return $unit->type->name;
            })
            ->editColumn('created_at', function ($unit) {
                return editDateColumn($unit->created_at);
            })
            ->editColumn('updated_at', function ($unit) {
                return editDateColumn($unit->updated_at);
            })
            ->editColumn('actions', function ($unit) {
                return view('app.sites.file-managements.customers.units.actions', ['site_id' => $this->site_id, 'customer_id' => $unit->salesPlan[0]['stakeholder']['id'], 'unit_id' => $unit->id]);
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
        return $model->newQuery()->select('units.*')->with(['type', 'status' ,'salesPlan'])->whereIn('id', $this->unit_ids);
    }

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
            ->addTableClass(['table-striped', 'table-hover'])
            ->setTableId('floors-units-table')
            ->columns($this->getColumns())
            ->deferRender()
            ->scrollX()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
            ->rowGroupDataSrc('type_id')
            ->orders([
                [5, 'desc'],
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
            Column::make('floor_unit_number')->title('Unit Number')->addClass('text-nowrap'),
            Column::make('name')->title('Units'),
            Column::make('type_id')->name('type.name')->title('Type'),
            Column::make('status_id')->name('status.name')->title('Status')->addClass('text-center'),
            Column::computed('full_name')->name('salesPlan.stakeholder.full_name')->title('Full Name')->addClass('text-center text-nowrap'),
            Column::computed('father_name')->name('salesPlan.stakeholder.father_name')->title('FATHER NAME')->addClass('text-center text-nowrap'),
            Column::computed('cnic')->name('salesPlan.stakeholder.cnic')->title('CNIC')->addClass('text-center text-nowrap'),
            Column::computed('contact')->name('salesPlan.stakeholder.contact')->title('CONTACT')->addClass('text-center text-nowrap'),
            // Column::make('created_at')->addClass('text-nowrap'),
            // Column::make('updated_at'),
            Column::computed('actions')->exportable(false)->printable(false)->addClass('text-center text-nowrap')->width(60),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Customers_' . date('YmdHis');
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
