<?php

namespace App\DataTables;

use App\Models\TempSalePlan;
use App\Models\TempSalesPlanAdditionalCost;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportSalesPlanAdCostsDataTable extends DataTable
{
    public function __construct($site_id)
    {
        $model = new TempSalesPlanAdditionalCost();
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
            ->editColumn('additional_costs_name', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'additional_costs_name', 'inputtype' => 'text', 'value' => $data->additional_costs_name]
                );
            })
            ->editColumn('percentage', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'percentage', 'inputtype' => 'number', 'value' => $data->percentage]
                );
            })
            ->editColumn('total_amount', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'total_amount', 'inputtype' => 'number', 'value' => $data->total_amount]
                );
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TempSalesPlanAdditionalCost $model): QueryBuilder
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
            Column::computed('unit_short_label')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'name' => 'unit_short_label'
            ])->render())->searchable(true),
            Column::computed('stakeholder_cnic')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'name' => 'stakeholder_cnic'
            ])->render())->searchable(true),
            Column::computed('total_price')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'name' => 'total_price'
            ])->render())->searchable(true),
            Column::computed('down_payment_total')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'name' => 'down_payment_total'
            ])->render()),
            Column::computed('validity')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'name' => 'validity'
            ])->render()),
            Column::computed('additional_costs_name')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'name' => 'additional_costs_name'
            ])->render())->searchable(true),
            Column::computed('percentage')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'name' => 'percentage'
            ])->render()),

            Column::computed('total_amount')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'name' => 'total_amount'
            ])->render()),
        ];
    }
}
