<?php

namespace App\DataTables;

use App\Models\Bin;
use App\Models\Type;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;


class TypesBinDataTable extends DataTable
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

            ->setRowId('id')
            ->addIndexColumn()

            ->editColumn('actions', function ($user) {
                return view('app.sites.settings.bin.actions', ['site_id' => $this->site_id, 'id' => $user->id]);
            })
            ->editColumn('deleted_at', function ($fileManagement) {
                return editDateColumn($fileManagement->deleted_at);
            })
            // ->editColumn('updated_at', function ($fileManagement) {
            //     return editDateColumn($fileManagement->updated_at);
            // })
            // ->editColumn('check', function ($user) {
            //     return $user;
            // })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Bin $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Type $type): QueryBuilder
    {
        return $type->newQuery()->onlyTrashed();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('bin-table')
            ->addTableClass(['table-hover'])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide()
            ->processing()
            ->scrollX(true)
            ->deferRender()
            ->dom('Bfrtip')
            ->lengthMenu([20, 30, 50, 70, 100])
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons(
                Button::make('reset')->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light'),
                Button::make('reload')->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light'),
            )
            ->orders([
                [2, 'desc'],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('#'),
            Column::make('name')->title('Name'),
            Column::make('deleted_at')->title('Deleted At'),
            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Bin_' . date('YmdHis');
    }
}
