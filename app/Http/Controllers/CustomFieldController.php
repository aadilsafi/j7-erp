<?php

namespace App\Http\Controllers;

use App\DataTables\CustomFieldsDataTable;
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

    public function store(store $request, $site_id)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                $record = $this->unitTypeInterface->store($site_id, $inputs);
                return redirect()->route('sites.types.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.types.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . sqlErrorMessagesByCode($ex->getCode()));
        }
    }
}
