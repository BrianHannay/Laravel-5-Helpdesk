<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Role;
class FirstLogin
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // first, handle the closure, and save it for later.
        $closureResult = $next($request);

        //now in the middle, as long as the user is logged in (created)
        if($this->auth->check()){
            //make the user default to a end user
            $endUserRole = Role::description('user')->get()->first();
            $endUserRole->users()->attach($this->auth->user());
        }

        return $closureResult;
    }
}
