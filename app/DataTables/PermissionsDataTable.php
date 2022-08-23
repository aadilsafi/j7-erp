<?php

namespace App\DataTables;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PermissionsDataTable extends DataTable
{
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
            ->editColumn('created_at', function ($permission) {
                return editDateColumn($permission->created_at);
            })
            ->editColumn('class', function ($permission) {
                return Str::of(explode('.', $permission->name)[0])->replace('-', ' ')->title();
            })
            ->editColumn('default', function ($permission) {
                return editBooleanColumn($permission->default);
            })
            // ->editColumn('actions', function ($permission) {
            //     return view('app.permissions.actions', ['id' => $permission->id]);
            // })
            // ->editColumn('check', function ($permission) {

            // })
            ->editColumn('roles', function ($permission) {
                return [
                    'permission_id' => $permission->id,
                    'roles' => $permission->roles->pluck('id')->toArray()
                ];
            })
            ->setRowId('id')
            ->rawColumns(array_merge($columns, ['action', 'check']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Spatie\Permission\Models\Permission $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Permission $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('permissions-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->stateSave()
            ->serverSide()
            ->processing()
            ->deferRender()
            ->dom('BlfrtipC')
            ->lengthMenu([20, 30, 50, 70, 100])
            ->dom('<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">')
            ->buttons(
                Button::make('export')->addClass('btn btn-relief-outline-secondary waves-effect waves-float waves-light dropdown-toggle')->buttons([
                    Button::make('print')->addClass('dropdown-item'),
                    Button::make('copy')->addClass('dropdown-item'),
                    Button::make('csv')->addClass('dropdown-item'),
                    Button::make('excel')->addClass('dropdown-item'),
                    Button::make('pdf')->addClass('dropdown-item'),
                ]),
                Button::make('reset')->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light'),
                Button::make('reload')->addClass('btn btn-relief-outline-primary waves-effect waves-float waves-light'),
                // Button::raw('delete-selected')
                //     ->addClass('btn btn-relief-outline-danger waves-effect waves-float waves-light')
                //     ->text('<i class="bi bi-trash3-fill"></i> Delete Selected')->attr([
                //         'onclick' => 'deleteSelected()',
                //     ]),
            )
            ->rowGroupDataSrc('class')
            ->scrollX()
            // ->columnDefs([
            //     [
            //         'targets' => 0,
            //         'className' => 'text-center text-primary',
            //         'width' => '10%',
            //         'orderable' => false,
            //         'searchable' => false,
            //         'responsivePriority' => 3,
            //         'render' => "function (data, type, full, setting) {
            //             var permission = JSON.parse(data);
            //             return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" onchange=\"changeTableRowColor(this)\" type=\"checkbox\" value=\"' + permission.id + '\" name=\"chkPermission[]\" id=\"chkPermission_' + permission.id + '\" /><label class=\"form-check-label\" for=\"chkPermission_' + permission.id + '\"></label></div>';
            //         }",
            //         'checkboxes' => [
            //             'selectAllRender' =>  '<div class="form-check"> <input class="form-check-input" onchange="changeAllTableRowColor()" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
            //         ]
            //     ],
            // ])
            ->orders([
                [1, 'asc'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        // $roles = (new Role())->select('id', 'name')->orderBy('id')->get();
        $currentAuthentiactedRoleId = Auth::user()->roles->pluck('id');
        $roles = getLinkedTreeData(new Role(), $currentAuthentiactedRoleId);
        $roles = array_merge(Auth::user()->roles->toArray(), $roles);
        $colArray = [
            // Column::computed('#'),
            Column::make('show_name')->title('Permission Name')->ucfirst(),
            Column::make('guard_name')->title('Guard Name'),
            Column::computed('class')->title('Class')->visible(false),
        ];

        foreach ($roles as $key => $role) {

            // if (in_array($role->name, ['Director', 'Admin', 'Super Admin']) )
            //     continue;
            $checkAssignPermission  = Auth::user()->hasPermissionTo('permissions.assign-permission');
            $checkRevokePermission  = Auth::user()->hasPermissionTo('permissions.revoke-permission');
            $checkEditOwnPermission  = Auth::user()->hasPermissionTo('permissions.edit-own-permission');
            $assignPermssion = 0;
            $revokePermission = 0;
            $editOwnPermission = 0;
            if($checkAssignPermission){
                $assignPermssion = 1;
            }
            if($checkRevokePermission){
                $revokePermission = 1;
            }
            if($checkEditOwnPermission){
                $editOwnPermission = 1;
            }
            $colArray[] = Column::computed('roles')
                ->title($role['name'])
                ->searchable(false)
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->render('function () {
                    var roles = data.roles;
                    var isPermissionAssigned = roles.includes(' . $role['id'] . ');
                    if('.$currentAuthentiactedRoleId[0].' == '.$role['id'].'){
                        var checkbox = "<div class=\'form-check d-flex justify-content-center\'>";
                        if(isPermissionAssigned) {
                            if('.$editOwnPermission.')
                            {
                                if('.$revokePermission.'){
                                checkbox += "<input  class=\'form-check-input\' type=\'checkbox\' onchange=\'changeRolePermission(' . $role['id'] . ', " + data.permission_id + ")\'  id=\'chkRolePermission_' . $role['id'] . '_' . '" + data.permission_id + "\' checked />";
                                }
                                else{
                                    checkbox += "<input disabled class=\'form-check-input\' type=\'checkbox\' onchange=\'changeRolePermission(' . $role['id'] . ', " + data.permission_id + ")\'  id=\'chkRolePermission_' . $role['id'] . '_' . '" + data.permission_id + "\' checked />";
                                }
                            }
                            else{
                                checkbox += "<input disabled class=\'form-check-input\' type=\'checkbox\' onchange=\'changeRolePermission(' . $role['id'] . ', " + data.permission_id + ")\'  id=\'chkRolePermission_' . $role['id'] . '_' . '" + data.permission_id + "\' checked />";
                            }
                        } else {
                            if('.$editOwnPermission.')
                            {
                                if('.$assignPermssion.'){
                                    checkbox += "<input class=\'form-check-input\' type=\'checkbox\' onchange=\'changeRolePermission(' . $role['id'] . ', " + data.permission_id + ")\'  id=\'chkRolePermission_' . $role['id'] . '_' . '" + data.permission_id + "\' />";
                                }
                                else
                                {
                                    checkbox += "<input disabled class=\'form-check-input\' type=\'checkbox\' onchange=\'changeRolePermission(' . $role['id'] . ', " + data.permission_id + ")\'  id=\'chkRolePermission_' . $role['id'] . '_' . '" + data.permission_id + "\' />";
                                }
                            }
                            else{
                                checkbox += "<input disabled class=\'form-check-input\' type=\'checkbox\' onchange=\'changeRolePermission(' . $role['id'] . ', " + data.permission_id + ")\'  id=\'chkRolePermission_' . $role['id'] . '_' . '" + data.permission_id + "\' />";
                            }
                        }
                        checkbox += "<label class=\'form-check-label\' for=\'chkRolePermission_' . $role['id'] . '\'></label></div>";

                        return checkbox;
                    }
                    else
                    {
                        var checkbox = "<div class=\'form-check d-flex justify-content-center\'>";
                        if(isPermissionAssigned) {
                            if('.$revokePermission.'){
                               checkbox += "<input  class=\'form-check-input\' type=\'checkbox\' onchange=\'changeRolePermission(' . $role['id'] . ', " + data.permission_id + ")\'  id=\'chkRolePermission_' . $role['id'] . '_' . '" + data.permission_id + "\' checked />";
                            }
                            else{
                                checkbox += "<input disabled class=\'form-check-input\' type=\'checkbox\' onchange=\'changeRolePermission(' . $role['id'] . ', " + data.permission_id + ")\'  id=\'chkRolePermission_' . $role['id'] . '_' . '" + data.permission_id + "\' checked />";
                            }
                        } else {
                            if('.$assignPermssion.'){
                                checkbox += "<input class=\'form-check-input\' type=\'checkbox\' onchange=\'changeRolePermission(' . $role['id'] . ', " + data.permission_id + ")\'  id=\'chkRolePermission_' . $role['id'] . '_' . '" + data.permission_id + "\' />";
                            }
                            else
                            {
                                checkbox += "<input disabled class=\'form-check-input\' type=\'checkbox\' onchange=\'changeRolePermission(' . $role['id'] . ', " + data.permission_id + ")\'  id=\'chkRolePermission_' . $role['id'] . '_' . '" + data.permission_id + "\' />";
                            }
                        }
                        checkbox += "<label class=\'form-check-label\' for=\'chkRolePermission_' . $role['id'] . '\'></label></div>";

                        return checkbox;
                    }
                 }');
        }

        // $colArray[] = Column::computed('actions')->exportable(false)->printable(false)->width(60)->addClass('text-center');
        return $colArray;
    }


    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Permissions_' . date('YmdHis');
    }
}
