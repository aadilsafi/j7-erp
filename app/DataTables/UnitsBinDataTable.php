<?php

namespace App\DataTables;

use App\Models\Country;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Services\User\Interface\UserInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;

class CountryDataTable extends DataTable
{

    private $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
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
        ->setRowId('id')
        ->addIndexColumn()

        ->editColumn('actions', function ($user) {
            return view('app.sites.settings.bin.actions', ['site_id' => $this->site_id, 'id' => $user->id]);
        })
        ->editColumn('deleted_at', function ($fileManagement) {
            return editDateColumn($fileManagement->deleted_at);
        })
        ->setRowId('id')
        ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Unit $unit): QueryBuilder
    {
        return $unit->newQuery()->onlyTrashed();
    }

    public function html(): HtmlBuilder
    {
        // $createPermission =  Auth::user()->hasPermissionTo('sites.settings.countries.create');
        // $selectedDeletePermission =  0;

        return $this->builder()
            ->setTableId('countries-table')
            ->addTableClass(['table-hover'])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide()
            ->processing()
            ->scrollX(true)
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons(
                Button::make('reset')->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light'),
                Button::make('reload')->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light'),
            )
            ->orders([
                [2, 'desc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $selectedDeletePermission =  Auth::user()->hasPermissionTo('sites.users.destroy-selected');
        $editPermission =  Auth::user()->hasPermissionTo('sites.settings.countries.edit');
        return [
            // ($selectedDeletePermission ?
            //     Column::computed('check')->exportable(false)->printable(false)->width(60)
            //     :
            //     Column::computed('check')->exportable(false)->printable(false)->width(60)->addClass('hidden')
            // ),
            Column::computed('DT_RowIndex')->title('#'),
            Column::make('name')->title('Name'),
            Column::make('deleted_at')->title('Deleted At'),
            Column::make('updated_at')->title('Updated At'),

            ($editPermission ?
                Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')
                :
                Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center')->addClass('hidden')
            )

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Countries_' . date('YmdHis');
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
