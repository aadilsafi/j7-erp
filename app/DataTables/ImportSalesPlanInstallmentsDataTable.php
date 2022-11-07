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
            // ->editColumn('down_payment_total', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'down_payment_total', 'inputtype' => 'number', 'value' => $data->down_payment_total]
            //     );
            // })

            // ->editColumn('validity', function ($data) {
            //     return view(
            //         'app.components.unit-preview-cell',
            //         ['id' => $data->id, 'field' => 'validity', 'inputtype' => 'text', 'value' => $data->validity]
            //     );
            // })
            ->editColumn('due_date', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'due_date', 'inputtype' => 'text', 'value' => $data->due_date]
                );
            })
            ->editColumn('last_paid_at', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'last_paid_at', 'inputtype' => 'text', 'value' => $data->last_paid_at]
                );
            })
            ->editColumn('type', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'type', 'inputtype' => 'text', 'value' => $data->type]
                );
            })
            ->editColumn('label', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'label', 'inputtype' => 'text', 'value' => $data->label]
                );
            })
            ->editColumn('installment_no', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'installment_no', 'inputtype' => 'number', 'value' => $data->installment_no]
                );
            })
            ->editColumn('total_amount', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'total_amount', 'inputtype' => 'number', 'value' => $data->total_amount]
                );
            })
            ->editColumn('paid_amount', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'paid_amount', 'inputtype' => 'number', 'value' => $data->paid_amount]
                );
            })
            ->editColumn('remaining_amount', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'remaining_amount', 'inputtype' => 'number', 'value' => $data->remaining_amount]
                );
            })
            ->editColumn('status', function ($data) {
                $values = ['paid' => 'Paid', 'unpaid' => 'Un Paid', 'partially-paid' => 'Partially Paid'];
                return view(
                    'app.components.input-select-fields',
                    ['id' => $data->id, 'field' => 'status', 'values' => $values, 'selectedValue' => $data->status]
                );
            })
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
            Column::computed('type')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,
                'name' => 'type'
            ])->render())->searchable(true),
            Column::computed('label')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,
                'name' => 'label'
            ])->render())->searchable(true),
            Column::computed('due_date')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,

                'name' => 'due_date'
            ])->render())->searchable(true),
            Column::computed('installment_no')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,

                'name' => 'installment_no'
            ])->render()),
            Column::computed('total_amount')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,

                'name' => 'total_amount'
            ])->render()),
            Column::computed('paid_amount')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,

                'name' => 'paid_amount'
            ])->render()),
            Column::computed('remaining_amount')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,

                'name' => 'remaining_amount'
            ])->render()),
            Column::computed('last_paid_at')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,

                'name' => 'last_paid_at'
            ])->render())->searchable(true),
            Column::computed('status')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,

                'name' => 'status'
            ])->render()),
        ];
    }
}
