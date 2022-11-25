<?php

namespace App\Services\LeadSource;

use App\Models\LeadSource;
use App\Services\LeadSource\LeadSourceInterface;

class LeadSourceService implements LeadSourceInterface
{

    public function model()
    {
        return new LeadSource();
    }

    // Get
    public function getByAll($site_id)
    {
        return $this->model()->whereSiteId($site_id)->get();
    }

    public function getById($site_id, $id)
    {
        return $this->model()->where([
            'site_id' => $site_id,
            'id' => $id,
        ])->first();
    }

    // Store
    public function store($site_id, $inputs, $customFields)
    {
        $data = [
            'site_id' => $site_id,
            'name' => filter_strip_tags($inputs['lead_source_name']),
        ];

        $leadSource = $this->model()->create($data);

        foreach ($customFields as $key => $value) {
            $leadSource->CustomFieldValues()->updateOrCreate([
                'custom_field_id' => $value->id,
            ], [
                'value' => isset($inputs[$value->slug]) ? $inputs[$value->slug] : null,
            ]);
        }
        return $leadSource;
    }

    public function update($site_id, $id, $inputs, $customFields)
    {
        $data = [
            'name' => filter_strip_tags($inputs['lead_source_name']),
        ];

        $leadSource = $this->model()->where([
            'site_id' => $site_id,
            'id' => $id,
        ])->first();

        $leadSource->name = $data['name'];
        $leadSource->update();
        foreach ($customFields as $key => $value) {
            $leadSource->CustomFieldValues()->updateOrCreate([
                'custom_field_id' => $value->id,
            ], [
                'value' => isset($inputs[$value->slug]) ? $inputs[$value->slug] : null,
            ]);
        }
        return $leadSource;
    }

    public function destroy($site_id, $inputs)
    {
        $this->model()->whereIn('id', $inputs)->get()->each(function ($row) {
            $row->delete();
        });

        return true;
    }
}
