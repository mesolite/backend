<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Railken\Amethyst\Contracts\DataBuilderContract;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('api');

        $events = [];
        $dataBuilders = [];

        if ($user && $user->role === 'admin') {
            foreach (Config::get('amethyst.event-logger.models-loggable') as $model) {
                $events = array_merge($events, [
                    'eloquent.created: '.$model,
                    'eloquent.updated: '.$model,
                    'eloquent.removed: '.$model,
                ]);
            }

            foreach (Config::get('amethyst.event-logger.events-loggable') as $class) {
                $events = array_merge(
                    $events,
                    $this->findCachedClasses('app', $class),
                    $this->findCachedClasses('src', $class)
                );
            }

            $dataBuilders = array_merge(
                $this->findCachedClasses('app', DataBuilderContract::class),
                $this->findCachedClasses('vendor/railken/amethyst-*/src', DataBuilderContract::class)
            );
        }

        return [
            'name'        => config('api.name'),
            'url'         => config('api.url'),
            'description' => config('api.description'),
            'version'     => config('api.version'),
            'app'         => [
                'events'        => $events,
                'data_builders' => $dataBuilders,
            ],
        ];
    }

    public function findCachedClasses($directory, $subclass)
    {
        $key = 'api.info.classes:'.$directory.$subclass;

        $value = Cache::get($key, null);

        if ($value === null) {
            $value = $this->findClasses($directory, $subclass);
        }

        Cache::put($key, $value, 60);

        return $value;
    }

    public function findClasses($directory, $subclass)
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $iter = new \hanneskod\classtools\Iterator\ClassIterator($finder->in(base_path($directory)));

        return array_keys($iter->type($subclass)->where('isInstantiable')->getClassMap());
    }
}
