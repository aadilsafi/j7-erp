<?php

namespace App\Services\Team;

use App\Models\Team;
use Illuminate\Support\Facades\Storage;
use App\Services\Team\Interface\TeamInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeamService implements TeamInterface
{

    public function model()
    {
        return new Team();
    }

    public function getByAll($site_id)
    {
        return $this->model()->where('site_id', $site_id)->get();
    }

    public function getAllWithTree()
    {
        $teams = $this->model()->all();
        return getTreeData(collect($teams), $this->model());
    }

    public function store($site_id, $inputs)
    {
        DB::transaction(function () use ($site_id, $inputs) {
            $data = [
                'site_id' => decryptParams($site_id),
                'name' => $inputs['team_name'],
                'parent_id' => $inputs['team'],
            ];

            $team = $this->model()->create($data);

            $team->users()->syncWithPivotValues(
                [$inputs['user_id']],
                ['site_id' => decryptParams($site_id)]
            );

            return $team;
        });
    }

    public function getById($site_id, $id)
    {
        if ($id == 0) {
            return $this->getEmptyInstance();
        }

        return $this->model()->find($id);
    }

    public function update($site_id, $id, $inputs)
    {
        DB::transaction(function () use ($site_id, $id, $inputs) {
            $data = [
                'name' => $inputs['name'],
                'email' => $inputs['email'],
                'phone_no' => $inputs['phone_no'],
            ];
            if (isset($inputs['password'])) {
                $data['password'] =  Hash::make($inputs['password']);
            }

            $user = $this->model()->where('id', $id)->update($data);
            $user = $this->model()->find($id);
            $user->clearMediaCollection('user_cnic');

            if (isset($inputs['attachment'])) {
                foreach ($inputs['attachment'] as $attachment) {
                    $user->addMedia($attachment)->toMediaCollection('user_cnic');
                }
            }
            $user->syncRoles([$inputs['role_id']]);

            return $user;
        });
    }

    public function destroySelected($id)
    {
        DB::transaction(function () use ($id) {
            if (!empty($id)) {
                foreach ($id as $data) {
                    $user = $this->model()->find($data);
                    $user->roles()->detach();
                    $user->delete();
                }
                // $this->model()->whereIn('id', $id)->delete();
                return true;
            }
            return false;
        });
    }


    public function getEmptyInstance()
    {
        $team = [
            'id' => 0,
            'name' => '',
            'parent_id' => '',
        ];

        return $team;
    }
}
