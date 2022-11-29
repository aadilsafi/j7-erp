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

    public function store($site_id, $inputs, $customFields)
    {
        DB::transaction(function () use ($site_id, $inputs, $customFields) {
            $data = [
                'site_id' => decryptParams($site_id),
                'name' => $inputs['team_name'],
                'parent_id' => $inputs['team'],
                'has_team' => $inputs['has_team'],
            ];

            $team = $this->model()->create($data);

            if (!$inputs['has_team']) {
                $team->users()->attach($inputs['user_id'], ['site_id' => decryptParams($site_id)]);
            }

            foreach ($customFields as $key => $value) {
                $team->CustomFieldValues()->updateOrCreate([
                    'custom_field_id' => $value->id,
                ], [
                    'value' => isset($inputs[$value->slug]) ? $inputs[$value->slug] : null,
                ]);
            }
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

    public function update($site_id, $id, $inputs, $customFields)
    {
        DB::transaction(function () use ($site_id, $id, $inputs, $customFields) {

            $data = [
                'site_id' => $site_id,
                'name' => $inputs['team_name'],
                'parent_id' => $inputs['team'],
                'has_team' => $inputs['has_team'],
            ];

            $team = $this->model()->where('id', $id)->first();
            $team->update($data);

            if ($inputs['has_team']) {
                $team->users()->detach();
            } else {
                $team->users()->syncWithPivotValues($inputs['user_id'], ['site_id' => $site_id]);
            }

            foreach ($customFields as $key => $value) {
                $team->CustomFieldValues()->updateOrCreate([
                    'custom_field_id' => $value->id,
                ], [
                    'value' => isset($inputs[$value->slug]) ? $inputs[$value->slug] : null,
                ]);
            }

            return $team;
        });
    }

    public function destroySelected($id)
    {
        DB::transaction(function () use ($id) {
            if (!empty($id)) {
                foreach ($id as $data) {
                    $team = $this->model()->find($data);
                    $team->users()->detach();
                    $team->delete();
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
