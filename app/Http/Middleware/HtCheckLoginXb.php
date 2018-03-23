<?php

namespace App\Http\Middleware;

use App\Models\Ht\Menu;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HtCheckLoginXb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $admin_xb = session("admin_xb");
//        dd($admin_xb);
        if(empty($admin_xb)){
            return redirect('/'.htname.'/index_xb');
        }
        return $next($request);
    }
}
