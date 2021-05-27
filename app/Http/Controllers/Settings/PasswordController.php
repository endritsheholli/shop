<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PostaController;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);
        if ($request->user() && $request->user()->role == 'shop') {
            $posta =  new PostaController();
            $update = $posta->updatePassword($request);
            if (!$update) {
                return $this->respondWithError("can't update");
            }
        }
        $request->user()->update([
            'password' => bcrypt($request->password),
        ]);
    }
}
