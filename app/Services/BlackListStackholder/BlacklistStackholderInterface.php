<?php

namespace App\Services\BlackListStackholder;

interface BlacklistStackholderInterface
{
    public function model();
    public function getByAll($site_id);
    public function store($site_id, $inputs);
    public function update($site_id, $id, $inputs);
     public function getById($id);
    public function destroySelected($id);
}