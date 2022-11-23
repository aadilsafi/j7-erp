<?php

namespace App\DataTables;

use App\Models\Country;
use App\Models\TempFloor;
use App\Models\TempKins;
use App\Models\TempStakeholder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ImportStakeholdersKinsDataTable extends DataTable
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
    public function query(TempKins $model): QueryBuilder
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
            Column::computed('stakeholder_cnic')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'stakeholder_cnic'
            ])->render())->addClass('removeTolltip'),
            Column::computed('kin_cnic')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'kin_cnic'
            ])->render())->addClass('removeTolltip'),
            Column::computed('relation')->title(view('app.components.select-fields', [
                'db_fields' => $this->db_fields,
                'is_disable' => false,
                'required_fields' => $this->required_fields,
                'name' => 'relation'
            ])->render())->addClass('removeTolltip'),
        ];
    }
}
