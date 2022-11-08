<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Str;

class ImageImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($site_id)

    {
        $data = [
            'site_id' => $site_id
        ];

        return view('app.sites.settings.import.images.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $files = $request->get('attachment');
        foreach ($files as $key => $folder) {
            dd($folder);
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
    public function edit($id)
    {
        //
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
        //
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


    public function saveFile(Request $request)
    {
        $files = $request->file('attachment');
        $name = Str::slug('Receipts-' . $files[0]->getClientOriginalName()) . '.'.$files[0]->getClientOriginalExtension();
        // $new_name = time() . '.' . $name;
        // $folder = uniqid('filepond', true);
        $destinationPath = public_path('app-assets/images/ReceiptsImages/');
        $file = $files[0]->move($destinationPath, $name);

        return $file;
    }
}
