<?php

namespace App\DataTables;

use App\Models\TempFloor;
use App\Models\TempStakeholder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportStakeholdersDataTable extends DataTable
{
    public function __construct($site_id)
    {
        $model = new TempStakeholder();
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
                return view(
                    'app.components.checkbox',
                    ['id' => $data->id, 'data' => $data, 'field' => 'is_dealer', 'is_true' => $data->is_dealer, 'value' => $data->is_dealer]
                );
            })
            ->editColumn('is_vendor', function ($data) {
                return view(
                    'app.components.checkbox',
                    ['id' => $data->id, 'data' => $data, 'field' => 'is_vendor', 'is_true' => $data->is_vendor, 'value' => $data->is_vendor]
                );
            })
            ->editColumn('is_customer', function ($data) {
                return view(
                    'app.components.checkbox',
                    ['id' => $data->id, 'data' => $data, 'field' => 'is_customer', 'is_true' => $data->is_customer, 'value' => $data->is_customer]
                );
            })
            ->editColumn('is_kin', function ($data) {
                return view(
                    'app.components.checkbox',
                    ['id' => $data->id, 'data' => $data, 'field' => 'is_kin', 'is_true' => $data->is_kin, 'value' => $data->is_kin]
                );
            })
            ->editColumn('parent_cnic', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'parent_cnic', 'inputtype' => 'number', 'value' => $data->parent_cnic]
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
    public function query(TempStakeholder $model): QueryBuilder
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
            Column::computed('full_name')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'full_name'
            ])->render()),
            Column::computed('father_name')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'father_name'

            ])->render()),
            Column::computed('occupation')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'occupation'
            ])->render()),
            Column::computed('designation')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'designation'
            ])->render()),
            Column::computed('cnic')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'cnic'
            ])->render()),
            Column::computed('ntn')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'ntn'
            ])->render()),
            Column::computed('contact')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'contact'
            ])->render()),
            Column::computed('address')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'address'
            ])->render()),
            Column::computed('comments')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'comments'
            ])->render()),
            Column::computed('is_dealer')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'is_dealer'
            ])->render()),
            Column::computed('is_vendor')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'is_vendor'
            ])->render()),
            Column::computed('is_customer')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'is_customer'
            ])->render()),
            Column::computed('is_kin')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'is_kin'
            ])->render()),
            Column::computed('parent_cnic')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'parent_cnic'
            ])->render()),
            Column::computed('relation')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => true,
                'name' => 'relation'
            ])->render()),
        ];
    }
}
