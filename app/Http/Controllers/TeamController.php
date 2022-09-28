<?php

namespace App\Http\Controllers;

use App\DataTables\TeamsDataTable;
use App\Services\Team\Interface\TeamInterface;
use Illuminate\Http\Request;
use App\Http\Requests\teams\{
    storeRequest as teamStoreRequest,
    updateRequest as teamUpdateRequest,
};
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    private $teamInterface;

    public function __construct(TeamInterface $teamInterface)
    {
        $this->teamInterface = $teamInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TeamsDataTable $dataTable, $site_id)
    {

        $data = [
            'site_id' => $site_id
        ];

        return $dataTable->with($data)->render('app.sites.teams.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        if (!request()->ajax()) {

            $users = User::all();

            $data = [
                'site_id' => decryptParams($site_id),
                'teams' => $this->teamInterface->getAllWithTree(),
                'users' => $users,
            ];
            return view('app.sites.teams.create', $data);
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
    public function store(teamStoreRequest $request, $site_id)
    {
        try {
            if (!request()->ajax()) {

                $inputs = $request->validated();
                $record = $this->teamInterface->store($site_id, $inputs);

                return redirect()->route('sites.teams.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.teams.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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
            $user = $this->teamInterface->getById($site_id, $id);
            $Selectedroles = $user->roles->pluck('name')->toArray();
            if ($user && !empty($user)) {
                $images = $user->getMedia('user_cnic');

                $data = [
                    'site_id' => $site_id,
                    'id' => $id,
                    'user' => $user,
                    'images' => $images,
                    'Selectedroles' => $Selectedroles,
                    'roles' => $roles
                ];

                return view('app.sites.teams.edit', $data);
            }

            return redirect()->route('sites.teams.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.teams.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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
                $record = $this->teamInterface->update($site_id, $id, $inputs);
                return redirect()->route('sites.teams.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.teams.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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

    public function destroySelected(Request $request,$site_id)
    {
        try {
            $site_id = decryptParams($site_id);
            if ($request->has('chkteams')) {
                $ids = $request->get('chkteams');
                
                $this->teamInterface->destroySelected($ids);

                return redirect()->route('sites.teams.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_deleted'));
            } else {
                return redirect()->route('sites.teams.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.please_select_at_least_one_item'));
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.teams.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
}
