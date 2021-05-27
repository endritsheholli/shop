<?php

namespace App\Http\Controllers;

use App\Models\LikedProducts;
use App\Models\Products;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LikedProductsController extends Controller
{
    private $user_id = 0;

    public function __construct()
    {
        $this->user_id = \Auth::user()->id;
    }
    public function create(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'product_id' => 'required',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $product = Products::find($request->input('product_id'));
                if (!$product) {
                    return $this->respondWithError('This product not found.');
                }

                $like = LikedProducts::where('user_id', $this->user_id)->where('product_id', $product->id)->first();
                if ($like) {
                    return $this->respondWithError('This product is already in your wish list.');
                }
                $product->likes = $product->likes ?  (int) ($product->likes) + 1 : 1;
                $product->save();
                $liked_products = new LikedProducts();
                $liked_products->user_id = $this->user_id;
                $liked_products->product_id = $product->id;

                $liked_products->save();

                return $this->respondWithSuccess($liked_products);
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function removeByProductId($id)
    {
        try {
            $liked_products = LikedProducts::where(['user_id' => $this->user_id, 'product_id' => $id])->first();
            if (!$liked_products) {
                return $this->respondWithError('Record not found!');
            }
            $product = Products::find($id);
            if (!$product) {
                return 'sss';
                return $this->respondWithError('Record not found!');
            }

            $product->likes = $product->likes ? $product->likes - 1 : 0;
            $product->save();

            $liked_products->delete();

            return $this->respondWithSuccess('Success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function getByProductId($id)
    {
        $liked_products = LikedProducts::where('user_id', $this->user_id)->where('product_id', $id)->get();
        return $this->respondWithSuccess($liked_products);
    }
    public function setByProductId($id)
    {
        $liked_products = LikedProducts::where('user_id', $this->user_id)->where('product_id', [$id])->first();
        if (empty($liked_products)) {
            $liked_products = new LikedProducts();
            $liked_products->user_id = $this->user_id;
            $liked_products->product_id = $id;
            $liked_products->product_like = true;

            $liked_products->save();
        }
        if ($liked_products->product_like == true) {
            $liked_products->product_like = false;
            $liked_products->save();
        } else if ($liked_products->product_like == false) {
            $liked_products->product_like = true;
            $liked_products->save();
        }

        return $this->respondWithSuccess($liked_products);
    }
    public function getMyProduct()
    {
        try {
            $liked_products = LikedProducts::with('product')->where('user_id', $this->user_id)->get();
            return $this->respondWithSuccess($liked_products);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getLikedProducts()
    {
        try {
            $liked_products = LikedProducts::with('product')->get();
            return $this->respondWithSuccess($liked_products);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function isLikeProduct($id)
    {
        $liked_products =   $liked_products = LikedProducts::where(['user_id' => $this->user_id, 'product_id' => $id])->first();
        $boolean =  false;
        if ($liked_products) {
            $boolean = true;
        }
        return $this->respondWithSuccess($boolean);
    }
}
