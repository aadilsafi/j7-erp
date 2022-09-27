<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Services\User\Interface\UserInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserService implements UserInterface
{

    public function model()
    {
        return new User();
    }

    public function getByAll($site_id)
    {
        return $this->model()->where('site_id', $site_id)->get();
    }

    public function store($site_id, $inputs)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'phone_no' => $inputs['phone_no'],
            'password' =>  Hash::make($inputs['password']),
        ];

        $user = $this->model()->create($data);

        $user->assignRole([$inputs['role_id']]);

        if (isset($inputs['attachment'])) {
            foreach ($inputs['attachment'] as $attachment) {
                $user->addMedia($attachment)->toMediaCollection('user_cnic');
            }
        }

        return $user;
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
        $data = [
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'phone_no' => $inputs['phone_no'],
            'password' =>  Hash::make($inputs['password']),
        ];

        $user = $this->model()->where('id', $id)->update($data);
        $user = $this->model()->find($id);
        $user->clearMediaCollection('user_cnic');

        if (isset($inputs['attachment'])) {
            foreach ($inputs['attachment'] as $attachment) {
                $user->addMedia($attachment)->toMediaCollection('user_cnic');
            }
        }
        return $user;
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
