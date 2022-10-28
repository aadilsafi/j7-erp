<?php

namespace App\Http\Controllers;

use App\DataTables\TypesDataTable;
use App\Exceptions\GeneralException;
use App\Http\Requests\types\{
    storeRequest as typeStoreRequest,
    updateRequest as typeUpdateRequest
};
use App\Models\Type;
use App\Services\CustomFields\CustomFieldInterface;
use App\Services\Interfaces\{
    UnitTypeInterface
};
use Exception;
use Illuminate\Http\Request;

class TypeController extends Controller
{

    private $unitTypeInterface;
    private $customFieldInterface;

    public function __construct(UnitTypeInterface $unitTypeInterface, CustomFieldInterface $customFieldInterface)
    {
        $this->unitTypeInterface = $unitTypeInterface;
        $this->customFieldInterface = $customFieldInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TypesDataTable $dataTable, $site_id)
    {
        $data = [
            'site_id' => $site_id
        ];

        return $dataTable->with($data)->render('app.sites.types.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        abort_if(request()->ajax(), 403);

        $site_id = decryptParams($site_id);

        $customFieldsHtml = [];
        $customFields = $this->customFieldInterface->getAllByModel($site_id, get_class($this->unitTypeInterface->model()));
        $customFields = collect($customFields)->sortBy('order');
        $customFields = generateCustomFields($customFields);

        $data = [
            'site_id' => $site_id,
            'types' => $this->unitTypeInterface->getAllWithTree($site_id),
            'customFields' => $customFields
        ];

        return view('app.sites.types.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(typeStoreRequest $request, $site_id)
    {
        // try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                $record = $this->unitTypeInterface->store($site_id, $inputs);
                return redirect()->route('sites.types.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        // }
        // catch (GeneralException $ex){
        //     return redirect()->route('sites.types.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . sqlErrorMessagesByCode($ex->getCode()));
        // }
        // catch (Exception $ex) {
        //     return redirect()->route('sites.types.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . sqlErrorMessagesByCode($ex->getCode()));
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);
        try {
            $type = $this->unitTypeInterface->getById($id);

            if ($type && !empty($type)) {

                $data = [
                    'site_id' => $site_id,
                    'id' => $id,
                    'types' => $this->unitTypeInterface->getAllWithTree($site_id),
                    'type' => $type,
                ];

                return view('app.sites.types.edit', $data);
            }

            return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . sqlErrorMessagesByCode($ex->getCode()));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(typeUpdateRequest $request, $site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                $record = $this->unitTypeInterface->update($site_id, $inputs, $id);
                return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function destroySelected(Request $request, $site_id)
    {
        try {
            $site_id = decryptParams($site_id);
            if (!request()->ajax()) {
                if ($request->has('chkRole')) {

                    $record = $this->unitTypeInterface->destroy($site_id, encryptParams($request->chkRole));

                    if ($record) {
                        return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_deleted'));
                    } else {
                        return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
}
