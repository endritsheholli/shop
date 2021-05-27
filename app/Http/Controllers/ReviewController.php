<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        try{
            $validation = Validator::make($request->all(), [
                'message' => 'required',
                'product_id' => 'required',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $review = new Review();
                $review->message = $request->input('message');
                $review->product_id = $request->input('product_id');
                $review->save();

                return $this->respondWithSuccess($review);
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = $request->input('id');

            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'message' => 'required',
                'product_id' => 'required',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $review = Review::where(['id' => $id])->first();

                if (!$review) {
                    return $this->respondWithError('Record not found!');
                }

   
                $review->message = $request->input('message');
                $review->product_id = $request->input('product_id');

                $review->save();
                DB::commit();

                return $this->respondWithSuccess($brand);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->respondWithError($ex->getResponse(), 'exception');
        }
    }


    public function remove($id)
    {
        try {
            $review = Review::find($id);
            if (!$review) {
                return $this->respondWithError('Record not found!');
            }
            $review->delete();

            return $this->respondWithSuccess('Success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function getById($id)
    {
        $result = Review::find($id);
        return $this->respondWithSuccess($result);
    }
    public function getAllReviewsPerProduct($prod_id)
    {
        try {
            $result = DB::table('review')->where('product_id', $prod_id)->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
}
