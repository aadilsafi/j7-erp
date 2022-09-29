<?php

namespace App\Services\Permissions;

interface PermissionInterface
{
    public function model();

    public function getByAll();
    public function getById($id);

    public function store($inputs);
    public function update($inputs, $id);

    public function destroy($id);

    public function destroySelected($ids);

    // public function makeDefaultRole($id)
    // {
    //     $record = (new Permission())->where('default', 1)->update([
    //         'default' => 0,
    //     ]);
    //     try {

    //         $record = (new Permission())->where('id', $id)->update([
    //             'default' => 1,
    //         ]);

    //         return 1;
    //     } catch (Exception $ex) {
    //         return $ex;
    //     }
    // }
}
