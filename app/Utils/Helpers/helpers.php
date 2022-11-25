<?php

use App\Exceptions\GeneralException;
use App\Models\{
    AccountLedger,
    AdditionalCost,
    Floor,
    SiteConfigration,
    Type,
    UserBatch,
    Stakeholder,
    StakeholderType,
    Team,
    Unit,
    AccountHead,
    AccountingStartingCode,
    SalesPlan,
};
use App\Utils\Enums\NatureOfAccountsEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\{Collection};
use Illuminate\Support\Facades\{Crypt, File};
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

if (!function_exists('filter_strip_tags')) {

    function filter_strip_tags($field): string
    {
        return trim(strip_tags($field));
    }
}

if (!function_exists('encode_html_entities')) {

    function encode_html_entities($field): string
    {
        return trim(htmlentities($field));
    }
}

if (!function_exists('decode_html_entities')) {

    function decode_html_entities($field): string
    {
        return trim(html_entity_decode($field));
    }
}

if (!function_exists('numberToWords')) {

    function numberToWords($number): string
    {
        return (new NumberFormatter("en", NumberFormatter::SPELLOUT))->format($number);
    }
}

if (!function_exists('englishCounting')) {

    function englishCounting($number): string
    {
        $detail = '';
        switch ($number) {
            case 1:
                $detail = '1st';
                break;

            case 2:
                $detail = '2nd';
                break;

            case 3:
                $detail = '3rd';
                break;

            default:
                $detail = $number . 'th';
                break;
        }
        return $detail;
    }
}

if (!function_exists('encryptParams')) {
    function encryptParams($params): array|string
    {
        if (is_array($params)) {
            $data = [];
            foreach ($params as $item) {
                $data[] = Crypt::encryptString($item);
            }
            return $data;
        }
        return Crypt::encryptString($params);
    }
}

if (!function_exists('decryptParams')) {
    function decryptParams($params): array|string
    {
        if (is_array($params)) {
            $data = [];
            foreach ($params as $item) {
                $data[] = Crypt::decryptString($item);
            }
            return $data;
        }
        return Crypt::decryptString($params);
    }
}

if (!function_exists('getSiteConfiguration')) {
    function getSiteConfiguration($site_id)
    {

        $site_id = decryptParams($site_id);

        $siteConfiguration = (new SiteConfigration())->whereSiteId($site_id)->first();
        return $siteConfiguration ?? null;
    }
}

if (!function_exists('getAllModels')) {
    function getAllModels($path = null): array
    {
        $Modelpath = ($path ?? app_path()) . "/Models";

        $out = [];
        $results = scandir($Modelpath);
        foreach ($results as $result) {
            //			dd($results);
            if ($result === '.' or $result === '..') continue;
            $filename = $Modelpath . '/' . $result;
            if (is_dir($filename)) {
                $out = array_merge($out, getAllModels($filename));
            } else {
                $out[] = substr($result, 0, -4);
            }
        }
        return $out;
    }
}

if (!function_exists('getTrashedDataCount')) {
    function getTrashedDataCount(): float|int
    {
        $trashed = [];
        foreach (getAllModels() as $model) {
            $models = app("App\Models\\" . $model);
            if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($models))) {
                $trashed[] = $models->onlyTrashed()->count();
            } else {
                $trashed[] = 0;
            }
        }
        return array_sum($trashed);
    }
}

if (!function_exists('getTreeData')) {
    function getTreeData(collection $collectionData, $model, $getFromDB = false): array
    {
        $typesTmp = [];

        // $model = "\\App\\Models\\" . $model;
        $dbTypes = ($getFromDB ? $model::all() : $collectionData);

        foreach ($collectionData as $key => $row) {
            $typesTmp[] = $row;
            $typesTmp[$key]["tree"] = ($getFromDB ? getTypeParentTreeElequent($model, $row, $row->name, $collectionData, $dbTypes) : getTypeParentTreeCollection($row, $row->name, $collectionData));
        }

        return $typesTmp;
        // dd($typesTmp);
    }
}
if (!function_exists('getStakeholderTreeData')) {
    function getStakeholderTreeData(collection $collectionData, $model, $getFromDB = false): array
    {
        $stakeholderTmp = [];

        $dbTypes = ($getFromDB ? $model::all() : $collectionData);

        foreach ($collectionData as $key => $row) {
            $stakeholderTmp[] = $row;
            $stakeholderTmp[$key]["tree"] = ($getFromDB ? getStakholderParentTreeElequent($model, $row, $row->full_name, $collectionData, $dbTypes) : getStakeholderParentTreeCollection($row, $row->full_name, $collectionData));
        }
        return $stakeholderTmp;
    }
}

if (!function_exists('getStakholderParentTreeElequent')) {
    function getStakholderParentTreeElequent($model, $row, $name, collection $parent, $dbTypes)
    {
        if ($row->parent_id == 0) {
            return $name;
        }

        $nextRow = $model::find($row->parent_id);
        $name = $nextRow->full_name . ' > ' . $name;

        return getStakholderParentTreeElequent($model, $nextRow, $name, $parent, $dbTypes);
    }
}

if (!function_exists('getStakeholderParentTreeCollection')) {
    function getStakeholderParentTreeCollection($row, $name, collection $parent): string
    {
        if ($row->parent_id == 0) {
            return $name;
        }

        $nextRow = $parent->firstWhere('id', $row->parent_id);
        $name = (is_null($nextRow) ?? empty($nextRow) ? '' : $nextRow->full_name) . ' > ' . $name;
        if (is_null($nextRow) ?? empty($nextRow)) {
            return $name;
        }

        return getStakeholderParentTreeCollection($nextRow, $name, $parent, $parent);
    }
}

if (!function_exists('getTypeParentTreeElequent')) {
    function getTypeParentTreeElequent($model, $row, $name, collection $parent, $dbTypes)
    {
        if ($row->parent_id == 0) {
            return $name;
        }

        $nextRow = $model::find($row->parent_id);
        $name = $nextRow->name . ' > ' . $name;

        return getTypeParentTreeElequent($model, $nextRow, $name, $parent, $dbTypes);
    }
}

if (!function_exists('getTypeParentTreeCollection')) {
    function getTypeParentTreeCollection($row, $name, collection $parent): string
    {
        if ($row->parent_id == 0) {
            return $name;
        }

        $nextRow = $parent->firstWhere('id', $row->parent_id);
        $name = (is_null($nextRow) ?? empty($nextRow) ? '' : $nextRow->name) . ' > ' . $name;
        if (is_null($nextRow) ?? empty($nextRow)) {
            return $name;
        }

        return getTypeParentTreeCollection($nextRow, $name, $parent, $parent);
    }
}

if (!function_exists('getLinkedTreeData')) {
    function getLinkedTreeData(Model $model, $id = [])
    {
        $id = $model::whereIn('parent_id', $id)->get()->toArray();
        if (count($id) > 0) {
            return array_merge($id, getLinkedTreeData($model, array_column($id, 'id')));
        }
        return $id;
    }
}

if (!function_exists('base64ToImage')) {
    function base64ToImage($image): string
    {
        ini_set('memory_limit', '256M');
        $filename = 'TelK7BnW63IAN6zuTTwJkqZeuM0YI5aNc7aFqOyz.jpg';
        if (!empty($image)) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . config('app.asset_url') . DIRECTORY_SEPARATOR . 'public_assets/admin/sites_images';
            $image_parts = explode(";base64,", $image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $filename = uniqid() . '.' . $image_type;
            $file = $dir . DIRECTORY_SEPARATOR . $filename;
            file_put_contents($file, $image_base64);
        }
        return $filename;
    }
}
if (!function_exists('account_number_format')) {
    function account_number_format($account_number): string
    {
        $first_6_number = substr($account_number, 0, 6);
        $code = wordwrap($first_6_number, 2, '-', true);
        if (Str::length($account_number) > 6) {
            $code = $code . '-' . $next_4_number = substr($account_number, 6, 4);
        }
        if (Str::length($account_number) > 10) {
            $code = $code . '-' . $after_10_number = substr($account_number, 10);
        }
        return $code;
    }
}

// if (!function_exists('makeImageThumbs')) {
//     function makeImageThumbs($request, $key = ""): string
//     {
//         $publicPath = public_path('public_assets/admin/sites_images') . DIRECTORY_SEPARATOR;
//         if (!is_string($request)) {
//             if (is_array($request)) {
//                 $image = $request[$key];
//             } else {
//                 $image = $request->file($key);
//             }
//             $imageHashedName = $image->hashName();
//         } else {
//             $image = $publicPath . $request;
//         }

//         $imgExplodedName = explode(".", $imageHashedName);

//         $img = Image::make($image)->backup();

//         $img->resize(1000, null, function ($constraint) {
//             $constraint->aspectRatio();
//             $constraint->upsize();
//         })->save($publicPath . $imgExplodedName[0] . '-thumbs1000.' . $imgExplodedName[1]);
//         $img->reset();

//         $img->resize(600, null, function ($constraint) {
//             $constraint->aspectRatio();
//             $constraint->upsize();
//         })->save($publicPath . $imgExplodedName[0] . '.' . $imgExplodedName[1]);
//         $img->reset();

//         $img->resize(350, null, function ($constraint) {
//             $constraint->aspectRatio();
//             $constraint->upsize();
//         })->save($publicPath . $imgExplodedName[0] . '-thumbs350.' . $imgExplodedName[1]);
//         $img->reset();

//         $img->resize(200, null, function ($constraint) {
//             $constraint->aspectRatio();
//             $constraint->upsize();
//         })->save($publicPath . $imgExplodedName[0] . '-thumbs200.' . $imgExplodedName[1]);
//         $img->reset();

//         $img->destroy();

//         return $imgExplodedName[0] . '.' . $imgExplodedName[1];
//     }
// }

if (!function_exists('deleteImageThumbs')) {
    function deleteImageThumbs($imgName,  $imageDirectory = 'admin'): string
    {
        $publicServerPath = public_path('public_assets/' . $imageDirectory . '/sites_images') . DIRECTORY_SEPARATOR;
        if (File::exists($publicServerPath . $imgName)) {
            $imageExplodedName = explode(".", $imgName);
            // dd($imageExplodedName);
            File::delete([
                $publicServerPath . $imageExplodedName[0] . "." . $imageExplodedName[1],
                $publicServerPath . $imageExplodedName[0] . "-thumbs1000." . $imageExplodedName[1],
                $publicServerPath . $imageExplodedName[0] . "-thumbs350." . $imageExplodedName[1],
                $publicServerPath . $imageExplodedName[0] . "-thumbs200." . $imageExplodedName[1],
            ]);

            return true;
        }
        return false;
    }
}

if (!function_exists('getImageByName')) {
    function getImageByName($imgName, $imageDirectory = 'admin'): array
    {
        $img = "";
        $imgThumb = "";
        $publicServerPath = public_path('public_assets/' . $imageDirectory . '/sites_images') . DIRECTORY_SEPARATOR;
        $publicLinkPath = asset('public_assets/' . $imageDirectory . '/sites_images') . DIRECTORY_SEPARATOR;

        $imageExplodedName = explode('.', (!is_null($imgName) && !empty($imgName) ? $imgName : 'TelK7BnW63IAN6zuTTwJkqZeuM0YI5aNc7aFqOyz.jpg'));

        if (File::exists($publicServerPath . ($imageExplodedName[0] . "-thumbs200." . $imageExplodedName[1]))) {
            $img = $publicLinkPath . $imageExplodedName[0] . "-thumbs1000." . $imageExplodedName[1];
            $imgThumb = $publicLinkPath . $imageExplodedName[0] . "-thumbs200." . $imageExplodedName[1];
        } else if (File::exists($publicServerPath . ($imageExplodedName[0] . "." . $imageExplodedName[1]))) {
            $img = $publicLinkPath . $imageExplodedName[0] . "." . $imageExplodedName[1];
            $imgThumb = $publicLinkPath . $imageExplodedName[0] . "." . $imageExplodedName[1];
        } else {
            $img = $publicLinkPath . "do_not_delete/do_not_delete.png";
            $imgThumb = $publicLinkPath . "do_not_delete/do_not_delete.png";
        }

        return [$img, $imgThumb];
    }
}

if (!function_exists('editDateColumn')) {
    function editDateColumn($date)
    {
        $date = new Carbon($date);

        return "<span>" . $date->format('H:i:s') . "</span> <br> <span class='text-primary fw-bold'>" . $date->format('Y-m-d') . "</span>";
    }
}

if (!function_exists('getTypeNameByID')) {
    function getTypeNameByID($type_id)
    {
        $type = (new Type())->where('id', $type_id)->first();
        if ($type) {
            return $type->name;
        }
        return 'parent';
    }
}

if (!function_exists('editBooleanColumn')) {
    function editBooleanColumn($boolean)
    {
        if ($boolean) {
            return "<span class='badge rounded-pill badge-light-success me-1'>" . __('lang.commons.yes') . "</span>";
        } else {
            return "<span class='badge rounded-pill badge-light-danger me-1'>" . __('lang.commons.no') . "</span>";
        }
    }
}

if (!function_exists('editBadgeColumn')) {
    function editBadgeColumn($value)
    {
        return "<span class='badge rounded-pill badge-light-primary me-1'>" . $value . "</span>";
    }
}

if (!function_exists('getTypeParentByParentId')) {
    function getTypeParentByParentId($parent_id)
    {
        $type = (new Type())->where('id', $parent_id)->first();
        if ($type) {
            return $type->name;
        }
        return 'parent';
    }
}

if (!function_exists('getRoleParentByParentId')) {
    function getRoleParentByParentId($parent_id)
    {
        $role =  (new Role())->where('id', $parent_id)->first();
        if ($role) {
            return $role->name;
        }
        return 'parent';
    }
}

if (!function_exists('getStakeholderParentByParentId')) {
    function getStakeholderParentByParentId($parent_id)
    {
        $Stakeholder =  (new Stakeholder())->where('id', $parent_id)->first();
        if ($Stakeholder) {
            return $Stakeholder->full_name;
        }
        return 'Nill';
    }
}

if (!function_exists('getAdditionalCostByParentId')) {
    function getAdditionalCostByParentId($parent_id)
    {
        $additionalCost = (new AdditionalCost())->where('id', $parent_id)->first();
        if ($additionalCost) {
            return $additionalCost->name;
        }
        return 'parent';
    }
}

if (!function_exists('getAuthentacatedUserInfo')) {
    function getAuthentacatedUserInfo()
    {
        $user = new stdClass();
        $user->data = auth()->user();
        $user->roles = implode(', ', auth()->user()->roles->pluck('name')->toArray());
        return $user;
    }
}

if (!function_exists('getNHeightestNumber')) {
    function getNHeightestNumber($numberOfDigits = 1)
    {
        return (int)str_repeat('9', $numberOfDigits);
    }
}

if (!function_exists('getbatchesByUserID')) {
    function getbatchesByUserID($user_id, $action_id = 0)
    {

        $user_id = decryptParams($user_id);

        $batches = (new UserBatch())->whereUserId($user_id)->limit(20)->latest();
        if ($action_id > 0) {
            $batches = $batches->whereActionId($action_id)->limit(20)->latest();
        }

        return $batches->get() ?? null;
    }
}

if (!function_exists('apiErrorResponse')) {
    function apiErrorResponse($message = 'data not found', $key = 'error')
    {
        return response()->json(
            [
                'status' => false,
                'message' => [
                    $key => $message,
                ],
                'data' => null,
                'stauts_code' => '200'
            ],
            200
        );
    }
}

if (!function_exists('apiSuccessResponse')) {
    function apiSuccessResponse($data = null, $message = 'data found', $key = 'success')
    {
        return response()->json(
            [
                'status' => true,
                'message' => [
                    $key => $message,
                ],
                'data' => $data,
                'stauts_code' => '200'
            ],
            200
        );
    }
}

if (!function_exists('sqlErrorMessagesByCode')) {
    function sqlErrorMessagesByCode($errCode)
    {
        $messages = [
            '1062' => 'Duplicate entry',
            '1452' => 'Cannot add or update a child row',
            '1451' => 'Cannot delete or update a parent row',
            '1364' => 'Field does not have a default value',
            '1048' => 'Column cannot be null',
            '1054' => 'Unknown column',
            '1052' => 'Column in where clause is ambiguous',
            '1051' => 'Unknown table',
            '1050' => 'Table already exists',
            '1046' => 'No database selected',
            '1045' => 'Access denied for user',
            '1044' => 'Access denied for user',
            '1042' => 'Can\'t get hostname for your address',
            '1040' => 'Too many connections',
            '1038' => 'Out of sort memory, consider increasing server sort buffer size',
            '1036' => 'Table is read only',
            '1035' => 'CRASHED ON USAGE',
            '1034' => 'CRASHED ON REPAIR',
            '1033' => 'Out of memory; restart server and try again (needed 98304 bytes)',
            '23505' => 'Data already exists',
        ];
        return $messages[$errCode] ?? 'Unknown error';
    }
}

if (!function_exists('cnicFormat')) {
    function cnicFormat($cnic)
    {
        $data = Str::of($cnic)->substrReplace('-', 5, 0)->substrReplace('-', 13, 0);
        return $data;
    }
}

if (!function_exists('storeMultiValue')) {
    function storeMultiValue($model, $data)
    {
        foreach ($data as $key => $value) {
            $model->multiValues()->create([
                'type' => $key,
                'value' => $value,
            ]);
        }
    }
}

if (!function_exists('getTeamParentByParentId')) {
    function getTeamParentByParentId($parent_id)
    {
        $team = (new Team())->where('id', $parent_id)->first();
        if ($team) {
            return $team->name;
        }
        return 'parent';
    }
}

if (!function_exists('getUserBrowserInfo')) {
    function getUserBrowserInfo($request)
    {
        $userPcInfo = new stdClass();
        $userPcInfo->ip = $request->ip();
        $userPcInfo->os = $request->header('User-Agent');
        return $userPcInfo;
    }
}

if (!function_exists('actionLog')) {
    function actionLog($logName, $causedByModel, $performedOnModel, $log, $properties = [], $event = '')
    {
        return activity()
            ->causedBy($causedByModel)
            ->performedOn($performedOnModel)
            ->inLog($logName)
            ->event($event)
            ->withProperties($properties)
            ->log($log);
    }
}

if (!function_exists('getMaxFloorOrder')) {
    function getMaxFloorOrder($site_id)
    {
        return (new Floor())->where('site_id', $site_id)->max('order');
    }
}

if (!function_exists('getMaxUnitNumber')) {
    function getMaxUnitNumber($floor_id)
    {
        return (new Unit())->where('floor_id', $floor_id)->max('unit_number');
    }
}

if (!function_exists('getModelsClasses')) {
    function getModelsClasses(string $dir, array $excepts = null, array $includes = null)
    {
        if ($excepts === null) {
            $excepts = [
                'App\Models\AccountAction',
                'App\Models\AccountActionBinding',
                'App\Models\AccountHead',
                'App\Models\AccountingStartingCode',
                'App\Models\AccountLedger',
                'App\Models\AccountPayable',
                'App\Models\CustomerAccountPayable',
                'App\Models\DealerAccountPayable',
                'App\Models\SupplierAccountPayable',
                'App\Models\Bank',
                'App\Models\Cash',
                'App\Models\Upload',
                'App\Models\CustomField',
                'App\Models\Media',
                'App\Models\CustomFieldValue',
                'App\Models\City',
                'App\Models\Country',
                'App\Models\AppSetting',
                'App\Models\FileAction',
                'App\Models\FileAdjustment',
                'App\Models\FileRefundAttachment',
                'App\Models\FileAdjustmentAttachment',
                'App\Models\FileTitleTransferAttachment',
                'App\Models\FileCancellationAttachment',
                'App\Models\FileBuyBackLabelsAttachment',
                'App\Models\FileResaleAttachment',
                'App\Models\ModelTemplate',
                'App\Models\UserBatch',
                'App\Models\UnitStakeholder',
                'App\Models\Template',
                'App\Models\TeamUser',
                'App\Models\Status',
                'App\Models\State',
                'App\Models\SalesPlanTemplate',
                'App\Models\Permission',
                'App\Models\Role',
                'App\Models\Status',
                'App\Models\StakeholderContact',
                'App\Models\StakeholderType',
                'App\Models\SalesPlan',
                'App\Models\SalesPlanAdditionalCost',
                'App\Models\SalesPlanInstallments',
                'App\Models\ReceiptDraftModel',
                'App\Models\ReceiptTemplate',
                'App\Models\Notification',
                'App\Models\MultiValue',
                'App\Models\SiteOwner',
                'App\Models\TempStakeholder',
                'App\Models\StakeholderNextOfKin',
                'App\Models\TempUnit',
                'App\Models\TempUnitType',
                'App\Models\TempSalesPlanAdditionalCost',
                'App\Models\TempSalePlanInstallment',
                'App\Models\TempReceipt',
            ];
        }
        if ($includes === null) {
            $includes = [
                'App\Models\Stakeholder',
            ];
        }
        $customFieldModels = array();
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $customFieldModels[$value] = getModelsClasses($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $fullClassName = "App\\Models\\" . basename($value, '.php');
                    if (in_array($fullClassName, $includes)) {
                        $customFieldModels[$fullClassName] = Str::snake(basename($value, '.php'));
                    }
                }
            }
        }
        return $customFieldModels;
    }
}

if (!function_exists('generateCheckbox')) {
    function generateCheckbox($isEditMode, $customFieldValue = null, $id, $name, $label, $bootstrapCols, $values = '', $required = false, $checked = false, $disabled = false, $with_col = true)
    {
        $element = view('app.partial-components.checkbox', [
            'id' => $id,
            'name' => $name,
            'label' => $label,
            'bootstrapCols' => $bootstrapCols,
            'with_col' => $with_col,
            'value' => $values,
            'required' => $required,
            'checked' => $checked,
            'disabled' => $disabled,
        ])->render();

        return $element;
    }
}

if (!function_exists('generateDate')) {
    function generateDate($isEditMode, $customFieldValue = null, $id, $name, $label, $bootstrapCols, $value = '', $required = false, $disabled = false, $readonly = false, $with_col = true)
    {
        $element = view('app.partial-components.date', [
            'id' => $id,
            'name' => $name,
            'label' => $label,
            'bootstrapCols' => $bootstrapCols,
            'with_col' => $with_col,
            'value' => $value,
            'required' => $required,
            'disabled' => $disabled,
            'readonly' => $readonly,
        ])->render();

        return $element;
    }
}



if (!function_exists('generateInput')) {
    function generateInput($isEditMode, $customFieldValue = null, $maxlength, $minlength, $min, $max, $type, $id, $name, $label, $bootstrapCols, $value = '', $required = false, $disabled = false, $readonly = false, $with_col = true)
    {
        $element = view('app.partial-components.input', [
            'type' => $type,
            'id' => $id,
            'name' => $name,
            'label' => $label,
            'bootstrapCols' => $bootstrapCols,
            'with_col' => $with_col,
            'value' => $value,
            'required' => $required,
            'disabled' => $disabled,
            'readonly' => $readonly,
            'maxlength' => $maxlength,
            'minlength' => $minlength,
            'min' => $min,
            'max' => $max,
        ])->render();

        return $element;
    }
}

if (!function_exists('generateTextarea')) {
    function generateTextarea($isEditMode, $customFieldValue = null, $maxlength, $minlength, $id, $name, $label, $bootstrapCols, $value = '', $required = false, $disabled = false, $readonly = false, $with_col = true)
    {
        $element = view('app.partial-components.textarea', [
            'maxlength' => $maxlength,
            'minlength' => $minlength,
            'id' => $id,
            'name' => $name,
            'label' => $label,
            'bootstrapCols' => $bootstrapCols,
            'with_col' => $with_col,
            'value' => $value,
            'required' => $required,
            'disabled' => $disabled,
            'readonly' => $readonly,
        ])->render();

        return $element;
    }
}

if (!function_exists('generateSelect')) {
    function generateSelect($isEditMode, $customFieldValue = null, $multiple, $id, $name, $label, $bootstrapCols, $values = '', $required = false, $disabled = false, $readonly = false, $with_col = true)
    {

        $element = view('app.partial-components.select', [
            'isEditMode' => $isEditMode,
            'customFieldValue' => $customFieldValue,
            'id' => $id,
            'name' => $name,
            'label' => $label,
            'bootstrapCols' => $bootstrapCols,
            'with_col' => $with_col,
            'values' => $values,
            'required' => $required,
            'disabled' => $disabled,
            'readonly' => $readonly,
            'multiple' => $multiple,
        ])->render();

        return $element;
    }
}

if (!function_exists('generateRadio')) {
    function generateRadio($isEditMode, $customFieldValue = null, $id, $name, $label, $bootstrapCols, $values = '', $required = false, $disabled = false, $readonly = false, $with_col = true)
    {

        $element = view('app.partial-components.radio', [
            'isEditMode' => $isEditMode,
            'customFieldValue' => $customFieldValue,
            'id' => $id,
            'name' => $name,
            'label' => $label,
            'bootstrapCols' => $bootstrapCols,
            'with_col' => $with_col,
            'values' => $values,
            'required' => $required,
            'disabled' => $disabled,
            'readonly' => $readonly,
        ])->render();

        return $element;
    }
}

if (!function_exists('generateCustomFields')) {
    function generateCustomFields($customFields, $isEditMode = false, $modelId = 0)
    {
        $customFieldHTML = [];

        foreach ($customFields as $customField) {
            // dd($customField->CustomFieldValue->where('modelable_id',$modelId)->first());
            switch ($customField->type) {
                case 'checkbox':
                    $customFieldHTML[] = generateCheckbox(
                        $isEditMode,
                        $customField->CustomFieldValue->where('modelable_id', $modelId)->first(),
                        $customField->slug,
                        $customField->slug,
                        $customField->name,
                        $customField->bootstrap_column,
                        $customField->values[0] ?? '',
                        $customField->required,
                        $customField->checked,
                        $customField->disabled,
                    );

                    break;

                case 'date':
                    $customFieldHTML[] = generateDate(
                        $isEditMode,
                        $customField->CustomFieldValue->where('modelable_id', $modelId)->first(),
                        $customField->slug,
                        $customField->slug,
                        $customField->name,
                        $customField->bootstrap_column,
                        $customField->value[0] ?? 'today',
                        $customField->required,
                        $customField->disabled,
                        $customField->readonly,
                    );

                    break;

                case 'email':
                case 'number':
                case 'password':
                case 'text':

                    $customFieldHTML[] = generateInput(
                        $isEditMode,
                        $customField->CustomFieldValue->where('modelable_id', $modelId)->first(),
                        $customField->maxlength,
                        $customField->minlength,
                        $customField->min,
                        $customField->max,
                        $customField->type,
                        $customField->slug,
                        $customField->slug,
                        $customField->name,
                        $customField->bootstrap_column,
                        '',
                        $customField->required,
                        $customField->disabled,
                        $customField->readonly,
                    );

                    break;

                case 'textarea':
                    $customFieldHTML[] = generateTextarea(
                        $isEditMode,
                        $customField->CustomFieldValue->where('modelable_id', $modelId)->first(),
                        $customField->maxlength,
                        $customField->minlength,
                        $customField->slug,
                        $customField->slug,
                        $customField->name,
                        $customField->bootstrap_column,
                        '',
                        $customField->required,
                        $customField->disabled,
                        $customField->readonly,
                    );

                    break;

                case 'select':
                    $customFieldHTML[] = generateSelect(
                        $isEditMode,
                        $customField->CustomFieldValue->where('modelable_id', $modelId)->first(),
                        $customField->multiple,
                        $customField->slug,
                        $customField->slug,
                        $customField->name,
                        $customField->bootstrap_column,
                        $customField->values,
                        $customField->required,
                        $customField->disabled,
                        $customField->readonly,
                    );

                    break;
                case 'radio':
                    $customFieldHTML[] = generateRadio(
                        $isEditMode,
                        $customField->CustomFieldValue->where('modelable_id', $modelId)->first(),
                        $customField->slug,
                        $customField->slug,
                        $customField->name,
                        $customField->bootstrap_column,
                        $customField->values,
                        $customField->required,
                        $customField->disabled,
                        $customField->readonly,
                    );

                    break;

                default:
                    # code...
                    break;
            }
        }

        return $customFieldHTML;
    }
}

if (!function_exists('changeImageDirectoryPermission')) {
    function changeImageDirectoryPermission()
    {
        $path = public_path() . '/app-assets/server-uploads';
        if (is_dir($path)) {
            exec('chmod -R 755 ' . $path);
            return 'true';
        } else {
            return false;
        }
    }
}

if (!function_exists('generateSlug')) {
    function generateSlug($site_id, $name, $model)
    {
        $slugCount = 0;
        $slug = Str::slug($name);
        $tmpSlug = $slug;
        $isUniqueSlug = false;
        while (!$isUniqueSlug) {
            if ($model->where('site_id', $site_id)->where('slug', $tmpSlug)->exists()) {
                $slugCount++;
                $tmpSlug = $slug . '-' . $slugCount;
            } else {
                $isUniqueSlug = true;
                $slug = $tmpSlug;
            }
        }
        return $slug;
    }
}

if (!function_exists('addAccountCodes')) {
    function addAccountCodes($model)
    {

        $account_starting_code = AccountingStartingCode::where([
            'level' => 2,
            'model' => $model,
        ])->orderBy('starting_code')->first();

        $account_ending_code = AccountingStartingCode::where([
            'level' => 2,
            'level_code' => $account_starting_code->level_code,
        ])->where(
            'starting_code',
            '>',
            $account_starting_code->starting_code
        )->orderBy('starting_code')->first();


        $starting_code = intval($account_starting_code->level_code . $account_starting_code->starting_code);
        $ending_code =  intval(empty($account_ending_code) ? 999999 : $account_ending_code->level_code . $account_ending_code->starting_code);

        $account_head  = AccountHead::whereHasMorph(
            'modelable',
            $model,
        )->get();

        $account_code = $starting_code;

        if (isset($account_head) && count($account_head) > 0) {
            $last_account_head = collect($account_head)->last();
            // dd($last_account_head);
            $level = $last_account_head->level;
            $level_code = $last_account_head->level_code;

            $account_code =  $last_account_head->code + 1;
            if ($account_code >= $ending_code) {
                throw new GeneralException('Accounts are conflicting. Please rearrange your coding system.');
            }
        }

        return $account_code;
    }
}

if (!function_exists('getTypeAncesstorData')) {
    function getTypeAncesstorData($type_id)
    {
        $type = (new Type())->find($type_id);
        if ($type->parent_id > 0) {
            $type = getTypeAncesstorData($type->parent_id);
        }
        return $type;
    }
}
