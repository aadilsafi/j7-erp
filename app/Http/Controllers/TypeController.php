<?php

namespace App\Http\Controllers;

use App\DataTables\TypesDataTable;
use App\Http\Requests\types\{
    storeRequest as typeStoreRequest,
    updateRequest as typeUpdateRequest
};
use App\Models\Type;
use App\Services\Interfaces\{
    UnitTypeInterface
};
use Exception;
use Illuminate\Http\Request;

class TypeController extends Controller
{

    private $unitTypeInterface;

    public function __construct(UnitTypeInterface $unitTypeInterface)
    {
        $this->unitTypeInterface = $unitTypeInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TypesDataTable $dataTable)
    {
        return $dataTable->render('app.types.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!request()->ajax()) {

            $data = [
                'types' => $this->unitTypeInterface->getAllWithTree(),
            ];
            return view('app.types.create', $data);
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
    public function store(typeStoreRequest $request)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                $record = $this->unitTypeInterface->store($inputs);
                return redirect()->route('types.index')->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('types.index')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $type = $this->unitTypeInterface->getById($id);

            if ($type && !empty($type)) {

                $data = [
                    'types' => $this->unitTypeInterface->getAllWithTree(),
                    'type' => $type,
                ];

                return view('app.types.edit', $data);
            }

            return redirect()->route('types.index')->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('types.index')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(typeUpdateRequest $request, $id)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                $record = $this->unitTypeInterface->update($inputs, $id);
                return redirect()->route('types.index')->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('types.index')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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
        if (!request()->ajax()) {
            $record = (new Type())->destroyType([$id]);

            if (is_a($record, 'Exception')) {
                return redirect()->route('types.index')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $record->getMessage());
            } else if ($record) {
                return redirect()->route('types.index')->withSuccess(__('lang.commons.data_deleted'));
            } else {
                return redirect()->route('types.index')->withDanger(__('lang.commons.data_not_found'));
            }
        } else {
            abort(403);
        }
    }

    public function destroySelected(Request $request)
    {
        try {
            if (!request()->ajax()) {
                if ($request->has('chkRole')) {

                    $record = $this->unitTypeInterface->destroy(encryptParams($request->chkRole));

                    if ($record) {
                        return redirect()->route('types.index')->withSuccess(__('lang.commons.data_deleted'));
                    } else {
                        return redirect()->route('types.index')->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('roles.index')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
}
