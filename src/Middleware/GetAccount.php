<?php

namespace Chernogolov\Fogcms\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

use Closure;
use Chernogolov\Fogcms\Records;

class GetAccount
{
    public $accounts;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $params['added_user'] = Auth::user()->id;
        $params['offset'] = 0;
        $accounts = Records::getRecords(Config::get('fogcms.accounts_reg_id'), $params)->sortBy('address');
        if($accounts->count()>0)
        {
            $current_account_id = session('ca' . Auth::user()->id);
            if(!isset($current_account_id))
                $current_account_id = $this->accounts->first()->id;

            $accounts = Records::getRecord($current_account_id);
        }
        else
            return Redirect::route('lk-accounts');

        $request->attributes->add(['accounts' => $accounts]);

        return $next($request);
    }
}
