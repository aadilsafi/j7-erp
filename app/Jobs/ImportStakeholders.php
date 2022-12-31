<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\Country;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Models\State;
use App\Models\TempStakeholder;
use DB;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportStakeholders implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 120000;
    public $failOnTimeout = false;

    private $siteId;
    private $importData;

    public function __construct($site_id, $importData)
    {
        $this->siteId = $site_id;
        $this->importData = $importData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = new TempStakeholder();
        $tempCols = $model->getFillable();

        $stakeholder = [];
        $parentsCnics = [];
        $parentsRelations = [];

        $site_id = $this->siteId;

        $importData = $this->importData;


        foreach ($importData as $key => $items) {

            $data[$key]['site_id'] = 1;
            $data[$key]['stakeholder_as'] = 'i';

            foreach ($tempCols as $k => $field) {
                $data[$key][$field] = $items[$tempCols[$k]];
            }
            $data[$key]['is_imported'] = true;

            if ($data[$key]['is_local'] == 'yes') {
                $data[$key]['nationality'] = 167;
            } else {
                $nationality = Country::whereRaw('LOWER(name) = (?)', [strtolower($data[$key]['nationality'])])->first();
                if ($nationality) {
                    $data[$key]['nationality'] = $nationality->id;
                } else {
                    $data[$key]['nationality'] = 167;
                }
            }
            // residential address 

            if ($data[$key]['residential_country'] != "null") {
                $country = Country::whereRaw('LOWER(name) = (?)', [strtolower($data[$key]['residential_country'])])->first();
                if ($country) {
                    $data[$key]['residential_country_id'] = $country->id;
                } else {
                    $data[$key]['residential_country_id'] = 167;
                }
            }

            if ($data[$key]['residential_state'] != "null") {
                $state = State::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['residential_state']))->first();
                if ($state) {
                    $data[$key]['residential_state_id'] = $state->id;
                } else {
                    $data[$key]['residential_state_id'] = 0;
                }
            }

            if ($data[$key]['residential_city'] != "null") {
                $city = City::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['residential_city']))->first();
                if ($city) {
                    $data[$key]['residential_city_id'] = $city->id;
                } else {
                    $data[$key]['residential_city_id'] = 0;
                }
            }

            if ($data[$key]['same_address_for_mailing'] == 'yes') {
                $data[$key]['mailing_address'] = $data[$key]['residential_address'];
                $data[$key]['mailing_address_type'] = $data[$key]['residential_address_type'];
                $data[$key]['mailing_country_id'] = $data[$key]['residential_country_id'];
                $data[$key]['mailing_state_id'] = $data[$key]['residential_state_id'];
                $data[$key]['mailing_city_id'] = $data[$key]['residential_city_id'];
                $data[$key]['mailing_postal_code'] = $data[$key]['residential_postal_code'];
            } else {
                // mailing address 

                if ($data[$key]['mailing_country'] != "null") {
                    $country = Country::whereRaw('LOWER(name) = (?)', [strtolower($data[$key]['mailing_country'])])->first();
                    if ($country) {
                        $data[$key]['mailing_country_id'] = $country->id;
                    } else {
                        $data[$key]['mailing_country_id'] = 167;
                    }
                }

                if ($data[$key]['mailing_state'] != "null") {
                    $state = State::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['mailing_state']))->first();
                    if ($state) {
                        $data[$key]['mailing_state_id'] = $state->id;
                    }
                }

                if ($data[$key]['mailing_city'] != "null") {
                    $city = City::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['mailing_city']))->first();
                    if ($city) {
                        $data[$key]['mailing_city_id'] = $city->id;
                    }
                }
            }

            $data[$key]['created_at'] = $items->created_at;
            $data[$key]['updated_at'] = $items->updated_at;

            unset($data[$key]['parent_cnic']);
            unset($data[$key]['is_dealer']);
            unset($data[$key]['is_vendor']);
            unset($data[$key]['is_customer']);
            unset($data[$key]['residential_country']);
            unset($data[$key]['residential_state']);
            unset($data[$key]['residential_city']);
            unset($data[$key]['mailing_country']);
            unset($data[$key]['mailing_state']);
            unset($data[$key]['mailing_city']);
            unset($data[$key]['same_address_for_mailing']);

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

            $items->delete();
        }



        // TempStakeholder::query()->truncate();
    }
}
