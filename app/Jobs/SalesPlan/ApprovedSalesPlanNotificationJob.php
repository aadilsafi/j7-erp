<?php

namespace App\Jobs\SalesPlan;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Notifications\ApprovedSalesPlanNotification;

class ApprovedSalesPlanNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    protected $users;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$users)
    {
        //
        $this->data = $data;
        $this->users = $users;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Notification::send($this->users, new ApprovedSalesPlanNotification($this->data));
    }
}
