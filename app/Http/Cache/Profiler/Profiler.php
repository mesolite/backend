<?php

namespace App\Http\Cache\Profiler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests;

class Profiler extends CacheAllSuccessfulGetRequests
{
    public function cacheNameSuffix(Request $request): string
    {
        $prefix = $request->header('Accept-Language');

        if (Auth::check()) {
            $prefix .= Auth::id();
        }

        return $prefix;
    }

    public function shouldCacheRequest(Request $request): bool
    {
        if ($request->ajax()) {
            return false;
        }

        return $request->isMethod('get');
    }

    /*public function getHashFor(Request $request): string
    {
    	$key = sprintf(
    		"%s-%s-%s-%s/%s",
    		$request->getHost(),
    		$request->getRequestUri(),
    		$request->getMethod(),
    		,
    		$this->cacheProfile->useCacheNameSuffix($request)
    	);

        return 'responsecache-'.md5($key);
    }*/
}
