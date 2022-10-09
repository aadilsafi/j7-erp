<?php

namespace App\DataTables;

use App\Models\Unit;
use App\Models\SalesPlan;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SalesPlanTemplate;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;


class RecoverySalesPlanDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $data  = [
            0 => ['Pending', 'bg-light-warning'],
            1 => ['Approved', 'bg-light-success'],
            2 => ['Disapproved', 'bg-light-danger'],
            3 => ['Cancelled', 'bg-light-danger'],
        ];
        $columns = array_column($this->getColumns(), 'data');
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('floor_unit_number', function ($salesPlan) {
                return $salesPlan->unit->floor_unit_number;
            })
            ->editColumn('unit_price', function ($salesPlan) {
                return $salesPlan->unit_price > 0 ? number_format($salesPlan->unit_price) : '-';
            })
            ->editColumn('total_price', function ($salesPlan) {
                return $salesPlan->total_price > 0 ? number_format($salesPlan->total_price) : '-';
            })
            ->editColumn('discount_percentage', function ($salesPlan) {
                return $salesPlan->discount_percentage > 0 ? number_format($salesPlan->discount_percentage) : '-';
            })
            ->editColumn('discount_total', function ($salesPlan) {
                return $salesPlan->discount_total > 0 ? number_format($salesPlan->discount_total) : '-';
            })
            ->editColumn('down_payment_percentage', function ($salesPlan) {
                return $salesPlan->down_payment_percentage > 0 ? number_format($salesPlan->down_payment_percentage) : '-';
            })
            ->editColumn('down_payment_total', function ($salesPlan) {
                return $salesPlan->down_payment_total > 0 ? number_format($salesPlan->down_payment_total) : '-';
            })
            ->editColumn('down_payment_total', function ($salesPlan) {
                return $salesPlan->down_payment_total > 0 ? number_format($salesPlan->down_payment_total) : '-';
            })
            ->editColumn('lead_source_id', function ($salesPlan) {
                return $salesPlan->leadSource->name;
            })
            ->editColumn('status', function ($salesPlan) use ($data) {
                return '<span class="badge badge-pill ' . $data[$salesPlan->status][1] . '">' . $data[$salesPlan->status][0] . '</span>';
            })
            ->editColumn('approved_date', function ($salesPlan) {
                return editDateColumn($salesPlan->approved_date);
            })
            ->editColumn('created_at', function ($salesPlan) {
                return editDateColumn($salesPlan->created_at);
            })
            ->editColumn('updated_at', function ($salesPlan) {
                return editDateColumn($salesPlan->updated_at);
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SalesPlanDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SalesPlan $model): QueryBuilder
    {
        return $model->newQuery()->with(['unit', 'stakeholder', 'additionalCosts', 'installments', 'leadSource', 'receipts',])->select('sales_plans.*')->where(['status' => 1]);
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
            ->addTableClass(['table-hover'])
            ->setTableId('sales-plan-table')
            ->columns($this->getColumns())
            ->deferRender()
            ->scrollX()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
            ->rowGroupDataSrc('salesplanstatus')
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
        $columns = [
            // Column::make('DT_RowIndex')->title('#'),
            Column::make('user_id')->title('Sales Person'),
            Column::make('floor_unit_number')->name('unit.floor_unit_number')->addClass('text-nowrap'),
            Column::make('unit_price')->addClass('text-nowrap'),
            Column::make('total_price')->addClass('text-nowrap'),
            Column::make('discount_percentage')->addClass('text-nowrap'),
            Column::make('discount_total')->addClass('text-nowrap'),
            Column::make('down_payment_percentage')->addClass('text-nowrap'),
            Column::make('down_payment_total')->addClass('text-nowrap'),
            Column::make('lead_source_id')->addClass('text-nowrap'),
            Column::make('status')->addClass('text-nowrap'),
            Column::make('approved_date')->addClass('text-nowrap'),
            Column::make('created_at')->title('Created At')->addClass('text-nowrap'),
            Column::make('updated_at')->title('Created At')->addClass('text-nowrap'),
        ];

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'SalesPlan_' . date('YmdHis');
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
