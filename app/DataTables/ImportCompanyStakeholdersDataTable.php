<?php

namespace App\DataTables;

use App\Models\Country;
use App\Models\TempCompanyStakeholder;
use App\Models\TempFloor;
use App\Models\TempStakeholder;
use Doctrine\DBAL\Schema\ColumnDiff;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportCompanyStakeholdersDataTable extends DataTable
{
    public function __construct($site_id)
    {
        $model = new TempCompanyStakeholder();
        if ($model->count() == 0) {
            return redirect()->route('sites.stakeholders.index', ['site_id' => decryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
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
            ->editColumn('full_name', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'full_name', 'inputtype' => 'text', 'value' => $data->full_name]
                );
            })
            ->editColumn('father_name', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'father_name', 'inputtype' => 'text', 'value' => $data->father_name]
                );
            })
            ->editColumn('occupation', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'occupation', 'inputtype' => 'text', 'value' => $data->occupation]
                );
            })
            ->editColumn('designation', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'designation', 'inputtype' => 'text', 'value' => $data->designation]
                );
            })
            ->editColumn('cnic', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'cnic', 'inputtype' => 'number', 'value' => $data->cnic]
                );
            })
            ->editColumn('ntn', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'ntn', 'inputtype' => 'number', 'value' => $data->ntn]
                );
            })
            ->editColumn('contact', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'ntn', 'inputtype' => 'number', 'value' => $data->ntn]
                );
            })
            ->editColumn('address', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'address', 'inputtype' => 'text', 'value' => $data->address]
                );
            })
            ->editColumn('comments', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'comments', 'inputtype' => 'text', 'value' => $data->comments]
                );
            })
            ->editColumn('is_dealer', function ($data) {
                $values = ['FALSE' => 'No', 'TRUE' => 'Yes'];

                return view(
                    'app.components.input-select-fields',
                    ['id' => $data->id, 'field' => 'is_dealer', 'values' => $values, 'selectedValue' => $data->is_dealer]
                );
                // return view(
                //     'app.components.checkbox',
                //     ['id' => $data->id, 'data' => $data, 'field' => 'is_dealer', 'is_true' => $data->is_dealer, 'value' => $data->is_dealer]
                // );
            })
            ->editColumn('country', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'country', 'inputtype' => 'text', 'value' => $data->country]
                );
            })
            ->editColumn('state', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'state', 'inputtype' => 'text', 'value' => $data->state]
                );
            })
            ->editColumn('city', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'city', 'inputtype' => 'text', 'value' => $data->city]
                );
            })
            ->editColumn('nationality', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'nationality', 'inputtype' => 'text', 'value' => $data->nationality]
                );
            })
            ->editColumn('optional_contact_number', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'optional_contact_number', 'inputtype' => 'text', 'value' => json_decode($data->optional_contact_number)]
                );
            })
            ->editColumn('is_vendor', function ($data) {
                $values = ['FALSE' => 'No', 'TRUE' => 'Yes'];

                return view(
                    'app.components.input-select-fields',
                    ['id' => $data->id, 'field' => 'is_vendor', 'values' => $values, 'selectedValue' => $data->is_vendor]
                );
            })
            ->editColumn('is_customer', function ($data) {
                $values = ['FALSE' => 'No', 'TRUE' => 'Yes'];

                return view(
                    'app.components.input-select-fields',
                    ['id' => $data->id, 'field' => 'is_customer', 'values' => $values, 'selectedValue' => $data->is_customer]
                );
            })
            ->editColumn('is_kin', function ($data) {
                $values = ['FALSE' => 'No', 'TRUE' => 'Yes'];

                return view(
                    'app.components.input-select-fields',
                    ['id' => $data->id, 'field' => 'is_kin', 'values' => $values, 'selectedValue' => $data->is_kin]
                );
            })
            ->editColumn('parent_cnic', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'parent_cnic', 'inputtype' => 'text', 'value' => json_decode($data->parent_cnic)]
                );
            })
            ->editColumn('relation', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'relation', 'inputtype' => 'text', 'value' => $data->relation]
                );
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TempCompanyStakeholder $model): QueryBuilder
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
            ->searching(false)
            ->serverSide()
            ->processing()
            ->deferRender()
            ->scrollX(true)
            ->lengthMenu([20, 50, 100, 500, 1000]);
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
            Column::computed('company_name')->title('Company Name')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('registration')->title('Rgistration No')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('industry')->title('Industry')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('strn')->title('STRN')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('ntn')->title('NTN')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('origin')->title('Origin')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('email')->title('Email')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('office_email')->title('Office Email')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('mobile_contact')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('office_contact')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('website')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('parent_company')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('residential_country')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('residential_state')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('residential_city')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('residential_postal_code')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('residential_address_type')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('residential_address')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('mailing_country')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('mailing_state')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('mailing_city')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('mailing_postal_code')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('mailing_address_type')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('mailing_address')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('comments')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('is_dealer')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('is_vendor')->addClass('removeTolltip')->addClass('text-nowrap'),
            Column::computed('is_customer')->addClass('removeTolltip')->addClass('text-nowrap'),
        ];
    }
}
