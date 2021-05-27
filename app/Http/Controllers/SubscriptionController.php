<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function create(Request $request)
    {
        try{
            $validation = Validator::make($request->all(), [
                'email' => 'required|unique:subscription,email'
              
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $subscription = new Subscription();
                $subscription->email = $request->input('email');
                if($subscription->email)
                $subscription->save();

                return $this->respondWithSuccess($subscription);
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function remove($id)
    {
        try {
            $subscription = Subscription::find($id);
            if (!$subscription) {
                return $this->respondWithError('Record not found!');
            }

            $subscription->delete();

            return $this->respondWithSuccess('Success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    
    public function getById($id)
    {
        $result = Subscription::find($id);
        return $this->respondWithSuccess($result);
    }

    public function getAllSubscribers()
    {
        try {
            $result = Subscription::get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }



}
