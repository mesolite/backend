<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        return [
            'name'        => config('api.name'),
            'url'         => config('api.url'),
            'description' => config('api.description'),
            'version'     => config('api.version'),
        ];
    }
}
