<?php

namespace App\DataTables;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Services\StakeholderInvestorDeals\investor_deals_interface;
use Barryvdh\DomPDF\Facade\Pdf;

class StakeholderInvestorsDatatable extends DataTable
{

    private $StakeholderInvestorInterface;

    public function __construct(investor_deals_interface $StakeholderInvestorInterface)
    {
        $this->StakeholderInvestorInterface = $StakeholderInvestorInterface;
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
        $editColumns = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('full_name', function ($stakeholder_investor_deal) {
                return $stakeholder_investor_deal->investor->full_name;
            })
            ->editColumn('cnic', function ($stakeholder_investor_deal) {
                return $stakeholder_investor_deal->investor->cnic;
            })
            ->editColumn('status', function ($stakeholder_investor_deal) {
                if($stakeholder_investor_deal->status == "pending"){
                    return '<span class="badge badge-glow bg-warning">Pending</span>';
                }
                else if($stakeholder_investor_deal->status == "approved"){
                    return '<span class="badge badge-glow bg-success">Approved</span>';
                }

            })
            ->editColumn('contact', function ($stakeholder_investor_deal) {
                if ($stakeholder_investor_deal->investor->stakeholder_as == 'i') {
                    return $stakeholder_investor_deal->investor->mobile_contact;
                } else {
                    return $stakeholder_investor_deal->investor->office_contact;
                }
            })
            ->editColumn('total_received_amount', function ($stakeholder_investor_deal) {
                return  number_format((float)$stakeholder_investor_deal->total_received_amount, 2);
            })
            ->editColumn('total_payable_amount', function ($stakeholder_investor_deal) {
                if(isset($stakeholder_investor_deal->total_payable_amount)){
                    return  number_format((float)$stakeholder_investor_deal->total_payable_amount, 2);
                }
                else{
                    return '-';
                }
            })
            ->editColumn('created_date', function ($stakeholder_investor_deal) {
                return editDateColumn($stakeholder_investor_deal->created_date);
            })
            ->editColumn('updated_at', function ($stakeholder_investor_deal) {
                return editDateColumn($stakeholder_investor_deal->updated_at);
            })
            ->editColumn('check', function ($stakeholder_investor_deal) {
                return $stakeholder_investor_deal;
            })
            ->setRowId('id')

            ->rawColumns(array_merge($columns, ['action', 'check']))
            ->editColumn('residential_country_id', function ($stakeholder_investor_deal) {
                return  $stakeholder_investor_deal->residentialCountry != null ? $stakeholder_investor_deal->residentialCountry->name : '-';
            })
            ->editColumn('residential_city_id', function ($stakeholder_investor_deal) {
                return  $stakeholder_investor_deal->residentialCity != null ? $stakeholder_investor_deal->residentialCity->name : '-';
            })
            ->editColumn('actions', function ($stakeholder_investor_deal) {
                return view('app.sites.stakeholder-investors.actions', ['site_id' => $this->site_id, 'id' => $stakeholder_investor_deal->id, 'status' => $stakeholder_investor_deal->status]);
            });



        return $editColumns;
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): QueryBuilder
    {
        return $this->StakeholderInvestorInterface->model()->newQuery()->with(['investor', 'user'])->where('site_id', decryptParams($this->site_id))->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        $createPermission = Auth::user()->hasPermissionTo('sites.investors-deals.create');
        $selectedDeletePermission = 0;

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
            $addbutton = Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i class="bi bi-plus"></i> Add New')->attr([
                    'onclick' => 'addNew()',
                ]);

            array_unshift($buttons, $addbutton);
        }


            // $buttons[] = Button::raw('delete-selected')
            //     ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
            //     ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')->attr([
            //         'onclick' => 'deleteSelected()',
            //     ]);


        return $this->builder()
            ->setTableId('stakeholder-table')
            ->addTableClass(['table-hover'])
            ->columns($this->getColumns())
            ->scrollX(true)
            ->minifiedAjax()
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([30, 50, 70, 100, 200, 500, 1000])
            // ->rowGroupDataSrc('satkeholderAs')
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
            ->orders([
                // [8, 'desc'],
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
            Column::make('serial_number')->title('Sr#')->addClass('text-nowrap'),
            Column::make('doc_no')->title('Document#')->addClass('text-nowrap'),
            Column::computed('full_name')->name('investor.full_name')->title('Investor Name')->searchable(true)->orderable(true),
            Column::computed('cnic')->name('investor.cnic')->title('Identity No #')->addClass('text-nowrap')->searchable(true)->orderable(true),
            Column::computed('contact')->name('investor.mobile_contact')->title('Contact')->searchable(false)->orderable(false),
            Column::make('total_received_amount')->title('Amount Receivable')->addClass('text-nowrap'),
            Column::make('total_payable_amount')->title('Amount Payable')->addClass('text-nowrap'),
            Column::make('status')->title('Status')->addClass('text-nowrap'),
        ];

        $columns[] = Column::make('created_date')->addClass('text-nowrap');
        // $columns[] = Column::make('updated_at')->addClass('text-nowrap');
        $columns[] = Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center');


        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'investors-deals_' . date('YmdHis');
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
