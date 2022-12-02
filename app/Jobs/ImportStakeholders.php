<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\Country;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Models\State;
use App\Models\TempStakeholder;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportStakeholders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $siteId;

    public function __construct($site_id)
    {
        $this->siteId = $site_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = new TempStakeholder();
        $tempdata = $model->cursor();
        $tempCols = $model->getFillable();

        $stakeholder = [];
        $parentsCnics = [];
        $parentsRelations = [];

        $site_id = $this->siteId;

        foreach ($tempdata->chunk(30) as $Importkey => $importData) {

            DB::transaction(function () use ($site_id, $importData, $Importkey, $tempCols) {
                foreach ($importData as $key => $items) {

                    foreach ($tempCols as $k => $field) {
                        $data[$key][$field] = $items[$tempCols[$k]];
                    }
                    $data[$key]['site_id'] = decryptParams($site_id);
                    $data[$key]['is_imported'] = true;

                    if ($data[$key]['country'] != "null") {
                        $country = Country::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['country']))->first();
                        if ($country) {
                            $data[$key]['country_id'] = $country->id;
                        } else {
                            $data[$key]['country_id'] = 1;
                        }
                    }

                    if ($data[$key]['city'] != "null") {
                        $city = City::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['city']))->first();
                        if ($city) {
                            $data[$key]['city_id'] = $city->id;
                        }
                    }
                    if ($data[$key]['state'] != "null") {
                        $state = State::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['state']))->first();
                        if ($state) {
                            $data[$key]['state_id'] = $state->id;
                        }
                    }

                    $data[$key]['created_at'] = $items->created_at;
                    $data[$key]['updated_at'] = $items->updated_at;

                    unset($data[$key]['parent_cnic']);
                    unset($data[$key]['is_dealer']);
                    unset($data[$key]['is_vendor']);
                    unset($data[$key]['is_customer']);
                    unset($data[$key]['country']);
                    unset($data[$key]['state']);
                    unset($data[$key]['city']);

                    $stakeholder = Stakeholder::create($data[$key]);

                    $stakeholdertype = [
                        [
                            'stakeholder_id' => $stakeholder->id,
                            'type' => 'C',
                            'stakeholder_code' => 'C-00' . $stakeholder->id,
                            'status' => $items['is_customer'] ? 1 : 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'stakeholder_id' => $stakeholder->id,
                            'type' => 'V',
                            'stakeholder_code' => 'V-00' . $stakeholder->id,
                            'status' => $items['is_vendor'] ? 1 : 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'stakeholder_id' => $stakeholder->id,
                            'type' => 'D',
                            'stakeholder_code' => 'D-00' . $stakeholder->id,
                            'status' => $items['is_dealer'] ? 1 : 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'stakeholder_id' => $stakeholder->id,
                            'type' => 'K',
                            'stakeholder_code' => 'K-00' . $stakeholder->id,
                            'status' => $items['is_kin'] ? 1 : 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'stakeholder_id' => $stakeholder->id,
                            'type' => 'L',
                            'stakeholder_code' => 'L-00' . $stakeholder->id,
                            'status' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ];

                    $stakeholder_type = StakeholderType::insert($stakeholdertype);
                }
            });
        }

        TempStakeholder::query()->truncate();
    }
}
