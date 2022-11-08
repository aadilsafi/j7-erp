<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use Str;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;

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
    public function store(Request $request, $site_id)
    {
        $files = $request->get('attachment');
        foreach ($files as $key => $folder) {
            $file = Str::before($folder, '<link');
            $destinationPath = public_path('app-assets/images/ReceiptsImages/');
                dd(Storage::get($file));
            // $new_file->move($destinationPath);
            $newfile = Storage::put($destinationPath, $new_file);

            $test = File::delete($file);
        }
        $data = [
            'site_id' => $site_id
        ];

        return view('app.sites.settings.import.images.index', $data);
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
        $name = Str::slug('Receipts-' . $files[0]->getClientOriginalName()) . '.' . $files[0]->getClientOriginalExtension();
        // $new_name = time() . '.' . $name;
        // $folder = uniqid('filepond', true);
        $destinationPath = public_path('app-assets/images/temporaryfiles/Receipts');
        $file = $files[0]->move($destinationPath, $name);

        return $file;
    }

    public function revertFile(Request $request)
    {
        $folder = $request->getContent();
        $file = Str::before($folder, '<link');

        $test = File::delete($file);
        return $test;
    }
}
