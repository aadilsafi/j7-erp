<?php

namespace App\DataTables;

use App\Models\TempSalePlan;
use App\Models\TempSalePlanInstallment;
use App\Models\TempSalesPlanAdditionalCost;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportSalesPlanInstallmentsDataTable extends DataTable
{
    public function __construct($site_id)
    {
        $model = new TempSalePlanInstallment();
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
            // ->editColumn('due_date', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'due_date', 'inputtype' => 'text', 'value' => $data->due_date]
            //     );
            // })
            // ->editColumn('type', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'type', 'inputtype' => 'text', 'value' => $data->type]
            //     );
            // })
            // ->editColumn('label', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'label', 'inputtype' => 'text', 'value' => $data->label]
            //     );
            // })
            // ->editColumn('installment_no', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'installment_no', 'inputtype' => 'number', 'value' => $data->installment_no]
            //     );
            // })
            // ->editColumn('total_amount', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'total_amount', 'inputtype' => 'number', 'value' => $data->total_amount]
            //     );
            // })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TempSalePlanInstallment $model): QueryBuilder
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
            Column::computed('unit_short_label')->title('Unit')->addClass('text-nowrap')->searchable(true),
            Column::computed('stakeholder_cnic')->title('CNIC')->searchable(true),
            Column::computed('total_price')->title('Price')->searchable(true),
            Column::computed('down_payment_total')->title('DP Price')->addClass('text-nowrap'),
            Column::computed('validity')->title('Validity')->addClass('text-nowrap'),
            Column::computed('type')->addClass('removeTolltip')->searchable(true),
            Column::computed('label')->searchable(true)->addClass('removeTolltip'),
            Column::computed('due_date')->searchable(true)->addClass('removeTolltip'),
            Column::computed('installment_no')->addClass('removeTolltip'),
            Column::computed('total_amount')->addClass('removeTolltip'),
            Column::computed('due_date')->searchable(true)->addClass('removeTolltip'),
        ];
    }
}
