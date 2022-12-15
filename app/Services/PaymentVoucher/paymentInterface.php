<?php

namespace App\Services\PaymentVoucher;

interface paymentInterface
{
    public function model();
    public function store($site_id, $inputs);
}
