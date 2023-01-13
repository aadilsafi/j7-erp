<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Services\User\Interface\UserInterface;
use App\Services\CustomFields\CustomFieldInterface;
use Illuminate\Http\Request;
use App\Http\Requests\users\{
    storeRequest as userStoreRequest,
    updateRequest as userUpdateRequest,
};
use App\Models\Country;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private $userInterface,$customFieldInterface;

    public function __construct(UserInterface $userInterface, CustomFieldInterface $customFieldInterface)
    {
        $this->userInterface = $userInterface;
        $this->customFieldInterface = $customFieldInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $dataTable, $site_id)
    {
        $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->userInterface->model()));

        $data = [
            'site_id' => $site_id,
            'customFields' => $customFields->where('in_table', true),
        ];

        return $dataTable->with($data)->render('app.sites.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        if (!request()->ajax()) {

            $role = Role::get();

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->userInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $data = [
                'site_id' => decryptParams($site_id),
                'role' => $role,
                'country' => Country::all(),
                'city' => [],
                'state' => [],
                'customFields' => $customFields,
            ];
            return view('app.sites.users.create', $data);
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(userStoreRequest $request, $site_id)
    {
        try {
            if (!request()->ajax()) {

                $inputs = $request->all();
                $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->userInterface->model()));

                $record = $this->userInterface->store($site_id, $inputs, $customFields);

                return redirect()->route('sites.users.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.users.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            $roles = Role::get();
            $user = $this->userInterface->getById($site_id, $id);

            $customFields = $this->customFieldInterface->getAllByModel($site_id, get_class($this->userInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields, true, $user->id);

            $Selectedroles = $user->roles->pluck('name')->toArray();
            if ($user && !empty($user)) {
                $images = $user->getMedia('user_cnic');

                $data = [
                    'site_id' => $site_id,
                    'id' => $id,
                    'user' => $user,
                    'images' => $images,
                    'Selectedroles' => $Selectedroles,
                    'roles' => $roles,
                    'customFields' => $customFields,
                    'country' => Country::all(),
                    'city' => [],
                    'state' => [],
                ];

                return view('app.sites.users.edit', $data);
            }

            return redirect()->route('sites.users.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.users.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(userUpdateRequest  $request, $site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        try {
            if (!request()->ajax()) {
                $inputs = $request->all();
                $customFields = $this->customFieldInterface->getAllByModel($site_id, get_class($this->userInterface->model()));

                $record = $this->userInterface->update($site_id, $id, $inputs, $customFields);
                return redirect()->route('sites.users.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.users.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function destroySelected(Request $request, $site_id)
    {
        try {
            $site_id = decryptParams($site_id);
            if ($request->has('chkUsers')) {
                $ids = $request->get('chkUsers');

                $this->userInterface->destroySelected($ids);

                return redirect()->route('sites.users.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_deleted'));
            } else {
                return redirect()->route('sites.users.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.please_select_at_least_one_item'));
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.users.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
}
