<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $return = [
            'version' => config('api.version'),
        ];

        if (Auth::user()) {
        	$return['lang'] = $this->getLang();
        }

        return $return;
    }

    public function getLang()
    {
        foreach (glob(resource_path('/lang/vendor/*')) as $pathPackage) {
            $packageName = basename($pathPackage);
            foreach (glob($pathPackage.'/*') as $pathLocale) {
                if (is_dir($pathLocale)) {
                    $locale = basename($pathLocale);

                    foreach (glob($pathLocale.'/*') as $file) {
                        $data = basename($file, '.php');
                        $trans = trans(sprintf('%s::%s', $packageName, $data));

                        $lang[$data] = is_array($trans) ? $trans : [];
                    }
                }
            }
        }

        foreach (app('amethyst')->getPackages() as $packageName) {
            foreach (app('amethyst')->getDataByPackageName($packageName) as $data) {
                $trans = trans(sprintf('amethyst-%s::%s', $packageName, $data));
                $lang[$data] = is_array($trans) ? $trans : [];
            }
        }

        return array_merge($amethyst, [
            'lang' => $lang,
        ]);

    }
}
