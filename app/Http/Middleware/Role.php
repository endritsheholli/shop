<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use JWTAuth;
use Illuminate\Http\{
    Request,
    JsonResponse,
    Response
};

class Role {

    public function handle($request, Closure $next, $role) {

        $roles =explode("|", $role);
        $user_role=$request->user()->role;
        if (in_array($user_role, $roles)) {
            return $next($request);
        }else{
            return new JsonResponse([
                'message' => 'Not authorized request!',
                'status' => 'failed'
            ]);
        }
        
    }

}
