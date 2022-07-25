<?php

namespace App\Http\Controllers;

use App\DataTables\SitesDataTable;
use App\Http\Requests\sites\storeRequest;
use App\Models\Country;
use App\Models\Site;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SitesDataTable $dataTable)
    {
        return $dataTable->render('app.sites.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = (new Country())->all();
        // dd($countries);
        return view('app.sites.create', ['countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeRequest $request)
    {
        try {

            (new Site())->create([
                'name'          => $request->name,
                'city_id'       => $request->city,
                'address'       => $request->address,
                'area_width'    => $request->area_width,
                'area_length'   => $request->area_length,
                'max_floors'    => $request->max_floors,
            ]);

            return redirect()->route('sites.index')->withSuccess(__('lang.commons.data_saved'));
        } catch (Exception $ex) {
            return redirect()->route('sites.index')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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
            $site = (new Site())->find(decryptParams($id));
            $countries = (new Country())->all();

            if ($site && !empty($site)) {
                return view('app.sites.edit', ['site' => $site, 'countries' => $countries]);
            }
            return redirect()->route('sites.index')->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.index')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $record = (new Site())->where('id', decryptParams($id))->update([
                'name'          => $request->name,
                'city_id'       => $request->city,
                'address'       => $request->address,
                'area_width'    => $request->area_width,
                'area_length'   => $request->area_length,
                'max_floors'    => $request->max_floors,
            ]);

            return redirect()->route('sites.index')->withSuccess(__('lang.commons.data_saved'));
        } catch (Exception $ex) {
            return redirect()->route('sites.index')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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
        try {
            $record = (new Site())->find(decryptParams($id));

            if ($record && !empty($record)) {
                $record->delete();
                return redirect()->route('sites.index')->withSuccess(__('lang.commons.data_deleted'));
            }
            return redirect()->route('sites.index')->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {

            switch ($ex->getCode()) {
                case '23503':
                    $errMessage = 'This record is used in another table, so it can not be deleted.';
                    break;

                default:
                    $errMessag = '';
                    break;
            }

            return redirect()->route('sites.index')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $errMessage);
        }
    }

    public function destroySelected(Request $request)
    {
        try {
            if ($request->has('chkSites')) {

                (new Site())->whereIn('id', $request->chkSites)->delete();

                return redirect()->route('sites.index')->withSuccess(__('lang.commons.data_deleted'));
            } else {
                return redirect()->route('sites.index')->withWarning(__('lang.commons.please_select_at_least_one_item'));
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.index')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
}
