<?php

namespace App\DataTables;

use App\Models\TempBank;
use App\Models\TempFloor;
use App\Models\TempStakeholder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportBanksDataTable extends DataTable
{
    public function __construct($site_id)
    {
        $model = new TempBank();
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
            ->editColumn('name', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'name', 'inputtype' => 'text', 'value' => $data->name]
                );
            })
            ->editColumn('account_number', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'account_number', 'inputtype' => 'number', 'value' => $data->account_number]
                );
            })
            ->editColumn('branch_code', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'branch_code', 'inputtype' => 'number', 'value' => $data->branch_code]
                );
            })
            ->editColumn('contact_number', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'contact_number', 'inputtype' => 'number', 'value' => $data->contact_number]
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
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TempBank $model): QueryBuilder
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
            Column::computed('name')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'name'
            ])->render())->addClass('removeTolltip'),
            Column::computed('account_number')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'account_number'
            ])->render())->addClass('removeTolltip'),
            Column::computed('branch_code')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'branch_code'
            ])->render())->addClass('removeTolltip'),
            Column::computed('address')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'address'
            ])->render())->addClass('removeTolltip'),
            Column::computed('contact_number')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'contact_number'
            ])->render())->addClass('removeTolltip'),
            Column::computed('comments')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'comments'
            ])->render())->addClass('removeTolltip'),

        ];
    }
}
