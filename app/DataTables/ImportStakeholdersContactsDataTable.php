<?php

namespace App\DataTables;

use App\Models\Country;
use App\Models\TempFloor;
use App\Models\TempKins;
use App\Models\TempStakeholder;
use App\Models\TempStakeholderContact;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportStakeholdersContactsDataTable extends DataTable
{
    public function __construct($site_id)
    {
        $model = new TempStakeholderContact();
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
            ->editColumn('stakeholder_cnic', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'stakeholder_cnic', 'inputtype' => 'text', 'value' => $data->stakeholder_cnic]
                );
            })
            ->editColumn('kin_cnic', function ($data) {
                return view(
                    'app.components.unit-preview-cell',
                    ['id' => $data->id, 'field' => 'kin_cnic', 'inputtype' => 'text', 'value' => $data->kin_cnic]
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
    public function query(TempStakeholderContact $model): QueryBuilder
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
            Column::computed('stakeholder_cnic')->addClass('text-nowrap'),
            Column::computed('full_name')->addClass('text-nowrap'),
            Column::computed('father_name')->addClass('text-nowrap'),
            Column::computed('cnic')->addClass('text-nowrap'),
            Column::computed('contact_no')->addClass('text-nowrap'),
            Column::computed('occupation')->addClass('text-nowrap'),
            Column::computed('designation')->addClass('text-nowrap'),
            Column::computed('address')->addClass('text-nowrap'),
            Column::computed('ntn')->addClass('text-nowrap'),
        ];
    }
}
