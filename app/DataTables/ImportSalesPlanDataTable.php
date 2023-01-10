<?php

namespace App\DataTables;

use App\Models\TempSalePlan;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportSalesPlanDataTable extends DataTable
{
    public function __construct($site_id)
    {
        $model = new TempSalePlan();
        if ($model->count() == 0) {
            return redirect()->route('sites.floors.index', ['site_id' => decryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
        }
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
            ->addIndexColumn()
            // ->editColumn('unit_short_label', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'unit_short_label', 'inputtype' => 'text', 'value' => $data->unit_short_label]
            //     );
            // })
            // ->editColumn('stakeholder_cnic', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'stakeholder_cnic', 'inputtype' => 'number', 'value' => $data->stakeholder_cnic]
            //     );
            // })
            // ->editColumn('total_price', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'total_price', 'inputtype' => 'number', 'value' => $data->total_price]
            //     );
            // })
            // ->editColumn('unit_price', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'unit_price', 'inputtype' => 'number', 'value' => $data->unit_price]
            //     );
            // })
            // ->editColumn('discount_percentage', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'discount_percentage', 'inputtype' => 'number', 'value' => $data->discount_percentage]
            //     );
            // })
            // ->editColumn('discount_total', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'discount_total', 'inputtype' => 'number', 'value' => $data->discount_total]
            //     );
            // })
            // ->editColumn('down_payment_percentage', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'down_payment_percentage', 'inputtype' => 'number', 'value' => $data->down_payment_percentage]
            //     );
            // })
            // ->editColumn('down_payment_total', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'down_payment_total', 'inputtype' => 'number', 'value' => $data->down_payment_total]
            //     );
            // })
            // ->editColumn('lead_source', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'lead_source', 'inputtype' => 'text', 'value' => $data->lead_source]
            //     );
            // })
            // ->editColumn('validity', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'validity', 'inputtype' => 'text', 'value' => $data->validity]
            //     );
            // })
            // ->editColumn('status', function ($data) {
            //     $values = ['pending' => 'Pending', 'approved' => 'Approved', 'disapproved' => 'Disapproved', 'cancelled' => 'Cancelled'];
            //     return view(
            //         'app.components.input-select-fields',
            //         ['id' => $data->id, 'field' => 'status', 'values' => $values, 'selectedValue' => $data->status]
            //     );
            // })
            // ->editColumn('comment', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'comment', 'inputtype' => 'text', 'value' => $data->comment]
            //     );
            // })
            // ->editColumn('approved_date', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'approved_date', 'inputtype' => 'text', 'value' => $data->approved_date]
            //     );
            // })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TempSalePlan $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id');
    }

    public function html(): HtmlBuilder
    {

        return $this->builder()
            ->setTableId('import-table')
            ->addTableClass(['table-hover'])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->ordering(false)
            ->searching(true)
            ->serverSide()
            ->processing()
            ->deferRender()
            ->scrollX(true)
            ->lengthMenu([50, 100, 500, 1000]);
        // ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">');
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::computed('unit_short_label')->searchable(true)->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('stakeholder_cnic')->searchable(true)->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('total_price')->searchable(true)->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('unit_price')->searchable(true)->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('discount_percentage')->searchable(true)->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('discount_total')->searchable(true)->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('down_payment_percentage')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('down_payment_total')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('lead_source')->searchable(true)->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('created_date')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('validity')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('status')->title('Status')->searchable(true)->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('comment')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('approved_date')->addClass('removeTolltip')->addClass('text-nowrap'),
        ];
    }
}
