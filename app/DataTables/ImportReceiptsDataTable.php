<?php

namespace App\DataTables;

use App\Models\TempReceipt;
use App\Models\TempSalePlan;
use App\Models\TempSalePlanInstallment;
use App\Models\TempSalesPlanAdditionalCost;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportReceiptsDataTable extends DataTable
{
    public function __construct($site_id)
    {
        $model = new TempReceipt();
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
            ->editColumn('mode_of_payment', function ($data) {
                $values = ['cash' => 'Cash', 'cheque' => 'Cheque', 'online' => 'Online', 'other' => 'Other'];
                return view(
                    'app.components.input-select-fields',
                    ['id' => $data->id, 'field' => 'mode_of_payment', 'values' => $values, 'selectedValue' => $data->mode_of_payment]
                );
            })
            ->editColumn('cheque_no', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'cheque_no', 'inputtype' => 'number', 'value' => $data->cheque_no]
                );
            })
            ->editColumn('bank_name', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'bank_name', 'inputtype' => 'text', 'value' => $data->bank_name]
                );
            })
            ->editColumn('bank_acount_number', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'bank_acount_number', 'inputtype' => 'text', 'value' => $data->bank_acount_number]
                );
            })
            ->editColumn('online_transaction_no', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'online_transaction_no', 'inputtype' => 'number', 'value' => $data->online_transaction_no]
                );
            })
            ->editColumn('transaction_date', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'transaction_date', 'inputtype' => 'text', 'value' => $data->transaction_date]
                );
            })
            ->editColumn('other_payment_mode_value', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'other_payment_mode_value', 'inputtype' => 'number', 'value' => $data->other_payment_mode_value]
                );
            })
            ->editColumn('amount', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'amount', 'inputtype' => 'number', 'value' => $data->amount]
                );
            })
            ->editColumn('installment_no', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'installment_no', 'inputtype' => 'number', 'value' => $data->installment_no]
                );
            })
            ->editColumn('image_url', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'image_url', 'inputtype' => 'number', 'value' => $data->image_url]
                );
            })
            ->editColumn('status', function ($data) {
                $values = ['active' => 'Active', 'inactive' => 'In Active', 'cancel' => 'Cancel'];
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
    public function query(TempReceipt $model): QueryBuilder
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
            Column::computed('mode_of_payment')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,
                'name' => 'mode_of_payment'
            ])->render())->searchable(true)->addClass('removeTolltip'),
            Column::computed('amount')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,
                'name' => 'amount'
            ])->render())->searchable(true)->addClass('removeTolltip'),
            Column::computed('installment_no')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,
                'name' => 'installment_no'
            ])->render())->searchable(true)->addClass('removeTolltip'),
            Column::computed('cheque_no')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,
                'name' => 'cheque_no'
            ])->render())->searchable(true)->addClass('removeTolltip'),
            Column::computed('bank_name')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,
                'name' => 'bank_name'
            ])->render())->addClass('removeTolltip'),
            Column::computed('bank_acount_number')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,
                'name' => 'bank_acount_number'
            ])->render())->addClass('removeTolltip'),
            Column::computed('online_transaction_no')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,

                'name' => 'online_transaction_no'
            ])->render())->addClass('removeTolltip'),
            Column::computed('transaction_date')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,

                'name' => 'transaction_date'
            ])->render())->addClass('removeTolltip'),
            Column::computed('other_payment_mode_value')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,

                'name' => 'other_payment_mode_value'
            ])->render())->addClass('removeTolltip'),

            Column::computed('status')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,
                'name' => 'status'
            ])->render())->addClass('removeTolltip'),
            Column::computed('image_url')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'spInstallment' => true,
                'name' => 'image_url'
            ])->render())->addClass('removeTolltip'),
        ];
    }
}
