<?php
namespace App\Services\BlackListStackholder;
use App\Models\BacklistedStakeholder;
use Exception;
use DB;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Str;
class BlackListStackholderService implements BlacklistStackholderInterface
{
    public function model()
    {
        return new BacklistedStakeholder();
    }
    // Get By All
    public function getByAll($site_id)
    {
        return $this->model()
            ->all();
    }
     //get by single record
    public function getById($id)
    {
        $getblackList = BacklistedStakeholder::find($id);

        return $getblackList;

    }

    // Store
    public function store($site_id, $inputs)
    {

        $province = State::find($inputs['province']);
        $cityName = City::find($inputs['district']);

        $data = ['name' => ($inputs['name']) , 'fatherName' => ($inputs['fatherName']) , 'cnic' => filter_strip_tags($inputs['cnic']) , 'province' => $province->name, 'district' => $cityName->name, 'country_id' => $inputs['country'], 'state_id' => $inputs['province'], 'city_id' => $inputs['district'],

        ];

        $StoreBlackList = BacklistedStakeholder::create($data);
        return $StoreBlackList;

    }
    //update
    public function update($site_id, $inputs, $id)
    {

        $blacklist = BacklistedStakeholder::find($id);
        
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        $province = State::find($inputs['province']);
        $cityName = City::find($inputs['district']);

        $data = ['name' => ($inputs['name']) , 'fatherName' => ($inputs['fatherName']) , 'cnic' => filter_strip_tags($inputs['cnic']) , 'province' => $province->name, 'district' => $cityName->name, 'country_id' => $inputs['country'], 'state_id' => $inputs['province'], 'city_id' => $inputs['district'],

        ];

        $StoreBlackList = BacklistedStakeholder::find($id)->update($data);
        return $StoreBlackList;

    }
  //delete
    public function destroySelected($ids)
    {
        DB::transaction(function () use ($ids)
        {
            if (!empty($ids))
            {

                $deleted = $this->model()
                    ->whereIn('id', $ids)->delete();

            }

        });
    }

}
