<?php

namespace App\DataTables;

use App\Models\Stakeholder;
use App\Models\FileManagement;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

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
            ->editColumn('floor_unit_number', function ($fileManagement) {
                return strlen($fileManagement->unit->floor_unit_number) > 0 ? $fileManagement->unit->floor_unit_number : '-';
            })
            ->editColumn('unit_name', function ($fileManagement) {
                return strlen($fileManagement->unit->name) > 0 ? $fileManagement->unit->name : '-';
            })
            ->editColumn('unit_type', function ($fileManagement) {
                return strlen($fileManagement->unit->type->name) > 0 ? $fileManagement->unit->type->name : '-';
            })
            ->editColumn('unit_status', function ($fileManagement) {
                return editBadgeColumn($fileManagement->unit->status->name);
            })
            ->editColumn('stakeholder_full_name', function ($fileManagement) {
                return strlen($fileManagement->stakeholder->full_name) > 0 ? $fileManagement->stakeholder->full_name : '-';
            })
            ->editColumn('stakeholder_father_name', function ($fileManagement) {
                return strlen($fileManagement->stakeholder->father_name) > 0 ? $fileManagement->stakeholder->father_name : '-';
            })
            ->editColumn('stakeholder_cnic', function ($fileManagement) {
                return strlen($fileManagement->stakeholder->cnic) > 0 ? cnicFormat($fileManagement->stakeholder->cnic) : '-';
            })
            ->editColumn('stakeholder_contact', function ($fileManagement) {
                return strlen($fileManagement->stakeholder->contact) > 0 ? $fileManagement->stakeholder->contact : '-';
            })
            // ->editColumn('created_at', function ($fileManagement) {
            //     return editDateColumn($fileManagement->created_at);
            // })
            // ->editColumn('updated_at', function ($fileManagement) {
            //     return editDateColumn($fileManagement->updated_at);
            // })
            ->editColumn('file_status', function ($fileManagement) {
                return editBadgeColumn($fileManagement->fileAction->name);
            })
            // refund status
            ->editColumn('refund_status', function ($fileManagement) {
                if (isset($fileManagement->fileRefund[0])) {
                    if ($fileManagement->fileRefund[0]['status'] == 1) {
                        return editBadgeColumn('File Refund Request Approved');
                    } else {
                        return editBadgeColumn('Pending');
                    }
                } else {
                    return editBadgeColumn(' File Refund Request Not Found');
                }
            })
            // buy back status
            ->editColumn('buy_back_status', function ($fileManagement) {
                if (isset($fileManagement->fileBuyBack[0])) {
                    // dd($fileManagement->fileBuyBack[0]['status']);
                    if ($fileManagement->fileBuyBack[0]['status'] == 1) {
                        return editBadgeColumn('File Buy Back Request Approved');
                    } else {
                        return editBadgeColumn('Pending');
                    }
                } else {
                    return editBadgeColumn(' File Buy Back Request Not Found');
                }
            })
            // cancellation status
            ->editColumn('cancellation_status', function ($fileManagement) {
                if (isset($fileManagement->fileCancellation[0])) {
                    if ($fileManagement->fileCancellation[0]['status'] == 1) {
                        return editBadgeColumn('File Cancellation Request Approved');
                    } else {
                        return editBadgeColumn('Pending');
                    }
                } else {
                    return editBadgeColumn(' File Cancellation Request Not Found');
                }
            })
            // resale status
            ->editColumn('resale_status', function ($fileManagement) {
                if (isset($fileManagement->fileResale[0])) {
                    if ($fileManagement->fileResale[0]['status'] == 1) {
                        return editBadgeColumn('File Resale Request Approved');
                    } else {
                        return editBadgeColumn('Pending');
                    }
                } else {
                    return editBadgeColumn(' File Resale Request Not Found');
                }
            })
            // Title Transfer status
            ->editColumn('title_transfer_status', function ($fileManagement) {

                if(isset($fileManagement->fileTitleTransfer[0])){
                    $statuses = 0;
                    foreach($fileManagement->fileTitleTransfer as $fileCheck){

                        if($fileCheck->status == 1){
                            $statuses = false;
                        }
                        else{
                            $statuses = true;
                            break;
                        }
                    }

                    if($statuses == true){
                        return editBadgeColumn('Pending');
                    }
                    else{
                        return editBadgeColumn('File Title Transfer Request Approved');
                    }

                } else {
                    return editBadgeColumn(' File Title Transfer Request Not Found');
                }
            })
            // All File Actions
            ->editColumn('actions', function ($fileManagement) {
                return view('app.sites.file-managements.files.actions', ['site_id' => $this->site_id, 'customer_id' => $fileManagement->stakeholder->id, 'unit_id' => $fileManagement->unit->id,'file_id' =>$fileManagement->id]);
            })
            // Refund Actions
            ->editColumn('refund_actions', function ($fileManagement) {
                if (isset($fileManagement->fileRefund[0])) {
                    return view('app.sites.file-managements.files.files-actions.file-refund.actions', ['site_id' => $this->site_id, 'customer_id' => $fileManagement->stakeholder->id, 'unit_id' => $fileManagement->unit->id, 'file_refund_id' => $fileManagement->fileRefund[0]['id'], 'file_refund_status' => $fileManagement->fileRefund[0]['status'],]);
                } else {
                    return "-";
                }
            })
            // Buy Back Actions
            ->editColumn('buy_back_actions', function ($fileManagement) {
                if (isset($fileManagement->fileBuyBack[0])) {
                    return view('app.sites.file-managements.files.files-actions.file-buy-back.actions', ['site_id' => $this->site_id, 'customer_id' => $fileManagement->stakeholder->id, 'unit_id' => $fileManagement->unit->id, 'file_refund_id' => $fileManagement->fileBuyBack[0]['id'], 'file_refund_status' => $fileManagement->fileBuyBack[0]['status'],]);
                } else {
                    return "-";
                }
            })
            // Cancellation Actions
            ->editColumn('cancellation_actions', function ($fileManagement) {
                if (isset($fileManagement->fileCancellation[0])) {
                    return view('app.sites.file-managements.files.files-actions.file-cancellation.actions', ['site_id' => $this->site_id, 'customer_id' => $fileManagement->stakeholder->id, 'unit_id' => $fileManagement->unit->id, 'file_refund_id' => $fileManagement->fileCancellation[0]['id'], 'file_refund_status' => $fileManagement->fileCancellation[0]['status'],]);
                } else {
                    return "-";
                }
            })
            // Resale Actions
            ->editColumn('resale_actions', function ($fileManagement) {
                if (isset($fileManagement->fileResale[0])) {
                    return view('app.sites.file-managements.files.files-actions.file-resale.actions', ['site_id' => $this->site_id, 'customer_id' => $fileManagement->stakeholder->id, 'unit_id' => $fileManagement->unit->id, 'file_refund_id' => $fileManagement->fileResale[0]['id'], 'file_refund_status' => $fileManagement->fileResale[0]['status'],]);
                } else {
                    return "-";
                }
            })
            // Title Transfer Actions
            ->editColumn('title_transfer_actions', function ($fileManagement) {
                if (isset($fileManagement->fileTitleTransfer[0])) {
                    $statuses = 0;
                    $id = 0;
                    foreach($fileManagement->fileTitleTransfer as $fileCheck){

                        if($fileCheck->status == 1){
                            $statuses = false;
                        }
                        else{
                            $statuses = true;
                            $id = $fileCheck->id;
                            break;
                        }
                    }
                    return view('app.sites.file-managements.files.files-actions.file-title-transfer.actions', ['site_id' => $this->site_id, 'customer_id' => $fileManagement->stakeholder->id, 'unit_id' => $fileManagement->unit->id, 'file_refund_id' => $id, 'file_titleTransfer_status' => $statuses,]);
                } else {
                    return "-";
                }
            })
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
        if (Route::current()->getName() == 'sites.file-managements.view-files') {
            return $model->newQuery()->with('unit', 'stakeholder', 'unit.type', 'unit.status', 'fileRefund', 'fileAction', 'fileBuyBack', 'fileCancellation', 'fileResale', 'fileTitleTransfer')->where('site_id', $this->site_id);
        }
        if (Route::current()->getName() == 'sites.file-managements.file-refund.index') {
            return $model->newQuery()->with('unit', 'stakeholder', 'unit.type', 'unit.status', 'fileRefund', 'fileAction', 'fileBuyBack', 'fileCancellation', 'fileResale', 'fileTitleTransfer')->where('site_id', $this->site_id)->where('file_action_id', 1)->orWhere('file_action_id', 2);
        }
        if (Route::current()->getName() == 'sites.file-managements.file-buy-back.index') {
            return $model->newQuery()->with('unit', 'stakeholder', 'unit.type', 'unit.status', 'fileRefund', 'fileAction', 'fileBuyBack', 'fileCancellation', 'fileResale', 'fileTitleTransfer')->where('site_id', $this->site_id)->where('file_action_id', 1)->orWhere('file_action_id', 3);
        }
        if (Route::current()->getName() == 'sites.file-managements.file-cancellation.index') {
            return $model->newQuery()->with('unit', 'stakeholder', 'unit.type', 'unit.status', 'fileRefund', 'fileAction', 'fileBuyBack', 'fileCancellation', 'fileResale', 'fileTitleTransfer')->where('site_id', $this->site_id)->where('file_action_id', 1)->orWhere('file_action_id', 4);
        }
        if (Route::current()->getName() == 'sites.file-managements.file-resale.index') {
            return $model->newQuery()->with('unit', 'stakeholder', 'unit.type', 'unit.status', 'fileRefund', 'fileAction', 'fileBuyBack', 'fileCancellation', 'fileResale', 'fileTitleTransfer')->where('site_id', $this->site_id)->where('file_action_id', 1)->orWhere('file_action_id', 5);
        }
        if (Route::current()->getName() == 'sites.file-managements.file-title-transfer.index') {
            return $model->newQuery()->with('unit', 'stakeholder', 'unit.type', 'unit.status', 'fileRefund', 'fileAction', 'fileBuyBack', 'fileCancellation', 'fileResale', 'fileTitleTransfer')->where('site_id', $this->site_id)->where('file_action_id', 1)->orWhere('file_action_id', 6);
        }
        if (Route::current()->getName() == 'sites.file-managements.file-adjustment.index') {
            return $model->newQuery()->with('unit', 'stakeholder', 'unit.type', 'unit.status', 'fileRefund', 'fileAction', 'fileBuyBack', 'fileCancellation', 'fileResale', 'fileTitleTransfer')->where('site_id', $this->site_id)->where('file_action_id', 1)->orWhere('file_action_id', 7);
        }
        if (Route::current()->getName() == 'sites.file-managements.unit-shifting.index') {
            return $model->newQuery()->with('unit', 'stakeholder', 'unit.type', 'unit.status', 'fileRefund', 'fileAction', 'fileBuyBack', 'fileCancellation', 'fileResale', 'fileTitleTransfer')->where('site_id', $this->site_id)->where('file_action_id', 1)->orWhere('file_action_id', 8);
        }
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
            ->buttons($buttons);
            // ->rowGroupDataSrc('file_status');
            // ->orders([
            //     [3, 'desc'],
            // ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $refundRoute = false;
        $buyBackroute = false;
        $cancelationRoute = false;
        $resaleRoute = false;
        $titleTransferRoute = false;
        if (Route::current()->getName() == "sites.file-managements.file-refund.index") {
            $refundRoute = true;
        }
        if (Route::current()->getName() == "sites.file-managements.file-buy-back.index") {
            $buyBackroute = true;
        }
        if (Route::current()->getName() == "sites.file-managements.file-cancellation.index") {
            $cancelationRoute = true;
        }
        if (Route::current()->getName() == "sites.file-managements.file-resale.index") {
            $resaleRoute = true;
        }
        if (Route::current()->getName() == "sites.file-managements.file-title-transfer.index") {
            $titleTransferRoute = true;
        }
        return [
            Column::computed('DT_RowIndex')->title('#'),
            Column::make('floor_unit_number')->name('unit.floor_unit_number')->title('Unit No')->addClass('text-nowrap'),
            Column::make('unit_name')->name('unit.name')->title('Unit Name')->addClass('text-nowrap'),
            Column::make('unit_type')->name('unit.type.name')->title('Unit Type')->addClass('text-nowrap'),
            Column::make('unit_status')->name('unit.status.name')->title('Unit Status')->addClass('text-nowrap text-center'),
            Column::make('stakeholder_full_name')->name('stakeholder.full_name')->title('Customer Name')->addClass('text-nowrap'),
            Column::make('stakeholder_father_name')->name('stakeholder.father_name')->title('Son of')->addClass('text-nowrap'),
            Column::make('stakeholder_cnic')->name('stakeholder.cnic')->title('CNIC')->addClass('text-nowrap'),
            Column::make('stakeholder_contact')->name('stakeholder.contact')->title('Contact')->addClass('text-nowrap'),
            Column::make('file_status')->name('fileAction.name')->title('File Action Status')->addClass('text-nowrap text-center'),
            // // Column::computed('created_at')->title('Created At')->addClass('text-nowrap'),
            // // Column::computed('updated_at')->title('Updated At')->addClass('text-nowrap'),
            // Refund Actions
            ($refundRoute ?
                Column::computed('refund_status')->title('Refund File Status')->exportable(false)->printable(false)->width(60)->addClass('text-center text-nowrap')
                :
                Column::computed('refund_status')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            ),
            ($refundRoute ?
                Column::computed('refund_actions')->title('Refund Actions')->exportable(false)->printable(false)->width(60)->addClass('text-center text-nowrap')
                :
                Column::computed('refund_actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            ),

            // // Buy Back Actions
            ($buyBackroute ?
                Column::computed('buy_back_status')->title('Buy Back File Status')->exportable(false)->printable(false)->width(60)->addClass('text-center text-nowrap')
                :
                Column::computed('buy_back_status')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            ),
            ($buyBackroute ?
                Column::computed('buy_back_actions')->title('Buy Back Actions')->exportable(false)->printable(false)->width(60)->addClass('text-center text-nowrap')
                :
                Column::computed('buy_back_actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            ),

            // Cancellation Actions
            ($cancelationRoute ?
                Column::computed('cancellation_status')->title('File Cancellation Status')->exportable(false)->printable(false)->width(60)->addClass('text-center text-nowrap')
                :
                Column::computed('cancellation_status')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            ),
            ($cancelationRoute ?
                Column::computed('cancellation_actions')->title('Cancellation Actions')->exportable(false)->printable(false)->width(60)->addClass('text-center text-nowrap')
                :
                Column::computed('cancellation_actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            ),

            // Resale Actions
            ($resaleRoute ?
                Column::computed('resale_status')->title('File Resale Status')->exportable(false)->printable(false)->width(60)->addClass('text-center text-nowrap')
                :
                Column::computed('resale_status')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            ),
            ($resaleRoute ?
                Column::computed('resale_actions')->title('Resale Actions')->exportable(false)->printable(false)->width(60)->addClass('text-center text-nowrap')
                :
                Column::computed('resale_actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            ),

            // titleTransfer Actions
            ($titleTransferRoute ?
                Column::computed('title_transfer_status')->title('File Title Transfer Status')->exportable(false)->printable(false)->width(60)->addClass('text-center text-nowrap')
                :
                Column::computed('title_transfer_status')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            ),
            ($titleTransferRoute ?
                Column::computed('title_transfer_actions')->title('File Title Transfer Actions')->exportable(false)->printable(false)->width(60)->addClass('text-center text-nowrap')
                :
                Column::computed('title_transfer_actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            ),

            Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center'),
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
