<?php

namespace App\Services\Company;

use App\Models\Company;
use App\Services\Company\Interface\CompanyInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Log;

class CompanyService implements CompanyInterface
{

    public function model()
    {
        return new Company();
    }

    public function getByAll($site_id)
    {
        return $this->model()->where('site_id', $site_id)->get();
    }

    public function store($site_id, $inputs)
    {
        DB::transaction(function () use ($site_id, $inputs) {
            $data = [
                'site_id' => decryptParams($site_id),
                'name' => $inputs['name'],
                'email' => $inputs['email'],
                'contact_no' => $inputs['contact_no'],
                'countryDetails' => $inputs['countryDetails'],
                'address' => $inputs['address'],

            ];

            $company = $this->model()->create($data);

            if (isset($inputs['attachment'])) {
                foreach ($inputs['attachment'] as $attachment) {
                    $company->addMedia($attachment)->toMediaCollection('company_logo');
                }
                $returnValue = changeImageDirectoryPermission();
                Log::info("changeImageDirectoryPermission => " . $returnValue);
            }
            return $company;
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
        DB::transaction(function () use ($site_id, $id, $inputs, $customFields) {
            $data = [
                'name' => $inputs['name'],
                'email' => $inputs['email'],
                'designation' => $inputs['designation'],
                'contact' => $inputs['contact'],
                'cnic' => $inputs['cnic'],
                'countryDetails' => $inputs['countryDetails'],
                'optional_contact' => $inputs['optional_contact'],
                'OptionalCountryDetails' => $inputs['OptionalCountryDetails'],
                'address' => $inputs['address'],
                'mailing_address' => $inputs['mailing_address'],
                'city_id' => $inputs['city_id'],
                'country_id' => $inputs['country_id'],
                'state_id' => $inputs['state_id'],
                'nationality' => isset($inputs['nationality']) ? $inputs['nationality'] : 'pakistani',
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

            foreach ($customFields as $key => $value) {
                $user->CustomFieldValues()->updateOrCreate([
                    'custom_field_id' => $value->id,
                ], [
                    'value' => isset($inputs[$value->slug]) ? $inputs[$value->slug] : null,
                ]);
            }
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
        $user = [
            'id' => 0,
            'name' => '',
            'email' => '',
            'phone_no' => '',
        ];

        return $user;
    }
}
