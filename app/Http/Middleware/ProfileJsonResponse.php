<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProfileJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // check if debugger is enabled
        if(! app()->bound('debugbar') || !app('debugbar')->isEnabled()){
            return $response;
        }

        // profile the json response
        if($response instanceof JsonResponse && $request->has('_debug')){
            $response->setData(array_merge([$response->getData()], [
                'debugbar' => Arr::only(app('debugbar')->getData(), 'queries')
            ]));
        }

        return $response;
    }
}
