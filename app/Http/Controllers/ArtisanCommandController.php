<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ArtisanCommandController extends Controller
{
    public function commands(Request $request, $command)
    {

        switch ($command) {
            case 'optimize-clear':
                Artisan::call('optimize:clear');
                break;

            case 'migrate':
                Artisan::call('migrate');
                break;

            case 'migrate-fresh':
                Artisan::call('migrate:fresh');
                break;

            case 'migrate-fresh-seed':
                Artisan::call('migrate:fresh', ['--seed' => true]);
                break;

            default:
                return "No Command Found!";
                break;
        }

        return "<pre>" . Artisan::output() . "</pre>";
    }
}
