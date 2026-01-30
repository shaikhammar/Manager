<?php

namespace App\Http\Middleware;

use App\Service\Business\BusinessManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Business;

class SetDefaultBusinessContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $businessId = session('active_business_id');

        if(Auth::check() && !$businessId) {
            $business = Auth::user()->businesses()->where('is_default', true)->first() ?? Auth::user()->businesses()->first();
           
            if ($business) {
                $businessId = $business->id;
                session(['active_business_id' => $businessId]);
            }
        }

        if($businessId){
            $business = Business::find($businessId);
            if ($business) {
                app(BusinessManager::class)->setBusiness($business);
            }
        }
        return $next($request);
    }
}
