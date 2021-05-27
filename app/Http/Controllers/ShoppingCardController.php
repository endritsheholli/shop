<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class ShoppingCardController extends Controller
{
    private $user_id = 0;

    public function __construct()
    {
        if (\Auth::user()) {
            $this->user_id = \Auth::user()->id;
        }
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
                $sameProduct = ShoppingCard::where([
                    'product_id' => $request->input('product_id'),
                    'user_id' => $this->user_id,
                    'status' => 0
                ])
                    ->first();

                if ($sameProduct) {
                    return $this->respondWithError('Tashme e keni ne shport');
                }
                $shopping_card = new ShoppingCard();
                $shopping_card->product_id = $request->input('product_id');
                $shopping_card->quantity = $request->input('quantity') ? $request->input('quantity') : 1;
                $shopping_card->comment = $request->input('comment') ? $request->input('comment') : '';
                $shopping_card->user_id = $this->user_id;

                $shopping_card->save();
                return $this->respondWithSuccess($shopping_card);
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function remove($id)
    {
        try {
            $shopping_card = ShoppingCard::find($id);
            if (!$shopping_card) {
                return $this->respondWithError('Record not found!');
            }
            $shopping_card->delete();

            return $this->respondWithSuccess('Success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function getByUserId()
    {
        $result = null;

        $shopping_card = ShoppingCard::with('product')
            ->where(['user_id' => $this->user_id, 'status' => 0])
            ->get();
        $total = 0;
        if ($shopping_card) {
            foreach ($shopping_card as $pc) {
                $total = $total + $pc->product->price;
            }
        }
        $result['shopping_card'] = $shopping_card;
        $result['total'] = $total;

        return $this->respondWithSuccess($result);
    }
    public function getThreeProduct()
    {
        $result = null;

        $shopping_card = ShoppingCard::with('product')
            ->select('shopping_card.*', 'p.price')
            ->where(['shopping_card.user_id' => $this->user_id, 'shopping_card.status' => 0])
            ->join('products as p', 'shopping_card.product_id', 'p.id')
            ->orderBy('shopping_card.updated_at', 'desc');

        $total = $shopping_card->sum('price');
        $length = $shopping_card->count();
        $shopping_card = $shopping_card->take(3)
            ->get();

        $result['shopping_card'] = $shopping_card;
        $result['total'] = $total;
        $result['length_shoping'] = $length;

        return $this->respondWithSuccess($result);
    }
    public function removeAllProduct(Request $request)
    {
        try {
            //stausi me u bo 1
            $array_id = $request->input('array_id');
            ShoppingCard::where('user_id', $this->user_id)
                ->whereIn('id', $array_id)
                ->delete();
            return $this->respondWithSuccess('Success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function comment(Request $request)
    {
        try {
            $shopping = ShoppingCard::where(['id' => $request->input('id'), 'user_id' => $this->user_id])
                ->first();
            if (!$shopping) {
                return $this->respondWithError('not found');
            }
            $shopping->comment = $request->input('comment') ?  $request->input('comment')  : '';
            $shopping->save();
            return $this->respondWithSuccess($shopping);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
}
