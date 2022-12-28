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
use App\Services\Stakeholder\Interface\StakeholderInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class StakeholderDataTable extends DataTable
{

    private $stakeholderInterface;

    public function __construct(StakeholderInterface $stakeholderInterface)
    {
        $this->stakeholderInterface = $stakeholderInterface;
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
          
            ->editColumn('cnic', function ($stakeholder) {
                return cnicFormat($stakeholder->cnic);
            })
            ->editColumn('contact', function ($stakeholder) {
                if ($stakeholder->stakeholder_as == 'i') {
                    return $stakeholder->mobile_contact;
                } else {
                    return $stakeholder->office_contact;
                }
            })
            ->editColumn('nationality', function ($stakeholder) {
                return  $stakeholder->nationalityCountry->name;
            })
            ->editColumn('created_at', function ($stakeholder) {
                return editDateColumn($stakeholder->created_at);
            })
            ->editColumn('updated_at', function ($stakeholder) {
                return editDateColumn($stakeholder->updated_at);
            })
            ->editColumn('actions', function ($stakeholder) {
                return view('app.sites.stakeholders.actions', ['site_id' => decryptParams($this->site_id), 'id' => $stakeholder->id]);
            })
            ->editColumn('check', function ($stakeholder) {
                return $stakeholder;
            })
            ->editColumn('satkeholderAs', function ($stakeholder) {
                if ($stakeholder->stakeholder_as == "i") {
                    return 'Individual';
                } elseif ($stakeholder->stakeholder_as == "c") {
                    return 'Company';
                }
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']))
            ->editColumn('residential_country_id', function ($stakeholder) {
                return  $stakeholder->residentialCountry != null ? $stakeholder->residentialCountry->name : '-';
            })
            ->editColumn('residential_city_id', function ($stakeholder) {
                return  $stakeholder->residentialCity != null ? $stakeholder->residentialCity->name : '-';
            });

        if (count($this->customFields) > 0) {
            foreach ($this->customFields as $customfields) {
                $editColumns->addColumn($customfields->slug, function ($data) use ($customfields) {
                    $val = $customfields->CustomFieldValue->where('modelable_id', $data->id)->first();
                    if ($val) {
                        return Str::title($val->value);
                    } else {
                        return '-';
                    }
                });
            }
        }

        return $editColumns;
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): QueryBuilder
    {
        return $this->stakeholderInterface->model()->newQuery()->with(['residentialCity', 'residentialCountry'])->where('site_id', decryptParams($this->site_id));
    }

    public function html(): HtmlBuilder
    {
        $createPermission = Auth::user()->hasPermissionTo('sites.stakeholders.create');
        $selectedDeletePermission = Auth::user()->hasPermissionTo('sites.stakeholders.destroy-selected');
        $importPermission = Auth::user()->can('sites.stakeholders.importStakeholders');
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

        if ($importPermission) {
            $importbutton = Button::raw('import')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i data-feather="upload"></i> Import Stakeholders')
                ->attr([
                    'onclick' => 'Import()',
                ]);
            array_unshift($buttons, $importbutton);
        }
        if ($createPermission) {
            $addbutton = Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light')
                ->text('<i class="bi bi-plus"></i> Add New')->attr([
                    'onclick' => 'addNew()',
                ]);

            array_unshift($buttons, $addbutton);
        }

        if ($selectedDeletePermission) {
            $buttons[] = Button::raw('delete-selected')
                ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
                ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')->attr([
                    'onclick' => 'deleteSelected()',
                ]);
        }

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
            ->lengthMenu([20, 30, 50, 70, 100, 200, 500, 1000])
            ->rowGroupDataSrc('satkeholderAs')
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons($buttons)
            ->orders([
                [8, 'desc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $editPermission = Auth::user()->hasPermissionTo('sites.stakeholders.edit');
        $selectedDeletePermission = Auth::user()->hasPermissionTo('sites.stakeholders.destroy-selected');
        $selectedDeletePermission = 0;

        $columns = [
            Column::computed('DT_RowIndex')->title('#'),
            Column::make('full_name')->title('Name'),
            // Column::make('father_name')->title('Father / Husband Name')->addClass('text-nowrap'),
            Column::make('cnic')->title('CNIC / REGISTRATION #'),
            Column::computed('contact')->title('Contact'),
            Column::computed('residential_city_id')->name('residentialCity.name')->title('City')->addClass('text-nowrap')->searchable(true)->orderable(true),
            Column::computed('residential_country_id')->name('residentialCountry.name')->title('Country')->searchable(true)->orderable(true),
            Column::make('nationality')->title('Nationality')->orderable(true),
            Column::computed('satkeholderAs')->visible(false),
        ];
        if (count($this->customFields) > 0) {
            foreach ($this->customFields as $customfields) {
                $columns[] = Column::computed($customfields->slug)->addClass('text-nowrap')->title($customfields->name);
            }
        }
        $columns[] = Column::make('created_at')->addClass('text-nowrap');
        $columns[] = Column::make('updated_at')->addClass('text-nowrap');

        if ($selectedDeletePermission) {
            $checkColumn = Column::computed('check')->exportable(false)->printable(false)->width(60)->addClass('text-center');
            array_unshift($columns, $checkColumn);
        }

        if ($editPermission) {
            $columns[] = Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center');
        }

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Stakeholders_' . date('YmdHis');
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
