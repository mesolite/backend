<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Amethyst\Contracts\DataBuilderContract;

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
