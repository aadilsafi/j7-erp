<?php

namespace App\Services\AccountRecevories;

interface AccountRecevoryInterface
{
    public function generateDataTable($site_id, $filters = []);
}
