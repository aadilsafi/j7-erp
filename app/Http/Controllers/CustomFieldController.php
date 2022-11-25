<?php

namespace App\Http\Controllers;

use App\DataTables\CustomFieldsDataTable;
use App\Http\Requests\CustomFields\storeRequest;
use App\Http\Requests\CustomFields\updateRequest;
use App\Services\CustomFields\CustomFieldInterface;
use App\Utils\Enums\CustomFieldsEnum;
use Exception;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    private $customFieldInterface;

    public function __construct(
        CustomFieldInterface $customFieldInterface
    ) {
        $this->customFieldInterface = $customFieldInterface;
    }

    public function index(Request $request, CustomFieldsDataTable $dataTable, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
        ];

        return $dataTable->with($data)->render('app.sites.settings.custom-fields.index', $data);
    }

    public function create(Request $request, $site_id)
    {
        if (!request()->ajax()) {

            $data = [
                'site_id' => decryptParams($site_id),
                'fieldTypes' => CustomFieldsEnum::array(),
                'models' => getModelsClasses(app_path('Models')),
            ];

            // dd($data);

            return view('app.sites.settings.custom-fields.create', $data);
        } else {
            abort(403);
        }
    }

    public function store(storeRequest $request, $site_id)
    {
        $site_id = decryptParams($site_id);
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                $record = $this->customFieldInterface->store($site_id, $inputs);
                return redirect()->route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . sqlErrorMessagesByCode($ex->getCode()));
        }
    }

    public function edit(Request $request, $site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        try {
            $customField = $this->customFieldInterface->getById($site_id, $id);
            $customValues = count($customField->CustomFieldValue);

            if ($customField && !empty($customField)) {

                $data = [
                    'site_id' => $site_id,
                    'id' => $id,
                    'fieldTypes' => CustomFieldsEnum::array(),
                    'models' => getModelsClasses(app_path('Models')),
                    'customField' => $customField,
                    'customValues' => $customValues,
                ];
                // dd($data);
                return view('app.sites.settings.custom-fields.edit', $data);
            }

            return redirect()->route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . sqlErrorMessagesByCode($ex->getCode()));
        }
    }

    public function update(updateRequest $request, $site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                $record = $this->customFieldInterface->update($site_id, $id, $inputs);
                return redirect()->route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . sqlErrorMessagesByCode($ex->getCode()));
        }
    }

    public function destroy(Request $request, $site_id)
    {
        try {
            $site_id = decryptParams($site_id);

            if (!request()->ajax()) {
                if ($request->has('chkTableRow')) {
                    $record = $this->customFieldInterface->destroy($site_id, $request->chkTableRow);

                    if ($record) {
                        return redirect()->route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_deleted'));
                    } else {
                        return redirect()->route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
}
