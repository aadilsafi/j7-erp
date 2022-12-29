<?php

namespace App\DataTables;

use App\Models\PaymentVocuher;
use App\Models\payment_voucher;
use App\Services\payment_voucher\Interface\payment_voucherInterface;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Constraint\Count;

class PaymentVoucherDatatable extends DataTable
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
            ->editColumn('check', function ($payment_voucher) {
                return $payment_voucher;
            })
            ->editColumn('created_at', function ($payment_voucher) {
                return editDateColumn($payment_voucher->created_at);
            })
            ->editColumn('updated_at', function ($payment_voucher) {
                return editDateColumn($payment_voucher->updated_at);
            })
            ->editColumn('amount_to_be_paid', function ($payment_voucher) {
                return number_format($payment_voucher->amount_to_be_paid);
            })
            ->editColumn('status', function ($payment_voucher) {
                // $approvePermission =  Auth::user()->hasPermissionTo('sites.file-managements.rebate-incentive.approve');
                $status = $payment_voucher->status == 1 ? '<span class="badge badge-glow bg-success">Active</span>' : '<span class="badge badge-glow bg-warning">InActive</span>';
                if ($payment_voucher->status == 0) {
                    $status .= '  <a onClick="ApproveModal( {{ encryptParams($payment_voucher->id) }})" id="approveID" payment_voucher_id="' . encryptParams($payment_voucher->id) . '" class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Approve'. $payment_voucher->id . '"
                    href="#" >
                    <i class="bi bi-check" style="font-size: 1.1rem" class="m-10"></i>
                </a>';
                }
                return $status;
            })

            ->editColumn('cheque_status', function ($payment_voucher) {
                // $approvePermission =  Auth::user()->hasPermissionTo('sites.file-managements.rebate-incentive.approve');
                $status = $payment_voucher->cheque_status == 1 ? '<span class="badge badge-glow bg-success">Active</span>' : '<span class="badge badge-glow bg-warning">InActive</span>';
                if ($payment_voucher->payment_mode == "Cheque" && $payment_voucher->cheque_status == 0 && $payment_voucher->status == 1) {
                    $status .= '  <a onClick="ActiveCheque({{ encryptParams($payment_voucher->id) }})" id="approveID" payment_voucher_id="' . encryptParams($payment_voucher->id) . '" class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Active Cheque"
                    href="#" >
                    <i class="bi bi-check" style="font-size: 1.1rem" class="m-10"></i>
                </a>';
                }
                if ($payment_voucher->payment_mode == "Cheque") {
                    return $status;
                } else {
                    return  '-';
                }
            })

            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));

        return $editColumns;
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PaymentVocuher $model): QueryBuilder
    {
        return $model->newQuery()->where('site_id', decryptParams($this->site_id));
    }

    public function html(): HtmlBuilder
    {
        $createPermission =  Auth::user()->hasPermissionTo('sites.payment-voucher.create');
        // $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.payment_vouchers.destroy-selected');

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

        if ($createPermission) {
            $newButton = Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i class="bi bi-plus"></i> Add New')->attr([
                    'onclick' => 'addNew()',
                ]);
            array_unshift($buttons, $newButton);
        }

        // if ($selectedDeletePermission) {
        // $buttons[] = Button::raw('delete-selected')
        //     ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
        //     ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')->attr([
        //         'onclick' => 'deleteSelected()',
        //     ]);
        // }

        return $this->builder()
            ->setTableId('payment_voucher-table')
            ->addTableClass(['table-hover'])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->scrollX(true)
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([10, 20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
            ->rowGroupDataSrc('name')
            ->columnDefs([
                // [
                //     'targets' => 0,
                //     'className' => 'text-center text-primary',
                //     'width' => '10%',
                //     'orderable' => false,
                //     'searchable' => false,
                //     'responsivePriority' => 3,
                //     'render' => "function (data, type, full, setting) {
                //         var role = JSON.parse(data);
                //         return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" onchange=\"changeTableRowColor(this)\" type=\"checkbox\" value=\"' + role.id + '\" name=\"chkpayment_vouchers[]\" id=\"chkpayment_vouchers_' + role.id + '\" /><label class=\"form-check-label\" for=\"chkpayment_vouchers_' + role.id + '\"></label></div>';
                //     }",
                //     'checkboxes' => [
                //         'selectAllRender' =>  '<div class="form-check"> <input class="form-check-input" onchange="changeAllTableRowColor()" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
                //     ]
                // ],
            ])
            ->orders([
                [2, 'asc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        // $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.payment_vouchers.destroy-selected');
        $columns = [
            // Column::computed('check')->exportable(false)->printable(false)->width(60),
            Column::make('serial_no')->title('Serial Number')->addClass('text-nowrap'),
            Column::make('name')->title('Name')->addClass('text-nowrap'),
            Column::make('identity_number')->title('Identity Number')->addClass('text-nowrap'),
            Column::make('account_payable')->title('Account Payable')->addClass('text-nowrap'),
            Column::make('amount_to_be_paid')->title('Paid Amount')->addClass('text-nowrap'),
            Column::make('payment_mode')->title('Payment Mode')->addClass('text-nowrap'),
            Column::make('cheque_status')->title('Cheque Status')->addClass('text-nowrap'),
            Column::make('status')->title('Voucher Status')->addClass('text-nowrap'),
        ];

        $columns[] = Column::make('created_at')->title('Created At')->addClass('text-nowrap');
        $columns[] = Column::make('updated_at')->title('Updated At')->addClass('text-nowrap');


        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'payment_vouchers_' . date('YmdHis');
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
