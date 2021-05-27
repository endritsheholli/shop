<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PostaController;
use App\User;
use Illuminate\Http\Request;
use App\Services\DocumentManagement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    private $user_id = 0;
    private $dms;

    public function __construct(DocumentManagement $dms)
    {
        if (\Auth::user()) {
            $this->user_id = \Auth::user()->id;
        }
        $this->dms = $dms;
    }
    public function update(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        return tap($user)->update($request->only('name', 'email', 'last_name', 'shop_name', 'phone', 'address
        ', 'description', 'interest_id', 'country_id', 'city', 'photo_url'));
    }

    public function update2(Request $request)
    {
        $userEmail = User::where('email', $request->input('email'))
            ->where('id', '!=', $this->user_id)->first();
        if ($userEmail) {
            return $this->respondWithError("This email is taken");
        }
        $user = User::find($this->user_id);
        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        if ($user->role == 'shop') {
            $shopname = "";
            if ($request->input('shop_name')) {
                //  if ($user->shop_name != $request->input('shop_name')) {
                $shopname = $this->slugify($request->input('shop_name'), 'users', 'shop_name');
                // }
            }
            $data = ["name" => $request->input('shop_name') ? $request->input('shop_name') : "", "slug" => $shopname];
            $user->shop_name = $data;
            $posta = new PostaController();
            $update =  $posta->updateProfile($request);
            if (!$update) {
                return $this->respondWithError("cant't update");
            }
        }

        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->description = $request->input('description');
        // $user->interest_id = $request->input('interest_id');
        $user->country_id = $request->input('country_id');
        $user->city = $request->input('city');
        if ($request->file('images')) {
            $this->dms->removeFromS3($user->img_path);
            $path = $this->dms->storeFile($request->file('images'), 'users', $user->id);
            $user->img_path = $path;
        }

        // $user->photo_url = $request->input('photo_url');
        $user->save();
        return $this->respondWithSuccess($user);
    }

    public function getUserDataByShopName($shop_name)
    {
        try {
            // $result = User::where(['shop_name->slug' => $shop_name])->toSql();
            $result = User::whereRaw("JSON_EXTRACT(shop_name, '$.slug') = '" . $shop_name . "'")
                ->where('active', 1)
                ->first();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function getUser()
    {
        try {
            $result = User::find($this->user_id);
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getUsers()
    {
        try {
            $result = User::where('role', '!=', 'admin')->paginate(10);
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function shopImportant()
    {
        try {
            $result = User::where(['role' => 'shop', 'important_shop' => 1, 'active' => 1])
                ->take(10)
                ->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function addImportantShop(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'status' => 'required',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            }
            $id = $request->input('id');
            $status = $request->input('status');
            $result = User::find($id);
            $result->important_shop = $status;
            $result->save();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function shop()
    {
        try {
            $result = User::where('role', 'shop')
                ->where('active', 1)
                ->paginate(18);
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }


    public function deactivateAccount()
    {
        try {
            $user = User::find($this->user_id);
            if (!$user) {
                return $this->respondWithError('not fountd');
            }

            if ($user->role == 'shop') {
                $posta =  new PostaController();
                $deactive =  $posta->deactivateAcc($user->id);
                if (!$deactive) {
                    return $this->respondWithError("Can't deactivated");
                }
            }

            $user->active = 0;
            $user->save();
            return $this->respondWithSuccess($user);
        } catch (Exception $ex) {
            return $this->respondWithException($ex->getResponse());
        }
    }

    public function insetImageBackground(Request $request)
    {
        try {
            $img_background = $request->input('img_background') ? $request->input('img_background') : "";
            $user = $request->user();
            $user->img_background = $img_background;
            $user->save();
            return $this->respondWithSuccess($user);
        } catch (Exception $ex) {
            return $this->respondWithException($ex->getMessage());
        }
    }
}
