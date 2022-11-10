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
    public function create($site_id)
    {
        $data = [
            'site_id' => $site_id
        ];

        return view('app.sites.settings.import.images.create', $data);
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

        // $files = File::glob(public_path('app-assets/images/ReceiptsImages/*'));
        // dd($files);
        foreach ($files as $key => $file) {
            $file = Str::before($file, '<link');

            if ($file) {
                $file_name = str_replace(public_path('app-assets/images/temporaryfiles/Receipts/'), '', $file);
                $destinationPath = public_path('app-assets/images/Import/');

                $newfile = File::move($file, $destinationPath . $file_name);

                $test = File::delete($file);
            }
        }
        $data = [
            'site_id' => $site_id
        ];

        return redirect()->route('sites.settings.import.images.index', ['site_id' => $site_id]);
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
        $ext = $files[0]->getClientOriginalExtension();
        $name = str_replace($ext, '', Str::slug('Receipts-' . time() . '-' . $files[0]->getClientOriginalName()));
        $name = $name . '.' . $ext;
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

    public function deleteFile(Request $request)
    {
        $file = $request->get('file');

        $test = File::delete(public_path('app-assets/images/Import/' . $file));
        if ($test) {
            return apiSuccessResponse();
        }else{
            return apiErrorResponse();
        }
    }


    public function cancel($site_id)
    {
        foreach (File::glob(public_path('app-assets') . '/images/temporaryfiles/Receipts/*') as $key => $path) {
            $test = File::delete($path);
        }
        $data = [
            'site_id' => $site_id
        ];

        return redirect()->route('sites.settings.import.images.index', ['site_id' => $site_id]);
    }
}
