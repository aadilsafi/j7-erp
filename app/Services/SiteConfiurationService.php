<?php

namespace App\Services;

use App\Models\Site;
use App\Services\Interfaces\SiteConfigurationInterface;
use Exception;
use Illuminate\Support\Str;

class SiteConfiurationService implements SiteConfigurationInterface
{

    public function model()
    {
        return new Site();
    }

    // Get
    public function getByAll()
    {
        return $this->model()->all();
    }

    public function getById($id)
    {
        $id = decryptParams($id);
        return $this->model()->find($id);
    }

    public function getAllWithTree()
    {
        $types = $this->model()->all();
        return getTreeData(collect($types), $this->model());
    }

    // Store
    public function store($inputs)
    {
        $data = [
            'name' => $inputs['type_name'],
            'slug' => Str::of($inputs['type_name'])->slug(),
            'parent_id' => $inputs['type'],
        ];
        $type = $this->model()->create($data);
        return $type;
    }

    public function update($inputs, $id)
    {
        $id = decryptParams($id);
        // dd($inputs, $id);

        switch ($inputs['selected_tab']) {
            case 'site':
                $data = [
                    'name' => $inputs['name'],
                    'address' => $inputs['address'],
                    'area_width' => $inputs['area_width'],
                    'area_length' => $inputs['area_length'],
                ];

                $this->model()->find($id)->update($data);
                $siteConfigutaion = $this->model()->find($id)->siteConfiguration->update($inputs['arr_site']);
                break;

            case 'floor':
                $siteConfigutaion = $this->model()->find($id)->siteConfiguration->update($inputs['arr_floor']);
                break;

            case 'unit':
                $siteConfigutaion = $this->model()->find($id)->siteConfiguration->update($inputs['arr_unit']);
                break;

            case 'salesplan':
                $siteConfigutaion = $this->model()->find($id)->siteConfiguration->update($inputs['arr_salesplan']);
                break;

            case 'others':
                $siteConfigutaion = $this->model()->find($id)->siteConfiguration->update($inputs['arr_others']);
                break;

            default:
                break;
        }
        // dd('done');
        return $siteConfigutaion;
    }

    // public function destroy($id)
    // {
    //     $id = decryptParams($id);

    //     $types = getTypeLinkedTypes($id);

    //     $typesIDs = array_merge($id, array_column($types, 'id'));

    //     $this->model()->whereIn('id', $typesIDs)->delete();

    //     return true;
    // }
}
