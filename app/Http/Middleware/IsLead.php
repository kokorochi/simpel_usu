<?php

namespace App\Http\Middleware;

use App\Http\Controllers\BlankonController;
use Closure;
use App\Propose;
use View;
use Illuminate\Support\Facades\Auth;

class IsLead extends BlankonController{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $propose = Propose::find($request->id);
        if ($propose !== null)
        {
            if (! ($propose->created_by === Auth::user()->nidn) )
            {
                array_push($this->css['themes'], 'admin/css/pages/error-page.css');
                View::share('css', $this->css);
                View::share('js', $this->js);
                View::share('title', 'ERROR 403 | ' . $this->mainTitle);

                return abort('403');
            }
        }

        return $next($request);
    }
}
